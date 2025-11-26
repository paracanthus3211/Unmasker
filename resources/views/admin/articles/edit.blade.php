<x-layouts.admin>
    <div class="p-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Artikel</h5>
                        
                        <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="title" class="form-label">Judul Artikel</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $article->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="image" class="form-label">Gambar Utama</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Biarkan kosong jika tidak ingin mengubah gambar. 
                                        Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                                    </div>
                                    @if($article->image)
                                        <div class="mt-2">
                                            <p>Gambar saat ini:</p>
                                            <img src="{{ Storage::url($article->image) }}" alt="Current image" 
                                                 style="max-height: 200px; max-width: 300px;" class="img-thumbnail">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="excerpt" class="form-label">Deskripsi Singkat</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                              id="excerpt" name="excerpt" rows="3" maxlength="200" required>{{ old('excerpt', $article->excerpt) }}</textarea>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Maksimal 200 karakter.</div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="content" class="form-label">Konten Lengkap</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="15" required>{{ old('content', $article->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" 
                                               {{ $article->is_published ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">
                                            Publikasikan Artikel
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.literacy') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Kembali
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Update Artikel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Counter untuk excerpt
        document.addEventListener('DOMContentLoaded', function() {
            const excerptTextarea = document.getElementById('excerpt');
            const excerptCounter = document.createElement('div');
            excerptCounter.className = 'form-text';
            excerptCounter.innerHTML = '<span id="excerpt-counter">' + excerptTextarea.value.length + '</span>/200 karakter';
            excerptTextarea.parentNode.appendChild(excerptCounter);

            excerptTextarea.addEventListener('input', function() {
                document.getElementById('excerpt-counter').textContent = this.value.length;
            });
        });
    </script>
</x-layouts.admin>