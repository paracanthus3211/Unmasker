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
                $avatarUrl = $userAvatar ? (str_starts_with($userAvatar->image_url, 'http') ? $userAvatar->image_url : asset('storage/' . $userAvatar->image_url)) : '/assets/img/default-avatar.png';
              @endphp
              <img id="avatarPreview" src="{{ $avatarUrl }}" 
                   alt="Profile Avatar" class="avatar-preview">
            </div>
            <h3>{{ Auth::user()->name }}</h3>
            <p class="mb-0">Member</p>
          </div>
          
          <div class="profile-body">
            <!-- Personal Information Section -->
            <div class="form-section">
              <h5 class="section-title">
                <i class="bi bi-person-circle"></i>
                Personal Information
              </h5>
              
              <form wire:submit.prevent="updateProfile">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input wire:model="name" type="text" class="form-control" id="name" required>
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input wire:model="username" type="text" class="form-control" id="username" required>
                    @error('username') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="birth_date" class="form-label">Date of Birth</label>
                    <input wire:model="birth_date" type="date" class="form-control" id="birth_date">
                    @error('birth_date') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>
                </div>
                
                <!-- Avatar Selection - User bisa pilih, upload, dan delete -->
                <div class="mb-4">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-label mb-0">Select Avatar</label>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadAvatarModal">
                      <i class="bi bi-plus-circle me-1"></i>
                      Upload Custom Avatar
                    </button>
                  </div>
                  
                  <div class="avatar-options">
                    @foreach($availableAvatars as $avatar)
                      @php
                        $avatarImageUrl = str_starts_with($avatar->image_url, 'http') ? $avatar->image_url : asset('storage/' . $avatar->image_url);
                        $isCustom = !is_null($avatar->created_by);
                      @endphp
                      <div class="avatar-option-container position-relative">
                        <img src="{{ $avatarImageUrl }}" 
                             class="avatar-option {{ $selectedAvatar == $avatar->id ? 'selected' : '' }}"
                             wire:click="selectAvatar({{ $avatar->id }})"
                             alt="Avatar Option"
                             title="{{ $avatar->name }} {{ $isCustom ? '(Custom)' : '(Default)' }}">
                        
                        <!-- ✅ Tombol delete untuk avatar custom -->
                        @if($isCustom)
                          <button class="btn btn-sm btn-outline-danger avatar-delete-btn"
                                  wire:click="deleteAvatar({{ $avatar->id }})"
                                  onclick="return confirm('Are you sure you want to delete this custom avatar?')"
                                  title="Delete Custom Avatar">
                            <i class="bi bi-trash"></i>
                          </button>
                        @endif
                      </div>
                    @endforeach
                  </div>
                  
                  @if($availableAvatars->isEmpty())
                    <div class="text-muted small mt-2">
                      <i class="bi bi-info-circle me-1"></i>
                      No avatars available. Upload your first avatar!
                    </div>
                  @endif
                </div>
                
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-circle me-2"></i>
                  Update Profile
                </button>
              </form>
            </div>
            
            <!-- Contact Information Section -->
            <div class="form-section">
              <h5 class="section-title">
                <i class="bi bi-envelope"></i>
                Contact Information
              </h5>
              
              <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="d-flex align-items-center mb-2">
                  <input wire:model="email" type="email" class="form-control me-3" id="email" disabled>
                  @if($emailVerified)
                    <span class="verification-badge">
                      <i class="bi bi-check-circle-fill me-1"></i>
                      Verified
                    </span>
                  @else
                    <span class="verification-badge unverified">
                      <i class="bi bi-x-circle-fill me-1"></i>
                      Unverified
                    </span>
                  @endif
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <div class="d-flex align-items-center mb-2">
                  <input wire:model="phone" type="tel" class="form-control me-3" id="phone" placeholder="+62 XXX XXXX XXXX">
                  @if($phoneVerified)
                    <span class="verification-badge">
                      <i class="bi bi-check-circle-fill me-1"></i>
                      Verified
                    </span>
                  @else
                    <span class="verification-badge unverified">
                      <i class="bi bi-x-circle-fill me-1"></i>
                      Unverified
                    </span>
                  @endif
                </div>
                @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Avatar Modal untuk User -->
    <div class="modal fade" id="uploadAvatarModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Upload Custom Avatar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form wire:submit.prevent="uploadAvatar">
              <div class="mb-3">
                <label for="avatarUpload" class="form-label">Choose Avatar Image</label>
                <input type="file" class="form-control" id="avatarUpload" wire:model="newAvatar" accept="image/*">
                @error('newAvatar') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="form-text">Max file size: 2MB. Supported formats: JPG, PNG, GIF, WEBP</div>
              </div>
              
              @if($newAvatar)
                <div class="mb-3">
                  <label class="form-label">Preview</label>
                  <div class="text-center">
                    <img src="{{ $newAvatar->temporaryUrl() }}" class="avatar-preview" alt="Preview">
                  </div>
                </div>
              @endif
              
              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" {{ !$newAvatar ? 'disabled' : '' }}>
                  <i class="bi bi-upload me-2"></i>
                  Upload & Select Avatar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
  .avatar-option-container {
      position: relative;
      display: inline-block;
      margin: 5px;
  }

  .avatar-delete-btn {
      position: absolute;
      top: -5px;
      right: -5px;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
  }

  .avatar-option {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      cursor: pointer;
      border: 3px solid transparent;
      transition: all 0.3s ease;
  }

  .avatar-option:hover {
      transform: scale(1.1);
      border-color: #4154f1;
  }

  .avatar-option.selected {
      border-color: #4154f1;
      box-shadow: 0 0 0 3px rgba(65, 84, 241, 0.3);
  }
  </style>
</div>