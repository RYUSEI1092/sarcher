@extends('layouts.app')

@section('activeFooter', 'mypage')
@section('content')
  <pages-register :csrf="{{ json_encode(csrf_token()) }}" :old="{{ json_encode(Session::getOldInput()) }}" :errors="{{ $errors }}"></pages-register>
@endsection
