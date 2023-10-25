<x-guest-layout>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <x-authentication-card-logo />
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <x-validation-errors class="mb-4" />
      <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="email" class="form-control" :value="old('email')" name="email" required autofocus autocomplete="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control"  name="password" required autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember_me" name="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <x-button>{{ __('Log in') }}</x-button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center mt-2 mb-3">
        <a href="{{ url('auth/facebook') }}" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="{{ route('auth.google') }}" class="btn btn-block btn-danger">
          <i class="fab fa-google"></i> Sign in using Google
        </a>
      </div>
      <!-- /.social-auth-links -->
    
      @if (Route::has('password.request'))
      <p class="mb-1">
        <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
      </p>
      @endif
      @if (Route::has('register'))
      <p class="mb-0">
        <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
      </p>
      @endif
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

</x-guest-layout>
