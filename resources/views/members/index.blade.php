@extends('layouts.app')

@section('header', 'Members')

@section('content')
    <div class="">
        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Members</span>
            @if (Auth::user()->hasRole('admin'))
                <div>
                    <a href="{{ route('members.create') }}" class="add-person-action">Add Member</a>
                </div>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        {{-- <th>Business Share</th> --}}
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $member->name }}
                                @if ($member->user->isAdmin())
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-primary">Member</span>
                                @endif
                            </td>
                            <td>{{ $member->user->email }}</td>
                            <td>{{ $member->mobile_number }}</td>
                            {{-- <td>{{ $member->business_share }}</td> --}}
                            <td>
                                @if ($member->user->isActive())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            <td>
                                <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info">View</a>
                                @if (Auth::user()->hasRole('admin'))
                                    <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">No member found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


@endsection
