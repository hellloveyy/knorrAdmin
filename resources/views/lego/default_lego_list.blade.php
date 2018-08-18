@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="ibox">

        <div class="ibox-title">

            <h2>{{ $title or '筛选' }}</h2></div>

        <div class="ibox-content">
            {!! $grid !!}
        </div>
    </div>
@endsection
