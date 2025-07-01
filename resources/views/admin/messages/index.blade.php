@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="dashboard-container">
        <h2 class="welcome-message">Admin Messages</h2>

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
            <form action="{{ route('admin.messages.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Recipient</label>
                    <select name="recipient_id" class="form-select" id="recipientSelect">
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
                <div class="mb-3 form-check">
                    {{-- Ongeza value="1" kwenye checkbox na default ya old --}}
                    <input type="checkbox" name="is_broadcast" id="isBroadcast" class="form-check-input" value="1" {{ old('is_broadcast', false) ? 'checked' : '' }}>
                    <label for="isBroadcast" class="form-check-label">Send to all users</label>
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

        ---

        {{-- Message List --}}
        <div class="books-table-container">
            <h4>All Sent/Received Messages</h4>
            @if($messages->isEmpty())
                <div class="text-danger text-center">No messages found.</div>
            @else
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover align-middle" style="font-size: 0.9em; table-layout: fixed; width: 100%;">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 20%;">From</th>
                                <th style="width: 20%;">To</th>
                                <th style="width: 35%;">Message</th>
                                <th style="width: 15%;">Date</th>
                                <th style="width: 5%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $index => $message)
                                <tr style="height: 50px;">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $message->sender ? $message->sender->name : ($message->name ?? 'Unknown') }}</td>
                                    {{-- Kurekebisha jinsi 'To' inavyoonyeshwa kwa ujumbe wa broadcast --}}
                                    <td>
                                        @if($message->is_broadcast && is_null($message->recipient_id))
                                            All Users (Broadcast)
                                        @else
                                            {{ $message->recipient ? $message->recipient->name : ($message->email ?? 'None') }}
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($message->message, 50) }}</td>
                                    <td>{{ $message->created_at->format('m/d/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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

---

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .content {
        padding: 30px 20px;
        background-color: white;
        min-height: calc(100vh - 140px);
    }
    .dashboard-container {
        max-width: 1000px;
        margin: 0 auto;
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
    @keyframes slideDown {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
</style>
@endsection

---

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const broadcastCheckbox = document.getElementById('isBroadcast');
        const recipientSelect = document.getElementById('recipientSelect');

        function toggleRecipient() {
            recipientSelect.disabled = broadcastCheckbox.checked;
            if (broadcastCheckbox.checked) {
                recipientSelect.value = ''; // Futa chaguo la mtumiaji binafsi wakati broadcast imechaguliwa
            }
        }

        if (broadcastCheckbox) {
            broadcastCheckbox.addEventListener('change', toggleRecipient);
            toggleRecipient(); // Piga function mara moja wakati ukurasa umefunguliwa ili kuweka hali sahihi
        }
    });
</script>
@endsection