<div class="card mb-3">
  <div class="card-body">

    <div class="pt-4 pb-2">
      <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
      <p class="text-center small">Enter your details to create an account</p>
    </div>

    <form wire:submit.prevent="register" class="row g-3 needs-validation" novalidate>

      <div class="col-12">
        <label for="yourName" class="form-label">Your Name</label>
        <input wire:model="name" type="text" class="form-control" id="yourName" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <label for="yourEmail" class="form-label">Your Email</label>
        <input wire:model="email" type="email" class="form-control" id="yourEmail" required>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <label for="yourPassword" class="form-label">Password</label>
        <input wire:model="password" type="password" class="form-control" id="yourPassword" required>
        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <button class="btn btn-primary w-100" type="submit">Create Account</button>
      </div>

      <div class="col-12">
        <p class="small mb-0">Already have an account? <a href="/login">Login here</a></p>
      </div>
    </form>

  </div>
</div>
