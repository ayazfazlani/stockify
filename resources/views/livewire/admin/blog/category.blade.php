<div>
    <section class="pt-28 pb-12 bg-gradient-to-r from-primary-500 to-indigo-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-sm font-medium uppercase tracking-wider opacity-80 mb-2">Category</div>
            <h1 class="text-3xl md:text-5xl font-bold mb-4">{{ $category->name }}</h1>
            @if($category->description)
            <p class="text-xl opacity-90 max-w-2xl mx-auto">{{ $category->description }}</p>@endif
        </div>
    </section>

    @if($categories->count())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('/blog') }}"
                    class="px-4 py-2 rounded-full text-sm font-medium bg-white text-gray-700 border border-gray-200 hover:border-primary-300 transition-all">All
                    Posts</a>
                @foreach($categories as $cat)
                    <a href="{{ url('/blog/category/' . $cat->slug) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium {{ $cat->id === $category->id ? 'bg-primary-500 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-primary-300' }} transition-all">
                        {{ $cat->name }} <span
                            class="{{ $cat->id === $category->id ? 'text-primary-100' : 'text-gray-400' }} ml-1">({{ $cat->published_posts_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                        <h3 class="text-lg font-bold mb-2 text-gray-900 line-clamp-2">{{ $post->title }}</h3>
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
                    <i class="fas fa-folder-open text-4xl mb-4 opacity-40"></i>
                    <p class="text-lg">No posts in this category yet.</p>
                    <a href="{{ url('/blog') }}" class="inline-block mt-4 text-primary-600 font-medium hover:underline">←
                        Back to all posts</a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $posts->links() }}</div>
    </section>

    <style>
        .blog-card {
            transition: all 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.1);
        }
    </style>
</div>