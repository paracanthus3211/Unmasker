<div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Library Management</h5>
                        
                        <!-- Tombol Tambah Materi -->
                        <div class="text-center mb-4">
                            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Materi/Artikel
                            </a>
                        </div>

                        <!-- Alert Success -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row g-4">
                            @if($articles->count() > 0)
                                @foreach($articles as $article)
                                <div class="col-xl-4 col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <small class="text-muted">ID: {{ $article->id }}</small>
                                            @if($article->is_published)
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-warning">Draft</span>
                                            @endif
                                        </div>
                                        @if($article->image && Storage::disk('public')->exists($article->image))
                                            <img src="{{ Storage::url($article->image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                                <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $article->title }}</h5>
                                            <p class="card-text">
                                                {{ $article->excerpt }}
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            <!-- Tombol Aksi -->
                                            <div class="d-flex gap-2 justify-content-center mb-2">
                                                <!-- Tombol Baca - gunakan ID -->
<a href="{{ route('admin.articles.show', $article->id) }}" class="btn btn-primary btn-sm">
    <i class="bi bi-eye"></i> Baca
</a>

<!-- Tombol Edit -->
<a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-warning btn-sm">
    <i class="bi bi-pencil"></i> Edit
</a>

<!-- Tombol Hapus -->
<form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" 
      onsubmit="return confirm('Hapus artikel ini?')" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm">
        <i class="bi bi-trash"></i> Hapus
    </button>
</form>
                                            </div>
                                            <small class="text-muted d-block text-center">
                                                Dibuat: {{ $article->created_at->format('d M Y') }} |
                                                Oleh: {{ $article->user->name ?? 'System' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-book display-1 text-muted"></i>
                                    <h4 class="text-muted mt-3">Belum ada artikel</h4>
                                    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-circle"></i> Tambah Artikel Pertama
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>