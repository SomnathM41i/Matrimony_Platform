@extends('user.layouts.app')

@section('content')

<section class="register-section">
  <div class="register-card">

    <div class="register-header">
      <h2>Create Your Free Profile 🙏</h2>
      <p>Complete your profile in simple steps.</p>
    </div>

    <div class="register-body">

      {{-- 🔴 Top Errors --}}
      @if ($errors->any())
        <div style="background:#ffe6e6;padding:12px;border-radius:8px;margin-bottom:20px;">
          <ul style="margin:0;padding-left:18px;color:#b30000;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('user.register.post') }}">
        @csrf

        {{-- ================= STEP 1 ================= --}}
        <div class="form-step active">
          <h3>Personal Details</h3>

          <div class="form-row">
            <div class="form-group">
              <label>First Name *</label>
              <input type="text" name="first_name"
                     pattern="[A-Za-z\s\-]+"
                     title="Only letters, spaces and hyphens allowed"
                     class="form-control @error('first_name') is-invalid @enderror"
                     value="{{ old('first_name') }}">
              @error('first_name') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
              <label>Last Name *</label>
              <input type="text" name="last_name"
                     pattern="[A-Za-z\s\-]+"
                     class="form-control @error('last_name') is-invalid @enderror"
                     value="{{ old('last_name') }}">
              @error('last_name') <small class="error">{{ $message }}</small> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Gender *</label>
              <select name="gender"
                      class="form-control @error('gender') is-invalid @enderror">
                <option value="">Select</option>
                <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
              </select>
              @error('gender') <small class="error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
              <label>Date of Birth *</label>
              <input type="date" name="date_of_birth"
                     max="{{ now()->subYears(18)->format('Y-m-d') }}"
                     min="{{ now()->subYears(80)->format('Y-m-d') }}"
                     class="form-control @error('date_of_birth') is-invalid @enderror"
                     value="{{ old('date_of_birth') }}">
              @error('date_of_birth') <small class="error">{{ $message }}</small> @enderror
            </div>
          </div>

          <button type="button" class="btn btn-primary" data-next>Next</button>
        </div>

        {{-- ================= STEP 2 ================= --}}
        <div class="form-step">
          <h3>Contact Details</h3>

          <div class="form-group">
            <label>Mobile *</label>
            <input type="tel" name="phone"
                   pattern="[0-9]{10}"
                   placeholder="Enter 10 digit number"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}">
            @error('phone') <small class="error">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email') <small class="error">{{ $message }}</small> @enderror
          </div>

          <div class="step-nav">
            <button type="button" class="btn btn-outline" data-prev>Back</button>
            <button type="button" class="btn btn-primary" data-next>Next</button>
          </div>
        </div>

        {{-- ================= STEP 3 ================= --}}
        <div class="form-step">
          <h3>Account Setup</h3>

          <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password"
                   pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4,}"
                   title="Password must contain letters and numbers"
                   class="form-control @error('password') is-invalid @enderror">
            @error('password') <small class="error">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label>Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>

          <div class="form-group">
            <label>
              <input type="checkbox" name="terms" value="1"
                     {{ old('terms') ? 'checked' : '' }}>
              Accept Terms & Conditions
            </label>
            @error('terms') <small class="error">{{ $message }}</small> @enderror
          </div>

          <div class="step-nav">
            <button type="button" class="btn btn-outline" data-prev>Back</button>
            <button type="submit" class="btn btn-primary">🎉 Register</button>
          </div>
        </div>

      </form>

    </div>

    <div style="text-align:center;padding:20px;">
      Already have an account?
      <a href="{{ route('user.login') }}">Login here</a>
    </div>

  </div>
</section>

<style>
.is-invalid {
  border-color: red !important;
}
.error {
  color: red;
  font-size: 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if ($errors->any())
        let step = 0;

        if (@json($errors->has('phone') || $errors->has('email'))) {
            step = 1;
        }

        if (@json($errors->has('password') || $errors->has('terms'))) {
            step = 2;
        }

        document.querySelectorAll('.form-step').forEach((el, i) => {
            el.classList.toggle('active', i === step);
        });
    @endif
});
</script>

@endsection