@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 6: Partner Preferences')

@section('content')

@include('user.profile.setup._progress', ['step' => 6])

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
        <div class="setup-step-icon">💑</div>
        <div>
          <h2>Partner Preferences</h2>
          <p>Define what you're looking for in your life partner</p>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 6) }}">
        @csrf

        @php $pref = $preference; @endphp

        {{-- Age & Height --}}
        <div class="form-section">
          <h3 class="form-section-title">Age &amp; Physical</h3>
          <div class="form-row form-row-4">
            <div class="form-group">
              <label class="form-label">Min Age <span class="req">*</span></label>
              <input type="number" name="age_min" min="18" max="80"
                class="form-control @error('age_min') is-invalid @enderror"
                value="{{ old('age_min', $pref->age_min ?? 22) }}">
              @error('age_min') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Max Age <span class="req">*</span></label>
              <input type="number" name="age_max" min="18" max="80"
                class="form-control @error('age_max') is-invalid @enderror"
                value="{{ old('age_max', $pref->age_max ?? 35) }}">
              @error('age_max') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Min Height (cm)</label>
              <input type="number" name="height_min_cm" min="100" max="250"
                class="form-control @error('height_min_cm') is-invalid @enderror"
                value="{{ old('height_min_cm', $pref->height_min_cm ?? '') }}"
                placeholder="Any">
              @error('height_min_cm') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Max Height (cm)</label>
              <input type="number" name="height_max_cm" min="100" max="250"
                class="form-control @error('height_max_cm') is-invalid @enderror"
                value="{{ old('height_max_cm', $pref->height_max_cm ?? '') }}"
                placeholder="Any">
              @error('height_max_cm') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Preferred Marital Status</label>
            <div class="checkbox-grid">
              @foreach ($marital_statuses as $ms)
                <label class="checkbox-chip">
                  <input type="checkbox" name="marital_status[]" value="{{ $ms }}"
                    {{ in_array($ms, old('marital_status', $pref->marital_status ?? [])) ? 'checked' : '' }}>
                  <span>{{ ucwords(str_replace('_', ' ', $ms)) }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Religion & Community --}}
        <div class="form-section">
          <h3 class="form-section-title">Religion &amp; Community</h3>
          <div class="form-group">
            <label class="checkbox-label" style="margin-bottom:16px;">
              <input type="hidden" name="caste_no_bar" value="0">
              <input type="checkbox" name="caste_no_bar" value="1"
                {{ old('caste_no_bar', $pref->caste_no_bar ?? false) ? 'checked' : '' }}>
              Caste No Bar — I'm open to all castes
            </label>
          </div>

          <div class="form-group">
            <label class="form-label">Preferred Religion(s)</label>
            <div class="checkbox-grid">
              @foreach ($religions as $religion)
                <label class="checkbox-chip">
                  <input type="checkbox" name="religion_ids[]" value="{{ $religion->id }}"
                    {{ in_array($religion->id, old('religion_ids', $pref->religion_ids ?? [])) ? 'checked' : '' }}>
                  <span>{{ $religion->name }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Preferred Mother Tongue(s)</label>
            <div class="checkbox-grid">
              @foreach ($mother_tongues as $mt)
                <label class="checkbox-chip">
                  <input type="checkbox" name="mother_tongue_ids[]" value="{{ $mt->id }}"
                    {{ in_array($mt->id, old('mother_tongue_ids', $pref->mother_tongue_ids ?? [])) ? 'checked' : '' }}>
                  <span>{{ $mt->name }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Manglik Preference</label>
            <select name="manglik_pref"
              class="form-control @error('manglik_pref') is-invalid @enderror"
              style="max-width:320px;">
              <option value="">No Preference</option>
              @foreach ($manglik_options as $opt)
                <option value="{{ $opt }}"
                  {{ old('manglik_pref', $pref->manglik_pref ?? '') == $opt ? 'selected' : '' }}>
                  {{ ucwords(str_replace('_', ' ', $opt)) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Location --}}
        <div class="form-section">
          <h3 class="form-section-title">Location Preference</h3>
          <div class="form-group">
            <label class="form-label">Preferred Country / Countries</label>
            <div class="checkbox-grid">
              @foreach ($countries as $country)
                <label class="checkbox-chip">
                  <input type="checkbox" name="country_ids[]" value="{{ $country->id }}"
                    {{ in_array($country->id, old('country_ids', $pref->country_ids ?? [])) ? 'checked' : '' }}>
                  <span>{{ $country->name }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="form-group" style="max-width:320px;">
            <label class="form-label">Residency Status Preference</label>
            <select name="residency_status_pref"
              class="form-control @error('residency_status_pref') is-invalid @enderror">
              <option value="">Any</option>
              @foreach ($residency_statuses as $rs)
                <option value="{{ $rs }}"
                  {{ old('residency_status_pref', $pref->residency_status_pref ?? '') == $rs ? 'selected' : '' }}>
                  {{ ucwords(str_replace('_', ' ', $rs)) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Education & Career --}}
        <div class="form-section">
          <h3 class="form-section-title">Education &amp; Career</h3>
          <div class="form-group">
            <label class="form-label">Preferred Education Level(s)</label>
            <div class="checkbox-grid">
              @foreach ($education_levels as $level)
                <label class="checkbox-chip">
                  <input type="checkbox" name="education_level_ids[]" value="{{ $level->id }}"
                    {{ in_array($level->id, old('education_level_ids', $pref->education_level_ids ?? [])) ? 'checked' : '' }}>
                  <span>{{ $level->name }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="form-group" style="max-width:380px;">
            <label class="form-label">Minimum Annual Income</label>
            <select name="annual_income_range_id_min"
              class="form-control @error('annual_income_range_id_min') is-invalid @enderror">
              <option value="">No Preference</option>
              @foreach ($annual_income_ranges as $range)
                <option value="{{ $range->id }}"
                  {{ old('annual_income_range_id_min', $pref->annual_income_range_id_min ?? '') == $range->id ? 'selected' : '' }}>
                  {{ $range->label }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Lifestyle --}}
        <div class="form-section">
          <h3 class="form-section-title">Lifestyle Preferences</h3>
          <div class="form-row form-row-3">
            <div class="form-group">
              <label class="form-label">Diet</label>
              <div class="checkbox-stack">
                @foreach ($diet_options as $d)
                  <label class="checkbox-label">
                    <input type="checkbox" name="diet[]" value="{{ $d }}"
                      {{ in_array($d, old('diet', $pref->diet ?? [])) ? 'checked' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $d)) }}
                  </label>
                @endforeach
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Smoking</label>
              <div class="checkbox-stack">
                @foreach ($smoking_options as $s)
                  <label class="checkbox-label">
                    <input type="checkbox" name="smoking[]" value="{{ $s }}"
                      {{ in_array($s, old('smoking', $pref->smoking ?? [])) ? 'checked' : '' }}>
                    {{ ucwords($s) }}
                  </label>
                @endforeach
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Drinking</label>
              <div class="checkbox-stack">
                @foreach ($drinking_options as $d)
                  <label class="checkbox-label">
                    <input type="checkbox" name="drinking[]" value="{{ $d }}"
                      {{ in_array($d, old('drinking', $pref->drinking ?? [])) ? 'checked' : '' }}>
                    {{ ucwords($d) }}
                  </label>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        {{-- About Partner --}}
        <div class="form-section">
          <h3 class="form-section-title">Describe Your Ideal Partner</h3>
          <div class="form-group">
            <textarea name="about_partner" rows="4"
              class="form-control @error('about_partner') is-invalid @enderror"
              placeholder="Describe your ideal life partner — values, personality, qualities you admire…"
              maxlength="1000">{{ old('about_partner', $pref->about_partner ?? '') }}</textarea>
            @error('about_partner') <span class="field-error">{{ $message }}</span> @enderror
            <span class="field-hint">Optional but helps attract the right matches.</span>
          </div>
        </div>

        <div class="setup-actions">
          <a href="{{ route('user.profile.setup.show', 5) }}" class="btn btn-outline">← Back</a>
          <button type="submit" class="btn btn-primary btn-lg">Save &amp; Continue →</button>
        </div>
      </form>
    </div>
  </div>
</section>

@include('user.profile.setup._setup_styles')
@endsection