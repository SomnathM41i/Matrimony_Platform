@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 4: Education & Career')

@section('content')

@include('user.profile.setup._progress', ['step' => 4])

<section class="setup-section">
  <div class="container">
    <div class="setup-card">

      @if (session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-error">
          <strong>Please fix the following:</strong>
          <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="setup-card-header">
        <div class="setup-step-icon">🎓</div>
        <div>
          <h2>Education &amp; Career</h2>
          <p>Your academic background and professional details</p>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 4) }}">
        @csrf

        <div class="form-section">
          <h3 class="form-section-title">Education</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Highest Education Level <span class="req">*</span></label>
              <select name="education_level_id"
                class="form-control @error('education_level_id') is-invalid @enderror">
                <option value="">Select education level</option>
                @foreach ($education_levels as $level)
                  <option value="{{ $level->id }}"
                    {{ old('education_level_id', $profile->education_level_id ?? '') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                  </option>
                @endforeach
              </select>
              @error('education_level_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Education Details / Specialisation</label>
            <input type="text" name="education_details"
              class="form-control @error('education_details') is-invalid @enderror"
              value="{{ old('education_details', $profile->education_details ?? '') }}"
              placeholder="e.g. B.Tech in Computer Engineering, Mumbai University"
              maxlength="255">
            @error('education_details') <span class="field-error">{{ $message }}</span> @enderror
            <span class="field-hint">Mention your degree, specialisation, and institution if you wish.</span>
          </div>
        </div>

        <div class="form-section">
          <h3 class="form-section-title">Career</h3>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Profession / Occupation <span class="req">*</span></label>
              <select name="profession_id"
                class="form-control @error('profession_id') is-invalid @enderror">
                <option value="">Select profession</option>
                @php $lastCategory = null; @endphp
                @foreach ($professions as $profession)
                  @if ($profession->category && $profession->category !== $lastCategory)
                    @if ($lastCategory !== null) </optgroup> @endif
                    <optgroup label="{{ $profession->category }}">
                    @php $lastCategory = $profession->category; @endphp
                  @endif
                  <option value="{{ $profession->id }}"
                    {{ old('profession_id', $profile->profession_id ?? '') == $profession->id ? 'selected' : '' }}>
                    {{ $profession->name }}
                  </option>
                @endforeach
                @if ($lastCategory !== null) </optgroup> @endif
              </select>
              @error('profession_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Company / Organisation Name</label>
              <input type="text" name="company_name"
                class="form-control @error('company_name') is-invalid @enderror"
                value="{{ old('company_name', $profile->company_name ?? '') }}"
                placeholder="e.g. Tata Consultancy Services"
                maxlength="150">
              @error('company_name') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group" style="max-width:380px;">
            <label class="form-label">Annual Income Range <span class="req">*</span></label>
            <select name="annual_income_range_id"
              class="form-control @error('annual_income_range_id') is-invalid @enderror">
              <option value="">Select income range</option>
              @foreach ($annual_income_ranges as $range)
                <option value="{{ $range->id }}"
                  {{ old('annual_income_range_id', $profile->annual_income_range_id ?? '') == $range->id ? 'selected' : '' }}>
                  {{ $range->display_label }}
                </option>
              @endforeach
            </select>
            @error('annual_income_range_id') <span class="field-error">{{ $message }}</span> @enderror
            @if($annual_income_ranges->isEmpty())
              <span class="field-error">No active income ranges are configured. Please add them from the admin lookup panel.</span>
            @endif
            <span class="field-hint">Your income is shown only to matches you accept.</span>
          </div>
        </div>

        <div class="setup-actions">
          <a href="{{ route('user.profile.setup.show', 3) }}" class="btn btn-outline">← Back</a>
          <button type="submit" class="btn btn-primary btn-lg">Save &amp; Continue →</button>
        </div>
      </form>
    </div>
  </div>
</section>

@include('user.profile.setup._setup_styles')
@endsection
