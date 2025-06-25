<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Library Management System - @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            transition: all 0.3s;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .nav-bar {
            background-color: #ee9919;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-bar .header-title {
            color: #2c3e50;
            font-size: clamp(18px, 5vw, 22px);
            font-weight: bold;
            margin: 0;
        }

        .hamburger-btn, .minimize-btn {
            background: none;
            border: none;
            color: #2c3e50;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .hamburger-btn:hover, .minimize-btn:hover {
            color: #1a252f;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            height: 100%;
            transition: width 0.3s ease, transform 0.3s ease;
            position: fixed;
            top: 60px;
            left: 0;
            z-index: 999;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .nav-link-text,
        .sidebar.collapsed .username,
        .sidebar.collapsed .status {
            display: none;
        }

        .sidebar.collapsed .nav-item {
            text-align: center;
        }

        .sidebar.collapsed .nav-item a {
            padding: 12px 5px;
        }

        .profile-section {
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .profile-section img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            cursor: pointer;
        }

        .profile-section .username {
            font-size: 16px;
            font-weight: bold;
        }

        .profile-section .status {
            font-size: 12px;
            color: #28a745;
        }

        .sidebar a {
            color: white;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 14px;
            white-space: nowrap;
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar .active a {
            background-color: #007bff;
        }

        .sidebar i {
            min-width: 25px;
            text-align: center;
            margin-right: 10px;
        }

        .content-area {
            flex-grow: 1;
            margin-left: 250px;
            padding: 15px;
            transition: margin-left 0.3s ease;
        }

        .content-area.sidebar-collapsed {
            margin-left: 70px;
        }

        .content {
            flex-grow: 1;
            min-height: calc(100vh - 60px);
        }

        .nav-divider {
            height: 1px;
            background-color: rgba(255,255,255,0.1);
            margin: 15px 0;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
            margin-right: 15px;
        }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .header-profile:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .header-profile img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        .header-profile span {
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
        }

        .admin-profile-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
        }

        .profile-image-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 4px solid #f0f0f0;
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-detail-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .profile-detail-item:last-child {
            border-bottom: none;
        }

        .profile-detail-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        .profile-detail-value {
            color: #333;
        }

        .edit-profile-form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0069d9;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 70px;
                transform: translateX(0);
            }

            .content-area {
                margin-left: 0;
            }

            .content-area.sidebar-active {
                margin-left: 250px;
            }

            .content-area.sidebar-collapsed {
                margin-left: 70px;
            }

            .hamburger-btn {
                display: block;
            }

            .minimize-btn {
                display: block;
            }

            .header-profile span {
                display: none;
            }

            .nav-bar .header-title {
                font-size: 18px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
            }

            .sidebar.collapsed {
                width: 70px;
                transform: translateX(0);
            }

            .content-area {
                margin-left: 0;
            }

            .content-area.sidebar-active {
                margin-left: 250px;
            }

            .content-area.sidebar-collapsed {
                margin-left: 70px;
            }

            .content {
                padding: 10px;
            }

            .admin-profile-card, .edit-profile-form {
                padding: 15px;
            }

            .profile-image-container {
                width: 100px;
                height: 100px;
            }

            .form-control {
                font-size: 12px;
            }

            .nav-bar {
                padding: 8px 10px;
            }
        }

        @media (min-width: 993px) {
            .hamburger-btn {
                display: none;
            }

            .sidebar {
                transform: translateX(0);
                position: fixed;
            }

            .content-area {
                margin-left: 250px;
            }

            .content-area.sidebar-collapsed {
                margin-left: 70px;
            }
        }

        @media (max-width: 576px) {
            .content {
                padding: 8px;
            }

            .admin-profile-card, .edit-profile-form {
                padding: 10px;
            }

            .profile-image-container {
                width: 80px;
                height: 80px;
            }

            .form-control {
                font-size: 11px;
                padding: 8px;
            }

            .btn {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="main-container">
        <!-- Navigation Bar -->
        <div class="nav-bar" id="navBar">
            <div class="d-flex align-items-center gap-3">
                <button class="hamburger-btn d-none" id="hamburgerBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="header-title">DEUS Library</h1>
                <button class="minimize-btn" id="minimizeSidebarBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol id="mainBreadcrumb" class="breadcrumb mb-0 bg-transparent ps-2">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-secondary text-decoration-none">Home</a></li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="notification-icon">
                    <i class="fas fa-bell fa-lg"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="header-profile" id="profileCircle">
                    <img src="{{ asset('images/login.png') }}" alt="Admin Profile">
                    <span id="headerUsername">Admin</span>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" style="text-decoration: underline;">Logout</button>

                </form>
            </div>
        </div>

        <!-- Sidebar and Content -->
        <div class="d-flex flex-grow-1">
            <aside class="sidebar" id="sidebar">
                <div class="profile-section" id="sidebarProfileSection">
                    <img src="{{ asset('images/login.png') }}" alt="User Profile" id="sidebarProfileCircle">
                    <div>
                        <div class="username" id="sidebarUsername">Ronald</div>
                        <div class="status text-success">Online</div>
                    </div>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" id="adminProfileLink">
                            <i class="fas fa-user"></i>
                            <span class="nav-link-text">Admin Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.books.index') }}">
                            <i class="fas fa-book"></i>
                            <span class="nav-link-text">Manage Books</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i>
                            <span class="nav-link-text">Manage Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.borrowings.index') }}">
                            <i class="fas fa-exchange-alt"></i>
                            <span class="nav-link-text">Manage Borrowings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-tags"></i>
                            <span class="nav-link-text">Manage Categories</span>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="{{ route('admin.payments.index') }}">
                            <i class="fas fa-tags"></i>
                            <span class="nav-link-text">Payments</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown position-relative">
                        <a class="nav-link dropdown-toggle" href="#" id="accessLogsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-history"></i>
                            <span class="nav-link-text">Access Logs</span>
                        </a>
                        <ul class="dropdown-menu" style="width: 160px; max-height: 150px; overflow-y: auto;">
                            <li><a class="dropdown-item" href="{{ route('admin.access-logs.index') }}">Admin History</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.access.logs') }}">User History</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.messages.index') }}">
                            <i class="fa fa-comments"></i>
                            <span class="nav-link-text">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.notifications.index') }}">
                            <i class="fas fa-bell"></i>
                            <span class="nav-link-text">Notifications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.statistics.index') }}">
                            <i class="fas fa-chart-line"></i>
                            <span class="nav-link-text">Statistics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}">
                            <i class="fas fa-cog"></i>
                            <span class="nav-link-text">Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span class="nav-link-text">Reports</span>
                        </a>
                    </li>
                </ul>
            </aside>

            <div class="content-area" id="contentArea">
                <main class="content" id="mainContent">
                    @yield('content')

                    <div id="adminProfileSection" style="display: none;">
                        <div class="admin-profile-card">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="profile-image-container">
                                        <img src="{{ asset('images/login.png') }}" id="profileImageDisplay" alt="Profile Image">
                                    </div>
                                    <button class="btn btn-primary" id="editProfileBtn">
                                        <i class="fas fa-edit"></i> Edit Profile
                                    </button>
                                </div>
                                <div class="col-md-8">
                                    <div class="profile-detail-item">
                                        <div class="profile-detail-label">Username</div>
                                        <div class="profile-detail-value" id="usernameDisplay">Ronald</div>
                                    </div>
                                    <div class="profile-detail-item">
                                        <div class="profile-detail-label">Email</div>
                                        <div class="profile-detail-value" id="emailDisplay">ronald@example.com</div>
                                    </div>
                                    <div class="profile-detail-item">
                                        <div class="profile-detail-label">Role</div>
                                        <div class="profile-detail-value">Administrator</div>
                                    </div>
                                    <div class="profile-detail-item">
                                        <div class="profile-detail-label">Joined Date</div>
                                        <div class="profile-detail-value">2023-01-15</div>
                                    </div>
                                    <div class="profile-detail-item">
                                        <div class="profile-detail-label">Last Login</div>
                                        <div class="profile-detail-value">2023-06-15 14:30</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="editProfileSection" style="display: none;">
                        <div class="edit-profile-form">
                            <h2 class="mb-4">Edit Profile</h2>
                            <form id="profileForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <div class="profile-image-container mb-3">
                                            <img src="{{ asset('images/login.png') }}" id="profileImagePreview" alt="Profile Image">
                                        </div>
                                        <input type="file" id="profileImage" name="profile_image" accept="image/*" style="display: none;">
                                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('profileImage').click()">
                                            <i class="fas fa-camera"></i> Change Photo
                                        </button>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="editUsername" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="editUsername" value="Ronald">
                                        </div>
                                        <div class="form-group">
                                            <label for="editEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="editEmail" value="ronald@example.com">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" value="Administrator" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Joined Date</label>
                                            <input type="text" class="form-control" value="2023-01-15" readonly>
                                        </div>
                                        <div class="form-group mt-4">
                                            <button type="button" id="saveProfileBtn" class="btn btn-success">
                                                <i class="fas fa-save"></i> Save Changes
                                            </button>
                                            <button type="button" id="cancelEditBtn" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const contentArea = document.getElementById('contentArea');
            const minimizeBtn = document.getElementById('minimizeSidebarBtn');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const savedImage = localStorage.getItem('adminProfileImage') || "{{ asset('images/login.png') }}";
            const savedUsername = localStorage.getItem('adminUsername') || "Ronald";
            const savedEmail = localStorage.getItem('adminEmail') || "ronald@example.com";

            // Set profile images and details
            function setProfileImages(src) {
                document.getElementById('profileImagePreview').src = src;
                document.getElementById('profileImageDisplay').src = src;
                document.getElementById('sidebarProfileCircle').src = src;
                document.querySelector('.header-profile img').src = src;
                document.querySelector('.profile-section img').src = src;
            }

            function setProfileDetails(username, email) {
                document.getElementById('usernameDisplay').textContent = username;
                document.getElementById('emailDisplay').textContent = email;
                document.getElementById('sidebarUsername').textContent = username;
                document.getElementById('headerUsername').textContent = username;
                document.getElementById('editUsername').value = username;
                document.getElementById('editEmail').value = email;
            }

            setProfileImages(savedImage);
            setProfileDetails(savedUsername, savedEmail);

            // Sidebar toggle for desktop (minimize/expand)
            function toggleSidebar() {
                sidebar.classList.toggle('collapsed');
                contentArea.classList.toggle('sidebar-collapsed');
                const icon = minimizeBtn.querySelector('i');
                icon.classList.toggle('fa-chevron-left');
                icon.classList.toggle('fa-chevron-right');
            }

            // Sidebar toggle for mobile (show/hide)
            function toggleMobileSidebar() {
                sidebar.classList.toggle('active');
                contentArea.classList.toggle('sidebar-active');
                if (sidebar.classList.contains('active')) {
                    sidebar.classList.remove('collapsed');
                    contentArea.classList.add('sidebar-active');
                } else {
                    sidebar.classList.add('collapsed');
                    contentArea.classList.remove('sidebar-active');
                }
            }

            minimizeBtn.addEventListener('click', toggleSidebar);
            hamburgerBtn.addEventListener('click', toggleMobileSidebar);

            // Profile section handling
            document.getElementById('adminProfileLink').addEventListener('click', function (e) {
                e.preventDefault();
                hideAllContent();
                document.getElementById('adminProfileSection').style.display = 'block';
            });

            document.getElementById('sidebarProfileSection').addEventListener('click', function () {
                hideAllContent();
                document.getElementById('adminProfileSection').style.display = 'block';
            });

            document.getElementById('editProfileBtn').addEventListener('click', function () {
                document.getElementById('adminProfileSection').style.display = 'none';
                document.getElementById('editProfileSection').style.display = 'block';
            });

            document.getElementById('cancelEditBtn').addEventListener('click', function () {
                document.getElementById('adminProfileSection').style.display = 'block';
                document.getElementById('editProfileSection').style.display = 'none';
            });

            document.getElementById('saveProfileBtn').addEventListener('click', function () {
                const newUsername = document.getElementById('editUsername').value;
                const newEmail = document.getElementById('editEmail').value;

                localStorage.setItem('adminUsername', newUsername);
                localStorage.setItem('adminEmail', newEmail);
                setProfileDetails(newUsername, newEmail);

                alert('Profile updated successfully!');
                document.getElementById('adminProfileSection').style.display = 'block';
                document.getElementById('editProfileSection').style.display = 'none';
            });

            document.getElementById('profileImage').addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        localStorage.setItem('adminProfileImage', event.target.result);
                        setProfileImages(event.target.result);
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            document.getElementById('profileCircle').addEventListener('click', function () {
                hideAllContent();
                document.getElementById('adminProfileSection').style.display = 'block';
            });

            function hideAllContent() {
                document.querySelectorAll('#mainContent > *').forEach(el => {
                    if (el.id !== 'adminProfileSection' && el.id !== 'editProfileSection') {
                        el.style.display = 'none';
                    }
                });
            }

            // Breadcrumb handling
            const routeNames = {
                '/admin/dashboard': 'Dashboard',
                '/admin/books': 'Manage Books',
                '/admin/books/create': 'Add New Book',
                '/admin/books/edit': 'Edit Book',
                '/admin/users': 'Manage Users',
                '/admin/users/create': 'Add New User',
                '/admin/borrowings': 'Manage Borrowings',
                '/admin/categories': 'Manage Categories',
                '/admin/messages': 'Messages',
                '/admin/profile': 'My Profile',
                '/admin/settings': 'Settings',
                '/admin/reports': 'Reports',
                '/admin/notifications': 'Notifications',
                '/admin/access-logs': 'Access Logs'
            };

            function updateBreadcrumb() {
                const path = window.location.pathname;
                const breadcrumb = document.getElementById('mainBreadcrumb');

                while (breadcrumb.children.length > 1) {
                    breadcrumb.removeChild(breadcrumb.lastChild);
                }

                const parts = path.split('/').filter(p => p);
                let currentPath = '';

                parts.forEach((part, index) => {
                    currentPath += '/' + part;
                    if (part && part !== 'admin') {
                        const li = document.createElement('li');
                        li.className = 'breadcrumb-item';

                        if (index === parts.length - 1) {
                            li.classList.add('active');
                            li.setAttribute('aria-current', 'page');
                            li.textContent = routeNames[currentPath] || part.replace(/-/g, ' ');
                        } else {
                            const a = document.createElement('a');
                            a.href = currentPath;
                            a.className = 'text-secondary text-decoration-none';
                            a.textContent = routeNames[currentPath] || part.replace(/-/g, ' ');
                            li.appendChild(a);
                        }

                        breadcrumb.appendChild(li);
                    }
                });
            }

            updateBreadcrumb();
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function (e) {
                    if (this.href && this.href.startsWith(window.location.origin)) {
                        setTimeout(updateBreadcrumb, 50);
                    }
                });
            });
            window.addEventListener('popstate', updateBreadcrumb);

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 992 && sidebar.classList.contains('active') &&
                    !sidebar.contains(e.target) && !hamburgerBtn.contains(e.target)) {
                    sidebar.classList.remove('active');
                    contentArea.classList.remove('sidebar-active');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>