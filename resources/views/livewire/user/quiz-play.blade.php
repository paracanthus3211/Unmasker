<div class="container-fluid py-4 quiz-container">
    @if($showResult)
        <!-- Tampilan Hasil Quiz -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 result-card">
                    <div class="card-header text-white text-center py-4" 
                         style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                        <h2 class="mb-0 fw-bold">Quiz Selesai! 🎉</h2>
                    </div>
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-trophy-fill display-1 text-warning" style="text-shadow: 0 0 20px gold;"></i>
                        </div>
                        <h4 class="text-dark mb-3">Level: <span class="text-primary">{{ $level->name }}</span></h4>
                        <div class="score-display my-4 p-4 rounded-3" 
                             style="background: linear-gradient(135deg, #00b09b, #96c93d);">
                            <h1 class="display-3 fw-bold text-white">{{ $score }}/{{ $level->questions->count() }}</h1>
                            <p class="text-white mb-0">Skor Akhir</p>
                        </div>
                        <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-4">
                            <button wire:click="restartQuiz" 
                                    class="btn btn-primary btn-lg px-5 fw-bold">
                                <i class="bi bi-arrow-repeat me-2"></i> Main Lagi
                            </button>
                            <a href="{{ route('user.quizz') }}" 
   class="btn btn-outline-dark btn-lg px-5 fw-bold">
    <i class="bi bi-house me-2"></i> Kembali
</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Tampilan Quiz Berlangsung -->
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card shadow-lg border-0 quiz-card mx-auto">
                    <!-- Header Quiz -->
                    <div class="card-header text-white py-4" 
                         style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-1 fw-bold">{{ $level->name }}</h4>
                                <small class="opacity-75">Soal {{ $currentQuestionIndex + 1 }} dari {{ $level->questions->count() }}</small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="timer bg-light text-dark px-4 py-2 rounded-pill d-inline-block">
                                    <i class="bi bi-clock me-2"></i> 
                                    <span id="timeLeft" class="fw-bold fs-5">{{ $timeLeft }}</span> 
                                    <small>detik</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="card-body">
                        <div class="progress-container mb-4">
                            <div class="progress-bar" 
                                 style="width: {{ (($currentQuestionIndex + 1) / $level->questions->count()) * 100 }}%">
                            </div>
                        </div>

                        <!-- Kartu Referensi -->
                        <div class="card-section mb-4">
                            <h5 class="section-title mb-3 text-center">Struktur Kartu Referensi</h5>
                            <div class="card-container mb-4 justify-content-center">
                                @php
                                    $referenceCards = ['A', 'B', '', 'D', ''];
                                @endphp
                                @foreach($referenceCards as $card)
                                    <div class="quiz-mini-card {{ $card ? '' : 'empty' }}">
                                        {{ $card }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pertanyaan -->
                        @if(isset($level->questions[$currentQuestionIndex]))
                            @php $question = $level->questions[$currentQuestionIndex]; @endphp
                            <div class="question-container">
                                <h3 class="question-text mb-4 text-center">
                                    {{ $question->question_text }}
                                </h3>

                                <!-- Debug Info untuk Gambar Soal -->
                                @if($question->question_image)
                                    <div class="image-debug mb-3">
                                        <strong>Debug Gambar Soal:</strong><br>
                                        Path: {{ $question->question_image }}<br>
                                        Full URL: {{ asset('storage/' . $question->question_image) }}<br>
                                        Storage exists: {{ Storage::disk('public')->exists($question->question_image) ? 'Ya' : 'Tidak' }}
                                    </div>
                                    
                                    <div class="question-image mb-4 text-center">
                                        <!-- Coba beberapa alternatif path -->
                                        <img src="{{ asset('storage/' . $question->question_image) }}" 
                                             class="img-fluid rounded-3 question-img"
                                             alt="Gambar Soal"
                                             onerror="
                                                console.error('Gagal load gambar dari storage, mencoba alternatif...');
                                                this.src='{{ $question->question_image }}';
                                                this.onerror=function() {
                                                    this.src='https://via.placeholder.com/600x400/6a11cb/white?text=Gambar+Soal';
                                                    console.error('Semua alternatif gagal');
                                                }">
                                        <small class="text-muted d-block mt-2">Gambar Soal</small>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">
                                        <i class="bi bi-info-circle me-2"></i>Tidak ada gambar untuk soal ini
                                    </div>
                                @endif

                                <!-- Kartu Pertanyaan -->
                                <div class="card-section mb-4">
                                    <h5 class="section-title mb-3 text-center">Pola Pertanyaan</h5>
                                    <div class="card-container mb-4 justify-content-center">
                                        @php
                                            $questionCards = ['', 'B', '', 'D', 'A'];
                                        @endphp
                                        @foreach($questionCards as $card)
                                            <div class="quiz-mini-card {{ $card ? '' : 'empty' }}">
                                                {{ $card }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Opsi Jawaban -->
                                <div class="options-container">
                                    <div class="row g-3 justify-content-center">
                                        @foreach(['A', 'B', 'C', 'D'] as $option)
                                            @php
                                                $optionText = $question->{'option_' . strtolower($option)};
                                                $optionImage = $question->{'option_' . strtolower($option) . '_image'};
                                                $isSelected = $userAnswers[$currentQuestionIndex] === $option;
                                            @endphp
                                            <div class="col-md-6">
                                                <div class="option-card {{ $isSelected ? 'selected' : '' }} text-center"
                                                     wire:click="answerQuestion('{{ $option }}')">
                                                    <div class="option-header justify-content-center">
                                                        <span class="option-letter">{{ $option }}</span>
                                                        <div class="option-text">{{ $optionText }}</div>
                                                    </div>
                                                    
                                                    @if($optionImage)
                                                        <!-- Debug Info untuk Gambar Opsi -->
                                                        <div class="image-debug mt-2">
                                                            <small><strong>Debug Opsi {{ $option }}:</strong><br>
                                                            Path: {{ $optionImage }}<br>
                                                            Exists: {{ Storage::disk('public')->exists($optionImage) ? 'Ya' : 'Tidak' }}</small>
                                                        </div>
                                                        
                                                        <div class="option-image mt-2 text-center">
                                                            <img src="{{ asset('storage/' . $optionImage) }}" 
                                                                 class="img-thumbnail option-img"
                                                                 alt="Gambar Opsi {{ $option }}"
                                                                 onerror="
                                                                    console.error('Gagal load gambar opsi dari storage, mencoba alternatif...');
                                                                    this.src='{{ $optionImage }}';
                                                                    this.onerror=function() {
                                                                        this.src='https://via.placeholder.com/200x150/2575fc/white?text=Gambar+{{ $option }}';
                                                                        console.error('Semua alternatif gambar opsi gagal');
                                                                    }">
                                                            <small class="text-muted d-block mt-1">Gambar Opsi {{ $option }}</small>
                                                        </div>
                                                    @else
                                                        <div class="text-muted small mt-2">
                                                            <i class="bi bi-image me-1"></i>Tidak ada gambar
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
                                <h4 class="mt-3">Soal tidak ditemukan</h4>
                                <a href="{{ route('user.quizz') }}" class="btn btn-primary mt-3">
                                    Kembali ke Level
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Navigasi -->
                    <div class="card-footer bg-transparent border-0 pt-3">
                        <div class="navigation-buttons d-flex justify-content-between">
                            @if($currentQuestionIndex > 0)
                                <button class="btn btn-outline-primary" 
                                        wire:click="previousQuestion">
                                    <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                                </button>
                            @else
                                <div></div>
                            @endif
                            
                            @if($currentQuestionIndex < $level->questions->count() - 1)
                                <button class="btn btn-primary" 
                                        wire:click="nextQuestion"
                                        {{ $userAnswers[$currentQuestionIndex] ? '' : 'disabled' }}>
                                    Selanjutnya<i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            @else
                                <button class="btn btn-success" 
                                        wire:click="calculateScore"
                                        {{ $userAnswers[$currentQuestionIndex] ? '' : 'disabled' }}>
                                    Selesaikan Quiz<i class="bi bi-check-lg ms-2"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Style -->
    <style>
    .quiz-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px 0;
        justify-content: center;
    }

    .quiz-card, .result-card {
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        width: 100%;
    }

    .progress-container {
        width: 100%;
        background: #f1f1f1;
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #3498db, #9b59b6);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .card-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
    }

    .section-title {
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .card-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .quiz-mini-card {
        width: 80px;
        height: 100px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #3498db;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 2px solid transparent;
        font-weight: bold;
    }

    .quiz-mini-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .quiz-mini-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3498db, #9b59b6);
    }

    .quiz-mini-card.empty {
        background: #f8f9fa;
        color: transparent;
        border: 2px dashed #bdc3c7;
    }

    .quiz-mini-card.empty::before {
        background: #bdc3c7;
    }

    .question-text {
        font-size: 1.4rem;
        color: #2c3e50;
        line-height: 1.6;
        font-weight: 500;
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 15px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .question-img, .option-img {
        max-height: 300px;
        max-width: 100%;
        border: 3px solid #6a11cb;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        object-fit: contain;
    }

    .option-img {
        max-height: 120px;
        border: 2px solid #2575fc;
    }

    .option-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #dee2e6;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        background: #e9ecef;
    }

    .option-card.selected {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        color: white;
        border-color: #6a11cb;
        box-shadow: 0 8px 20px rgba(106, 17, 203, 0.3);
    }

    .option-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        justify-content: center;
        width: 100%;
    }

    .option-letter {
        background: #6a11cb;
        color: white;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .option-card.selected .option-letter {
        background: white;
        color: #6a11cb;
    }

    .option-text {
        font-weight: 500;
        flex-grow: 1;
        text-align: left;
    }

    .image-debug {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 8px;
        margin: 10px 0;
        font-size: 0.8rem;
        color: #856404;
    }

    .navigation-buttons .btn {
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .timer {
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .quiz-mini-card {
            width: 60px;
            height: 80px;
            font-size: 1.5rem;
        }
        
        .question-text {
            font-size: 1.2rem;
            padding: 15px;
        }
        
        .option-card {
            padding: 15px;
        }
        
        .card-section {
            padding: 15px;
        }
        
        .option-header {
            flex-direction: column;
        }
        
        .option-letter {
            margin-right: 0;
            margin-bottom: 10px;
        }
        
        .option-text {
            text-align: center;
        }
    }
    </style>

    <!-- Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Timer countdown
        let timeLeft = {{ $timeLeft }};
        const timerElement = document.getElementById('timeLeft');
        
        const timer = setInterval(function() {
            timeLeft--;
            if (timerElement) {
                timerElement.textContent = timeLeft;
                
                if (timeLeft <= 10) {
                    timerElement.parentElement.style.background = 'linear-gradient(135deg, #ff6b6b, #ff8e53)';
                    timerElement.parentElement.style.color = 'white';
                }
            }
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                @this.calculateScore();
            }
        }, 1000);

        // Animasi untuk kartu
        const cards = document.querySelectorAll('.quiz-mini-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Debug gambar di console
        console.log('=== DEBUG GAMBAR QUIZ ===');
        const questionImages = document.querySelectorAll('.question-img');
        questionImages.forEach((img, index) => {
            console.log(`Gambar Soal ${index + 1}:`, {
                src: img.src,
                naturalWidth: img.naturalWidth,
                naturalHeight: img.naturalHeight,
                complete: img.complete
            });
        });

        const optionImages = document.querySelectorAll('.option-img');
        optionImages.forEach((img, index) => {
            console.log(`Gambar Opsi ${index + 1}:`, {
                src: img.src,
                naturalWidth: img.naturalWidth,
                naturalHeight: img.naturalHeight,
                complete: img.complete
            });
        });
    });
    </script>
</div>