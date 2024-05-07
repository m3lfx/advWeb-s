@extends('layouts.master')
@section('content')
    <p>order shipped</p>
    <h2>{{ $orderTotal }}</h2>
    {{dump($order)}}
@endsection