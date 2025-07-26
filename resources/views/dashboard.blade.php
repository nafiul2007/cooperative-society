@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    Dashboard
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center card-header">
        <span>Welcome</span>
    </div>
    <div class="card-body" style="text-align: center">
        <x-application-logo class="me-3" style="height: auto; width: 50%;" />
    </div>
@endsection
