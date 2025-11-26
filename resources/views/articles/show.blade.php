<x-layouts.admin>
    <div class="p-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Tombol Kembali -->
                        <a href="{{ route('admin.literacy') }}" class="btn btn-secondary mb-4">
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

                        <!-- Tombol Aksi untuk Admin -->
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div class="mt-5 pt-4 border-top">
                                <h6>Admin Actions:</h6>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Edit Artikel
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Hapus Artikel
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
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
</x-layouts.admin>