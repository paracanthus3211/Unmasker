<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 text-primary">Input Level & Soal Baru</h1>
                <a href="{{ route('admin.quizz') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Informasi Level</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Nama Level -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Level</label>
                    <input type="text" class="form-control" wire:model="levelName" 
                           placeholder="Contoh: Level 1 - Dasar">
                    @error('levelName') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Waktu Pengerjaan -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Waktu (detik)</label>
                    <input type="number" class="form-control" wire:model="timeLimit" 
                           min="30" value="300">
                    @error('timeLimit') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Upload Gambar Tombol -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Gambar Tombol Level</label>
                    <input type="file" class="form-control" wire:model="buttonImage" 
                           accept="image/*">
                    @error('buttonImage') <span class="text-danger small">{{ $message }}</span> @enderror
                    @if ($buttonImage)
                        <small class="text-muted">File: {{ $buttonImage->getClientOriginalName() }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Soal -->
    <div class="card mt-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Soal-soal</h5>
            <button type="button" class="btn btn-light btn-sm" wire:click="addQuestion">
                <i class="bi bi-plus-circle"></i> Tambah Soal
            </button>
        </div>
        <div class="card-body">
            @foreach ($questions as $index => $question)
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong>Soal {{ $index + 1 }}</strong>
                        @if (count($questions) > 1)
                            <button type="button" class="btn btn-danger btn-sm" 
                                    wire:click="removeQuestion({{ $index }})"
                                    onclick="return confirm('Hapus soal ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Pertanyaan -->
                        <div class="mb-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea class="form-control" rows="3" 
                                      wire:model="questions.{{ $index }}.question_text"
                                      placeholder="Masukkan pertanyaan..."></textarea>
                            @error('questions.' . $index . '.question_text') 
                                <span class="text-danger small">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Gambar Pertanyaan -->
                        <div class="mb-3">
                            <label class="form-label">Gambar Pertanyaan (Opsional)</label>
                            <input type="file" class="form-control" 
                                   wire:model="questions.{{ $index }}.question_image"
                                   accept="image/*">
                            @if ($question['question_image'])
                                <small class="text-muted">File: {{ $question['question_image']->getClientOriginalName() }}</small>
                            @endif
                        </div>

                        <!-- Opsi Jawaban -->
                        <div class="row mb-3">
                            @foreach (['A', 'B', 'C', 'D'] as $option)
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Opsi {{ $option }}</label>
                                    <div class="input-group mb-1">
                                        <input type="text" class="form-control" 
                                               wire:model="questions.{{ $index }}.option_{{ strtolower($option) }}"
                                               placeholder="Jawaban {{ $option }}">
                                    </div>
                                    <input type="file" class="form-control" 
                                           wire:model="questions.{{ $index }}.option_{{ strtolower($option) }}_image"
                                           accept="image/*">
                                    @if ($question['option_' . strtolower($option) . '_image'])
                                        <small class="text-muted">File: {{ $question['option_' . strtolower($option) . '_image']->getClientOriginalName() }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Jawaban Benar -->
                        <div class="mb-3">
                            <label class="form-label">Jawaban Benar</label>
                            <select class="form-select" 
                                    wire:model="questions.{{ $index }}.correct_answer">
                                <option value="">Pilih Jawaban Benar</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                            @error('questions.' . $index . '.correct_answer') 
                                <span class="text-danger small">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tombol Simpan -->
    <div class="mt-4 text-center">
        <button type="button" class="btn btn-success btn-lg" wire:click="saveLevel">
            <i class="bi bi-save"></i> Simpan Level & Soal
        </button>
    </div>
</div>