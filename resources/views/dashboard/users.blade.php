@extends('layouts.master')
@section('content')
    @include('layouts.flash-messages')
    <div class="row">
        <form method="POST" enctype="multipart/form-data" action="{{ route('customer-import') }}">
            {{ csrf_field() }}
            <input type="file" id="uploadName" name="customer_upload" required>
            <button type="submit" class="btn btn-info btn-primary ">Import Excel File</button>

        </form>
        <div class="container">
            {{ Auth::check() ? Auth::user()->name : '' }}

            <div class="container">
                <hr>
                <h2>users list</h2>
                {{ $dataTable->table() }}
            </div>
        </div>
        {{ $dataTable->scripts() }}
    @endsection
