@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <h1 class="mb-4">Settings</h1>

    <!-- Settings Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Library Settings</h5>
            <form id="settingsForm" class="g-3">
                <!-- Library Settings -->
                <div class="mb-4">
                    <h6>General</h6>
                    <div class="mb-3">
                        <label for="libraryName" class="form-label">Library Name</label>
                        <input type="text" class="form-control" id="libraryName" placeholder="Enter library name" required>
                    </div>
                    <div class="mb-3">
                        <label for="maxBorrowDays" class="form-label">Maximum Borrowing Duration (Days)</label>
                        <input type="number" class="form-control" id="maxBorrowDays" min="1" required>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="mb-4">
                    <h6>Notifications</h6>
                    <div class="mb-3">
                        <label for="notificationDays" class="form-label">Automatic Notification (Days Before Return)</label>
                        <input type="number" class="form-control" id="notificationDays" min="1" required>
                    </div>
                </div>

                <!-- User Management Settings -->
                <div class="mb-4">
                    <h6>User Management</h6>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="allowRegistrations">
                        <label class="form-check-label" for="allowRegistrations">Allow New User Registrations</label>
                    </div>
                    <div class="mb-3">
                        <label for="maxBooksPerUser" class="form-label">Maximum Books Per User</label>
                        <input type="number" class="form-control" id="maxBooksPerUser" min="1" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Settings
                </button>
            </form>
        </div>
    </div>

    <!-- Current Settings Display -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Current Settings</h5>
            <ul class="list-group">
                <li class="list-group-item"><strong>Library Name:</strong> <span id="currentLibraryName">Not set</span></li>
                <li class="list-group-item"><strong>Maximum Borrowing Duration:</strong> <span id="currentMaxBorrowDays">Not set</span> days</li>
                <li class="list-group-item"><strong>Automatic Notification:</strong> <span id="currentNotificationDays">Not set</span> days before return</li>
                <li class="list-group-item"><strong>Allow New User Registrations:</strong> <span id="currentAllowRegistrations">Not set</span></li>
                <li class="list-group-item"><strong>Maximum Books Per User:</strong> <span id="currentMaxBooksPerUser">Not set</span></li>
            </ul>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmSettingsModal" tabindex="-1" aria-labelledby="confirmSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmSettingsModalLabel">Confirm Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to save these settings?</p>
                    <ul>
                        <li><strong>Library Name:</strong> <span id="confirmLibraryName"></span></li>
                        <li><strong>Maximum Borrowing Duration:</strong> <span id="confirmMaxBorrowDays"></span> days</li>
                        <li><strong>Automatic Notification:</strong> <span id="confirmNotificationDays"></span> days before return</li>
                        <li><strong>Allow New User Registrations:</strong> <span id="confirmAllowRegistrations"></span></li>
                        <li><strong>Maximum Books Per User:</strong> <span id="confirmMaxBooksPerUser"></span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSaveButton">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    #confirmSettingsModal .modal-content {
        background-color: #ffffff;
    }
    .card-body {
        padding: 1.5rem;
    }
    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Load settings from localStorage or set defaults
    let settings = JSON.parse(localStorage.getItem('settings')) || {
        libraryName: "My Library",
        maxBorrowDays: 14,
        notificationDays: 3,
        allowRegistrations: true,
        maxBooksPerUser: 5
    };

    function saveSettingsToStorage() {
        localStorage.setItem('settings', JSON.stringify(settings));
    }

    function loadSettings() {
        // Populate form fields
        document.getElementById('libraryName').value = settings.libraryName;
        document.getElementById('maxBorrowDays').value = settings.maxBorrowDays;
        document.getElementById('notificationDays').value = settings.notificationDays;
        document.getElementById('allowRegistrations').checked = settings.allowRegistrations;
        document.getElementById('maxBooksPerUser').value = settings.maxBooksPerUser;

        // Update current settings display
        document.getElementById('currentLibraryName').textContent = settings.libraryName;
        document.getElementById('currentMaxBorrowDays').textContent = settings.maxBorrowDays;
        document.getElementById('currentNotificationDays').textContent = settings.notificationDays;
        document.getElementById('currentAllowRegistrations').textContent = settings.allowRegistrations ? 'Yes' : 'No';
        document.getElementById('currentMaxBooksPerUser').textContent = settings.maxBooksPerUser;
    }

    document.getElementById('settingsForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const newSettings = {
            libraryName: document.getElementById('libraryName').value.trim(),
            maxBorrowDays: parseInt(document.getElementById('maxBorrowDays').value),
            notificationDays: parseInt(document.getElementById('notificationDays').value),
            allowRegistrations: document.getElementById('allowRegistrations').checked,
            maxBooksPerUser: parseInt(document.getElementById('maxBooksPerUser').value)
        };

        // Validate inputs
        if (!newSettings.libraryName) {
            alert('Library name is required.');
            return;
        }
        if (newSettings.maxBorrowDays < 1) {
            alert('Maximum borrowing duration must be at least 1 day.');
            return;
        }
        if (newSettings.notificationDays < 1) {
            alert('Notification days must be at least 1 day.');
            return;
        }
        if (newSettings.maxBooksPerUser < 1) {
            alert('Maximum books per user must be at least 1.');
            return;
        }

        // Show confirmation modal
        document.getElementById('confirmLibraryName').textContent = newSettings.libraryName;
        document.getElementById('confirmMaxBorrowDays').textContent = newSettings.maxBorrowDays;
        document.getElementById('confirmNotificationDays').textContent = newSettings.notificationDays;
        document.getElementById('confirmAllowRegistrations').textContent = newSettings.allowRegistrations ? 'Yes' : 'No';
        document.getElementById('confirmMaxBooksPerUser').textContent = newSettings.maxBooksPerUser;

        const modal = new bootstrap.Modal(document.getElementById('confirmSettingsModal'));
        modal.show();

        // Handle confirm save
        document.getElementById('confirmSaveButton').onclick = function () {
            settings = newSettings;
            saveSettingsToStorage();
            loadSettings();
            modal.hide();
            alert('Settings saved successfully!');
        };
    });

    // Initial load
    loadSettings();
</script>
@endsection