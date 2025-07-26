@extends('layouts.app')

@section('header', 'Member Details')

@section('content')
    <div class="">
        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Member Details</span>
        </div>
        <div class="card-body">
            <h5 class="card-title">User: {{ $member->name }}</h5>
            <p class="card-text">NID: {{ $member->nid ?? '-' }}</p>
            <p class="card-text">Date of Birth: {{ $member->date_of_birth ?? '-' }}</p>
            <p class="card-text">TIN: {{ $member->tin ?? '-' }}</p>
            <p class="card-text">Business Share: {{ $member->business_share ?? '-' }}</p>

            <a href="{{ route('members.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('members.edit', $member) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
@endsection
