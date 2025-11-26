<div class="container-fluid">
  <div class="row">
    <div class="col-lg-8 mx-auto">
      <div class="profile-card">
        <div class="profile-header">
          <div class="avatar-container mb-3">
            <img id="avatarPreview" src="{{ Auth::user()->avatar_url ?? '/assets/img/default-avatar.png' }}" 
                 alt="Profile Avatar" class="avatar-preview">
          </div>
          <h3>{{ Auth::user()->name }}</h3>
          <p class="mb-0">Administrator</p>
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
                  <label for="birth_date" class="form-label">Date of Birth</label>
                  <input wire:model="birth_date" type="date" class="form-control" id="birth_date">
                  @error('birth_date') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Select Avatar</label>
                <div class="avatar-options">
                  @foreach($availableAvatars as $avatar)
                    <img src="{{ $avatar->image_url }}" 
                         class="avatar-option {{ $selectedAvatar == $avatar->id ? 'selected' : '' }}"
                         wire:click="selectAvatar({{ $avatar->id }})"
                         alt="Avatar Option">
                  @endforeach
                </div>
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
              <div class="d-flex align-items-center">
                <input wire:model="email" type="email" class="form-control me-3" id="email">
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
              @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
              
              @if(!$emailVerified)
                <div class="mt-2">
                  <button type="button" class="btn btn-outline-primary btn-sm" id="sendEmailCode" wire:click="sendEmailVerification">
                    <i class="bi bi-send me-1"></i>
                    Send Verification Code
                  </button>
                  
                  @if($showEmailVerification)
                    <div class="mt-3">
                      <label class="form-label">Enter Verification Code</label>
                      <div class="verification-code-input">
                        <input type="text" maxlength="1" class="form-control" wire:model="emailCode.0">
                        <input type="text" maxlength="1" class="form-control" wire:model="emailCode.1">
                        <input type="text" maxlength="1" class="form-control" wire:model="emailCode.2">
                        <input type="text" maxlength="1" class="form-control" wire:model="emailCode.3">
                        <input type="text" maxlength="1" class="form-control" wire:model="emailCode.4">
                        <input type="text" maxlength="1" class="form-control" wire:model="emailCode.5">
                      </div>
                      <button type="button" class="btn btn-verify btn-sm mt-2" wire:click="verifyEmail">
                        <i class="bi bi-shield-check me-1"></i>
                        Verify Email
                      </button>
                    </div>
                  @endif
                </div>
              @endif
            </div>
            
            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <div class="d-flex align-items-center">
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
              
              @if(!$phoneVerified && $phone)
                <div class="mt-2">
                  <button type="button" class="btn btn-outline-primary btn-sm" id="sendSmsCode" wire:click="sendSmsVerification">
                    <i class="bi bi-phone me-1"></i>
                    Send SMS Code
                  </button>
                  
                  @if($showSmsVerification)
                    <div class="mt-3">
                      <label class="form-label">Enter SMS Code</label>
                      <div class="verification-code-input">
                        <input type="text" maxlength="1" class="form-control" wire:model="smsCode.0">
                        <input type="text" maxlength="1" class="form-control" wire:model="smsCode.1">
                        <input type="text" maxlength="1" class="form-control" wire:model="smsCode.2">
                        <input type="text" maxlength="1" class="form-control" wire:model="smsCode.3">
                        <input type="text" maxlength="1" class="form-control" wire:model="smsCode.4">
                        <input type="text" maxlength="1" class="form-control" wire:model="smsCode.5">
                      </div>
                      <button type="button" class="btn btn-verify btn-sm mt-2" wire:click="verifyPhone">
                        <i class="bi bi-shield-check me-1"></i>
                        Verify Phone
                      </button>
                    </div>
                  @endif
                </div>
              @endif
            </div>
            
            <button type="button" class="btn btn-primary" wire:click="updateContact">
              <i class="bi bi-check-circle me-2"></i>
              Update Contact Info
            </button>
          </div>
          
          <!-- Security Section -->
          <div class="form-section">
            <h5 class="section-title">
              <i class="bi bi-shield-lock"></i>
              Security
            </h5>
            
            <form wire:submit.prevent="updatePassword">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="current_password" class="form-label">Current Password</label>
                  <input wire:model="current_password" type="password" class="form-control" id="current_password">
                  @error('current_password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="new_password" class="form-label">New Password</label>
                  <input wire:model="new_password" type="password" class="form-control" id="new_password">
                  @error('new_password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                  <input wire:model="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation">
                </div>
              </div>
              
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-key me-2"></i>
                Change Password
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>