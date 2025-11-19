<div class="p-4">
    <!-- Header dengan Glow Effect -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center p-4 bg-dark rounded-3 shadow-lg" 
                 style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div>
                    <h1 class="h2 mb-0 text-white fw-bold" style="text-shadow: 0 0 20px rgba(255,255,255,0.5);">
                        <i class="bi bi-stars me-2"></i>Quiz Management
                    </h1>
                    <p class="text-light mb-0 opacity-75">Manage your quiz levels with style</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.quiz.manager') }}" 
                       class="btn btn-light btn-lg fw-bold px-4"
                       style="background: rgba(255,255,255,0.9); border: none; color: #6a11cb; box-shadow: 0 0 20px rgba(106, 17, 203, 0.5);">
                        <i class="bi bi-plus-circle me-2"></i> New Level
                    </a>
                    <a href="{{ route('user.quizz') }}" 
                       class="btn btn-outline-light btn-lg fw-bold px-4"
                       style="border: 2px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);"
                       target="_blank">
                        <i class="bi bi-play-circle me-2"></i> Play as User
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow" 
             style="background: linear-gradient(135deg, #00b09b, #96c93d); color: white;" 
             role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Cek jika TIDAK ADA level sama sekali -->
    @if($levels->isEmpty())
        <!-- Tampilan Kosong Aesthetic -->
        <div class="text-center text-white p-5 rounded-3 shadow" 
             style="background: linear-gradient(135deg, rgba(106, 17, 203, 0.8), rgba(37, 117, 252, 0.8)); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
            <div class="mb-4">
                <i class="bi bi-stars display-1 text-warning" style="text-shadow: 0 0 30px gold;"></i>
            </div>
            <h3 class="fw-bold mb-3" style="text-shadow: 0 0 10px rgba(255,255,255,0.5);">No Levels Yet</h3>
            <p class="mb-4 opacity-75">Create your first magical quiz level to begin the journey</p>
            <a href="{{ route('admin.quiz.manager') }}" 
               class="btn btn-light btn-lg fw-bold px-5 py-3"
               style="background: rgba(255,255,255,0.9); border: none; color: #6a11cb; box-shadow: 0 0 30px rgba(255,255,255,0.5);">
                <i class="bi bi-magic me-2"></i> Create First Level
            </a>
        </div>
    @else
        <!-- Tabel Daftar Level dengan Design Aesthetic -->
        <div class="card mb-4 border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="card-header py-3 text-white" 
                 style="background: linear-gradient(135deg, #8A2BE2, #9370DB); border: none;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-collection-play me-2"></i>Level Collection
                        <span class="badge bg-light text-purple ms-2">{{ $levels->count() }}</span>
                    </h5>
                    <div class="d-flex align-items-center">
                        <span class="me-3 opacity-75">Total Levels</span>
                        <span class="badge bg-light text-dark fw-bold fs-6 px-3 py-2">{{ $levels->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body" style="background: rgba(248, 249, 250, 0.5);">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #6A5ACD, #836FFF);">
                            <tr class="text-white">
                                <th class="border-0 ps-4" style="width: 60px;">#</th>
                                <th class="border-0">Level Name</th>
                                <th class="border-0 text-center" style="width: 80px;">Icon</th>
                                <th class="border-0 text-center" style="width: 120px;">Time</th>
                                <th class="border-0 text-center" style="width: 120px;">Questions</th>
                                <th class="border-0 text-center" style="width: 100px;">Order</th>
                                <th class="border-0 text-center" style="width: 200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($levels as $level)
                                <tr class="border-bottom" style="background: rgba(255,255,255,0.8);">
                                    <td class="ps-4 fw-bold text-purple">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong class="text-dark">{{ $level->name }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($level->button_image)
                                            <img src="{{ asset('storage/' . $level->button_image) }}" 
                                                 class="rounded-circle shadow"
                                                 style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #8A2BE2;"
                                                 alt="{{ $level->name }}">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow"
                                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #8A2BE2, #9370DB);">
                                                <i class="bi bi-controller text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge fw-bold py-2 px-3 rounded-pill" 
                                              style="background: linear-gradient(135deg, #FF6B6B, #FF8E53); color: white;">
                                            {{ $level->time_limit }}s
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge fw-bold py-2 px-3 rounded-pill 
                                              {{ $level->questions_count > 0 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $level->questions_count }} Q
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-dark fw-bold py-2 px-3 rounded-pill">{{ $level->order }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-success fw-bold px-3 rounded-start" 
                                                    wire:click="playLevel({{ $level->id }})"
                                                    title="Play this level"
                                                    @if($level->questions_count == 0) disabled @endif
                                                    style="border: none;">
                                                <i class="bi bi-play-fill me-1"></i> PLAY
                                            </button>
                                            <button class="btn btn-outline-warning fw-bold px-3"
                                                    title="Edit Level"
                                                    style="border-color: #FFD700;">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger fw-bold px-3 rounded-end" 
                                                    wire:click="deleteLevel({{ $level->id }})"
                                                    wire:confirm="Are you sure you want to delete '{{ $level->name }}'?"
                                                    title="Delete Level"
                                                    style="border-color: #DC3545;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        @if($level->questions_count == 0)
                                            <small class="text-danger d-block mt-1 fw-bold">
                                                <i class="bi bi-exclamation-triangle me-1"></i>No questions yet
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Preview Level dengan Design Gaming Aesthetic -->
        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="card-header py-3 text-white" 
                 style="background: linear-gradient(135deg, #9D50BB, #6A48D7); border: none;">
                <h5 class="card-title mb-0 fw-bold text-center">
                    <i class="bi bi-joystick me-2"></i>Level Selector - Click to Play!
                </h5>
            </div>
            <div class="card-body text-center" 
                 style="background: linear-gradient(135deg, rgba(157, 80, 187, 0.1), rgba(106, 72, 215, 0.1));">
                <p class="text-muted mb-4">
                    <i class="bi bi-mouse me-1"></i>Click on any level to start your adventure
                </p>
                
                <div class="level-container position-relative mx-auto rounded-4 p-5 shadow-inner" 
                     style="max-width: 450px; min-height: 500px; 
                            background: radial-gradient(circle at center, rgba(157, 80, 187, 0.3), rgba(106, 72, 215, 0.1));
                            border: 2px solid rgba(255,255,255,0.2);
                            backdrop-filter: blur(10px);">
                    
                    <!-- Background Stars/Pattern -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25">
                        <div class="position-absolute" style="top: 20%; left: 30%; width: 4px; height: 4px; background: white; border-radius: 50%;"></div>
                        <div class="position-absolute" style="top: 60%; left: 70%; width: 3px; height: 3px; background: white; border-radius: 50%;"></div>
                        <div class="position-absolute" style="top: 40%; left: 20%; width: 2px; height: 2px; background: white; border-radius: 50%;"></div>
                    </div>

                    @foreach($levels as $index => $level)
                        @php
                            $positions = [
                                'top: 80px; left: 50%; transform: translateX(-50%);',
                                'top: 160px; left: 80px;',
                                'top: 160px; right: 80px;',
                                'top: 240px; left: 120px;',
                                'top: 240px; right: 120px;',
                                'top: 320px; left: 50%; transform: translateX(-50%);',
                            ];
                            $positionStyle = $positions[$index % 6] ?? 'top: 80px; left: 50%; transform: translateX(-50%);';
                            $isDisabled = $level->questions_count == 0;
                            $glowColor = $isDisabled ? 'rgba(108, 117, 125, 0.5)' : 'rgba(157, 80, 187, 0.8)';
                        @endphp
                        
                        <button class="level-btn position-absolute border-0 rounded-circle shadow-lg"
                                style="width: 70px; height: 70px; {{ $positionStyle }} 
                                       background: {{ $isDisabled ? '#6c757d' : 'linear-gradient(135deg, #9D50BB, #6A48D7)' }};
                                       cursor: {{ $isDisabled ? 'not-allowed' : 'pointer' }};
                                       transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                                       box-shadow: 0 0 0 {{ $isDisabled ? '0px' : '8px' }} {{ $glowColor }};
                                       z-index: 1;"
                                @if(!$isDisabled)
                                    wire:click="playLevel({{ $level->id }})"
                                    onmouseover="this.style.transform='translateX(-50%) scale(1.15)'; this.style.boxShadow='0 0 0 12px {{ $glowColor }}, 0 8px 25px rgba(157, 80, 187, 0.6)'"
                                    onmouseout="this.style.transform='translateX(-50%) scale(1)'; this.style.boxShadow='0 0 0 8px {{ $glowColor }}'"
                                @endif
                                title="{{ $isDisabled ? 'Level not ready' : 'Play '.$level->name }}">
                            
                            @if($level->button_image)
                                <img src="{{ asset('storage/' . $level->button_image) }}" 
                                     class="w-100 h-100 rounded-circle"
                                     style="object-fit: cover; opacity: {{ $isDisabled ? '0.5' : '1' }};"
                                     alt="Level {{ $level->name }}">
                            @else
                                <div class="w-100 h-100 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="font-size: 1.2rem; background: {{ $isDisabled ? '#6c757d' : 'linear-gradient(135deg, #9D50BB, #6A48D7)' }};
                                            opacity: {{ $isDisabled ? '0.7' : '1' }};">
                                    {{ $index + 1 }}
                                    @if($isDisabled)
                                        <div class="position-absolute top-0 start-100 translate-middle">
                                            <span class="badge bg-danger rounded-circle" style="font-size: 0.6rem;">!</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Style dan Script HARUS di DALAM root element -->
    <style>
    .level-container {
        position: relative;
        background: 
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3), transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3), transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2), transparent 50%);
    }

    .level-btn {
        animation: float 3s ease-in-out infinite;
        animation-delay: calc(var(--i, 0) * 0.2s);
    }

    .level-btn:nth-child(1) { --i: 1; }
    .level-btn:nth-child(2) { --i: 2; }
    .level-btn:nth-child(3) { --i: 3; }
    .level-btn:nth-child(4) { --i: 4; }
    .level-btn:nth-child(5) { --i: 5; }
    .level-btn:nth-child(6) { --i: 6; }

    @keyframes float {
        0%, 100% { transform: translateX(-50%) translateY(0px); }
        50% { transform: translateX(-50%) translateY(-10px); }
    }

    .card {
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .shadow-inner {
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.scrollTo(0, 0);
        console.log('✨ Admin Quiz Management loaded - Levels count: {{ $levels->count() }}');
        
        // Add sparkle effect on hover
        const levelButtons = document.querySelectorAll('.level-btn:not(.level-disabled)');
        levelButtons.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.animation = 'pulse 0.6s ease-in-out';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.animation = 'float 3s ease-in-out infinite';
            });
        });
    });
    </script>
</div>