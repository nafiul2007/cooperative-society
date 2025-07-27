@extends('layouts.app')

@section('header', 'Contributions')

@section('content')
    <div class="">
        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Contributions</span>
            <div>
                <a href="{{ route('contributions.create') }}" class="add-person-action">Add Contribution</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Member Name</th>
                        <th>Amount</th>
                        <th>Contribution Date</th>
                        <th>Status</th>
                        {{-- <th>Files</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contributions as $contribution)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contribution->user->member->name ?? 'N/A' }}</td>
                            <td>{{ $contribution->amount ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($contribution->contribution_date)->format('d M Y') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $contribution->status === 'approved' ? 'success' : ($contribution->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($contribution->status) }}
                                </span>
                            </td>
                            {{-- <td>
                                @if ($contribution->files)
                                    @foreach ($contribution->files as $file)
                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                            target="_blank">{{ basename($file->file_path) }}</a><br>
                                    @endforeach
                                @else
                                    <span class="text-muted">No files</span>
                                @endif
                            </td> --}}
                            <td>
                                <a href="{{ route('contributions.show', $contribution) }}" class="btn btn-sm btn-info">View</a>
                                @if ($contribution->status === 'pending' && $contribution->user_id === auth()->id())
                                    <a href="{{ route('contributions.edit', $contribution) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">No contributions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
