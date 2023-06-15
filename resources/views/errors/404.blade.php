@extends('layouts.app')

@section('activeFooter', 'home')
@section('content')
  <pages-error :error-code="404"></pages-error>
@endsection
