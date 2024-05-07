@extends('layouts.master')

@section('content')
    <div class="container">
        {!! Form::open(['route' => ['items.update', $item->item_id], 'enctype' => 'multipart/form-data',  'method' => 'PUT']) !!}
        {!! Form::label('description', 'Description') !!}
        {!! Form::text('description', $item->description, ['class' => 'form-control']) !!}
        {!! Form::label('cost_price', 'cost price', ) !!}
        {!! Form::text('cost_price', $item->cost_price, ['class' => 'form-control']) !!}
        {!! Form::label('sell_price', 'sell price') !!}
        {!! Form::text('sell_price', $item->sell_price, ['class' => 'form-control']) !!}
        {!! Form::label('quantity', 'quantity') !!}
        {!! Form::number('quantity', $item->quantity, ['class' => 'form-control']) !!}
        {!! Form::label('img_path', 'upload image', ['class' => 'form-control']) !!}
        {!! Form::file('img_path',  ['class' => 'form-control']) !!}
        @error('img_path')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <img src="{{ url($item->img_path) }}" alt="item image" width="50" height="50">
        {!! Form::submit('submit', ['class' => 'btn btn-primary']) !!}
        <a class="btn btn-secondary" href="{{route('items.index')}}" role="button">cancel</a>
        {!! Form::close() !!}
    </div>
@endsection
