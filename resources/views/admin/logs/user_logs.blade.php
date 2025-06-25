@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">User Access Logs</h2>

    <!-- Form to select a user -->
    <form action="{{ route('admin.logs.user') }}" method="GET" class="mb-4">
        <div class="form-group">
            <label for="user_id">Select User</label>
            <select name="user_id" id="user_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Select a User --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $selectedUser && $selectedUser->id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Display logs if a user is selected -->
    @if ($selectedUser)
        <h3>Logs for {{ $selectedUser->name }}</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>🕒 Timestamp</th>
                    <th>💻 Platform</th>
                    <th>🌐 IP Address</th>
                    <th>🛠️ Browser</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->platform }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->browser }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No logs found for this user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
    @else
        <p class="text-muted">Please select a user to view their logs.</p>
    @endif
</div>
@endsection