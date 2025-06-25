<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Library Management System')</title>
    
    <!-- CSRF Token Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  
   
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px; /* Start below navbar */
            left: 0;
            background-color: #2c3e50;
            color: white;
            transition: all 0.3s;
            overflow-y: auto;
            z-index: 1100; /* Above navbar to ensure visibility */
            transform: translateX(-250px);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .sidebar.minimized {
            width: 60px;
            transform: translateX(0);
        }
        
        .sidebar.minimized .sidebar-text {
            display: none;
        }
        
        .sidebar.minimized .nav-link {
            justify-content: center;
        }
        
        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            white-space: nowrap;
            font-family: sans-serif;
        }
        
        .sidebar .nav-link:hover {
            background-color: #34495e;
        }
        
        .toggle-btn {
            background: none;
            color: #6c757d;
            border: none;
            padding: 8px;
            cursor: pointer;
            font-size: 1.2rem;
        }
        
        /* Navbar */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000; /* Below sidebar */
            padding: 0.5rem 1rem;
            height: 60px; /* Fixed height for consistency */
        }
        
        .profile-img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }
        
        .profile-img-sidebar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            left: auto;
            min-width: 200px;
            margin-top: 10px;
            padding: 10px 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            z-index: 1200; /* Above navbar and sidebar */
            max-height: 300px;
            overflow-y: auto;
        }
        
        .dropdown-item {
            padding: 8px 15px;
            font-size: 0.9rem;
            color: #495057;
            white-space: normal;
            word-break: break-word;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #2c3e50;
        }
        
        /* Main Content */
        .content {
            padding: 20px;
            min-height: calc(100vh - 60px);
            margin-left: 0;
            margin-top: 60px;
            transition: all 0.3s;
            width: 100%;
        }
        
        .content.sidebar-open {
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        
        .content.minimized {
            margin-left: 60px;
            width: calc(100% - 60px);
        }
        
        /* Profile circle */
        .profile-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #495057;
            font-weight: bold;
            margin-right: 5px;
            cursor: pointer;
        }
        
        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.75rem;
            line-height: 1;
            padding: 3px 6px;
        }
        
        /* Profile modal */
        .profile-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1300; /* Above all elements */
            justify-content: center;
            align-items: center;
            overflow-y: auto;
        }
        
        .profile-modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent;
            padding: 0.5rem 1rem;
            margin-bottom: 0;
        }
        
        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #495057;
        }
        
        /* File upload */
        .file-upload {
            position: relative;
            display: inline-block;
        }
        
        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        /* Navbar toggle button */
        .navbar-toggle {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.25rem;
            padding: 0.5rem;
            margin-right: 1rem;
            cursor: pointer;
        }

        /* Header text */
        .header-text {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-right: 1rem;
        }

        /* Profile section */
        .profile-section {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .user-name {
            color: #2c3e50;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Online status */
        .online-status {
            font-size: 12px;
            color: #28a745;
        }
        
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .content {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
            
            .sidebar.minimized + .content {
                margin-left: 60px;
                width: calc(100% - 60px);
            }
            
            .navbar-toggle#sidebarToggle {
                display: none;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                top: 60px;
            }

            .profile-section .user-name {
                display: none;
            }
            
            .navbar-toggle#minimizeToggle {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .header-text {
                font-size: 1.2rem;
            }

            .navbar {
                padding: 0.5rem;
            }

            .profile-modal-content {
                width: 95%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container-fluid">
            <!-- Header Name -->
            <span class="header-text">DEUS Library</span>

            <button class="navbar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <button class="navbar-toggle toggle-btn" id="minimizeToggle">
                <i class="fas fa-chevron-left"></i>
            </button>

            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="me-auto">
                <ol class="breadcrumb mb-0" id="mainBreadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                    <!-- Dynamic breadcrumbs will be added here -->
                </ol>
            </nav>

            <div class="ms-auto d-flex align-items-center">
                <!-- Notification Icon -->
                <div class="nav-item dropdown me-3">
                    <a href="#" class="nav-link position-relative" title="Notifications" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if (isset($notificationCount) && $notificationCount > 0)
                            <span class="badge bg-danger rounded-circle notification-badge">{{ $notificationCount }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                        @if ($notifications->isEmpty())
                            <li class="dropdown-item">No notifications</li>
                        @else
                            @foreach ($notifications as $notification)
                                <li class="dropdown-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                    <strong>{{ $notification->data['subject'] }}</strong>
                                    <p class="mb-1">{{ $notification->data['message'] }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                            <li class="dropdown-item text-center">
                                <a href="{{ route('notifications.index') }}">View All Notifications</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown me-3">
                    <a class="nav-link dropdown-toggle profile-section" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(auth()->user()->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="profile-img" alt="Profile" id="navbarProfileImg">
                        @else
                            <span class="profile-circle" onclick="showProfileModal()">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                        <span class="user-name ms-2">{{ auth()->user()->name ?? 'User' }}</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="#" onclick="showProfileModal(); updateBreadcrumb('/user/profile', 'My Profile')">My Profile</a></li>
                        <li><a class="dropdown-item" href="#">Change Password</a></li>
                        <li><a class="dropdown-item" href="#">Access Logs</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- User Profile in Sidebar -->
        <div class="profile-section" id="sidebarProfile">
            @if(auth()->user()->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" 
                     class="profile-img-sidebar" 
                     id="sidebarProfileImg"
                     alt="Profile Image"
                     onclick="showProfileModal()">
            @else
                <div class="profile-img-sidebar bg-primary text-white d-flex align-items-center justify-content-center" 
                     onclick="showProfileModal()">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <div class="sidebar-text" style="font-weight: bold; font-size: 16px;" id="sidebarUserName">{{ auth()->user()->name }}</div>
                <div class="online-status sidebar-text">Online</div>
            </div>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('user.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <a class="nav-link" href="{{ route('books.search') }}">
                <i class="fas fa-search"></i>
                <span class="sidebar-text">Search Books</span>
            </a>
            <a class="nav-link" href="{{ route('user.books.borrowed') }}">
                <i class="fas fa-book"></i>
                <span class="sidebar-text">Borrowed Books</span>
            </a>
            <a class="nav-link" href="{{ route('reservations.index') }}">
                <i class="fas fa-bookmark"></i>
                <span class="sidebar-text">Book Reservation</span>
            </a>
            <a class="nav-link" href="{{ route('user.fines')}}">
                <i class="fas fa-money-bill-wave"></i>
                <span class="sidebar-text">Fines / Penalties</span>
            </a>
            <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="paymentsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-money-bill-wave"></i>
        <span class="sidebar-text">Payments</span>
    </a>
    <ul class="dropdown-menu" aria-labelledby="paymentsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('user.payments.create-invoice') }}">
                <i class="fas fa-file-invoice"></i> Create Invoice
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('user.payments.index') }}">
                <i class="fas fa-list"></i> Invoice List
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('user.fines') }}">
                <i class="fas fa-money-check-alt"></i> Pay Fine
            </a>
        </li>
    </ul>
</li>
            <a class="nav-link" href="#">
                <i class="fas fa-bell"></i>
                <span class="sidebar-text">Notifications</span>
            </a>
            <a class="nav-link" href="{{ route('user.messages.index')}}">
                <i class="fas fa-comments"></i>
                <span class="sidebar-text">Messages</span>
            </a>
            <a class="nav-link" href="#">
                <i class="fas fa-history"></i>
                <span class="sidebar-text">Access Logs</span>
            </a>
            
             <a class="nav-link" href="{{ route('user.help') }}">
                <i class="fas fa-life-ring"></i>
                <span class="sidebar-text">Help & Support</span>
            </a>
            <a class="nav-link" href="{{ route('reports')}}">
                <i class="fas fa-chart-bar"></i>
                <span class="sidebar-text">Reports</span>
            </a>
        </nav>
    </div>

    <!-- Profile Modal -->
    <div class="profile-modal" id="profileModal">
        <div class="profile-modal-content">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>User Profile</h4>
                <button type="button" class="btn-close" onclick="hideProfileModal()"></button>
            </div>
            
            <form id="profileForm" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="text-center mb-4">
                    <div class="file-upload position-relative mx-auto" style="width: 120px;">
                        @if(auth()->user()->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" 
                                 id="profileImagePreview" 
                                 class="rounded-circle" 
                                 width="120" 
                                 height="120"
                                 style="object-fit: cover;">
                        @else
                            <div id="profileImagePreview" 
                                 class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <input type="file" 
                               id="profileImage" 
                               name="profile_image" 
                               class="file-upload-input" 
                               accept="image/*"
                               onchange="previewImage(this)">
                        <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm">
                            <i class="fas fa-camera text-dark"></i>
                        </div>
                    </div>
                    <small class="text-muted">Click to change photo</small>
                </div>
                
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}">
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2">{{ auth()->user()->address ?? '' }}</textarea>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" onclick="hideProfileModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        @yield('content')
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const minimizeToggle = document.getElementById('minimizeToggle');
        let isLargeScreen = window.matchMedia("(min-width: 992px)").matches;

        // Initialize sidebar state
        function initializeSidebar() {
            if (isLargeScreen) {
                sidebar.classList.add('show');
                content.classList.add('sidebar-open');
                minimizeToggle.style.display = 'block';
            } else {
                sidebar.classList.remove('show', 'minimized');
                content.classList.remove('sidebar-open', 'minimized');
                minimizeToggle.style.display = 'none';
            }
        }

        initializeSidebar();

        // Toggle sidebar (open/close)
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            content.classList.toggle('sidebar-open');
            if (isLargeScreen && sidebar.classList.contains('show')) {
                minimizeToggle.style.display = 'block';
            } else {
                minimizeToggle.style.display = 'none';
            }
        });

        // Toggle sidebar minimization
        minimizeToggle.addEventListener('click', () => {
            sidebar.classList.toggle('minimized');
            content.classList.toggle('minimized');
            minimizeToggle.innerHTML = sidebar.classList.contains('minimized') 
                ? '<i class="fas fa-chevron-right"></i>' 
                : '<i class="fas fa-chevron-left"></i>';
        });

        // Debounce function for resize events
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Handle responsive behavior
        const handleResponsive = debounce(() => {
            const newIsLargeScreen = window.matchMedia("(min-width: 992px)").matches;
            if (newIsLargeScreen !== isLargeScreen) {
                isLargeScreen = newIsLargeScreen;
                initializeSidebar();
            }
        }, 100);

        window.addEventListener('resize', handleResponsive);

        // Profile Modal Functions
        function showProfileModal() {
            document.getElementById('profileModal').style.display = 'flex';
            updateBreadcrumb('/user/profile', 'My Profile');
        }

        function hideProfileModal() {
            document.getElementById('profileModal').style.display = 'none';
            updateBreadcrumb();
        }

        document.getElementById('profileModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideProfileModal();
            }
        });

        // Image preview function
        function previewImage(input) {
            const preview = document.getElementById('profileImagePreview');
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.id = 'profileImagePreview';
                    img.className = 'rounded-circle';
                    img.style.width = '120px';
                    img.style.height = '120px';
                    img.style.objectFit = 'cover';
                    img.src = e.target.result;
                    preview.parentNode.replaceChild(img, preview);
                }
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // Form submission with fetch API
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: "POST",
                body: formData,
                headers: {
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const sidebarImg = document.getElementById('sidebarProfileImg');
                    const navbarImg = document.getElementById('navbarProfileImg');
                    const sidebarUserName = document.getElementById('sidebarUserName');

                    if (data.profile_image_url) {
                        if (sidebarImg) {
                            sidebarImg.src = data.profile_image_url;
                        } else {
                            const div = document.querySelector('#sidebarProfile .profile-img-sidebar');
                            const img = document.createElement('img');
                            img.id = 'sidebarProfileImg';
                            img.className = 'profile-img-sidebar';
                            img.src = data.profile_image_url;
                            img.alt = 'Profile Image';
                            img.onclick = showProfileModal;
                            div.parentNode.replaceChild(img, div);
                        }
                        
                        if (navbarImg) {
                            navbarImg.src = data.profile_image_url;
                        } else {
                            const span = document.querySelector('.profile-circle');
                            const img = document.createElement('img');
                            img.id = 'navbarProfileImg';
                            img.className = 'profile-img';
                            img.src = data.profile_image_url;
                            img.alt = 'Profile';
                            span.parentNode.replaceChild(img, span);
                        }
                    }

                    if (data.name) {
                        sidebarUserName.textContent = data.name;
                        document.querySelector('.user-name').textContent = data.name;
                    }

                    alert('Profile updated successfully!');
                    hideProfileModal();
                } else {
                    alert('Error updating profile: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating profile');
            });
        });

        // Breadcrumb Navigation Logic
        const routeNames = {
            '/user/dashboard': 'Dashboard',
            '/books/search': 'Search Books',
            '/user/books/borrowed': 'Borrowed Books',
            '/reservations': 'Book Reservation',
            '/user/fines': 'Fines / Penalties',
            '/notifications': 'Notifications',
            '/access-logs': 'Access Logs',
            '/messages': 'Messages',
            '/reports': 'Reports',
            '/user/profile': 'My Profile'
        };

        function updateBreadcrumb(path, title) {
            const breadcrumb = document.getElementById('mainBreadcrumb');
            if (!breadcrumb) return;

            while (breadcrumb.children.length > 1) {
                breadcrumb.removeChild(breadcrumb.lastChild);
            }

            const currentPath = path || window.location.pathname;
            const parts = currentPath.split('/').filter(p => p);
            let tempPath = '';

            parts.forEach((part, index) => {
                tempPath += '/' + part;
                if (part !== 'user') {
                    const li = document.createElement('li');
                    li.className = 'breadcrumb-item';
                    if (index === parts.length - 1) {
                        li.classList.add('active');
                        li.setAttribute('aria-current', 'page');
                        li.textContent = title || routeNames[tempPath] || part.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    } else {
                        const a = document.createElement('a');
                        a.href = tempPath;
                        a.textContent = routeNames[tempPath] || part.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        a.style.color = '#6c757d';
                        a.style.textDecoration = 'none';
                        li.appendChild(a);
                    }
                    breadcrumb.appendChild(li);
                }
            });
        }

        updateBreadcrumb();

        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.startsWith('/')) {
                    setTimeout(() => {
                        updateBreadcrumb();
                    }, 50);
                }
            });
        });

        window.addEventListener('popstate', () => {
            updateBreadcrumb();
        });
    </script>
</body>
</html>