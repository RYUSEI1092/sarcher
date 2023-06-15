@extends('layouts.app')

@section('content')
<pages-main :favorites='@json($favorites)' :histories='@json($histories)' :register-success="{{ var_export($registerSuccess) }}"></pages-main>
@endsection

@section('activeFooter', 'home')

