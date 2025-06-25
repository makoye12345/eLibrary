@extends('layouts.user')

@section('content')
<div class="container">
    <h2 class="text-center">📩 Messages</h2>

    <div class="row">
        <!-- Send Message Form -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Send New Message</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('user.messages.store') }}" class="mb-4" id="messageForm">
                        @csrf
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
                        </div>

                        <div class="form-group mt-3">
                            <label>Send to</label>
                            <p class="form-control-static">Admin</p>
                            <input type="hidden" name="recipient_id" value="{{ $admin_id }}">
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Recent Messages</div>
                <div class="card-body">
                    <div class="row">
                        @forelse($messages as $message)
                            <div class="col-12 mb-3 border-bottom pb-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1"><strong>{{ $message->created_at->diffForHumans() }}</strong></p>
                                        <p class="mb-1">{{ $message->message }}</p>
                                        <small class="text-muted">
                                            {{ $message->user_id == auth()->id() ? 'Sent to: Admin' : 'From: Admin' }}
                                        </small>
                                    </div>

                                    <div class="text-right">
                                        @if($message->user_id != auth()->id())
                                            <button class="btn btn-sm btn-primary reply-btn"
                                                    data-message-id="{{ $message->id }}"
                                                    data-sender-id="{{ $message->user_id }}"
                                                    data-message="{{ \Illuminate\Support\Str::limit($message->message, 20) }}">
                                                Reply
                                            </button>
                                        @endif

                                        <form action="{{ route('user.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this message?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Reply Box (hidden by default) -->
                                <div id="replyBox{{ $message->id }}" class="reply-box mt-2" style="display: none;">
                                    <form method="POST" action="{{ route('user.messages.store') }}">
                                        @csrf
                                        <input type="hidden" name="recipient_id" value="{{ $message->user_id }}">

                                        <div class="form-group">
                                            <textarea name="message" rows="3" class="form-control" placeholder="Type your reply..."></textarea>
                                        </div>

                                        <div class="form-group d-flex gap-2">
                                            <button type="submit" class="btn btn-sm btn-success">Send</button>
                                            <button type="button" class="btn btn-sm btn-secondary cancel-reply" data-box-id="{{ $message->id }}">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted">No recent messages found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Reply button logic
        document.querySelectorAll('.reply-btn').forEach(button => {
            button.addEventListener('click', function () {
                const messageId = this.dataset.messageId;
                const replyBox = document.getElementById('replyBox' + messageId);

                // Hide other reply boxes
                document.querySelectorAll('.reply-box').forEach(box => box.style.display = 'none');

                // Show selected reply box
                if (replyBox) {
                    replyBox.style.display = 'block';
                    replyBox.querySelector('textarea').focus();
                }
            });
        });

        // Cancel reply
        document.querySelectorAll('.cancel-reply').forEach(button => {
            button.addEventListener('click', function () {
                const boxId = this.dataset.boxId;
                const replyBox = document.getElementById('replyBox' + boxId);
                if (replyBox) replyBox.style.display = 'none';
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
        margin-top: 15px;
    }
    .reply-box textarea {
        resize: vertical;
    }
    .form-control-static {
        padding: 0.375rem 0.75rem;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
</style>
@endsection