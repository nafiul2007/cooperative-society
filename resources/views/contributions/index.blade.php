@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>My Contributions</h2>

        <a href="{{ route('contributions.create') }}" class="btn btn-primary mb-3">New Contribution</a>

        @if ($contributions->isEmpty())
            <div class="alert alert-info">No contributions found.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Contribution Date</th>
                        <th>Status</th>
                        <th>Files</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contributions as $contribution)
                        <tr>
                            <td>{{ $contribution->user->member->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($contribution->contribution_date)->format('d M Y') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $contribution->status === 'approved' ? 'success' : ($contribution->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($contribution->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($contribution->files)
                                    @foreach ($contribution->files as $file)
                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                            target="_blank">{{ basename($file->file_path) }}</a><br>
                                    @endforeach
                                @else
                                    <span class="text-muted">No files</span>
                                @endif
                            </td>
                            <td>
                                @if ($contribution->status === 'pending' && $contribution->user_id === auth()->id())
                                    <a href="{{ route('contributions.edit', $contribution) }}"
                                        class="btn btn-sm btn-outline-info">Edit</a>
                                @endif
                                    <a href="{{ route('contributions.show', $contribution) }}"
                                        class="btn btn-sm btn-outline-secondary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
