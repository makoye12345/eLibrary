@extends('layouts.admin')

@section('content')
<h2 class="text-center mb-4">📩 Send Message</h2>

<div class="row">
    <!-- Send Message Form -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">New Message</div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.messages.store') }}" id="messageForm">
                    @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
                        @error('message')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="send_to" class="form-label">Send To</label>
                        <select name="send_to" id="send_to" class="form-select">
                            <option value="all">Everyone</option>
                            <option value="user">Specific User</option>
                        </select>
                        @error('send_to')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="user_select" style="display:none;">
                        <label for="user_id" class="form-label">Select User</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">Choose a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Messages</div>
            <div class="card-body">
                @forelse($messages as $message)
                    <div class="mb-3 border-bottom pb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1"><strong>{{ $message->created_at->diffForHumans() }}</strong></p>
                                <p class="mb-1">{{ $message->message }}</p>
                                <small class="text-muted">
                                    {{ $message->send_to === 'all' ? 'Sent to: Everyone' : 'Sent to: ' . ($message->recipient->name ?? 'None') }}
                                    (From: {{ $message->user->name ?? 'Admin' }})
                                </small>
                            </div>

                            <div>
                                @if($message->recipient_id && $message->user_id === Auth::id())
                                    <button class="btn btn-sm btn-primary reply-btn" data-id="{{ $message->id }}">Reply</button>
                                @endif

                                <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div id="replyBox{{ $message->id }}" class="reply-box mt-2" style="display: none;">
                            <form method="POST" action="{{ route('admin.messages.store') }}">
                                @csrf
                                <input type="hidden" name="send_to" value="user">
                                <input type="hidden" name="user_id" value="{{ $message->recipient_id }}">

                                <div class="mb-3">
                                    <textarea name="message" rows="3" class="form-control" placeholder="Write your reply..."></textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-success">Send</button>
                                    <button type="button" class="btn btn-sm btn-secondary cancel-reply" data-id="{{ $message->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">No recent messages.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sendTo = document.getElementById('send_to');
        const userSelect = document.getElementById('user_select');
        sendTo.addEventListener('change', () => {
            userSelect.style.display = sendTo.value === 'user' ? 'block' : 'none';
        });

        document.querySelectorAll('.reply-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const replyBox = document.getElementById('replyBox' + id);
                document.querySelectorAll('.reply-box').forEach(box => box.style.display = 'none');
                replyBox.style.display = 'block';
                replyBox.querySelector('textarea').focus();
            });
        });

        document.querySelectorAll('.cancel-reply').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                document.getElementById('replyBox' + id).style.display = 'none';
            });
        });
    });
</script>
@endsection

@section('styles')
<style>
    .reply-box {
        background-color: #f8f9fc;
        padding: 15px;
        border-radius: 5px;
        margin-top: 10px;
    }
    .reply-box textarea {
        resize: vertical;
    }
</style>
@endsection
