<x-layouts.app>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Tombol Kembali -->
                        <a href="{{ route('user.literacy') }}" class="btn btn-secondary mb-4">
                            <i class="bi bi-arrow-left"></i> Kembali ke Library
                        </a>

                        <h1 class="card-title">{{ $article->title }}</h1>
                        
                        <!-- Info Artikel -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> Oleh: {{ $article->user->name ?? 'Admin' }}
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> {{ $article->created_at->format('d M Y') }} |
                                    <i class="bi bi-eye"></i> {{ $article->views_count }}x dilihat
                                </small>
                            </div>
                        </div>

                        <!-- Gambar Artikel -->
                        @if($article->image && Storage::disk('public')->exists($article->image))
                            <div class="text-center mb-4">
                                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" 
                                     class="img-fluid rounded" style="max-height: 400px;">
                            </div>
                        @endif

                        <!-- Konten Artikel -->
                        <div class="article-content">
                            {!! nl2br(e($article->content)) !!}
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
            margin-bottom: 1.5rem;
        }
    </style>
</x-layouts.app>