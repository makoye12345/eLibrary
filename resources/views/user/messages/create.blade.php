@extends('layouts.user')

@section('content')
<div class="container">
    <h2 class="welcome-message">Compose Message</h2>

    <div class="card">
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
    }
</style>
@endsection
