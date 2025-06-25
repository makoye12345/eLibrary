@extends('layouts.user')

@section('title', 'User Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="container mx-auto p-4">
    @if (session('error') || isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') ?? $error }}
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-red-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Database Connection Check -->
    @php
        try {
            $connection = DB::connection()->getPdo();
            $sqlConnected = true;
            
            // Get all counts in a single query for better performance
            $userId = auth()->id();
            $counts = DB::selectOne("
                SELECT 
                    (SELECT COUNT(*) FROM borrows WHERE user_id = ? AND returned_at IS NULL AND status = 'borrowed') as borrowed_books,
                    (SELECT COUNT(*) FROM borrows WHERE user_id = ? AND returned_at IS NOT NULL AND status = 'returned') as returned_books,
                    (SELECT COUNT(*) FROM borrows WHERE user_id = ? AND returned_at IS NULL AND status = 'borrowed' AND due_at < NOW()) as overdue_books,
                    (SELECT COUNT(*) FROM reservations WHERE user_id = ? AND status = 'pending') as reserved_books,
                    (SELECT COALESCE(SUM(amount), 0) FROM fines WHERE user_id = ? AND is_paid = 0) as pending_fines
                ", [$userId, $userId, $userId, $userId, $userId]);
            
        } catch (\Exception $e) {
            $sqlConnected = false;
            $sqlError = $e->getMessage();
        }
    @endphp

    @if(!$sqlConnected)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            Database connection error: {{ $sqlError }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Borrowed Books Card -->
        <div class="bg-white p-6 rounded-lg shadow card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-book fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500">Borrowed Books</h3>
                    <p class="text-2xl font-bold">{{ $counts->borrowed_books ?? 0 }}</p>
                    @if (($counts->borrowed_books ?? 0) == 0)
                        <p class="text-sm text-gray-400">No books currently borrowed</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Returned Books Card -->
        <div class="bg-white p-6 rounded-lg shadow card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500">Returned Books</h3>
                    <p class="text-2xl font-bold">{{ $counts->returned_books ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Fines Card -->
        <div class="bg-white p-6 rounded-lg shadow card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500">Pending Fines</h3>
                    <p class="text-2xl font-bold">${{ number_format($counts->pending_fines ?? 0, 2) }}</p>
                    @if (($counts->pending_fines ?? 0) > 0)
                        <a href="{{ route('user.fines') }}" class="text-sm text-yellow-500 hover:underline">
                            View pending fines
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Overdue Books Card -->
        <div class="bg-white p-6 rounded-lg shadow card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-clock fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500">Overdue Books</h3>
                    <p class="text-2xl font-bold">{{ $counts->overdue_books ?? 0 }}</p>
                    @if (($counts->overdue_books ?? 0) > 0)
                        <a href="{{ route('user.books.borrowed') }}" class="text-sm text-red-500 hover:underline">
                            View overdue books
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reserved Books Card -->
        <div class="bg-white p-6 rounded-lg shadow card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-bookmark fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500">Reserved Books</h3>
                    <p class="text-2xl font-bold">{{ $counts->reserved_books ?? 0 }}</p>
                    @if (($counts->reserved_books ?? 0) > 0)
                        <a href="{{ route('reservations.index') }}" class="text-sm text-purple-500 hover:underline">
                            View reservations
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Total Books Read Card -->
        <div class="bg-white p-6 rounded-lg shadow card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-teal-100 text-teal-600">
                    <i class="fas fa-book-reader fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500">Total Books Read</h3>
                    <p class="text-2xl font-bold">{{ $counts->returned_books ?? 0 }}</p>
                    @if (($counts->returned_books ?? 0) > 0)
                        <a href="{{ route('user.books.borrowed') }}" class="text-sm text-teal-500 hover:underline">
                           Book already read
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Two-Column Layout for Recent Activity and Carousel -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Activity Section -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
            @php
                $recentActivities = [];
                if($sqlConnected) {
                    try {
                        $recentActivities = DB::select("
                            (SELECT 
                                'borrowed' as type,
                                books.title,
                                borrows.borrowed_at as activity_date
                            FROM borrows
                            JOIN books ON borrows.book_id = books.id
                            WHERE borrows.user_id = ?
                            ORDER BY borrows.borrowed_at DESC
                            LIMIT 3)
                            
                            UNION ALL
                            
                            (SELECT 
                                'returned' as type,
                                books.title,
                                borrows.returned_at as activity_date
                            FROM borrows
                            JOIN books ON borrows.book_id = books.id
                            WHERE borrows.user_id = ?
                            AND borrows.returned_at IS NOT NULL
                            ORDER BY borrows.returned_at DESC
                            LIMIT 2)
                            
                            ORDER BY activity_date DESC
                            LIMIT 5
                        ", [$userId, $userId]);
                    } catch (\Exception $e) {
                        \Log::error("Recent activities query failed: " . $e->getMessage());
                    }
                }
            @endphp

            @if (count($recentActivities) > 0)
                <ul class="space-y-4">
                    @foreach ($recentActivities as $activity)
                        <li class="flex items-center">
                            <div class="p-2 rounded-full 
                                @if($activity->type == 'returned') bg-green-100 text-green-600
                                @else bg-blue-100 text-blue-600 @endif">
                                <i class="fas 
                                    @if($activity->type == 'returned') fa-check-circle
                                    @else fa-book @endif fa-sm"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-800">
                                    {{ ucfirst($activity->type) }}: {{ $activity->title }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($activity->activity_date)->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No recent activity found.</p>
            @endif
        </div>

        <!-- Book Carousel Section -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Featured Books</h2>
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/book12.jpg') }}" class="d-block w-100 h-64 object-cover" alt="Lectural Book" onerror="this.src='https://via.placeholder.com/300x400?text=Lectural+Book'">
                        <p class="text-center mt-2 text-lg font-medium">1. Lectural Book</p>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/book43.png') }}" class="d-block w-100 h-64 object-cover" alt="Student Book" onerror="this.src='https://via.placeholder.com/300x400?text=Student+Book'">
                        <p class="text-center mt-2 text-lg font-medium">2. Student Book</p>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/book3.jpg') }}" class="d-block w-100 h-64 object-cover" alt="Guest Book" onerror="this.src='https://via.placeholder.com/300x400?text=Guest+Book'">
                        <p class="text-center mt-2 text-lg font-medium">3. Guest Book</p>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
            </div>
            <p id="carouselDebug" class="text-sm text-gray-500 mt-2">Carousel initialized</p>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .carousel-item img {
        max-width: 100%;
        height: 16rem; /* 256px, matches h-64 */
        object-fit: cover;
    }
    .carousel-inner {
        border-radius: 0.5rem;
        overflow: hidden;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const carousel = document.querySelector('#carouselExampleIndicators');
        const debugText = document.querySelector('#carouselDebug');

        if (!carousel) {
            console.error('Carousel element not found!');
            debugText.textContent = 'Error: Carousel not found';
            return;
        }

        // Initialize Bootstrap carousel with reverse direction
        const bsCarousel = new bootstrap.Carousel(carousel, {
            interval: 3000, // Slide every 3 seconds
            wrap: true,
            ride: 'carousel'
        });

        // Reverse sliding direction by swapping prev/next
        carousel.addEventListener('click', (event) => {
            if (event.target.closest('.carousel-control-prev')) {
                bsCarousel.next();
            } else if (event.target.closest('.carousel-control-next')) {
                bsCarousel.prev();
            }
        });

        // Debug image loading
        const images = carousel.querySelectorAll('img');
        images.forEach((img, index) => {
            img.addEventListener('load', () => {
                console.log(`Image ${index + 1} loaded successfully: ${img.src}`);
                debugText.textContent = `Showing slide ${index + 1}`;
            });
            img.addEventListener('error', () => {
                console.error(`Image ${index + 1} failed to load: ${img.src}`);
                debugText.textContent = `Error: Image ${index + 1} failed to load`;
            });
        });

        // Update debug text on slide change
        carousel.addEventListener('slide.bs.carousel', (event) => {
            debugText.textContent = `Showing slide ${event.to + 1}`;
        });
    });
</script>
@endsection