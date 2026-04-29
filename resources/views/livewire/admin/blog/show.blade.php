<div class="container mx-auto pt-28 md:pt-36">
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-wrap items-center gap-3 mb-6 text-sm text-gray-500">
            @if($post->category)
                <a href="{{ url('/blog/category/' . $post->category->slug) }}"
                    class="bg-primary-50 text-primary-700 px-3 py-1 rounded-full font-medium hover:bg-primary-100 transition-colors">
                    {{ $post->category->name }}
                </a>
            @endif
            <span><i class="far fa-calendar mr-1"></i>
                {{ $post->published_at ? $post->published_at->format('F d, Y') : '' }}</span>
            <span><i class="far fa-clock mr-1"></i> {{ $post->reading_time }} min read</span>
            <span><i class="far fa-eye mr-1"></i> {{ number_format($post->views_count) }} views</span>
        </div>

        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">{{ $post->title }}</h1>

        @if($post->excerpt)
            <p class="text-xl text-gray-500 mb-8 leading-relaxed">{{ $post->excerpt }}</p>
        @endif

        @if($post->creator)
            <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-200">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($post->creator->name, 0, 2)) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-900">{{ $post->creator->name }}</div>
                    <div class="text-sm text-gray-500">Author</div>
                </div>
            </div>
        @endif

        <div class="prose max-w-none">
            {!! $post->body !!}
        </div>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Share this article</h3>
            <div class="flex flex-wrap gap-3">
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/blog/' . $post->slug)) }}&text={{ urlencode($post->title) }}"
                    target="_blank" class="share-btn">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url('/blog/' . $post->slug)) }}"
                    target="_blank" class="share-btn">
                    <i class="fab fa-linkedin"></i> LinkedIn
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/blog/' . $post->slug)) }}"
                    target="_blank" class="share-btn">
                    <i class="fab fa-facebook"></i> Facebook
                </a>
                <button
                    onclick="navigator.clipboard.writeText('{{ url('/blog/' . $post->slug) }}'); this.innerHTML='<i class=\'fas fa-check\'></i> Copied!';"
                    class="share-btn">
                    <i class="fas fa-link"></i> Copy Link
                </button>
            </div>
        </div>
    </article>

    @if($relatedPosts->count())
        <section class="bg-gray-50 py-12 mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold mb-8 text-gray-900">Related Posts</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                        <a href="{{ url('/blog/' . $related->slug) }}"
                            class="blog-card block bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                            @if($related->featured_image)
                                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}"
                                    class="w-full h-40 object-cover">
                            @else
                                <div
                                    class="w-full h-40 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-2xl text-gray-300"></i></div>
                            @endif
                            <div class="p-5">
                                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $related->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $related->published_at ? $related->published_at->format('M d, Y') : '' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <style>
        .prose h2 { font-size: 1.5rem; font-weight: 700; margin-top: 2rem; margin-bottom: 1rem; color: #111827; }
        .prose h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #111827; }
        .prose p { margin-bottom: 1.25rem; line-height: 1.8; color: #374151; }
        .prose ul, .prose ol { margin-bottom: 1.25rem; padding-left: 1.5rem; }
        .prose li { margin-bottom: 0.5rem; line-height: 1.7; color: #374151; }
        .prose a { color: #0ea5e9; text-decoration: underline; }
        .prose img { border-radius: 0.75rem; margin: 1.5rem 0; }
        .prose blockquote { border-left: 4px solid #0ea5e9; padding-left: 1.25rem; margin: 1.5rem 0; color: #6b7280; font-style: italic; }
        .prose code { background: #f3f4f6; padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.875rem; }
        .prose pre { background: #1f2937; color: #e5e7eb; padding: 1.25rem; border-radius: 0.75rem; overflow-x: auto; margin: 1.5rem 0; }
        .share-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; border: 1px solid #e5e7eb; color: #6b7280; }
        .share-btn:hover { border-color: #0ea5e9; color: #0ea5e9; }
        .blog-card { transition: all 0.3s ease; }
        .blog-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -6px rgba(0,0,0,0.1); }
    </style>
</div>