<div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">{{ $page->title }}</h1>
        <div class="prose max-w-none">
            {!! $page->body !!}
        </div>
    </div>

    <style>
        .prose h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111827;
        }

        .prose h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #111827;
        }

        .prose p {
            margin-bottom: 1.25rem;
            line-height: 1.8;
            color: #374151;
        }

        .prose ul,
        .prose ol {
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
            line-height: 1.7;
            color: #374151;
        }

        .prose a {
            color: #0ea5e9;
            text-decoration: underline;
        }

        .prose img {
            border-radius: 0.75rem;
            margin: 1.5rem 0;
        }
    </style>
</div>