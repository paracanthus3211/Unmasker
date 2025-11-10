<div class="card mb-3">
  <div class="card-body">
    <div class="pt-4 pb-2">
      <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
      <p class="text-center small">Enter your email & password to login</p>
    </div>

    <form wire:submit.prevent="login" class="row g-3">

      {{-- Email --}}
      <div class="col-12">
        <label for="yourUsername" class="form-label">Email</label>
        <div class="input-group has-validation">
          <span class="input-group-text" id="inputGroupPrepend">@</span>
          <input
            wire:model="email"
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="yourUsername"
            aria-describedby="email-error"
            autocomplete="email"
            placeholder="name@example.com"
          >
        </div>
        @error('email')
          <div id="email-error" class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- Password --}}
      <div class="col-12">
        <label for="yourPassword" class="form-label">Password</label>
        <input
          wire:model="password"
          type="password"
          class="form-control @error('password') is-invalid @enderror"
          id="yourPassword"
          aria-describedby="password-error"
          autocomplete="current-password"
        >
        @error('password')
          <div id="password-error" class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      {{-- Remember Me --}}
      <div class="col-12">
        <div class="form-check">
          <input
            wire:model="remember"
            class="form-check-input"
            type="checkbox"
            id="rememberMe"
            value="true"
          >
          <label class="form-check-label" for="rememberMe">Remember me</label>
        </div>
      </div>

      {{-- Submit Button --}}
      <div class="col-12">
        <button
          type="submit"
          class="btn btn-primary w-100"
          wire:loading.attr="disabled"
          wire:target="login"
        >
          <span wire:loading.remove wire:target="login">Login</span>
          <span wire:loading wire:target="login">
            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
            <span class="visually-hidden">Loading...</span>
          </span>
        </button>
      </div>

      {{-- Register Link --}}
      <div class="col-12 text-center">
        <p class="small mb-0">
          Don't have an account? <a href="{{ route('register') }}">Create an account</a>
        </p>
      </div>
    </form>
  </div>
</div>