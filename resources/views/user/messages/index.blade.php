@extends('layouts.user')

@section('content')
<div class="container">
    <h2 class="welcome-message">📨 Message Center</h2>

    {{-- Compose Message Form --}}
    <div class="card mb-4">
        <div class="card-header">Compose Message</div>
        <div class="card-body">
            <form action="{{ route('user.messages.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Recipient</label>
                    <select name="recipient_id" class="form-select" required>
                        <option value="">Select a user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->email }}) {{ $user->isAdmin() ? '[Admin]' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('recipient_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Message</label>
                    <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>

    {{-- Messages Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>All Messages</span>
        </div>
        <div class="card-body">
            @if($messages->isEmpty())
                <div class="text-danger text-center">No messages found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $index => $message)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $message->sender ? $message->sender->name : 'Deleted User' }}</td>
                                    <td>
                                        {{ $message->is_broadcast ? 'All Users' : ($message->recipient ? $message->recipient->name : 'Deleted User') }}
                                    </td>
                                    <td>{{ Str::limit($message->content, 50) }}</td>
                                    <td>{{ $message->created_at->format('m/d/Y H:i') }}</td>
                                    <td>
                                        @if($message->recipient_id == Auth::id() && !$message->read_at)
                                            <span class="badge bg-warning">Unread</span>
                                        @else
                                            <span class="badge bg-success">Read</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($message->recipient_id == Auth::id() && !$message->read_at)
                                            <a href="{{ route('user.messages.markAsRead', $message->id) }}" class="btn btn-sm btn-success">
                                                Mark as Read
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
    }
    .welcome-message {
        font-size: 1.75rem;
        color: #1e3c72;
        margin-bottom: 20px;
        text-align: center;
    }
    .card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .table th, .table td {
        padding: 12px;
        vertical-align: middle;
    }
    .table thead th {
        background: #1e3c72;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    .table tbody tr:hover {
        background: #f8f9fa;
    }
    .badge {
        font-size: 0.85rem;
        padding: 6px 12px;
    }
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 0.85rem;
            padding: 8px;
        }
    }
    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.8rem;
        }
        .table th, .table td {
            padding: 6px;
        }
    }
</style>
@endsection
