@extends('user.layouts.app')
@section('title', 'Profile Setup — Step 7: Photos & Privacy')

@section('content')

@include('user.profile.setup._progress', ['step' => 7])

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
        <div class="setup-step-icon">📷</div>
        <div>
          <h2>Photos &amp; Privacy</h2>
          <p>Upload your photos and set your visibility preferences</p>
        </div>
      </div>

      <form method="POST" action="{{ route('user.profile.setup.save', 7) }}"
        enctype="multipart/form-data" id="photosForm">
        @csrf

        {{-- Existing photos --}}
        @if ($photos->isNotEmpty())
        <div class="form-section">
          <h3 class="form-section-title">Your Current Photos</h3>
          <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:16px;">
            ⏳ Photos are pending admin approval before they appear to matches.
            You can set one as your primary / display photo.
          </p>
          <div class="photo-grid">
            @foreach ($photos as $photo)
              <div class="photo-thumb {{ $photo->is_primary ? 'is-primary' : '' }}">
                <img src="{{ Storage::url($photo->thumbnail_path ?? $photo->path) }}"
                  alt="Profile photo">
                @if ($photo->is_primary)
                  <span class="photo-badge">Primary</span>
                @endif
                @if (!$photo->is_approved)
                  <span class="photo-pending">Pending</span>
                @endif
                <label class="photo-primary-label">
                  <input type="radio" name="primary_photo_id" value="{{ $photo->id }}"
                    {{ $photo->is_primary ? 'checked' : '' }}>
                  Set as primary
                </label>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Upload new photos --}}
        @php
          $remainingPhotos = is_null($max_photos) ? null : max(0, $max_photos - $photos->count());
          $canUploadMore = is_null($max_photos) || $photos->count() < $max_photos;
        @endphp

        @if ($canUploadMore)
        <div class="form-section">
          <h3 class="form-section-title">
            Upload Photos
            <span class="photo-count-badge">
              {{ $photos->count() }} / {{ is_null($max_photos) ? 'Unlimited' : $max_photos }}
            </span>
          </h3>
          <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:20px;">
            @if(is_null($remainingPhotos))
              Your current plan allows unlimited photo uploads.
            @else
              You can upload up to {{ $remainingPhotos }} more photo(s).
            @endif
            Accepted formats: JPG, PNG, WEBP. Max 5 MB per photo.
          </p>

          <div class="upload-area" id="uploadArea">
            <input type="file" id="photoFiles" name="photos[]"
              accept="image/jpeg,image/png,image/webp"
              multiple
              data-max="{{ $remainingPhotos ?? 999 }}"
              style="display:none;">
            <div class="upload-icon">📸</div>
            <p class="upload-text">
              <strong>Click to select photos</strong> or drag and drop here
            </p>
            <p style="font-size:0.8rem;color:var(--text-light);margin-top:6px;">
              JPG, PNG, WEBP — up to 5 MB each
            </p>
          </div>
          @error('photos') <span class="field-error">{{ $message }}</span> @enderror
          @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror

          <div id="photoPreviewGrid" class="photo-preview-grid" style="display:none;"></div>
        </div>
        @else
          <div class="skip-notice" style="background:rgba(26,63,160,0.06);border-color:rgba(26,63,160,0.2);">
            <span>✅</span>
            <div>You have uploaded the maximum of {{ $max_photos }} photo(s) allowed by your current plan.</div>
          </div>
        @endif

        {{-- Privacy settings --}}
        <div class="form-section">
          <h3 class="form-section-title">Privacy Settings</h3>

          <div class="privacy-grid">
            <div class="privacy-card">
              <div class="privacy-icon">🖼️</div>
              <div>
                <label class="form-label">Photo Visibility</label>
                <select name="photo_privacy"
                  class="form-control @error('photo_privacy') is-invalid @enderror">
                  @foreach ($photo_privacy_opts as $opt)
                    <option value="{{ $opt }}"
                      {{ old('photo_privacy', $profile->photo_privacy ?? 'all') == $opt ? 'selected' : '' }}>
                      @if ($opt === 'all') Visible to Everyone
                      @elseif ($opt === 'accepted_interest') Accepted Interests Only
                      @else Premium Members Only
                      @endif
                    </option>
                  @endforeach
                </select>
                @error('photo_privacy') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="privacy-card">
              <div class="privacy-icon">📞</div>
              <div>
                <label class="form-label">Contact Details Visibility</label>
                <select name="contact_privacy"
                  class="form-control @error('contact_privacy') is-invalid @enderror">
                  @foreach ($contact_privacy_opts as $opt)
                    <option value="{{ $opt }}"
                      {{ old('contact_privacy', $profile->contact_privacy ?? 'accepted_interest') == $opt ? 'selected' : '' }}>
                      @if ($opt === 'all') Visible to Everyone
                      @elseif ($opt === 'accepted_interest') Accepted Interests Only
                      @else Premium Members Only
                      @endif
                    </option>
                  @endforeach
                </select>
                @error('contact_privacy') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="privacy-card">
              <div class="privacy-icon">👁️</div>
              <div>
                <label class="form-label">Profile Visibility</label>
                <select name="profile_visibility"
                  class="form-control @error('profile_visibility') is-invalid @enderror">

                  @foreach ($visibility_opts as $opt)
                    <option value="{{ $opt }}"
                      {{ old('profile_visibility', $profile->profile_visibility ?? 'everyone') == $opt ? 'selected' : '' }}>

                      @if ($opt === 'everyone')
                        Visible to Everyone
                      @elseif ($opt === 'registered')
                        Registered Members Only
                      @else
                        Hidden (Private)
                      @endif

                    </option>
                  @endforeach
                </select>
                @error('profile_visibility') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
          </div>
        </div>

        {{-- Final CTA --}}
        <div class="setup-final-cta">
          <div class="final-cta-inner">
            <div class="final-cta-icon">🎉</div>
            <h3>You're almost there!</h3>
            <p>Submit your profile and start connecting with your perfect match.</p>
          </div>
        </div>

        <div class="setup-actions">
          <a href="{{ route('user.profile.setup.show', 6) }}" class="btn btn-outline">← Back</a>
          <button type="submit" class="btn btn-primary btn-lg">
            🚀 Complete Profile Setup
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

@include('user.profile.setup._setup_styles')

<style>
.photo-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
  gap: 16px;
  margin-bottom: 8px;
}
.photo-thumb {
  position: relative;
  border-radius: var(--radius-sm);
  overflow: hidden;
  border: 2px solid var(--border);
  transition: var(--transition);
}
.photo-thumb.is-primary { border-color: var(--gold); }
.photo-thumb img { width: 100%; height: 130px; object-fit: cover; display: block; }
.photo-badge {
  position: absolute;
  top: 6px; left: 6px;
  background: var(--gold);
  color: var(--text);
  font-size: 0.65rem;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 100px;
}
.photo-pending {
  position: absolute;
  top: 6px; right: 6px;
  background: rgba(0,0,0,0.55);
  color: rgba(255,255,255,0.8);
  font-size: 0.65rem;
  padding: 2px 8px;
  border-radius: 100px;
}
.photo-primary-label {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px;
  font-size: 0.75rem;
  color: var(--text-muted);
  cursor: pointer;
  background: var(--bg-light);
}
.photo-primary-label input { accent-color: var(--gold); }
.photo-count-badge {
  display: inline-block;
  background: rgba(196,30,58,0.1);
  color: var(--primary);
  font-size: 0.75rem;
  font-weight: 600;
  padding: 2px 10px;
  border-radius: 100px;
  margin-left: 10px;
  font-family: var(--font-body);
}
.photo-preview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  gap: 12px;
  margin-top: 16px;
}
.preview-item {
  position: relative;
  border-radius: var(--radius-sm);
  overflow: hidden;
  border: 1.5px solid var(--border);
}
.preview-item img { width: 100%; height: 100px; object-fit: cover; display: block; }
.preview-item .remove-btn {
  position: absolute;
  top: 4px; right: 4px;
  background: rgba(196,30,58,0.85);
  color: #fff;
  border: none;
  border-radius: 50%;
  width: 22px; height: 22px;
  font-size: 0.75rem;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: var(--transition);
}
.preview-item .remove-btn:hover { background: var(--primary); }

.privacy-grid {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.privacy-card {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 20px;
  background: var(--bg-light);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
}
.privacy-icon {
  font-size: 1.8rem;
  line-height: 1;
  flex-shrink: 0;
  margin-top: 4px;
}
.privacy-card > div { flex: 1; }
.privacy-card .form-label { margin-bottom: 8px; }

.setup-final-cta {
  margin: 32px 0 0;
  background: linear-gradient(135deg, rgba(196,30,58,0.06), rgba(212,160,23,0.06));
  border: 1px solid rgba(212,160,23,0.3);
  border-radius: var(--radius-md);
  padding: 32px;
  text-align: center;
}
.final-cta-icon { font-size: 2.4rem; margin-bottom: 10px; }
.setup-final-cta h3 { color: var(--text); margin-bottom: 6px; font-size: 1.2rem; }
.setup-final-cta p { font-size: 0.92rem; margin: 0; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const uploadArea = document.getElementById('uploadArea');
  const fileInput  = document.getElementById('photoFiles');
  const previewGrid = document.getElementById('photoPreviewGrid');
  if (!uploadArea || !fileInput) return;

  let selectedFiles = [];
  const maxAdd = parseInt(fileInput.dataset.max || 5);

  uploadArea.addEventListener('click', () => fileInput.click());

  uploadArea.addEventListener('dragover', e => {
    e.preventDefault();
    uploadArea.classList.add('drag-over');
  });
  uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
  uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
    handleFiles(e.dataTransfer.files);
  });

  fileInput.addEventListener('change', () => handleFiles(fileInput.files));

  function handleFiles(files) {
    const available = maxAdd - selectedFiles.length;
    const toAdd = Array.from(files).slice(0, available);
    if (!toAdd.length) return;

    selectedFiles = [...selectedFiles, ...toAdd];
    renderPreviews();
    updateFileInput();
  }

  function renderPreviews() {
    previewGrid.innerHTML = '';
    if (!selectedFiles.length) {
      previewGrid.style.display = 'none';
      return;
    }
    previewGrid.style.display = 'grid';
    selectedFiles.forEach((file, i) => {
      const reader = new FileReader();
      reader.onload = ev => {
        const div = document.createElement('div');
        div.className = 'preview-item';
        div.innerHTML = `
          <img src="${ev.target.result}" alt="Preview">
          <button type="button" class="remove-btn" data-idx="${i}">✕</button>`;
        previewGrid.appendChild(div);
        div.querySelector('.remove-btn').addEventListener('click', () => {
          selectedFiles.splice(i, 1);
          renderPreviews();
          updateFileInput();
        });
      };
      reader.readAsDataURL(file);
    });
  }

  function updateFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
  }
});
</script>
@endsection
