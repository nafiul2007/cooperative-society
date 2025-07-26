@extends('layouts.app')

@section('header', 'Member List')

@section('content')
    <div class="">
        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Manage Member</span>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('members.create') }}" class="btn btn-primary">Add New Member</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Business Share</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->user->email }}</td>
                            <td>{{ $member->mobile_number }}</td>
                            <td>{{ $member->business_share }}</td>
                            <td>
                                @if ($member->user->isActive())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            <td>
                                <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning">Edit</a>

                                @if (!$member->user->isAdmin())
                                    @if ($member->user->isActive())
                                        <!-- Disable button triggers modal -->
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#disableConfirmModal"
                                            data-action="{{ route('members.disable', $member) }}">
                                            Disable
                                        </button>
                                    @else
                                        <!-- Enable button -->
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#enableConfirmModal"
                                            data-action="{{ route('members.enable', $member) }}">
                                            Enable
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="disableConfirmModal" tabindex="-1" aria-labelledby="disableConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="disableForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="disableConfirmModalLabel">Confirm Disable</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to disable this member?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Disable</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enable Confirmation Modal -->
    <div class="modal fade" id="enableConfirmModal" tabindex="-1" aria-labelledby="enableConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="enableForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="enableConfirmModalLabel">Confirm Enable</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to enable this member?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Enable</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap modal action scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Disable modal setup
            var disableConfirmModal = document.getElementById('disableConfirmModal');
            disableConfirmModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var action = button.getAttribute('data-action');
                var form = disableConfirmModal.querySelector('#disableForm');
                form.action = action;
            });

            // Enable modal setup
            var enableConfirmModal = document.getElementById('enableConfirmModal');
            enableConfirmModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var action = button.getAttribute('data-action');
                var form = enableConfirmModal.querySelector('#enableForm');
                form.action = action;
            });
        });
    </script>

@endsection
