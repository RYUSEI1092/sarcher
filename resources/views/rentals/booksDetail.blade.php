
@extends('layouts.app')

@section('content')
<pages-booksdetail :is-authorized="{{ var_export(Auth::check()) }}" :csrf="{{ json_encode(csrf_token()) }}" :books="{{$books}}" :libraries="{{$libraries}}" :is-create="{{ json_encode($isCreate) }}"></pages-booksdetail>
@endsection

@section('activeFooter', 'search')
