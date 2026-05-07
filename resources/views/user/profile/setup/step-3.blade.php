@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 3: Horoscope')

@section('content')

@include('user.profile.setup._progress', ['step' => 3])

@php
  $birthTimeValue = old('birth_time', $profile->birth_time ?? '');
  if ($birthTimeValue && preg_match('/^\d{2}:\d{2}:\d{2}$/', $birthTimeValue)) {
      $birthTimeValue = substr($birthTimeValue, 0, 5);
  }
@endphp

<section class="setup-section">
  <div class="container">
    <div class="setup-card">

      @if (session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
      @endif
      @if (session('info'))
        <div class="alert alert-info">ℹ️ {{ session('info') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-error">
          <strong>Please fix the following:</strong>
          <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="setup-card-header">
        <div class="setup-step-icon">🌙</div>
        <div>
          <h2>Horoscope Details</h2>
          <p>Share your astrological information — or skip and fill it later</p>
        </div>
      </div>

      {{-- Skip notice --}}
      <div class="skip-notice">
        <span class="skip-notice-icon">💡</span>
        <div>
          <strong>This step is optional.</strong>
          You can skip horoscope details now and update them anytime from your profile settings.
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 3) }}">
        @csrf

        <div class="form-section">
          <h3 class="form-section-title">Astrological Details</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Rashi (Moon Sign)</label>
              <select name="rashi_id" class="form-control @error('rashi_id') is-invalid @enderror">
                <option value="">Select Rashi</option>
                @foreach ($rashis as $rashi)
                  <option value="{{ $rashi->id }}"
                    {{ old('rashi_id', $profile->rashi_id ?? '') == $rashi->id ? 'selected' : '' }}>
                    {{ $rashi->name }}
                    @if ($rashi->sanskrit_name && $rashi->sanskrit_name !== $rashi->name)
                      ({{ $rashi->sanskrit_name }})
                    @endif
                  </option>
                @endforeach
              </select>
              @error('rashi_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Nakshatra (Birth Star)</label>
              <select name="nakshatra_id" class="form-control @error('nakshatra_id') is-invalid @enderror">
                <option value="">Select Nakshatra</option>
                @foreach ($nakshatras as $nakshatra)
                  <option value="{{ $nakshatra->id }}"
                    {{ old('nakshatra_id', $profile->nakshatra_id ?? '') == $nakshatra->id ? 'selected' : '' }}>
                    {{ $nakshatra->name }}
                  </option>
                @endforeach
              </select>
              @error('nakshatra_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group" style="max-width:320px;">
            <label class="form-label">Manglik Status</label>
            <select name="manglik_status" class="form-control @error('manglik_status') is-invalid @enderror">
              <option value="">Select</option>
              @foreach ($manglik_options as $opt)
                <option value="{{ $opt }}"
                  {{ old('manglik_status', $profile->manglik_status ?? '') == $opt ? 'selected' : '' }}>
                  {{ ucwords(str_replace('_', ' ', $opt)) }}
                </option>
              @endforeach
            </select>
            @error('manglik_status') <span class="field-error">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="form-section">
          <h3 class="form-section-title">Birth Details</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Birth Time</label>
              <input type="time" name="birth_time"
                class="form-control @error('birth_time') is-invalid @enderror"
                value="{{ $birthTimeValue }}"
                step="60">
              @error('birth_time') <span class="field-error">{{ $message }}</span> @enderror
              <span class="field-hint">Used to calculate accurate horoscope</span>
            </div>

            <div class="form-group">
              <label class="form-label">Birth Place / City</label>
              <input type="text" name="birth_place"
                class="form-control @error('birth_place') is-invalid @enderror"
                value="{{ old('birth_place', $profile->birth_place ?? '') }}"
                placeholder="e.g. Nagpur, Maharashtra"
                maxlength="100">
              @error('birth_place') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>
        </div>

        <div class="setup-actions">
          <a href="{{ route('user.profile.setup.show', 2) }}" class="btn btn-outline">← Back</a>
          <div style="display:flex;gap:12px;align-items:center;">
            <button type="submit"
              formaction="{{ route('user.profile.setup.skip', 3) }}"
              formmethod="POST"
              class="btn btn-outline"
              style="color:var(--text-muted);border-color:var(--border);">
              Skip for now
            </button>
            <button type="submit" class="btn btn-primary btn-lg">Save &amp; Continue →</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

@include('user.profile.setup._setup_styles')
@endsection
