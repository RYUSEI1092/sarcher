@extends('layouts.app')

@section('content')
  <pages-bookshelf :csrf="{{ json_encode(csrf_token()) }}" :rentals="{{ json_encode($rentals) }}"
    :is-update="{{ json_encode($isUpdate) }}">
  </pages-bookshelf>
@endsection

@section('activeFooter', 'bookshelf')