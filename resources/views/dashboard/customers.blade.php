@extends('layouts.master')
@section('content')
@include('layouts.flash-messages')
    <div class="row">
        
        <div class="container">
            {{Auth::check() ? Auth::user()->name : '' }}
            <hr>
            <h2>customers list</h2>
            {{$dataTable->table()}}
          
           
        </div>
       
    </div>
    
    {{$dataTable->scripts()}}
@endsection