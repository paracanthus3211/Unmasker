<x-layouts.admin>
    <div class="p-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.literacy') }}" class="btn btn-secondary mb-3">
                            <i class="bi bi-arrow-left"></i> Kembali ke Library
                        </a>
                        
                        <h1 class="card-title">{{ $article->title }}</h1>
                        
                        <div class="text-center mb-4">
                            <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" 
                                 class="img-fluid rounded" style="max-height: 400px;">
                        </div>
                        
                        <div class="article-content">
                            {!! nl2br(e($article->content)) !!}
                        </div>
                        
                        <div class="mt-4 pt-4 border-top">
                            <small class="text-muted">
                                <strong>Kategori:</strong> {{ ucfirst($article->category) }} | 
                                <strong>Dibuat:</strong> {{ $article->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .article-content {
            line-height: 1.8;
            font-size: 1.1rem;
            text-align: justify;
        }
        
        .article-content p {
            margin-bottom: 1rem;
        }
    </style>
</x-layouts.admin>