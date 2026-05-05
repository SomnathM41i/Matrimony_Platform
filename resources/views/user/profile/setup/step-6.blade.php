@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 6: Partner Preferences')

@section('content')

@include('user.profile.setup._progress', ['step' => 6])

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
        <div class="setup-step-icon">💑</div>
        <div>
          <h2>Partner Preferences</h2>
          <p>Define what you're looking for in your life partner</p>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 6) }}">
        @csrf
        @php $pref = $preference; @endphp

        {{-- AGE & HEIGHT --}}
        <div class="form-section">
          <h3 class="form-section-title">Age & Physical</h3>

          <div class="form-row form-row-4">

            {{-- Min Age --}}
            <div class="form-group">
              <label class="form-label">Min Age <span class="req">*</span></label>
              <select name="age_min" class="form-control">
                @for ($i = 18; $i <= 60; $i++)
                  <option value="{{ $i }}"
                    {{ old('age_min', $pref->age_min ?? 22) == $i ? 'selected' : '' }}>
                    {{ $i }} years
                  </option>
                @endfor
              </select>
            </div>

            {{-- Max Age --}}
            <div class="form-group">
              <label class="form-label">Max Age <span class="req">*</span></label>
              <select name="age_max" class="form-control">
                @for ($i = 18; $i <= 60; $i++)
                  <option value="{{ $i }}"
                    {{ old('age_max', $pref->age_max ?? 35) == $i ? 'selected' : '' }}>
                    {{ $i }} years
                  </option>
                @endfor
              </select>
            </div>

            {{-- Min Height --}}
            <div class="form-group">
              <label class="form-label">Min Height</label>
              <select name="height_min_cm" class="form-control">
                <option value="">Any</option>
                @for ($cm = 140; $cm <= 210; $cm++)
                  @php
                    $totalInches = round($cm / 2.54);
                    $feet = intdiv($totalInches, 12);
                    $inches = $totalInches % 12;
                  @endphp
                  <option value="{{ $cm }}"
                    {{ old('height_min_cm', $pref->height_min_cm ?? '') == $cm ? 'selected' : '' }}>
                    {{ $cm }} cm ({{ $feet }} feet{{ $inches ? ' '.$inches.' inches' : '' }})
                  </option>
                @endfor
              </select>
            </div>

            {{-- Max Height --}}
            <div class="form-group">
              <label class="form-label">Max Height</label>
              <select name="height_max_cm" class="form-control">
                <option value="">Any</option>
                @for ($cm = 140; $cm <= 210; $cm++)
                  @php
                    $totalInches = round($cm / 2.54);
                    $feet = intdiv($totalInches, 12);
                    $inches = $totalInches % 12;
                  @endphp
                  <option value="{{ $cm }}"
                    {{ old('height_max_cm', $pref->height_max_cm ?? '') == $cm ? 'selected' : '' }}>
                    {{ $cm }} cm ({{ $feet }} feet{{ $inches ? ' '.$inches.' inches' : '' }})
                  </option>
                @endfor
              </select>
            </div>

          </div>

          {{-- Marital Status --}}
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

        {{-- RELIGION --}}
        <div class="form-section">
          <h3 class="form-section-title">Religion & Community</h3>

          <label class="checkbox-label">
            <input type="hidden" name="caste_no_bar" value="0">
            <input type="checkbox" name="caste_no_bar" value="1"
              {{ old('caste_no_bar', $pref->caste_no_bar ?? false) ? 'checked' : '' }}>
            Caste No Bar
          </label>

          {{-- Religion --}}
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

          {{-- Mother Tongue --}}
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

          {{-- Manglik --}}
          <div class="form-group">
            <label class="form-label">Manglik Preference</label>
            <select name="manglik_pref" class="form-control">
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

        {{-- LOCATION --}}
        <div class="form-section">
          <h3 class="form-section-title">Location Preference</h3>

          <div class="form-group">
            <label class="form-label">Preferred Country</label>
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
            <label class="form-label">Residency Status</label>
            <select name="residency_status_pref" class="form-control">
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

        {{-- EDUCATION --}}
        <div class="form-section">
          <h3 class="form-section-title">Education & Career</h3>

          <div class="form-group">
            <label class="form-label">Education Level</label>
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

          <div class="form-group">
            <label class="form-label">Minimum Income</label>
            <select name="annual_income_range_id_min" class="form-control">
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

        {{-- LIFESTYLE --}}
        <div class="form-section">
          <h3 class="form-section-title">Lifestyle</h3>

          <div class="form-row form-row-3">
            @foreach (['diet'=>$diet_options,'smoking'=>$smoking_options,'drinking'=>$drinking_options] as $key => $options)
              <div class="form-group">
                <label class="form-label">{{ ucfirst($key) }}</label>
                <div class="checkbox-stack">
                  @foreach ($options as $opt)
                    <label class="checkbox-label">
                      <input type="checkbox" name="{{ $key }}[]" value="{{ $opt }}"
                        {{ in_array($opt, old($key, $pref->$key ?? [])) ? 'checked' : '' }}>
                      {{ ucwords(str_replace('_',' ',$opt)) }}
                    </label>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- ABOUT --}}
        <div class="form-section">
          <h3 class="form-section-title">About Partner</h3>
          <textarea name="about_partner" rows="4" class="form-control">
            {{ old('about_partner', $pref->about_partner ?? '') }}
          </textarea>
        </div>

        <div class="setup-actions">
          <a href="{{ route('user.profile.setup.show', 5) }}" class="btn btn-outline">← Back</a>
          <button type="submit" class="btn btn-primary btn-lg">Save & Continue →</button>
        </div>

      </form>
    </div>
  </div>
</section>

@include('user.profile.setup._setup_styles')

@endsection