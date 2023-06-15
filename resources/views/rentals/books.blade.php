@extends('layouts.app')

@section('content')
<pages-books :books-state="{{ $books }}"></pages-books>
@endsection

@section('activeFooter', 'search')