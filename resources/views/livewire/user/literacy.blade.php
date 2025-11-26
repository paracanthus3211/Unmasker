<div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Perpustakaan Artikel</h5>

                        <!-- Debug Info -->
                        <div class="mb-3">
                            <small class="text-muted">
                                Total Artikel Tersedia: {{ $articles->count() }}
                            </small>
                        </div>

                        <div class="row g-4">
                            @if($articles->count() > 0)
                                @foreach($articles as $article)
                                <div class="col-xl-4 col-md-6">
                                    <div class="card h-100">
                                        @if($article->image)
                                            <img src="{{ Storage::url($article->image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $article->title }}</h5>
                                            <p class="card-text">
                                                {{ $article->excerpt }}
                                            </p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('user.articles.show', $article->slug) }}" class="btn btn-primary btn-sm">Baca</a>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <small class="text-muted">
                                                Dibuat: {{ $article->created_at->format('d M Y') }}
                                                • Dilihat: {{ $article->views_count }}x
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-book display-1 text-muted"></i>
                                    <h4 class="text-muted mt-3">Belum ada artikel tersedia</h4>
                                    <p class="text-muted">Admin akan menambahkan artikel segera</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>