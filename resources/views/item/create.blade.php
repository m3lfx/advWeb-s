@extends('layouts.master')

@section('content')
    <div class="container">
        {!! Form::open(['route' => 'items.store', 'enctype' => 'multipart/form-data']) !!}
        {!! Form::label('description', 'Description') !!}
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
        {!! Form::label('cost_price', 'cost price', ) !!}
        {!! Form::text('cost_price', null, ['class' => 'form-control']) !!}
        {!! Form::label('sell_price', 'sell price') !!}
        {!! Form::text('sell_price', null, ['class' => 'form-control']) !!}
        {!! Form::label('quantity', 'quantity') !!}
        {!! Form::number('quantity', null, ['class' => 'form-control']) !!}
        {!! Form::label('img_path', 'upload image', ['class' => 'form-control']) !!}
        {!! Form::file('img_path',  ['class' => 'form-control']) !!}
        @error('img_path')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        {!! Form::submit('submit', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@endsection
