@extends('layouts.admin')

@section('content')
<div class="container-fluid p-3">
    <h1 class="mb-4 text-xl md:text-2xl font-bold">Manage Books</h1>

    <!-- Buttons and Search Bar -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:gap-3">
        <div class="flex flex-col sm:flex-row gap-2 mb-3 md:mb-0">
            <!-- Add Book Button -->
            <button id="addBookButton" class="btn btn-primary text-sm py-2 px-3">
                <i class="fas fa-plus me-1"></i>Add Book
            </button>
            <!-- Bulk Import Button -->
            <button id="bulkImportButton" class="btn btn-success text-sm py-2 px-3">
                <i class="fas fa-upload me-1"></i>Bulk Import
            </button>
            <!-- Search Bar -->
            <form id="searchBooksForm" class="flex gap-2" action="{{ route('admin.books.index') }}" method="GET">
                <input type="text" name="search" id="searchInput" class="form-control text-sm py-2 px-3" placeholder="Search by title, author, or category..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-info text-sm py-2 px-3">
                    <i class="fas fa-search me-1"></i>Search
                </button>
            </form>
        </div>
    </div>

    <!-- Books Carousel -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-sm">{{ session('error') }}</div>
            @endif

            @if ($books->isEmpty())
                <p class="text-center text-muted text-sm">No books found.</p>
            @else
                <div id="booksCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($books->chunk(6) as $index => $bookChunk)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="books-grid">
                                    @foreach ($bookChunk as $book)
                                        <div class="card book-card">
                                            <div class="card-body p-3">
                                                <div class="text-center mb-2">
                                                    @if ($book->cover_image_path && Storage::disk('public')->exists($book->cover_image_path))
                                                        <a href="#" class="book-cover-link" data-bs-toggle="modal" 
                                                           data-bs-target="#viewCoverModal" 
                                                           data-cover="{{ asset('storage/' . $book->cover_image_path) }}" 
                                                           data-title="{{ addslashes($book->title) }}"
                                                           data-book-id="{{ $book->id }}"
                                                           data-file-path="{{ $book->file_path && Storage::disk('public')->exists($book->file_path) ? asset('storage/' . $book->file_path) : '' }}">
                                                            <img src="{{ asset('storage/' . $book->cover_image_path) }}" 
                                                                 alt="{{ $book->title }} cover" class="book-cover-img">
                                                        </a>
                                                    @else
                                                        <img src="{{ asset('images/default-book-cover.png') }}" 
                                                             alt="No cover" class="book-cover-img">
                                                    @endif
                                                </div>
                                                <h5 class="card-title text-sm font-semibold">{{ Str::limit($book->title, 25) }}</h5>
                                                <p class="card-text text-xs"><strong>Author:</strong> {{ $book->author ?? 'N/A' }}</p>
                                                <p class="card-text text-xs"><strong>Category:</strong> {{ $book->category ? $book->category->name : 'N/A' }}</p>
                                                <p class="card-text text-xs"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                                                <div class="action-buttons d-flex gap-2 mt-2">
                                                    <button class="btn btn-sm btn-warning text-xs py-1 px-2 flex-grow-1" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editBookModal" 
                                                            data-book="{{ json_encode($book->toArray()) }}"
                                                            data-file-exists="{{ $book->file_path && Storage::disk('public')->exists($book->file_path) ? 'true' : 'false' }}"
                                                            data-cover-exists="{{ $book->cover_image_path && Storage::disk('public')->exists($book->cover_image_path) ? 'true' : 'false' }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" 
                                                          class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger text-xs py-1 px-2 flex-grow-1">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#booksCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#booksCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- View Cover Modal -->
    <div class="modal fade" id="viewCoverModal" tabindex="-1" aria-labelledby="viewCoverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm" id="viewCoverModalLabel">Book Cover</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="enlargedCoverImage" src="#" alt="Book cover" class="modal-cover-img">
                    <p id="noCoverMessage" class="text-muted text-xs mt-2" style="display: none;">No cover image available</p>
                </div>
                <div class="modal-footer">
                    <a id="viewPdfButton" href="#" class="btn btn-primary text-xs py-1 px-2" style="display: none;" target="_blank">
                        <i class="fas fa-file-pdf me-1"></i>View File
                    </a>
                    <button type="button" class="btn btn-secondary text-xs py-1 px-2" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Book Modal -->
    <div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm" id="editBookModalLabel">Edit Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editBookForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="editTitle" class="form-label text-sm">Title</label>
                            <input type="text" class="form-control text-sm py-1 px-2" id="editTitle" name="title" required>
                            @error('title')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="editAuthor" class="form-label text-sm">Author</label>
                            <input type="text" class="form-control text-sm py-1 px-2" id="editAuthor" name="author" required>
                            @error('author')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="editCategory" class="form-label text-sm">Category</label>
                            <select class="form-control text-sm py-1 px-2" id="editCategory" name="category_id" required>
                                <option value="" disabled>Select Category</option>
                                @foreach (\App\Models\Category::orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="editIsbn" class="form-label text-sm">ISBN</label>
                            <input type="text" class="form-control text-sm py-1 px-2" id="editIsbn" name="isbn" required>
                            @error('isbn')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="editBook" class="form-label text-sm">Book File (PDF, TXT, EPUB)</label>
                            <input type="file" class="form-control text-sm py-1" id="editBook" name="book_file" accept=".pdf,.txt,.epub">
                            <p id="currentBookText" class="mt-1 text-xs"></p>
                            @error('book_file')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="editCoverImage" class="form-label text-sm">Book Cover (Image)</label>
                            <input type="file" class="form-control text-sm py-1" id="editCoverImage" name="cover_image" accept="image/*">
                            <p id="currentCoverText" class="mt-1 text-xs"></p>
                            <img id="currentCoverImage" src="#" alt="Current cover" class="current-cover-img" style="display: none;">
                            @error('cover_image')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-xs py-1 px-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary text-xs py-1 px-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Book Modal -->
    <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm" id="addBookModalLabel">Add New Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addBookForm" action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="addTitle" class="form-label text-sm">Title</label>
                            <input type="text" class="form-control text-sm py-1 px-2 @error('title') is-invalid @enderror" id="addTitle" name="title" required>
                            @error('title')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="addAuthor" class="form-label text-sm">Author</label>
                            <input type="text" class="form-control text-sm py-1 px-2 @error('author') is-invalid @enderror" id="addAuthor" name="author" required>
                            @error('author')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="addIsbn" class="form-label text-sm">ISBN</label>
                            <input type="text" class="form-control text-sm py-1 px-2 @error('isbn') is-invalid @enderror" id="addIsbn" name="isbn" required>
                            @error('isbn')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="addCategory" class="form-label text-sm">Category</label>
                            <select class="form-control text-sm py-1 px-2 @error('category_id') is-invalid @enderror" id="addCategory" name="category_id" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach (\App\Models\Category::orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="addBookFile" class="form-label text-sm">Book File (PDF, TXT, EPUB)</label>
                            <input type="file" class="form-control text-sm py-1 @error('book_file') is-invalid @enderror" id="addBookFile" name="book_file" accept=".pdf,.txt,.epub">
                            @error('book_file')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="addCoverImage" class="form-label text-sm">Book Cover (Image)</label>
                            <input type="file" class="form-control text-sm py-1 @error('cover_image') is-invalid @enderror" id="addCoverImage" name="cover_image" accept="image/*">
                            @error('cover_image')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-xs py-1 px-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary text-xs py-1 px-2">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Import Books Modal -->
    <div class="modal fade" id="bulkImportModal" tabindex="-1" aria-labelledby="bulkImportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm" id="bulkImportModalLabel">Bulk Import Books</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkImportForm" action="{{ route('admin.books.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="excelFile" class="form-label text-sm">Upload Excel File (.xlsx, .csv)</label>
                            <input type="file" class="form-control text-sm py-1 @error('excel_file') is-invalid @enderror" id="excelFile" name="excel_file" accept=".xlsx,.csv" required>
                            @error('excel_file')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <p class="text-muted text-xs">
                            Download <a href="{{ asset('templates/book_import_template.xlsx') }}" class="underline">template</a> for formatting.
                            Required: title, author, isbn, category_name.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-xs py-1 px-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success text-xs py-1 px-2">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .books-grid {
        display: grid;
        gap: 1rem;
        justify-content: center;
    }
    .book-card {
        width: 100%;
        max-width: 14rem;
    }
    .book-cover-img {
        width: 80px;
        height: 120px;
        object-fit: cover;
        margin: auto;
        transition: transform 0.2s;
        cursor: pointer;
    }
    .modal-cover-img {
        max-width: 100%;
        max-height: 60vh;
    }
    .current-cover-img {
        max-width: 80px;
        max-height: 120px;
    }
    .book-cover-link:hover .book-cover-img {
        transform: scale(1.05);
        box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }
    .action-buttons {
        white-space: nowrap;
    }
    .carousel-item {
        padding: 0.75rem;
    }
    .carousel-control-prev, .carousel-control-next {
        width: 4%;
        background: rgba(0, 0, 0, 0.3);
    }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: #000;
    }
    .card {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .form-control {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: none;
    }
    .form-control.is-invalid ~ .invalid-feedback {
        display: block;
    }
    .btn {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
    #searchInput {
        max-width: 200px;
    }
    .text-red-500 {
        color: #dc3545;
    }

    /* Mobile (≤ 576px): 2 books per slide, 1 top, 1 bottom */
    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.5rem;
        }
        .books-grid {
            grid-template-columns: 1fr;
            grid-template-rows: repeat(2, auto);
        }
        .book-card {
            max-width: 10rem;
        }
        .book-card:nth-child(n+3) {
            display: none;
        }
        .book-cover-img {
            width: 50px;
            height: 75px;
        }
        .current-cover-img {
            max-width: 50px;
            max-height: 75px;
        }
        .modal-cover-img {
            max-height: 50vh;
        }
        .card-body {
            padding: 0.5rem;
        }
        .card-title {
            font-size: 0.7rem;
        }
        .card-text {
            font-size: 0.65rem;
        }
        .action-buttons {
            flex-direction: column;
            gap: 0.5rem;
        }
        .btn, .form-control {
            font-size: 0.7rem;
            padding: 0.3rem 0.5rem;
        }
        #searchInput {
            width: 100%;
        }
        .carousel-control-prev, .carousel-control-next {
            width: 8%;
        }
        .modal-dialog {
            margin: 0.25rem;
        }
        .modal-body {
            padding: 0.75rem;
        }
        .modal-footer {
            padding: 0.5rem;
        }
        .text-sm {
            font-size: 0.7rem;
        }
        .text-xs {
            font-size: 0.65rem;
        }
    }

    /* Tablet and Desktop (> 576px): 6 books per slide, 3 top, 3 bottom */
    @media (min-width: 577px) {
        .books-grid {
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, auto);
        }
        .book-card {
            max-width: 14rem;
        }
        .book-cover-img {
            width: 80px;
            height: 120px;
        }
        .current-cover-img {
            max-width: 80px;
            max-height: 120px;
        }
        .card-title {
            font-size: 0.875rem;
        }
        .card-text {
            font-size: 0.75rem;
        }
    }

    /* Tablet (577px–992px): Slightly smaller elements */
    @media (min-width: 577px) and (max-width: 992px) {
        .book-card {
            max-width: 12rem;
        }
        .book-cover-img {
            width: 70px;
            height: 105px;
        }
        .current-cover-img {
            max-width: 70px;
            max-height: 105px;
        }
        .btn, .form-control {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
        }
        #searchInput {
            max-width: 150px;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewCoverModal = new bootstrap.Modal(document.getElementById('viewCoverModal'));
        const addBookModal = new bootstrap.Modal(document.getElementById('addBookModal'));
        const bulkImportModal = new bootstrap.Modal(document.getElementById('bulkImportModal'));
        const editBookModal = new bootstrap.Modal(document.getElementById('editBookModal'));

        // Handle book cover clicks
        document.querySelectorAll('.book-cover-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const cover = this.getAttribute('data-cover');
                const title = this.getAttribute('data-title');
                const filePath = this.getAttribute('data-file-path');
                
                const enlargedCoverImage = document.getElementById('enlargedCoverImage');
                const noCoverMessage = document.getElementById('noCoverMessage');
                document.getElementById('viewCoverModalLabel').textContent = `Book Cover: ${title}`;
                
                if (cover) {
                    enlargedCoverImage.src = cover;
                    enlargedCoverImage.style.display = 'block';
                    noCoverMessage.style.display = 'none';
                } else {
                    enlargedCoverImage.style.display = 'none';
                    noCoverMessage.style.display = 'block';
                }
                
                const pdfButton = document.getElementById('viewPdfButton');
                if (filePath && (filePath.endsWith('.pdf') || filePath.endsWith('.txt') || filePath.endsWith('.epub'))) {
                    pdfButton.style.display = 'inline-block';
                    pdfButton.href = filePath;
                } else {
                    pdfButton.style.display = 'none';
                }
                
                viewCoverModal.show();
            });
        });

        // Handle edit book modal
        document.getElementById('editBookModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const book = JSON.parse(button.getAttribute('data-book'));
            const fileExists = button.getAttribute('data-file-exists') === 'true';
            const coverExists = button.getAttribute('data-cover-exists') === 'true';
            const form = document.getElementById('editBookForm');
            
            form.action = `/admin/books/${book.id}`;
            document.getElementById('editTitle').value = book.title || '';
            document.getElementById('editAuthor').value = book.author || '';
            document.getElementById('editIsbn').value = book.isbn || '';
            document.getElementById('editCategory').value = book.category_id || '';

            const currentBookText = document.getElementById('currentBookText');
            const currentCoverText = document.getElementById('currentCoverText');
            const currentCoverImage = document.getElementById('currentCoverImage');

            // Handle book file display
            if (book.file_path && fileExists) {
                currentBookText.innerHTML = `Current: <a href="/storage/${book.file_path}" target="_blank">View file</a>`;
            } else if (book.file_path) {
                currentBookText.innerHTML = `<span class="text-red-500">Invalid file path: ${book.file_path}</span>`;
            } else {
                currentBookText.innerHTML = 'No file uploaded';
            }

            // Handle cover image display
            if (book.cover_image_path && coverExists) {
                currentCoverText.innerHTML = 'Current cover:';
                currentCoverImage.src = `/storage/${book.cover_image_path}`;
                currentCoverImage.style.display = 'block';
            } else if (book.cover_image_path) {
                currentCoverText.innerHTML = `<span class="text-red-500">Invalid cover image path: ${book.cover_image_path}</span>`;
                currentCoverImage.style.display = 'none';
            } else {
                currentCoverText.innerHTML = 'No cover image uploaded';
                currentCoverImage.style.display = 'none';
            }

            console.log('Edit modal opened for book:', {
                id: book.id,
                file_path: book.file_path,
                file_exists: fileExists,
                cover_image_path: book.cover_image_path,
                cover_exists: coverExists
            });
        });

        // Handle add book button click
        document.getElementById('addBookButton').addEventListener('click', function() {
            addBookModal.show();
        });

        // Handle bulk import button click
        document.getElementById('bulkImportButton').addEventListener('click', function() {
            bulkImportModal.show();
        });

        // Handle search form submission
        document.getElementById('searchBooksForm').addEventListener('submit', function(e) {
            const searchInput = document.getElementById('searchInput').value.trim();
            if (!searchInput) {
                e.preventDefault();
                window.location.href = "{{ route('admin.books.index') }}";
            }
        });

        // Debug form submissions
        ['addBookForm', 'bulkImportForm', 'editBookForm'].forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', function(e) {
                    const formData = new FormData(this);
                    console.log(`${formId} data:`, Array.from(formData.entries()));
                });
            }
        });

        // Debug file inputs
        ['addCoverImage', 'excelFile', 'editCoverImage', 'editBook', 'addBookFile'].forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('change', function() {
                    console.log(`${inputId} selected:`, this.files[0]?.name || 'None');
                });
            }
        });

        // Adjust carousel display
        function updateCarousel() {
            const isMobile = window.innerWidth <= 576;
            const carouselItems = document.querySelectorAll('.carousel-item');
            carouselItems.forEach(item => {
                const cards = item.querySelectorAll('.book-card');
                cards.forEach((card, index) => {
                    if (isMobile && index >= 2) {
                        card.style.display = 'none';
                    } else {
                        card.style.display = 'block';
                    }
                });
            });
        }

        updateCarousel();
        window.addEventListener('resize', updateCarousel);
    });
</script>
@endsection