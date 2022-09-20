@extends('layouts.app')

@section('content')
    Welcome {{ auth()->user()->name }}
@endsection