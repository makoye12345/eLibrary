
@extends('layouts.user')

@section('title', 'My Reserved Books')
@section('header', 'Books I Have Reserved')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($reservations->isEmpty())
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700">No Reserved Books</h3>
            <p class="text-gray-500 mt-2">You currently have no reserved books in your dashboard.</p>
            <a href="{{ route('reservations.create') }}" 
               class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Browse Available Books
            </a>
        </div>
    @else
        <div id="reservedBooksContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reservations as $reservation)
                <div id="reservation-{{ $reservation->id }}" class="bg-white p-6 rounded-lg shadow flex flex-col transition-all duration-300">
                    <div class="flex mb-4">
                        @if($reservation->book->cover_image_path)
                            <img src="{{ asset('storage/' . $reservation->book->cover_image_path) }}" 
                                 alt="{{ $reservation->book->title }}" 
                                 class="w-24 h-32 object-cover mr-4 rounded">
                        @else
                            <div class="w-24 h-32 bg-gray-200 mr-4 rounded flex items-center justify-center">
                                <span class="text-gray-500">No Cover</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $reservation->book->title }}
                            </h3>
                            <p class="text-sm text-gray-600">By {{ $reservation->book->author ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-600">
                                Reserved: {{ $reservation->reserved_at ? $reservation->reserved_at->format('M d, Y') : 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600">Status: {{ ucfirst($reservation->status) }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-auto flex gap-2">
                        @if($reservation->status != 'canceled')
                            <button onclick="cancelReservation({{ $reservation->id }})" 
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                                <i class="fas fa-times mr-2"></i> Cancel Reservation
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    @endif
</div>

<script>
function cancelReservation(reservationId) {
    if (!confirm('Are you sure you want to cancel this reservation?')) {
        return;
    }

    const reservationCard = document.getElementById(`reservation-${reservationId}`);
    if (!reservationCard) {
        console.error('Reservation card not found for reservation ID:', reservationId);
        return;
    }

    const cancelButton = reservationCard.querySelector('button');
    const originalButtonHTML = cancelButton.innerHTML;

    cancelButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Canceling...';
    cancelButton.disabled = true;
    reservationCard.classList.add('opacity-50', 'cursor-not-allowed');

    fetch(`/reservations/cancel/${reservationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Animate removal of the reservation card
            reservationCard.style.transition = 'all 0.3s ease';
            reservationCard.style.opacity = '0';
            reservationCard.style.transform = 'translateY(-20px)';
            reservationCard.style.height = '0';
            reservationCard.style.margin = '0';
            reservationCard.style.padding = '0';
            reservationCard.style.overflow = 'hidden';

            setTimeout(() => {
                reservationCard.remove();
                console.log(`Reservation ${reservationId} removed, book ID: ${data.book_id}`);

                // Check remaining reservations from server response
                if (data.remaining_reservations === 0) {
                    const successDiv = document.createElement('div');
                    successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
                    successDiv.textContent = data.message || 'Reservation canceled successfully!';

                    const emptyState = document.createElement('div');
                    emptyState.className = 'bg-white p-8 rounded-lg shadow text-center';
                    emptyState.innerHTML = `
                        <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700">No Reserved Books</h3>
                        <p class="text-gray-500 mt-2">You currently have no reserved books in your dashboard.</p>
                        <a href="{{ route('reservations.create') }}" 
                           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Browse Available Books
                        </a>
                    `;

                    const container = document.getElementById('reservedBooksContainer');
                    if (container) {
                        container.replaceWith(emptyState);
                    } else {
                        document.querySelector('.container').appendChild(emptyState);
                    }
                    document.querySelector('.container').prepend(successDiv);

                    // Remove pagination if present
                    const pagination = document.querySelector('.mt-6');
                    if (pagination) pagination.remove();

                    setTimeout(() => {
                        successDiv.remove();
                    }, 5000);
                }
            }, 300);
        } else {
            throw new Error(data.message || 'Failed to cancel reservation');
        }
    })
    .catch(error => {
        console.error('Error canceling reservation:', error);

        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        errorDiv.textContent = `Error: ${error.message}`;
        document.querySelector('.container').prepend(errorDiv);

        cancelButton.innerHTML = originalButtonHTML;
        cancelButton.disabled = false;
        reservationCard.classList.remove('opacity-50', 'cursor-not-allowed');

        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    });
}
</script>
@endsection
