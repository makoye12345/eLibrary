@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="dashboard-container">
            <h2 class="welcome-message">Welcome, Admin</h2>
            
            <!-- First Row - 3 Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="dashboard-card total-books">
                        <div class="card-content">
                            <h4>Total Books</h4>
                            <p id="totalBooks">{{ $totalBooks }}</p>
                            <a href="{{ route('admin.books.index') }}" class="more-info">More info <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <i class="fas fa-book icon-right"></i>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="dashboard-card active-users">
                        <div class="card-content">
                            <h4>Active Users</h4>
                            <p id="activeUsers">{{ $activeUsers }}</p>
                            <a href="{{ route('admin.users.index') }}" class="more-info">More info <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <i class="fas fa-users icon-right"></i>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="dashboard-card books-borrowed">
                        <div class="card-content">
                            <h4>Books Borrowed</h4>
                            <p id="booksBorrowed">{{ $booksBorrowed }}</p>
                            <a href="{{ route('admin.books.borrowed') }}" class="more-info">View borrowed books <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <i class="fas fa-exchange-alt icon-right"></i>
                    </div>
                </div>
            </div>

            <!-- Second Row - 4 Cards -->
            <div class="row mt-4">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="dashboard-card issued">
                        <div class="card-content">
                            <h4>ISSUED</h4>
                            <p>{{ $issuedBooks }}</p>
                            <a href="{{ route('admin.books.issued') }}" class="more-info">More info <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <i class="fas fa-paper-plane icon-right"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="dashboard-card returned">
                        <div class="card-content">
                            <h4>RETURNED</h4>
                            <p>{{ $returnedBooks }}</p>
                            <a href="{{ route('admin.books.returned') }}" class="more-info">More info <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <i class="fas fa-check-circle icon-right"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="dashboard-card not-returned">
                        <div class="card-content">
                            <h4>NOT RETURNED</h4>
                            <p>{{ $notReturnedBooks }}</p>
                            <a href="{{ route('admin.books.overdue') }}" class="more-info">More info <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <i class="fas fa-exclamation-circle icon-right"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="dashboard-card date">
                        <div class="card-content">
                            <h4>DATE TODAY</h4>
                            <p>{{ $todayDate }}</p>
                        </div>
                        <i class="fas fa-calendar-alt icon-right"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Activities Table -->
            <div class="books-table-container mt-4">
                <h3 class="table-title">Types of Recent Activities</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Activities</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentActivities as $activity)
                            <tr>
                                <td>{{ $activity->type }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="table-error">No Recent Activities</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Basic Styles */
        .content {
            flex-grow: 1;
            padding: 30px 20px 30px 10px;
            background-color: white !important;
            min-height: calc(100vh - 140px);
        }
        .dashboard-container {
            max-width: 2000px;
            margin: 0 auto;
        }
        .welcome-message {
            font-size: 1.75rem;
            color: #1e3c72 !important;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center;
        }

        /* Card Styles */
        .dashboard-card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: all 0.3s ease;
            animation: slideDown 0.8s ease-out forwards;
            opacity: 0;
            height: 150px;
            display: flex;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Card Content */
        .card-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            z-index: 2;
            width: 100%;
        }

        .dashboard-card h4 {
            color: rgb(221, 220, 224) !important;
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dashboard-card p {
            color: white !important;
            font-size: 2.2rem;
            margin: 5px 0;
            font-weight: 700;
        }

        /* More Info Links */
        .more-info {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-top: auto;
            padding-top: 8px;
            transition: all 0.3s ease;
            width: fit-content;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .more-info i {
            margin-left: 6px;
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .more-info:hover {
            color: white !important;
            text-decoration: none;
        }

        .more-info:hover i {
            transform: translateX(3px);
        }

        /* Icons */
        .icon-right {
            font-size: 5rem;
            opacity: 0.15;
            color: white;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .icon-right {
            opacity: 0.25;
            transform: translateY(-50%) scale(1.05);
        }

        /* Card Colors */
        .total-books { background: linear-gradient(135deg, #30bd30 0%, #2aa52a 100%); }
        .active-users { background: linear-gradient(135deg, #998238 0%, #85752e 100%); }
        .books-borrowed { background: linear-gradient(135deg, #db9fc0 0%, #c98fb0 100%); }
        .issued { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        .returned { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
        .not-returned { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
        .date { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }

        /* Hover Effects */
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        /* Animations */
        @keyframes slideDown {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Animation Timing */
        .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-card:nth-child(3) { animation-delay: 0.3s; }
        .dashboard-card:nth-child(4) { animation-delay: 0.4s; }
        .dashboard-card:nth-child(5) { animation-delay: 0.5s; }
        .dashboard-card:nth-child(6) { animation-delay: 0.6s; }
        .dashboard-card:nth-child(7) { animation-delay: 0.7s; }

        /* Table Styles */
        .books-table-container {
            background: white !important;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }
        .table-title {
            font-size: 1.5rem;
            color: #1e3c72 !important;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #1e3c72 !important;
        }
        .table td {
            color: #333 !important;
        }
        .table-error {
            display: none;
            color: #dc3545 !important;
            text-align: center;
            padding: 20px;
            font-size: 1rem;
        }
        .table-error:empty + .table-error {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .dashboard-card {
                height: 140px;
                padding: 15px;
            }
            .dashboard-card h4 {
                font-size: 1.1rem;
            }
            .dashboard-card p {
                font-size: 2rem;
            }
            .icon-right {
                font-size: 4.5rem;
            }
            .table th, .table td {
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-card {
                height: 130px;
            }
            .dashboard-card h4 {
                font-size: 1rem;
            }
            .dashboard-card p {
                font-size: 1.8rem;
            }
            .icon-right {
                font-size: 4rem;
            }
            .more-info {
                font-size: 0.8rem;
            }
            .table-title {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-card {
                height: 120px;
            }
            .dashboard-card p {
                font-size: 1.6rem;
            }
            .icon-right {
                font-size: 3.5rem;
                right: 10px;
            }
            .welcome-message {
                font-size: 1.5rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
                padding: 8px;
            }
            .table-title {
                font-size: 1.2rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animate all cards with sequential delays
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                }, index * 200);
            });
        });
    </script>
@endsection