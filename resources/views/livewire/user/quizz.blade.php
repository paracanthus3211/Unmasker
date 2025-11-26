<div class="container-fluid" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px 0;">
    <!-- Header dengan Refresh Button -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div></div> <!-- Spacer -->
                <div class="text-center">
                    <h1 class="h3 text-white">Pilih Level Quiz</h1>
                    <p class="text-center text-light mb-0">Selesaikan level untuk membuka level berikutnya</p>
                </div>
                <button wire:click="$refresh" class="btn btn-sm btn-light">
                    🔄 Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Debug Info (Sementara) -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show mx-auto" style="max-width: 500px;">
                <small>
                    <strong>Debug Info:</strong><br>
                    Total Level: {{ $levels->count() }}<br>
                    Progress: {{ count($userProgress) }} level selesai<br>
                    User ID: {{ Auth::id() }}
                </small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>

    <!-- Level Container -->
    <div class="level-container position-relative mx-auto" style="width: 320px; min-height: 600px;">
        @forelse($levels as $index => $level)
            @php
                $positionClass = 'level-position-' . (($index % 10) + 1);
                $isUnlocked = $this->isLevelUnlocked($level->id, $index);
                $isCompleted = $this->isLevelCompleted($level->id);
                
                // Debug info untuk tooltip
                $debugInfo = "Level {$level->id} (Index: {$index}) - Unlocked: " . ($isUnlocked ? 'Yes' : 'No') . " - Completed: " . ($isCompleted ? 'Yes' : 'No');
            @endphp
            
            <button class="level-btn position-absolute {{ $positionClass }} {{ !$isUnlocked ? 'disabled' : '' }}"
                    style="width: 120px; height: 120px; border: none; border-radius: 50%; color: white; font-weight: bold; box-shadow: 0 8px 16px rgba(0,0,0,0.3); border: 3px solid white;"
                    @if($isUnlocked)
                        onclick="window.location.href='{{ route('user.quiz.play', $level->id) }}'"
                    @else
                        onclick="alert('Selesaikan level {{ $index }} terlebih dahulu!')"
                    @endif
                    title="{{ $debugInfo }}">
                
                @if($level->button_image)
                    <img src="{{ asset('storage/' . $level->button_image) }}" 
                         style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"
                         class="{{ !$isUnlocked ? 'grayscale' : '' }}"
                         alt="Level {{ $index + 1 }}">
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center rounded-circle"
                         style="background: {{ $isCompleted ? '#28a745' : ($isUnlocked ? 'linear-gradient(135deg, #6a11cb, #2575fc)' : '#6c757d') }}; font-size: 1.5rem;">
                        {{ $index + 1 }}
                    </div>
                @endif
                
                <!-- Status Indicator -->
                @if($isCompleted)
                    <span class="position-absolute top-0 start-0 badge bg-success rounded-circle p-2" style="border: 2px solid white;">
                        <i class="bi bi-check"></i>
                    </span>
                @elseif(!$isUnlocked)
                    <span class="position-absolute top-0 start-0 badge bg-secondary rounded-circle p-2" style="border: 2px solid white;">
                        <i class="bi bi-lock"></i>
                    </span>
                @endif
            </button>
        @empty
            <div class="text-center text-white">
                <p>Belum ada level yang tersedia.</p>
            </div>
        @endforelse
    </div>

    <!-- Manual Check Button -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <button onclick="checkProgress()" class="btn btn-warning btn-sm">
                🛠️ Manual Check Progress
            </button>
        </div>
    </div>

    <style>
    .level-position-1 { top: 0%; left: 0; }
    .level-position-2 { top: 15%; right: 0; }
    .level-position-3 { top: 30%; left: 0; }
    .level-position-4 { top: 45%; right: 0; }
    .level-position-5 { top: 60%; left: 0; }
    .level-position-6 { top: 75%; right: 0; }
    .level-position-7 { top: 90%; left: 0; }
    .level-position-8 { top: 105%; right: 0; }
    .level-position-9 { top: 120%; left: 0; }
    .level-position-10 { top: 135%; right: 0; }

    .level-btn {
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
    }

    .level-btn:hover:not(.disabled) {
        transform: scale(1.1);
        box-shadow: 0 12px 24px rgba(0,0,0,0.4) !important;
        background: rgba(255, 255, 255, 0.2);
    }

    .level-btn.disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }

    .grayscale {
        filter: grayscale(100%);
    }

    .level-container {
        background: transparent !important;
        backdrop-filter: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
        border: none !important;
    }
    </style>

    <script>
    function checkProgress() {
        // Refresh Livewire component
        Livewire.dispatch('$refresh');
        
        // Show alert dengan info
        alert('Progress diperbarui! Cek level status sekarang.');
        
        // Optional: Redirect ke halaman yang sama untuk refresh penuh
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
    
    // Auto refresh ketika kembali ke halaman ini
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Quizz page loaded - checking progress...');
    });
    </script>
</div>