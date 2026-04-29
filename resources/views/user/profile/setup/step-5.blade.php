@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 5: Location & Family')

@section('content')

@include('user.profile.setup._progress', ['step' => 5])

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
        <div class="setup-step-icon">🏠</div>
        <div>
          <h2>Location &amp; Family</h2>
          <p>Where you live and about your family background</p>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 5) }}">
        @csrf

        {{-- Location --}}
        <div class="form-section">
          <h3 class="form-section-title">Location Details</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Country <span class="req">*</span></label>
              <select name="country_id" id="country_id"
                class="form-control @error('country_id') is-invalid @enderror">
                <option value="">Select country</option>
                @foreach ($countries as $country)
                  <option value="{{ $country->id }}"
                    {{ old('country_id', $profile->country_id ?? '') == $country->id ? 'selected' : '' }}>
                    {{ $country->name }}
                  </option>
                @endforeach
              </select>
              @error('country_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">State <span class="req">*</span></label>
              <select name="state_id" id="state_id"
                class="form-control @error('state_id') is-invalid @enderror">
                <option value="">Select state</option>
                @foreach ($states as $state)
                  <option value="{{ $state->id }}"
                    {{ old('state_id', $profile->state_id ?? '') == $state->id ? 'selected' : '' }}>
                    {{ $state->name }}
                  </option>
                @endforeach
              </select>
              @error('state_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">City <span class="req">*</span></label>
              <select name="city_id" id="city_id"
                class="form-control @error('city_id') is-invalid @enderror">
                <option value="">Select city</option>
                @foreach ($cities as $city)
                  <option value="{{ $city->id }}"
                    {{ old('city_id', $profile->city_id ?? '') == $city->id ? 'selected' : '' }}>
                    {{ $city->name }}
                  </option>
                @endforeach
              </select>
              @error('city_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Area / Locality</label>
              <select name="area_id" id="area_id"
                class="form-control @error('area_id') is-invalid @enderror">
                <option value="">Select area</option>
                @foreach ($areas as $area)
                  <option value="{{ $area->id }}"
                    {{ old('area_id', $profile->area_id ?? '') == $area->id ? 'selected' : '' }}>
                    {{ $area->name }}
                    @if ($area->pincode) ({{ $area->pincode }}) @endif
                  </option>
                @endforeach
              </select>
              @error('area_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Pincode</label>
              <input type="text" name="pincode"
                class="form-control @error('pincode') is-invalid @enderror"
                value="{{ old('pincode', $profile->pincode ?? '') }}"
                placeholder="e.g. 440001" maxlength="10">
              @error('pincode') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Residency Status <span class="req">*</span></label>
              <select name="residency_status"
                class="form-control @error('residency_status') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($residency_statuses as $rs)
                  <option value="{{ $rs }}"
                    {{ old('residency_status', $profile->residency_status ?? '') == $rs ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $rs)) }}
                  </option>
                @endforeach
              </select>
              @error('residency_status') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group" style="max-width:320px;">
            <label class="form-label">Citizenship</label>
            <input type="text" name="citizenship"
              class="form-control @error('citizenship') is-invalid @enderror"
              value="{{ old('citizenship', $profile->citizenship ?? '') }}"
              placeholder="e.g. Indian" maxlength="100">
            @error('citizenship') <span class="field-error">{{ $message }}</span> @enderror
          </div>
        </div>

        {{-- Family --}}
        <div class="form-section">
          <h3 class="form-section-title">Family Background</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Family Type <span class="req">*</span></label>
              <select name="family_type"
                class="form-control @error('family_type') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($family_types as $ft)
                  <option value="{{ $ft }}"
                    {{ old('family_type', $profile->family_type ?? '') == $ft ? 'selected' : '' }}>
                    {{ ucwords($ft) }} Family
                  </option>
                @endforeach
              </select>
              @error('family_type') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Family Status</label>
              <select name="family_status"
                class="form-control @error('family_status') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($family_statuses as $fs)
                  <option value="{{ $fs }}"
                    {{ old('family_status', $profile->family_status ?? '') == $fs ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $fs)) }}
                  </option>
                @endforeach
              </select>
              @error('family_status') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Father's Occupation</label>
              <input type="text" name="father_occupation"
                class="form-control @error('father_occupation') is-invalid @enderror"
                value="{{ old('father_occupation', $profile->father_occupation ?? '') }}"
                placeholder="e.g. Retired Government Employee"
                maxlength="150">
              @error('father_occupation') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Mother's Occupation</label>
              <input type="text" name="mother_occupation"
                class="form-control @error('mother_occupation') is-invalid @enderror"
                value="{{ old('mother_occupation', $profile->mother_occupation ?? '') }}"
                placeholder="e.g. Homemaker"
                maxlength="150">
              @error('mother_occupation') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-row" style="max-width:380px;">
            <div class="form-group">
              <label class="form-label">No. of Brothers</label>
              <input type="number" name="no_of_brothers" min="0" max="10"
                class="form-control @error('no_of_brothers') is-invalid @enderror"
                value="{{ old('no_of_brothers', $profile->no_of_brothers ?? 0) }}">
              @error('no_of_brothers') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">No. of Sisters</label>
              <input type="number" name="no_of_sisters" min="0" max="10"
                class="form-control @error('no_of_sisters') is-invalid @enderror"
                value="{{ old('no_of_sisters', $profile->no_of_sisters ?? 0) }}">
              @error('no_of_sisters') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>
        </div>

        <div class="setup-actions">
          <a href="{{ route('user.profile.setup.show', 4) }}" class="btn btn-outline">← Back</a>
          <button type="submit" class="btn btn-primary btn-lg">Save &amp; Continue →</button>
        </div>
      </form>
    </div>
  </div>
</section>

@include('user.profile.setup._setup_styles')

<script>
document.addEventListener('DOMContentLoaded', function () {
  const baseUrl = '{{ url("/user/ajax") }}';

  function loadOptions(url, targetId, selectedId) {
    const el = document.getElementById(targetId);
    if (!el) return;
    el.innerHTML = '<option value="">Loading…</option>';
    el.disabled = true;
    fetch(url)
      .then(r => r.json())
      .then(data => {
        el.innerHTML = '<option value="">Select</option>';
        data.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.id;
          opt.textContent = item.name + (item.pincode ? ` (${item.pincode})` : '');
          if (item.id == selectedId) opt.selected = true;
          el.appendChild(opt);
        });
        el.disabled = false;
      });
  }

  document.getElementById('country_id').addEventListener('change', function () {
    const id = this.value;
    if (!id) return;
    loadOptions(`${baseUrl}/states-by-country/${id}`, 'state_id', null);
    document.getElementById('city_id').innerHTML = '<option value="">Select city</option>';
    document.getElementById('area_id').innerHTML = '<option value="">Select area</option>';
  });

  document.getElementById('state_id').addEventListener('change', function () {
    const id = this.value;
    if (!id) return;
    loadOptions(`${baseUrl}/cities-by-state/${id}`, 'city_id', null);
    document.getElementById('area_id').innerHTML = '<option value="">Select area</option>';
  });

  document.getElementById('city_id').addEventListener('change', function () {
    const id = this.value;
    if (!id) return;
    loadOptions(`${baseUrl}/areas-by-city/${id}`, 'area_id', null);
  });
});
</script>
@endsection