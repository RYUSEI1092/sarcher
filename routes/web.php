<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
//メインページ表示処理
Route::get("/", [RentalController::class, "index"]);
//蔵書検索ページ表示処理
Route::get("/books", [RentalController::class, "books"]);//->middleware("logincheck");
//蔵書詳細ページ表示処理
Route::get("/books/{isbn}", [RentalController::class, "showBooksDetail"]);
//本棚ページ表示処理
Route::get("/bookshelf", [RentalController::class, "bookshelf"]);
//本棚ページ返却処理
Route::post("/bookshelf", [RentalController::class, "returnedBooks"]);
//レンタル処理
Route::post("/books/{isbn}", [RentalController::class, "addRental"]);

/* //マイページ
Route::get("/profile", [UserController::class, "showDetail"]);
//マイページ編集
Route::get("/profile/edit/", [UserController::class, "prepareEdit"]);
//マイデータ更新
Route::post("/profile/edit/", [UserController::class, "edit"]);
//パスワード変更ページ表示
Route::get("/password/reset", [UserController::class, "prepareEditPassword"]);
//パスワード更新
Route::post("/password/reset", [UserController::class, "editPassword"]); */

Route::fallback(function() {
    return response()->view('errors.404', [], 404);
});
