@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 1: Basic Info')

@section('content')

@include('user.profile.setup._progress', ['step' => 1])

<section class="setup-section">
  <div class="container">
    <div class="setup-card">

      {{-- Flash messages --}}
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
        <div class="setup-step-icon">👤</div>
        <div>
          <h2>Basic Information</h2>
          <p>Tell potential matches about yourself</p>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 1) }}">
        @csrf

        {{-- About Me --}}
        <div class="form-section">
          <h3 class="form-section-title">About Yourself</h3>
          <div class="form-group">
            <label class="form-label">About Me <span class="req">*</span></label>
            <textarea name="about_me" rows="4"
              class="form-control @error('about_me') is-invalid @enderror"
              placeholder="Write a brief introduction about yourself, your interests, values and what you're looking for... (minimum 30 characters)"
              maxlength="1000"
            >{{ old('about_me', $profile->about_me ?? '') }}</textarea>
            @error('about_me') <span class="field-error">{{ $message }}</span> @enderror
            <span class="field-hint">Minimum 30 characters. A good bio greatly increases your match rate.</span>
          </div>
        </div>

        {{-- Marital Status --}}
        <div class="form-section">
          <h3 class="form-section-title">Marital & Physical Details</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Marital Status <span class="req">*</span></label>
              <select name="marital_status" class="form-control @error('marital_status') is-invalid @enderror">
                <option value="">Select status</option>
                @foreach ($marital_statuses as $status)
                  <option value="{{ $status }}"
                    {{ old('marital_status', $profile->marital_status ?? '') == $status ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $status)) }}
                  </option>
                @endforeach
              </select>
              @error('marital_status') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" id="children-group" style="display:none;">
              <label class="form-label">Number of Children</label>
              <input type="number" name="no_of_children" min="0" max="10"
                class="form-control @error('no_of_children') is-invalid @enderror"
                value="{{ old('no_of_children', $profile->no_of_children ?? '') }}"
                placeholder="0">
              @error('no_of_children') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Height (cm) <span class="req">*</span></label>
              <input type="number" name="height_cm" min="100" max="250"
                class="form-control @error('height_cm') is-invalid @enderror"
                value="{{ old('height_cm', $profile->height_cm ?? '') }}"
                placeholder="e.g. 170">
              @error('height_cm') <span class="field-error">{{ $message }}</span> @enderror
              <span class="field-hint">Enter in centimetres (e.g. 5'7" = 170 cm)</span>
            </div>

            <div class="form-group">
              <label class="form-label">Weight (kg)</label>
              <input type="number" name="weight_kg" min="30" max="200" step="0.1"
                class="form-control @error('weight_kg') is-invalid @enderror"
                value="{{ old('weight_kg', $profile->weight_kg ?? '') }}"
                placeholder="e.g. 65">
              @error('weight_kg') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Body Type</label>
              <select name="body_type" class="form-control @error('body_type') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($body_types as $type)
                  <option value="{{ $type }}"
                    {{ old('body_type', $profile->body_type ?? '') == $type ? 'selected' : '' }}>
                    {{ ucwords($type) }}
                  </option>
                @endforeach
              </select>
              @error('body_type') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Complexion</label>
              <select name="complexion" class="form-control @error('complexion') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($complexions as $c)
                  <option value="{{ $c }}"
                    {{ old('complexion', $profile->complexion ?? '') == $c ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $c)) }}
                  </option>
                @endforeach
              </select>
              @error('complexion') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group" style="max-width:320px;">
            <label class="form-label">Blood Group</label>
            <select name="blood_group" class="form-control @error('blood_group') is-invalid @enderror">
              <option value="">Select</option>
              @foreach ($blood_groups as $bg)
                <option value="{{ $bg }}"
                  {{ old('blood_group', $profile->blood_group ?? '') == $bg ? 'selected' : '' }}>
                  {{ $bg }}
                </option>
              @endforeach
            </select>
            @error('blood_group') <span class="field-error">{{ $message }}</span> @enderror
          </div>
        </div>

        {{-- Lifestyle --}}
        <div class="form-section">
          <h3 class="form-section-title">Lifestyle</h3>
          <div class="form-row form-row-3">
            <div class="form-group">
              <label class="form-label">Diet <span class="req">*</span></label>
              <select name="diet" class="form-control @error('diet') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($diet_options as $d)
                  <option value="{{ $d }}"
                    {{ old('diet', $profile->diet ?? '') == $d ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $d)) }}
                  </option>
                @endforeach
              </select>
              @error('diet') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Smoking <span class="req">*</span></label>
              <select name="smoking" class="form-control @error('smoking') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($smoking_options as $s)
                  <option value="{{ $s }}"
                    {{ old('smoking', $profile->smoking ?? '') == $s ? 'selected' : '' }}>
                    {{ ucwords($s) }}
                  </option>
                @endforeach
              </select>
              @error('smoking') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Drinking <span class="req">*</span></label>
              <select name="drinking" class="form-control @error('drinking') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($drinking_options as $d)
                  <option value="{{ $d }}"
                    {{ old('drinking', $profile->drinking ?? '') == $d ? 'selected' : '' }}>
                    {{ ucwords($d) }}
                  </option>
                @endforeach
              </select>
              @error('drinking') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="checkbox-label">
              <input type="hidden" name="is_differently_abled" value="0">
              <input type="checkbox" name="is_differently_abled" value="1"
                {{ old('is_differently_abled', $profile->is_differently_abled ?? false) ? 'checked' : '' }}>
              I am differently abled
            </label>
          </div>
        </div>

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
  const statusSel = document.querySelector('[name="marital_status"]');
  const childGrp  = document.getElementById('children-group');
  function toggleChildren() {
    const show = ['divorced','widowed','awaiting_divorce'].includes(statusSel.value);
    childGrp.style.display = show ? 'block' : 'none';
  }
  statusSel.addEventListener('change', toggleChildren);
  toggleChildren();
});
</script>
@endsection