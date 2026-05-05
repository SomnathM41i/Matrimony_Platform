@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 1: Basic Info')

@section('content')

@include('user.profile.setup._progress', ['step' => 1])

<section class="setup-section">
  <div class="container">
    <div class="setup-card">

      {{-- Alerts --}}
      @if (session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-error">
          <strong>Please fix the following:</strong>
          <ul>
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="setup-card-header">
        <div class="setup-step-icon">👤</div>
        <div>
          <h2>Basic Information</h2>
          <p>Tell potential matches about yourself</p>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 1) }}">
        @csrf

        {{-- ABOUT ME --}}
        <div class="form-section">
          <h3 class="form-section-title">About Yourself</h3>

          <div class="form-group">
            <label class="form-label">About Me <span class="req">*</span></label>

            <textarea name="about_me" rows="4"
              class="form-control @error('about_me') is-invalid @enderror"
              maxlength="1000"
              placeholder="Write about yourself..."
            >{{ old('about_me', $profile->about_me ?? "Hi, I'm a simple and honest person. I value family, respect, and meaningful relationships. I enjoy spending time with loved ones and am looking for a compatible life partner.") }}</textarea>

            @error('about_me')
              <span class="field-error">{{ $message }}</span>
            @enderror

            <span class="field-hint">Minimum 30 characters</span>
          </div>
        </div>

        {{-- MARITAL + PHYSICAL --}}
        <div class="form-section">
          <h3 class="form-section-title">Marital & Physical Details</h3>

          <div class="form-row">
            {{-- Marital Status --}}
            <div class="form-group">
              <label class="form-label">Marital Status <span class="req">*</span></label>

              <select name="marital_status" class="form-control @error('marital_status') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($marital_statuses as $status)
                  <option value="{{ $status }}"
                    {{ old('marital_status', $profile->marital_status ?? '') == $status ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $status)) }}
                  </option>
                @endforeach
              </select>

              @error('marital_status')
                <span class="field-error">{{ $message }}</span>
              @enderror
            </div>

            {{-- Children --}}
            <div class="form-group" id="children-group" style="display:none;">
              <label class="form-label">Number of Children</label>

              <input type="number" name="no_of_children" min="0" max="10"
                class="form-control @error('no_of_children') is-invalid @enderror"
                value="{{ old('no_of_children', $profile->no_of_children ?? '') }}">

              @error('no_of_children')
                <span class="field-error">{{ $message }}</span>
              @enderror
            </div>
          </div>

          {{-- HEIGHT + WEIGHT --}}
          <div class="form-row">

            {{-- Height --}}
            <div class="form-group">
              <label class="form-label">Height <span class="req">*</span></label>

              <select name="height_cm" class="form-control @error('height_cm') is-invalid @enderror">
                <option value="">Select Height</option>

                @for ($cm = 140; $cm <= 210; $cm++)
                  @php
                    $totalInches = round($cm / 2.54);
                    $feet = intdiv($totalInches, 12);
                    $inches = $totalInches % 12;
                  @endphp

                  <option value="{{ $cm }}"
                    {{ old('height_cm', $profile->height_cm ?? '') == $cm ? 'selected' : '' }}>
                    {{ $cm }} cm ({{ $feet }} feet {{ $inches }} inches)
                  </option>
                @endfor
              </select>

              @error('height_cm')
                <span class="field-error">{{ $message }}</span>
              @enderror

            {{-- Weight --}}
            <div class="form-group">
              <label class="form-label">Weight</label>

              <select name="weight_kg" class="form-control @error('weight_kg') is-invalid @enderror">
                <option value="">Select Weight</option>
                @for ($i = 40; $i <= 120; $i++)
                  <option value="{{ $i }}"
                    {{ old('weight_kg', $profile->weight_kg ?? '') == $i ? 'selected' : '' }}>
                    {{ $i }} kg
                  </option>
                @endfor
              </select>

              @error('weight_kg')
                <span class="field-error">{{ $message }}</span>
              @enderror
            </div>

          </div>

          {{-- BODY TYPE + COMPLEXION --}}
          <div class="form-row">

            <div class="form-group">
              <label class="form-label">Body Type</label>

              <select name="body_type" class="form-control">
                <option value="">Select</option>
                @foreach ($body_types as $type)
                  <option value="{{ $type }}"
                    {{ old('body_type', $profile->body_type ?? '') == $type ? 'selected' : '' }}>
                    {{ ucwords($type) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Complexion</label>

              <select name="complexion" class="form-control">
                <option value="">Select</option>
                @foreach ($complexions as $c)
                  <option value="{{ $c }}"
                    {{ old('complexion', $profile->complexion ?? '') == $c ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $c)) }}
                  </option>
                @endforeach
              </select>
            </div>

          </div>

          {{-- BLOOD GROUP --}}
          <div class="form-group" style="max-width:300px;">
            <label class="form-label">Blood Group</label>

            <select name="blood_group" class="form-control">
              <option value="">Select</option>
              @foreach ($blood_groups as $bg)
                <option value="{{ $bg }}"
                  {{ old('blood_group', $profile->blood_group ?? '') == $bg ? 'selected' : '' }}>
                  {{ $bg }}
                </option>
              @endforeach
            </select>
          </div>

        </div>

        {{-- LIFESTYLE --}}
        <div class="form-section">
          <h3 class="form-section-title">Lifestyle</h3>

          <div class="form-row form-row-3">

            <div class="form-group">
              <label class="form-label">Diet</label>

              <select name="diet" class="form-control">
                <option value="">Select</option>
                @foreach ($diet_options as $d)
                  <option value="{{ $d }}"
                    {{ old('diet', $profile->diet ?? '') == $d ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $d)) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Smoking</label>

              <select name="smoking" class="form-control">
                <option value="">Select</option>
                @foreach ($smoking_options as $s)
                  <option value="{{ $s }}"
                    {{ old('smoking', $profile->smoking ?? '') == $s ? 'selected' : '' }}>
                    {{ ucwords($s) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Drinking</label>

              <select name="drinking" class="form-control">
                <option value="">Select</option>
                @foreach ($drinking_options as $d)
                  <option value="{{ $d }}"
                    {{ old('drinking', $profile->drinking ?? '') == $d ? 'selected' : '' }}>
                    {{ ucwords($d) }}
                  </option>
                @endforeach
              </select>
            </div>

          </div>

          {{-- Checkbox --}}
          <div class="form-group">
            <label class="checkbox-label">
              <input type="hidden" name="is_differently_abled" value="0">
              <input type="checkbox" name="is_differently_abled" value="1"
                {{ old('is_differently_abled', $profile->is_differently_abled ?? false) ? 'checked' : '' }}>
              I am differently abled
            </label>
          </div>

        </div>

        {{-- ACTION --}}
        <div class="setup-actions">
          <span></span>
          <button type="submit" class="btn btn-primary btn-lg">
            Save & Continue →
          </button>
        </div>

      </form>
    </div>
  </div>
</section>

@include('user.profile.setup._setup_styles')

<script>
document.addEventListener('DOMContentLoaded', function () {
  const status = document.querySelector('[name="marital_status"]');
  const children = document.getElementById('children-group');

  function toggle() {
    const show = ['divorced','widowed','awaiting_divorce'].includes(status.value);
    children.style.display = show ? 'block' : 'none';
  }

  status.addEventListener('change', toggle);
  toggle();
});
</script>

@endsection