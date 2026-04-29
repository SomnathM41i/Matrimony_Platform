@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 2: Religion & Community')

@section('content')

@include('user.profile.setup._progress', ['step' => 2])

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
        <div class="setup-step-icon">🛕</div>
        <div>
          <h2>Religion &amp; Community</h2>
          <p>Share your religious background and community identity</p>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 2) }}">
        @csrf

        {{-- Religion --}}
        <div class="form-section">
          <h3 class="form-section-title">Religious Background</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Religion <span class="req">*</span></label>
              <select name="religion_id" id="religion_id"
                class="form-control @error('religion_id') is-invalid @enderror">
                <option value="">Select religion</option>
                @foreach ($religions as $religion)
                  <option value="{{ $religion->id }}"
                    {{ old('religion_id', $profile->religion_id ?? '') == $religion->id ? 'selected' : '' }}>
                    {{ $religion->name }}
                  </option>
                @endforeach
              </select>
              @error('religion_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Caste</label>
              <select name="caste_id" id="caste_id"
                class="form-control @error('caste_id') is-invalid @enderror">
                <option value="">Select caste</option>
                @foreach ($castes as $caste)
                  <option value="{{ $caste->id }}"
                    {{ old('caste_id', $profile->caste_id ?? '') == $caste->id ? 'selected' : '' }}>
                    {{ $caste->name }}
                  </option>
                @endforeach
              </select>
              @error('caste_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Sub-Caste</label>
              <select name="sub_caste_id" id="sub_caste_id"
                class="form-control @error('sub_caste_id') is-invalid @enderror">
                <option value="">Select sub-caste</option>
                @foreach ($sub_castes as $sc)
                  <option value="{{ $sc->id }}"
                    {{ old('sub_caste_id', $profile->sub_caste_id ?? '') == $sc->id ? 'selected' : '' }}>
                    {{ $sc->name }}
                  </option>
                @endforeach
              </select>
              @error('sub_caste_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Gotra</label>
              <select name="gotra_id" id="gotra_id"
                class="form-control @error('gotra_id') is-invalid @enderror">
                <option value="">Select gotra</option>
                @foreach ($gotras as $gotra)
                  <option value="{{ $gotra->id }}"
                    {{ old('gotra_id', $profile->gotra_id ?? '') == $gotra->id ? 'selected' : '' }}>
                    {{ $gotra->name }}
                  </option>
                @endforeach
              </select>
              @error('gotra_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group" style="max-width:380px;">
            <label class="form-label">Community</label>
            <select name="community_id" id="community_id"
              class="form-control @error('community_id') is-invalid @enderror">
              <option value="">Select community</option>
              @foreach ($communities as $community)
                <option value="{{ $community->id }}"
                  {{ old('community_id', $profile->community_id ?? '') == $community->id ? 'selected' : '' }}>
                  {{ $community->name }}
                </option>
              @endforeach
            </select>
            @error('community_id') <span class="field-error">{{ $message }}</span> @enderror
          </div>
        </div>

        {{-- Language --}}
        <div class="form-section">
          <h3 class="form-section-title">Language</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Mother Tongue <span class="req">*</span></label>
              <select name="mother_tongue_id"
                class="form-control @error('mother_tongue_id') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($mother_tongues as $mt)
                  <option value="{{ $mt->id }}"
                    {{ old('mother_tongue_id', $profile->mother_tongue_id ?? '') == $mt->id ? 'selected' : '' }}>
                    {{ $mt->name }}
                  </option>
                @endforeach
              </select>
              @error('mother_tongue_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Languages Known</label>
            <div class="checkbox-grid">
              @foreach ($languages as $lang)
                <label class="checkbox-chip">
                  <input type="checkbox" name="languages_known[]" value="{{ $lang->id }}"
                    {{ in_array($lang->id, old('languages_known', $profile->languages_known ?? [])) ? 'checked' : '' }}>
                  <span>{{ $lang->name }}</span>
                </label>
              @endforeach
            </div>
            @error('languages_known') <span class="field-error">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="setup-actions">
          <a href="{{ route('user.profile.setup.show', 1) }}" class="btn btn-outline">← Back</a>
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

  function loadOptions(url, targetSel, selectedId) {
    const el = document.getElementById(targetSel);
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
          opt.textContent = item.name;
          if (item.id == selectedId) opt.selected = true;
          el.appendChild(opt);
        });
        el.disabled = false;
      })
      .catch(() => {
        el.innerHTML = '<option value="">Error loading</option>';
        el.disabled = false;
      });
  }

  const religionSel = document.getElementById('religion_id');
  const casteSel    = document.getElementById('caste_id');

  religionSel.addEventListener('change', function () {
    const id = this.value;
    if (!id) return;
    loadOptions(`${baseUrl}/castes-by-religion/${id}`, 'caste_id', null);
    loadOptions(`${baseUrl}/gotras-by-religion/${id}`, 'gotra_id', null);
    loadOptions(`${baseUrl}/communities-by-religion/${id}`, 'community_id', null);
    // Clear sub-caste
    document.getElementById('sub_caste_id').innerHTML = '<option value="">Select sub-caste</option>';
  });

  casteSel.addEventListener('change', function () {
    const id = this.value;
    if (!id) {
      document.getElementById('sub_caste_id').innerHTML = '<option value="">Select sub-caste</option>';
      return;
    }
    loadOptions(`${baseUrl}/sub-castes-by-caste/${id}`, 'sub_caste_id', null);
  });
});
</script>
@endsection