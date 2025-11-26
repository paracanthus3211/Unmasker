<div class="container-fluid py-4 quiz-container">
    @if($showResult)
        <!-- Tampilan Hasil Quiz untuk Admin -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 result-card">
                    <div class="card-header text-white text-center py-4" 
                         style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                        <h2 class="mb-0 fw-bold">Quiz Preview Selesai! 🎉</h2>
                    </div>
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-eye-fill display-1 text-info"></i>
                        </div>
                        <h4 class="text-dark mb-3">Level: <span class="text-primary">{{ $level->name }}</span></h4>
                        <div class="score-display my-4 p-4 rounded-3" 
                             style="background: linear-gradient(135deg, #00b09b, #96c93d);">
                            <h1 class="display-3 fw-bold text-white">{{ $score }}/{{ $level->questions->count() }}</h1>
                            <p class="text-white mb-0">Skor Preview</p>
                        </div>
                        <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-4">
                            <button wire:click="restartQuiz" 
                                    class="btn btn-primary btn-lg px-5 fw-bold">
                                <i class="bi bi-arrow-repeat me-2"></i> Preview Lagi
                            </button>
                            <a href="{{ route('admin.quizz') }}" 
                               class="btn btn-outline-dark btn-lg px-5 fw-bold">
                                <i class="bi bi-house me-2"></i> Kembali ke Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Tampilan Quiz Preview untuk Admin -->
        <div class="row justify-content-center">
            <div class="">
                <div class="card shadow-lg border-0 quiz-card mx-auto">
                    <!-- Header Quiz -->
                    <div class="card-header text-white py-4" 
                         style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-1 fw-bold">{{ $level->name }} (Preview)</h4>
                                <small class="opacity-75">Soal {{ $currentQuestionIndex + 1 }} dari {{ $level->questions->count() }}</small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-person-gear me-2"></i>Admin Preview
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Konten quiz sama seperti user -->
                    <div class="card-body">
                        <!-- Progress Bar -->
                        <div class="progress-container mb-4">
                            <div class="progress-bar" 
                                 style="width: {{ (($currentQuestionIndex + 1) / $level->questions->count()) * 100 }}%">
                            </div>
                        </div>

                        <!-- Pertanyaan -->
                        @if(isset($level->questions[$currentQuestionIndex]))
                            @php $question = $level->questions[$currentQuestionIndex]; @endphp
                            <div class="question-container">
                                <h3 class="question-text mb-4 text-center">
                                    {{ $question->question_text }}
                                </h3>

                                @if($question->question_image)
                                    <div class="question-image mb-4 text-center">
                                        <img src="{{ asset('storage/' . $question->question_image) }}" 
                                             class="img-fluid rounded-3 question-img"
                                             alt="Gambar Soal">
                                    </div>
                                @endif

                                <!-- Opsi Jawaban -->
                                <div class="options-container">
                                    <div class="row g-3 justify-content-center">
                                        @foreach(['A', 'B', 'C', 'D'] as $option)
                                            @php
                                                $optionText = $question->{'option_' . strtolower($option)};
                                                $optionImage = $question->{'option_' . strtolower($option) . '_image'};
                                                $isSelected = $userAnswers[$currentQuestionIndex] === $option;
                                                $isCorrect = $question->correct_answer === $option;
                                            @endphp
                                            <div class="col-md-6">
                                                <div class="option-card {{ $isSelected ? 'selected' : '' }} {{ $isCorrect ? 'correct-answer' : '' }} text-center"
                                                     wire:click="answerQuestion('{{ $option }}')">
                                                    <div class="option-header justify-content-center">
                                                        <span class="option-letter">{{ $option }}</span>
                                                        <div class="option-text">{{ $optionText }}</div>
                                                        @if($isCorrect)
                                                            <span class="badge bg-success ms-2">
                                                                <i class="bi bi-check-lg"></i> Benar
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($optionImage)
                                                        <div class="option-image mt-2 text-center">
                                                            <img src="{{ asset('storage/' . $optionImage) }}" 
                                                                 class="img-thumbnail option-img"
                                                                 alt="Gambar Opsi {{ $option }}">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
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
                                    Selesaikan Preview<i class="bi bi-check-lg ms-2"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
    .correct-answer {
        border: 2px solid #28a745 !important;
        background: rgba(40, 167, 69, 0.1) !important;
    }
    
    /* Style lainnya sama seperti user quiz play */

    .quiz-card, .result-card {
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        width: 100%;
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
    </style>
</div>