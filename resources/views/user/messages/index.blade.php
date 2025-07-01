@extends('layouts.user')

@section('content')
<div class="content">
    <div class="dashboard-container">
        <h2 class="welcome-message">Your Messages</h2>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        {{-- Compose Form --}}
        <div class="books-table-container mb-4">
            <h4>Compose Message</h4>
            <form action="{{ route('user.messages.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="recipient_id" class="form-label">Recipient</label>
                    <select name="recipient_id" id="recipient_id" class="form-select" required>
                        <option value="">Select a user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('recipient_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('recipient_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea name="message" id="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Send Message
                </button>
            </form>
        </div>

        {{-- Message List --}}
        <div class="books-table-container">
            <h4>Received Messages</h4>
            @if($messages->isEmpty())
                <div class="text-danger text-center">No messages found.</div>
            @else
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover align-middle" style="font-size: 0.9em; table-layout: fixed; width: 100%;">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 20%;">From</th>
                                <th style="width: 40%;">Message</th>
                                <th style="width: 15%;">Date</th>
                                <th style="width: 20%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $index => $message)
                                <tr style="height: 50px; {{ is_null($message->read_at) ? 'font-weight: bold;' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $message->sender ? $message->sender->name : ($message->name ?? 'Admin') }}</td>
                                    <td>{{ Str::limit($message->message, 50) }}</td>
                                    <td>{{ $message->created_at->format('m/d/Y H:i') }}</td>
                                    <td>
                                        @if(is_null($message->read_at))
                                            <a href="{{ route('user.messages.read', $message->id) }}" class="btn btn-sm btn-info">Mark as Read</a>
                                        @endif
                                        @if($message->sender_id)
                                            <button type="button" class="btn btn-sm btn-primary reply-btn" data-message-id="{{ $message->id }}">Reply</button>
                                        @endif
                                    </td>
                                </tr>
                                @if($message->sender_id)
                                    <tr id="reply-box-{{ $message->id }}" class="reply-box" style="display: none;">
                                        <td colspan="5">
                                            <form action="{{ route('user.messages.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="recipient_id" value="{{ $message->sender_id }}">
                                                <div class="mb-3">
                                                    <textarea name="message" class="form-control" rows="3" placeholder="Write your reply..." required></textarea>
                                                    @error('message')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-sm btn-success">Send Reply</button>
                                                    <button type="button" class="btn btn-sm btn-secondary cancel-reply" data-message-id="{{ $message->id }}">Cancel</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
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
    .content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .dashboard-container {
        max-width: 100%;
        margin: 0;
    }
    .welcome-message {
        font-size: 1.75rem;
        color: #1e3c72;
        font-weight: 600;
        text-align: center;
        margin-bottom: 30px;
    }
    .books-table-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        animation: slideDown 0.8s ease-out forwards;
    }
    .form-label {
        font-weight: 600;
    }
    .form-control, .form-select {
        border-radius: 6px;
    }
    .text-danger {
        font-size: 0.9rem;
    }
    th, td {
        padding: 0.5rem !important;
        vertical-align: middle;
    }
    tbody tr {
        height: 50px;
    }
    .table-responsive {
        -webkit-overflow-scrolling: touch;
    }
    .alert {
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 6px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }
    .reply-box {
        background-color: #f8f9fc;
        padding: 15px;
        border-radius: 5px;
    }
    .reply-box textarea {
        resize: vertical;
    }
    @keyframes slideDown {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
</style>
@endsection

@section('scripts')
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        try {
            // Find all reply buttons
            const replyButtons = document.querySelectorAll('.reply-btn');
            console.log('Reply buttons found:', replyButtons.length);

            replyButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const messageId = this.getAttribute('data-message-id');
                    console.log('Reply button clicked for message ID:', messageId);
                    
                    // Hide all reply boxes
                    document.querySelectorAll('.reply-box').forEach(box => {
                        box.style.display = 'none';
                    });

                    // Show the clicked reply box
                    const replyBox = document.getElementById('reply-box-' + messageId);
                    if (replyBox) {
                        replyBox.style.display = 'table-row';
                        replyBox.querySelector('textarea').focus();
                        console.log('Reply box shown for message ID:', messageId);
                    } else {
                        console.error('Reply box not found for ID: reply-box-' + messageId);
                    }
                });
            });

            // Find all cancel buttons
            const cancelButtons = document.querySelectorAll('.cancel-reply');
            console.log('Cancel buttons found:', cancelButtons.length);

            cancelButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const messageId = this.getAttribute('data-message-id');
                    console.log('Cancel button clicked for message ID:', messageId);
                    
                    const replyBox = document.getElementById('reply-box-' + messageId);
                    if (replyBox) {
                        replyBox.style.display = 'none';
                        console.log('Reply box hidden for message ID:', messageId);
                    } else {
                        console.error('Reply box not found for ID: reply-box-' + messageId);
                    }
                });
            });
        } catch (error) {
            console.error('Error in reply button script:', error);
        }
    });
</script>
@endsection
