@extends('layouts.app')

@section('activeFooter', 'mypage')
@section('content')
  <pages-login :csrf="{{ json_encode(csrf_token()) }}" :old="{{ json_encode(Session::getOldInput()) }}" :errors="{{ $errors }}"></pages-login>
@endsection
