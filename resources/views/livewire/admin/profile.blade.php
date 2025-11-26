<div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar-container mb-3">
                        @php
                            $userAvatar = $availableAvatars->firstWhere('id', $selectedAvatar);
                            $avatarUrl = $userAvatar ? 
                                (str_starts_with($userAvatar->image_url, 'http') ? 
                                    $userAvatar->image_url : 
                                    asset('storage/' . $userAvatar->image_url)) : 
                                '/assets/img/default-avatar.png';
                        @endphp
                        <img id="avatarPreview" src="{{ $avatarUrl }}" alt="Profile Avatar" class="avatar-preview">
                    </div>
                    <h3>{{ Auth::user()->name }}</h3>
                    <p class="mb-0 text-muted">@{{ Auth::user()->username }}</p>
                    <small class="text-muted">Administrator</small>
                </div>
                
                <div class="profile-body">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h5 class="section-title"><i class="bi bi-person-circle"></i>Personal Information</h5>
                        
                        <form wire:submit.prevent="updateProfile">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input wire:model="name" type="text" class="form-control" id="name" required>
                                    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input wire:model="username" wire:blur="checkUsername" type="text" class="form-control" id="username" required>
                                    </div>
                                    @error('username')<div class="text-danger small">{{ $message }}</div>@enderror
                                    <small class="text-muted">
                                        <a href="#" wire:click.prevent="generateUsername" class="text-primary">
                                            <i class="bi bi-magic me-1"></i>Generate username
                                        </a>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="birth_date" class="form-label">Date of Birth</label>
                                    <input wire:model="birth_date" type="date" class="form-control" id="birth_date" max="{{ date('Y-m-d') }}">
                                    @error('birth_date')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            <!-- Avatar Selection -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0">Select Your Avatar</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadAvatarModal">
                                        <i class="bi bi-plus-circle me-1"></i>Upload New Default Avatar
                                    </button>
                                </div>
                                
                                <div class="avatar-options">
                                    @foreach($availableAvatars as $avatar)
                                        @php
                                            $avatarImageUrl = str_starts_with($avatar->image_url, 'http') ? 
                                                $avatar->image_url : 
                                                asset('storage/' . $avatar->image_url);
                                        @endphp
                                        <img src="{{ $avatarImageUrl }}" 
                                             class="avatar-option {{ $selectedAvatar == $avatar->id ? 'selected' : '' }}"
                                             wire:click="selectAvatar({{ $avatar->id }})"
                                             alt="Avatar Option"
                                             title="{{ $avatar->name }}"
                                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border-radius: 50%; border: 3px solid {{ $selectedAvatar == $avatar->id ? '#4154f1' : 'transparent' }};">
                                    @endforeach
                                </div>
                                
                                @if($availableAvatars->isEmpty())
                                <div class="text-muted small mt-2">
                                    <i class="bi bi-info-circle me-1"></i>No avatars available. Upload your first avatar!
                                </div>
                                @endif
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Profile
                            </button>
                        </form>
                    </div>

                    <!-- Avatar Management Section -->
                    <div class="form-section">
                        <h5 class="section-title"><i class="bi bi-images"></i>Default Avatar Management</h5>
                        
                        <div class="admin-avatar-grid">
                            @foreach($avatars->where('created_by', null) as $avatar)
                                @php
                                    $avatarImageUrl = $avatar->image_url;
                                    if (!str_starts_with($avatarImageUrl, 'http')) {
                                        $avatarImageUrl = asset('storage/' . $avatarImageUrl);
                                    }
                                @endphp
                                <div class="admin-avatar-item">
                                    <img src="{{ $avatarImageUrl }}" alt="Avatar" class="admin-avatar-img">
                                    <div class="admin-avatar-actions mt-2">
                                        <button class="btn btn-sm btn-outline-danger" 
                                                wire:click="deleteAvatar({{ $avatar->id }})" 
                                                onclick="return confirm('Are you sure you want to delete this default avatar?')"
                                                title="Delete Avatar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-1">ID: {{ $avatar->id }}</small>
                                    <small class="text-muted d-block">Name: {{ $avatar->name }}</small>
                                    <small class="text-success d-block">Default Avatar</small>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($avatars->where('created_by', null)->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-people display-4 text-muted"></i>
                            <p class="text-muted mt-3">No default avatars available. Upload your first default avatar!</p>
                        </div>
                        @endif
                    </div>

                    <!-- Contact Information Section -->
                    <div class="form-section">
                        <h5 class="section-title"><i class="bi bi-envelope"></i>Contact Information</h5>
                        
                        <form wire:submit.prevent="updateContact">
                            <div class="mb-4">
                                <label class="form-label">Email Address *</label>
                                <div class="d-flex align-items-center mb-2">
                                    <input wire:model="email" type="email" class="form-control me-3" id="email" required>
                                    @if($emailVerified)
                                    <span class="verification-badge bg-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Verified
                                    </span>
                                    @else
                                    <span class="verification-badge bg-warning">
                                        <i class="bi bi-x-circle-fill me-1"></i>Unverified
                                    </span>
                                    @endif
                                </div>
                                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <div class="d-flex align-items-center mb-2">
                                    <input wire:model="phone" type="tel" class="form-control me-3" id="phone" placeholder="+62 XXX XXXX XXXX">
                                    @if($phoneVerified)
                                    <span class="verification-badge bg-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Verified
                                    </span>
                                    @else
                                    <span class="verification-badge bg-warning">
                                        <i class="bi bi-x-circle-fill me-1"></i>Unverified
                                    </span>
                                    @endif
                                </div>
                                @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Contact Info
                            </button>
                        </form>
                    </div>

                    <!-- Security Section -->
                    <div class="form-section">
                        <h5 class="section-title"><i class="bi bi-shield-lock"></i>Security</h5>
                        
                        <form wire:submit.prevent="updatePassword">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input wire:model="current_password" type="password" class="form-control" id="current_password">
                                    @error('current_password')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input wire:model="new_password" type="password" class="form-control" id="new_password">
                                    @error('new_password')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <input wire:model="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-key me-2"></i>Change Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Upload Avatar Modal -->
            <div class="modal fade" id="uploadAvatarModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload New Default Avatar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="uploadAvatar">
                                <div class="mb-3">
                                    <label for="avatarUpload" class="form-label">Choose Avatar Image</label>
                                    <input type="file" class="form-control" id="avatarUpload" wire:model="newAvatar" accept="image/*">
                                    @error('newAvatar')<div class="text-danger small">{{ $message }}</div>@enderror
                                    <div class="form-text">Max file size: 2MB. Supported formats: JPG, PNG, GIF, WEBP</div>
                                </div>
                                
                                @if($newAvatar)
                                <div class="mb-3">
                                    <label class="form-label">Preview</label>
                                    <div class="text-center">
                                        <img src="{{ $newAvatar->temporaryUrl() }}" class="avatar-preview" alt="Preview" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
                                    </div>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary" {{ !$newAvatar ? 'disabled' : '' }}>
                                        <i class="bi bi-upload me-2"></i>Upload Default Avatar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-card{background:white;border-radius:15px;box-shadow:0 4px 6px rgba(0,0,0,0.1);overflow:hidden}
.profile-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:2rem;text-align:center}
.avatar-preview{width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid white;box-shadow:0 4px 8px rgba(0,0,0,0.2)}
.profile-body{padding:2rem}
.form-section{margin-bottom:2.5rem;padding-bottom:2rem;border-bottom:1px solid #e9ecef}
.form-section:last-child{border-bottom:none;margin-bottom:0}
.section-title{color:#4154f1;font-weight:600;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem}
.avatar-options{display:flex;flex-wrap:wrap;gap:15px;margin-top:10px}
.avatar-option{transition:transform 0.2s ease}
.avatar-option:hover{transform:scale(1.05)}
.avatar-option.selected{border-color:#4154f1!important;box-shadow:0 0 0 3px rgba(65,84,241,0.3)}
.verification-badge{padding:0.25rem 0.75rem;border-radius:20px;font-size:0.75rem;font-weight:500;color:white}
.btn-primary{background:#4154f1;border-color:#4154f1}
.btn-primary:hover{background:#3242d1;border-color:#3242d1}
.admin-avatar-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:1.5rem;margin-top:1rem}
.admin-avatar-item{text-align:center;padding:1rem;border:1px solid #e9ecef;border-radius:10px;background:#f8f9fa;transition:all 0.3s ease}
.admin-avatar-item:hover{transform:translateY(-5px);box-shadow:0 5px 15px rgba(0,0,0,0.1)}
.admin-avatar-img{width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #4154f1}
.admin-avatar-actions{display:flex;justify-content:center;gap:0.5rem}
</style>