<div>
    <section class="pt-28 pb-12 bg-gradient-to-r from-primary-500 to-indigo-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Our Blog</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto">Insights, tips, and best practices for inventory management
                and business growth.</p>
        </div>
    </section>

    @if($categories->count())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('/blog') }}"
                    class="px-4 py-2 rounded-full text-sm font-medium {{ !request('category') ? 'bg-primary-500 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-primary-300' }} transition-all">All
                    Posts</a>
                @foreach($categories as $cat)
                    <a href="{{ url('/blog/category/' . $cat->slug) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium bg-white text-gray-700 border border-gray-200 hover:border-primary-300 transition-all">
                        {{ $cat->name }} <span class="text-gray-400 ml-1">({{ $cat->published_posts_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($featuredPosts->count())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Featured</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($featuredPosts as $fp)
                    <a href="{{ url('/blog/' . $fp->slug) }}"
                        class="blog-card block bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100">
                        @if($fp->featured_image)
                            <img src="{{ asset('storage/' . $fp->featured_image) }}" alt="{{ $fp->title }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 featured-card flex items-center justify-center"
                                style="background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);"><i
                                    class="fas fa-newspaper text-4xl text-white opacity-50"></i></div>
                        @endif
                        <div class="p-6">
                            @if($fp->category)<span
                            class="text-xs font-semibold text-primary-600 uppercase tracking-wider">{{ $fp->category->name }}</span>@endif
                            <h3 class="text-lg font-bold mt-1 mb-2 text-gray-900 line-clamp-2">{{ $fp->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2">
                                {{ $fp->excerpt ?: Str::limit(strip_tags($fp->body), 120) }}</p>
                            <div class="flex items-center justify-between mt-4 text-xs text-gray-400">
                                <span>{{ $fp->published_at ? $fp->published_at->format('M d, Y') : '' }}</span>
                                <span><i class="fas fa-eye mr-1"></i>{{ number_format($fp->views_count) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Latest Posts</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <a href="{{ url('/blog/' . $post->slug) }}"
                    class="blog-card block bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center"><i
                                class="fas fa-newspaper text-3xl text-gray-300"></i></div>
                    @endif
                    <div class="p-6">
                        @if($post->category)<span
                        class="text-xs font-semibold text-primary-600 uppercase tracking-wider">{{ $post->category->name }}</span>@endif
                        <h3 class="text-lg font-bold mt-1 mb-2 text-gray-900 line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-2">
                            {{ $post->excerpt ?: Str::limit(strip_tags($post->body), 120) }}</p>
                        <div class="flex items-center justify-between mt-4 text-xs text-gray-400">
                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</span>
                            <span>{{ $post->reading_time }} min read</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-16 text-gray-500">
                    <i class="fas fa-newspaper text-4xl mb-4 opacity-40"></i>
                    <p class="text-lg">No posts yet. Check back soon!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $posts->links() }}</div>
    </section>

    <style>
        .blog-card {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .blog-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.12);
        }
    </style>
</div>