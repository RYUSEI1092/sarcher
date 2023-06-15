<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\Rental;

class RentalController extends Controller
{

    /**
     * メインページ表示処理
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $templatePath = "rentals.main";
        $registerSuccess = array_key_exists('register-success', $request->all());

        // 人気ランキング
        // window関数で件数の多い順に1から順位付け
        $rankedRentals = Rental::select(DB::raw('row_number() over (order by count(1) desc, `isbn`) as `rank`'), DB::raw('min(id) as min_id'))
            // 集計期間を指定する場合はサブクエリ側で絞る必要あり
            // ->where(...)
            ->groupBy('isbn');
        $favorites = Rental::select('book_title as title', 'book_img as img', 'book_genre as genre', 'isbn')
            ->joinSub($rankedRentals, 'ranked_rentals', fn ($join) => $join->on('id', '=', 'ranked_rentals.min_id'))
            // 各順位の最小IDの1件だけが取得されているため、 rank <= 6 にすることで6件以下であることが保証される
            ->where('ranked_rentals.rank', '<=', 6)
            ->orderBy('ranked_rentals.rank')
            ->get();

        // 最近読まれた本
        // 同じ書籍が連続で返却された場合、最後に返されたデータのみが必要なので、
        // ISBNコードごとの最終返却レコードを取得する
        $sequences = Rental::select(DB::raw('row_number() over (partition by `isbn` order by `returned_at` desc) as `seq`'), 'id as rental_id');
        $histories = Rental::select('book_title as title', 'book_img as img', 'book_genre as genre', 'isbn')
            ->joinSub($sequences, 'sequences', fn ($join) => $join->on('id', '=', 'rental_id'))
            ->where('seq', '=', 1)
            ->whereNotNull('returned_at')
            ->orderByDesc('returned_at')
            ->take(6)
            ->get();

        return view($templatePath, compact("favorites", "histories", "registerSuccess"));
    }

    /**
     * 蔵書検索ページ表示処理
     *
     * @param Request $request
     * @return void
     */
    public function books(Request $request)
    {
        $books = [];
        $templatePath = "rentals.books";
        if (null !== $request->input('title')) {
            $books = [];
            $isbnList = [];
            $booksAPIUrl = "https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404?applicationId=1086215376294448280&elements=title,author,reviewAverage,largeImageUrl,booksGenreId,isbn";
            $genreAPIUrl = "https://app.rakuten.co.jp/services/api/BooksGenre/Search/20121128?applicationId=1086215376294448280&elements=current";
            $format = "formatVersion=2";
            $title = "title=" . $request->input('title');
            $booksResponse = Http::get("{$booksAPIUrl}&{$title}&{$format}");
            $booksJson = $booksResponse->json();
            if(count($booksJson) == 0){
                $books = json_encode($books);
                return view($templatePath, compact("books"));
            }
            $cashedGenres = [];
            foreach ($booksJson["Items"] as $list) {
                $genreNames = [];
                foreach (explode("/", $list["booksGenreId"]) as $genreId) {
                    if (isset($cashedGenres[$genreId])) {
                        $genreNames[] = $cashedGenres[$genreId];
                    } else {
                        $response = Http::get("{$genreAPIUrl}&genrePath=0&{$format}&booksGenreId={$genreId}");
                        $genresJson = json_decode($response, true);
                        if (isset($genresJson["current"])) {
                            $cashedGenres[$genreId] = $genresJson["current"]["booksGenreName"];
                        } else {
                            $cashedGenres[$genreId] = "";
                        }
                        $genreNames[] = $cashedGenres[$genreId];
                    }
                }
                $list["booksGenreName"] = implode("/", $genreNames);
                $books[] = $list;
            }
            //図書館システムIDAPI
            if (isset($books)) {
                //ログイン時
                $systemId = "Osaka_Pref";
                //ログイン時は郵便番号から位置情報を取得してsystemIdを得る
                if(!is_null(Auth::id())){
                    $zipcode = Auth::user()->post_code;
                    $geoAPIURL = Http::get("http://geoapi.heartrails.com/api/json?method=searchByPostal&postal={$zipcode}");
                    $geoAPIJson = $geoAPIURL->json();
                    $location = $geoAPIJson['response']['location'][0];
                    $geoCode = "{$location['x']},{$location['y']}";
                    $systemAPIUrl = "https://api.calil.jp/library?appkey=f469cf496d9cf8ec1d7048dd86e1c6ca&geocode={$geoCode}&format=json&callback=no";
                    $systemResponse = Http::get($systemAPIUrl);
                    $systemJson = $systemResponse->json();
                    $systemId = $systemJson[0]["systemid"];
                }
                //$systemIdがある時蔵書レンタル状況を取得
                if(isset($systemId)){
                    foreach ($booksJson["Items"] as $item) {
                        $isbnList[] = $item["isbn"];
                    }
                    $isbnStr = implode(",", $isbnList);
                    
                    $booksSearchAPIUrl = "https://api.calil.jp/check?appkey=f469cf496d9cf8ec1d7048dd86e1c6ca&isbn={$isbnStr}&systemid={$systemId}&format=json&callback=no";
                    $booksSearchResponse = Http::get($booksSearchAPIUrl);
                    $booksSearch = json_decode($booksSearchResponse, true);
                    $continue = $booksSearch["continue"];
                    $booksSession = $booksSearch['session'];
                    while ($continue >= 1) {
                        sleep(2);
                        $checkResponse = Http::get("https://api.calil.jp/check?session={$booksSession}&format=json&callback=no");
                        $booksSearch = $checkResponse->json();
                        $continue = $booksSearch["continue"];
                    }
                }
            }
            //蔵書検索API
            

            if (isset($booksSearch)) {
                $rentals = collect($isbnList)->map(fn ($isbn) => [
                    "isbn" => $isbn,
                    "rental" => collect($booksSearch["books"][$isbn][$systemId]["libkey"])->some(fn ($lib) => $lib == "貸出可")
                ])->keyBy("isbn");
            }

            $books = collect($books)->map(fn ($book) => [
                "rental" => $rentals[$book["isbn"]],
                "book" => $book
            ]);
            $books = json_encode($books);
            return view($templatePath, compact("books"));
        }
        $books = json_encode($books);
        return view($templatePath, compact("books"));
    }



    /**
     * 蔵書詳細ページ表示処理
     *
     * @param Request $request
     * @param string $isbn
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showBooksDetail(Request $request, string $isbn)
    {
        $templatePath = "rentals.booksDetail";

        //zipcloud(API) 
        $zipcode = Auth::user("post_code");
        $zipcloudResponse = Http::get("https://zipcloud.ibsnet.co.jp/api/search?zipcode={$zipcode}");
        //蔵書情報取得(API)
        $books = [];
        $booksAPIUrl = "https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404?applicationId=1086215376294448280&elements=publisherName,title,size,salesDate,itemCaption,author,reviewAverage,largeImageUrl,booksGenreId,isbn&isbn={$isbn}";
        $genreAPIUrl = "https://app.rakuten.co.jp/services/api/BooksGenre/Search/20121128?applicationId=1086215376294448280&elements=current";
        $format = "formatVersion=2";
        $booksResponse = Http::get($booksAPIUrl);
        $booksJson = $booksResponse->json();
        foreach ($booksJson["Items"][0] as $list) {
            if (isset($list["booksGenreId"])) {
                $genreId = explode("/", $list["booksGenreId"]);
                for ($i = 0; $i < count($genreId); $i++) {
                    $genreResponse = Http::get("{$genreAPIUrl}&{$format}&booksGenreId={$genreId[$i]}");
                    $genreJson = $genreResponse->json();
                    $genreName[$i] = $genreJson["current"]["booksGenreName"];
                }
                $list["genreName"] = implode("/", $genreName);
                $books = $list;
            }
        }
        if (isset($books)) {
            //ログイン時
            $systemId = "Osaka_Pref";
            //ログイン時は郵便番号から位置情報を取得してsystemIdを得る
            if(!is_null(Auth::id())){
                $zipcode = Auth::user()->post_code;
                $geoAPIURL = Http::get("http://geoapi.heartrails.com/api/json?method=searchByPostal&postal={$zipcode}");
                $geoAPIJson = $geoAPIURL->json();
                $location = $geoAPIJson['response']['location'][0];
                $geoCode = "{$location['x']},{$location['y']}";
                $systemAPIUrl = "https://api.calil.jp/library?appkey=f469cf496d9cf8ec1d7048dd86e1c6ca&geocode={$geoCode}&format=json&callback=no";
                $systemResponse = Http::get($systemAPIUrl);
                $systemJson = $systemResponse->json();
                $systemId = $systemJson[0]["systemid"];
            }
            //$systemidがある時蔵書レンタル状況を取得
            if (isset($systemId)) {
                $booksSearchAPIUrl = "https://api.calil.jp/check?appkey=f469cf496d9cf8ec1d7048dd86e1c6ca&isbn={$books["isbn"]}&systemid={$systemId}&format=json&callback=no";
                $booksSearchResponse = Http::get($booksSearchAPIUrl);
                $booksSearch = $booksSearchResponse->json();
                $continue = $booksSearch["continue"];
                $booksSession = $booksSearch['session'];
                while ($continue >= 1) {
                    sleep(2);
                    $checkResponse = Http::get("https://api.calil.jp/check?session={$booksSession}&format=json&callback=no");
                    $booksSearch = $checkResponse->json();
                    $continue = $booksSearch["continue"];
                }
            }
        }
        foreach ($booksSearch["books"][$isbn][$systemId]["libkey"] as $libkey => $libName) {
            if ($libName == "貸出可") {
                $libraries[$libkey] = true;
                $books["rental"] = true;
            } else {
                $libraries[$libkey] = false;
            }
        }
        if (isset($libraries)) {
            $books = json_encode($books);
            $libraries = json_encode($libraries);
            return view($templatePath, compact("books", "libraries"))->with('isCreate',null);
        }
        $books = json_encode($books);
        $libraries = json_encode([]);
        return view($templatePath, compact("books", "libraries"))->with('isCreate',null);
    }


    /**
     * レンタル処理
     *
     * @param Request $request
     * @param string $isbn
     * @return void
     */
    public function addRental(Request $request, string $isbn) {
        $user_id = Auth::id();
        $now = date('Y-m-d');
        $return = date('Y-m-d', strtotime("14 day"));
        //DBのrentalsにInsert
        Rental::create([
            'user_id' => $user_id,
            'isbn' => $isbn,
            'library' => $request['library'],
            'book_img' => $request['book_img'],
            'book_title' => $request['book_title'],
            'book_genre' => $request['book_genre'],
            'book_author' => $request['book_author'],
            'publisher_name' => $request['publisher_name'],
            'rental_on' => $now,
            'return_on' => $return,
        ]);
        return $this->showBooksDetail($request, $isbn)->with('isCreate', true);
    }

    /**
     * 本棚表示処理
     *
     * @param Request $request
     * @return void
     */
    public function bookshelf(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        //レンタル中の本を表示
        $templatePath = "rentals.bookshelf";
        $books = $this->getRentals();
        $rentals = $this->byDate($books);
        return view($templatePath, compact('rentals'))->with('isUpdate', null);
    }

    /**
     * 返却処理
     *
     * @param Request $request
     * @return void
     */
    public function returnedBooks(Request $request)
    {
        //返した本のDBに返却日を追加
        //レンタル中の本を表示
        $templatePath = "rentals.bookshelf";
        $ids = collect($request->input('ids'))
            ->map(function ($id) {
                return intval($id);
            });
        try {
            DB::transaction(function () use (&$ids) {
                foreach ($ids as $rental_id) {
                    Rental::where('id', $rental_id)->update(['returned_at' => date("Y-m-d H:i:s")]);
                }
            });
        } catch (\Throwable $th) {
            $$books = $this->getRentals();
            $rentals = $this->byDate($books);
            return view($templatePath, compact('rentals'))->with('isUpdate', false);
        }
        $books = $this->getRentals();
        $rentals = $this->byDate($books);
        return view($templatePath, compact('rentals'))->with('isUpdate', true);
    }

    function getRentals()
    {
        return Rental::where('user_id', '=', Auth::id())
            ->whereNull('returned_at')
            ->orderBy('return_on', 'asc')
            ->get();
    }
    function byDate($books){
        $date_now = strtotime(date('Y-m-d'));
        $days_array = [];
        $rentals = [];
        foreach ($books as $book) {
            $date_return = strtotime($book['return_on']);
            $days = ($date_return - $date_now) / 86400;
            if ($days < 0) {
                $days = -1;
            }
            $days_array[] = $days;
        }
        $days_array = array_values(array_unique($days_array));
        foreach ($days_array as $day) {
            if ($day == -1) {
                $rentals[$day] = array(
                    'days_left' => '貸出期限切れ',
                    'comments' => '※借りている本を返すまで本を貸出しできません。',
                    'day' => $day,
                    'books' => []
                );
            } else if ($day == 0) {
                $rentals[$day] = array(
                    'days_left' => '本日中',
                    'comments' => '',
                    'day' => $day,
                    'books' => []
                );
            } else {
                $rentals[$day] = array(
                    'days_left' => 'あと' . $day . '日',
                    'comments' => '',
                    'day' => $day,
                    'books' => []
                );
            }
        }
        foreach ($books as $book) {
            $date_return = strtotime($book['return_on']);
            $days = ($date_return - $date_now) / 86400;
            if ($days < 0) {
                $days = -1;
            }
            $rentals[$days]['books'][] = $book;
        }
        return array_values($rentals);
    }
}