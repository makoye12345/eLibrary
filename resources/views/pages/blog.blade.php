@extends('layouts.app')

@section('title', 'Blog')

@section('content')
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="1000">Our Blog</h1>
            
            <!-- Search Bar -->
            <div class="mb-8 max-w-md mx-auto">
                <input type="text" id="search" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search blog posts...">
            </div>

            <!-- Featured Post -->
            <div class="mb-12 bg-white p-6 rounded-lg shadow-lg card-hover">
                <img src="{{ asset('images/book12.jpg') }}" alt="Featured Post" class="w-full h-64 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/1200x400?text=Featured+Image'; this.alt='Placeholder Featured Image';">
                <h2 class="text-2xl font-semibold mb-2">Featured: Latest Book Arrivals</h2>
                <p class="text-gray-600 mb-4">Discover our newest collection of books added this month. From fiction to academic resources, we have something for everyone.</p>
                <a href="#!" class="text-blue-500 hover:underline read-more" onclick="toggleContent('featured-content', this)">Read More</a>
                <div id="featured-content" class="expanded-content text-gray-600 mt-4">
                    <p>Our latest collection includes bestsellers like "The New Frontier" by Jane Author, a thrilling sci-fi novel, and "Academic Insights" by Dr. John Scholar, perfect for researchers. Browse our digital library to access these titles instantly or reserve them for pickup at your local branch. Stay tuned for our upcoming book club discussion on these new arrivals!</p>
                </div>
            </div>

            <!-- Categories/Tags -->
            <div class="mb-8 flex flex-wrap gap-4 justify-center">
                <button class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full hover:bg-blue-200 transition category-btn active" data-category="all">All</button>
                <button class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full hover:bg-blue-200 transition category-btn" data-category="fiction">Fiction</button>
                <button class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full hover:bg-blue-200 transition category-btn" data-category="academic">Academic</button>
                <button class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full hover:bg-blue-200 transition category-btn" data-category="events">Events</button>
                <button class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full hover:bg-blue-200 transition category-btn" data-category="reading-tips">Reading Tips</button>
            </div>

            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" data-aos="fade-up" data-aos-duration="500" id="blog-grid">
                <!-- Fiction Posts -->
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="fiction">
                    <img src="{{ asset('images/book1.jpg') }}" alt="Fiction Post 1" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">The New Frontier</h3>
                    <p class="text-gray-600">Dive into a thrilling sci-fi novel by Jane Author.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('fiction-1-content', this)">Read More</a>
                    <div id="fiction-1-content" class="expanded-content text-gray-600 mt-4">
                        <p>"The New Frontier" by Jane Author takes you on an interstellar journey through a dystopian universe. Follow Captain Zara as she navigates alien worlds and political intrigue. Available now in our digital library and for reservation at your local branch!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="fiction">
                    <img src="{{ asset('images/book4.jpg') }}" alt="Fiction Post 2" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Mystery Tales</h3>
                    <p class="text-gray-600">Unravel gripping mysteries with this new release.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('fiction-2-content', this)">Read More</a>
                    <div id="fiction-2-content" class="expanded-content text-gray-600 mt-4">
                        <p>"Mystery Tales" is a collection of suspenseful short stories by renowned author Alex Noir. Each tale keeps you guessing until the final page. Perfect for fans of thrillers. Check it out in our e-book collection!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="fiction">
                    <img src="{{ asset('images/book3.jpg') }}" alt="Fiction Post 3" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Romantic Horizons</h3>
                    <p class="text-gray-600">A heartwarming romance novel to captivate readers.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('fiction-3-content', this)">Read More</a>
                    <div id="fiction-3-content" class="expanded-content text-gray-600 mt-4">
                        <p>"Romantic Horizons" by Sarah Love explores the journey of two souls finding each other against all odds. This emotional tale is a must-read for romance enthusiasts. Available for instant access in our digital library!</p>
                    </div>
                </div>
                <!-- Academic Posts -->
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="academic">
                    <img src="{{ asset('images/book3.jpg') }}" alt="Academic Post 1" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Academic Insights</h3>
                    <p class="text-gray-600">Explore new research resources by Dr. John Scholar.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('academic-1-content', this)">Read More</a>
                    <div id="academic-1-content" class="expanded-content text-gray-600 mt-4">
                        <p>"Academic Insights" by Dr. John Scholar provides in-depth analysis of current trends in scientific research. Ideal for students and researchers, this resource is available in our digital library with full-text search capabilities.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="academic">
                    <img src="{{ asset('images/book12.jpg') }}" alt="Academic Post 2" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Research Methodologies</h3>
                    <p class="text-gray-600">A guide to effective research techniques.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('academic-2-content', this)">Read More</a>
                    <div id="academic-2-content" class="expanded-content text-gray-600 mt-4">
                        <p>Our latest guide on research methodologies covers quantitative and qualitative approaches, data analysis, and citation management. Access this resource in our academic section to enhance your research skills.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="academic">
                    <img src="{{ asset('images/book1.jpg') }}" alt="Academic Post 3" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Open Access Journals</h3>
                    <p class="text-gray-600">Discover our collection of open access journals.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('academic-3-content', this)">Read More</a>
                    <div id="academic-3-content" class="expanded-content text-gray-600 mt-4">
                        <p>Our platform now includes over 1,000 open access journals covering various disciplines. Browse our academic section to find peer-reviewed articles and stay updated with the latest research.</p>
                    </div>
                </div>
                <!-- Events Posts -->
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="events">
                    <img src="{{ asset('images/book3.jpg') }}" alt="Event Post 1" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Book Club: The New Frontier</h3>
                    <p class="text-gray-600">Join our discussion on July 10, 2025.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('event-1-content', this)">Read More</a>
                    <div id="event-1-content" class="expanded-content text-gray-600 mt-4">
                        <p>Join us for a lively book club discussion on "The New Frontier" by Jane Author on July 10, 2025, at 6 PM. Connect with fellow readers and share your thoughts. Register now on our events page!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="events">
                    <img src="{{ asset('images/book12.jpg') }}" alt="Event Post 2" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Author Talk: Dr. John Scholar</h3>
                    <p class="text-gray-600">Meet the author on July 15, 2025.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('event-2-content', this)">Read More</a>
                    <div id="event-2-content" class="expanded-content text-gray-600 mt-4">
                        <p>Dr. John Scholar will discuss his book "Academic Insights" on July 15, 2025, at 7 PM. Learn about the latest research trends and ask questions. Secure your spot by registering today!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="events">
                    <img src="{{ asset('images/book1.jpg') }}" alt="Event Post 3" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Reading Workshop</h3>
                    <p class="text-gray-600">Enhance your skills on July 20, 2025.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('event-3-content', this)">Read More</a>
                    <div id="event-3-content" class="expanded-content text-gray-600 mt-4">
                        <p>Our reading workshop on July 20, 2025, will teach you advanced reading techniques and note-taking strategies. Perfect for students and professionals. Sign up now to join!</p>
                    </div>
                </div>
                <!-- Reading Tips Posts -->
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="reading-tips">
                    <img src="{{ asset('images/book1.jpg') }}" alt="Reading Tip 1" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Speed Reading Techniques</h3>
                    <p class="text-gray-600">Boost your reading speed with these tips.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('reading-1-content', this)">Read More</a>
                    <div id="reading-1-content" class="expanded-content text-gray-600 mt-4">
                        <p>Learn speed reading by minimizing subvocalization and using a pointer to guide your eyes. Practice with our digital tools to track your progress and read up to 50% faster. Join our workshop for hands-on training!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="reading-tips">
                    <img src="{{ asset('images/book4.jpg') }}" alt="Reading Tip 2" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Note-Taking Strategies</h3>
                    <p class="text-gray-600">Enhance retention with effective notes.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('reading-2-content', this)">Read More</a>
                    <div id="reading-2-content" class="expanded-content text-gray-600 mt-4">
                        <p>Use the Cornell method or mind mapping to organize your reading notes. Our digital platform allows you to highlight and annotate e-books, making it easy to review key points later.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="reading-tips">
                    <img src="{{ asset('images/book3.jpg') }}" alt="Reading Tip 3" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Building a Reading Habit</h3>
                    <p class="text-gray-600">Tips to read consistently every day.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('reading-3-content', this)">Read More</a>
                    <div id="reading-3-content" class="expanded-content text-gray-600 mt-4">
                        <p>Set a daily reading goal, start with short sessions, and use our app to track your reading streak. Join our community challenges to stay motivated and share your progress!</p>
                    </div>
                </div>
                <!-- Original Posts -->
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="all">
                    <img src="{{ asset('images/book12.jpg') }}" alt="Blog Image 1" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Latest Book Arrivals</h3>
                    <p class="text-gray-600">Check out our newest collection of books added this month.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('post-1-content', this)">Read More</a>
                    <div id="post-1-content" class="expanded-content text-gray-600 mt-4">
                        <p>This month, we’ve added over 200 new titles to our collection, including popular fiction, non-fiction, and academic resources. Highlights include "The New Frontier" by Jane Author and "Academic Insights" by Dr. John Scholar. Visit our digital library to start reading or reserve your copy today!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="reading-tips">
                    <img src="{{ asset('images/book1.jpg') }}" alt="Blog Image 2" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Effective Reading Tips</h3>
                    <p class="text-gray-600">Learn how to make the most of your reading time with our expert tips.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('post-2-content', this)">Read More</a>
                    <div id="post-2-content" class="expanded-content text-gray-600 mt-4">
                        <p>Maximize your reading efficiency with these tips: set a reading schedule, take notes to retain key points, and use our digital platform to highlight and bookmark pages. Join our reading workshops to learn more from experts and share your progress with our community!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="events">
                    <img src="{{ asset('images/book3.jpg') }}" alt="Blog Image 3" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Upcoming Library Events</h3>
                    <p class="text-gray-600">Join our book reading sessions and community discussions.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('post-3-content', this)">Read More</a>
                    <div id="post-3-content" class="expanded-content text-gray-600 mt-4">
                        <p>Our upcoming events include a book club discussion on "The New Frontier" on July 10, 2025, and an author talk with Dr. John Scholar on July 15, 2025. Register now to secure your spot and connect with fellow book lovers in our vibrant community!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover" data-category="all">
                    <img src="{{ asset('images/book4.jpg') }}" alt="Blog Image 4" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Blog+Image'; this.alt='Placeholder Blog Image';">
                    <h3 class="text-xl font-semibold mb-2">Digital Library Benefits</h3>
                    <p class="text-gray-600">Explore the advantages of our digital library platform.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('post-4-content', this)">Read More</a>
                    <div id="post-4-content" class="expanded-content text-gray-600 mt-4">
                        <p>Our digital library offers 24/7 access to thousands of e-books and audiobooks, searchable databases, and personalized reading recommendations. Enjoy seamless access on any device, with offline reading options and integrated note-taking features.</p>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center space-x-4">
                <a href="/blog?page=1" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">1</a>
                <a href="/blog?page=2" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">2</a>
                <a href="/blog?page=3" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">3</a>
                <a href="/blog?page=next" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Next</a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded for Blog page');

            const searchInput = document.getElementById('search');
            if (searchInput) {
                console.log('Search input found');
                searchInput.addEventListener('input', function() {
                    console.log('Search input changed:', this.value);
                    const searchTerm = this.value.toLowerCase();
                    const activeCategory = document.querySelector('.category-btn.active')?.dataset.category || 'all';
                    console.log('Active category:', activeCategory);
                    document.querySelectorAll('#blog-grid .card-hover').forEach(card => {
                        const title = card.querySelector('h3').textContent.toLowerCase();
                        const content = card.querySelector('p:not(.expanded-content p)').textContent.toLowerCase();
                        const expandedContent = card.querySelector('.expanded-content p')?.textContent.toLowerCase() || '';
                        const cardCategory = card.dataset.category;
                        const matchesSearch = title.includes(searchTerm) || content.includes(searchTerm) || expandedContent.includes(searchTerm);
                        const matchesCategory = activeCategory === 'all' || cardCategory === activeCategory || cardCategory === 'all';
                        card.style.display = matchesSearch && matchesCategory ? '' : 'none';
                        console.log(`Card "${title}": Search match=${matchesSearch}, Category match=${matchesCategory}, Display=${card.style.display}`);
                    });
                });
            } else {
                console.error('Search input not found');
            }

            const categoryButtons = document.querySelectorAll('.category-btn');
            if (categoryButtons.length > 0) {
                console.log('Category buttons found:', categoryButtons.length);
                categoryButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        console.log('Category button clicked:', this.dataset.category);
                        categoryButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        const category = this.dataset.category;
                        const searchTerm = document.getElementById('search')?.value.toLowerCase() || '';
                        console.log('Filtering for category:', category, 'Search term:', searchTerm);
                        document.querySelectorAll('#blog-grid .card-hover').forEach(card => {
                            const title = card.querySelector('h3').textContent.toLowerCase();
                            const content = card.querySelector('p:not(.expanded-content p)').textContent.toLowerCase();
                            const expandedContent = card.querySelector('.expanded-content p')?.textContent.toLowerCase() || '';
                            const cardCategory = card.dataset.category;
                            const matchesSearch = searchTerm === '' || title.includes(searchTerm) || content.includes(searchTerm) || expandedContent.includes(searchTerm);
                            const matchesCategory = category === 'all' || cardCategory === category || cardCategory === 'all';
                            card.style.display = matchesSearch && matchesCategory ? '' : 'none';
                            console.log(`Card "${title}": Search match=${matchesSearch}, Category match=${matchesCategory}, Display=${card.style.display}`);
                        });
                    });
                });
            } else {
                console.error('No category buttons found');
            }
        });
    </script>
@endsection