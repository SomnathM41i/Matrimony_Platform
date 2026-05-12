@php
    $isEdit  = isset($plan) && $plan->exists;
    $action  = $isEdit ? route('admin.plans.update', $plan) : route('admin.plans.store');
    $method  = $isEdit ? 'PUT' : 'POST';
@endphp

<form method="POST" action="{{ $action }}">
    @csrf
    @method($method)

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

        {{-- ── LEFT COLUMN ── --}}
        <div>

            {{-- Basic Info --}}
            <div class="card" style="margin-bottom:1.5rem;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Plan Details</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label class="form-label">Plan Name <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $plan->name ?? '') }}"
                               placeholder="e.g. Gold Premium, Silver Standard…" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Brief description shown on the pricing page…">{{ old('description', $plan->description ?? '') }}</textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">

                        <div class="form-group">
                            <label class="form-label">Price <span style="color:var(--danger)">*</span></label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem;">₹</span>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $plan->price ?? '') }}"
                                       min="0" step="0.01" placeholder="0" required
                                       style="padding-left:1.75rem;">
                            </div>
                            @error('price')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Currency</label>
                            <select name="currency" class="form-control">
                                @foreach(['INR','USD','GBP','AED','CAD','AUD'] as $cur)
                                    <option value="{{ $cur }}" {{ old('currency', $plan->currency ?? 'INR') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Duration (days) <span style="color:var(--danger)">*</span></label>
                            <input type="number" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror"
                                   value="{{ old('duration_days', $plan->duration_days ?? 30) }}"
                                   min="1" required>
                            @error('duration_days')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

                        <div class="form-group">
                            <label class="form-label">Trial Days</label>
                            <input type="number" name="trial_days" class="form-control"
                                   value="{{ old('trial_days', $plan->trial_days ?? 0) }}"
                                   min="0" placeholder="0 = no trial">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                   value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
                                   min="0" placeholder="0 = first">
                        </div>
                    </div>

                    {{-- Status toggles --}}
                    <div style="display:flex;gap:1.5rem;margin-top:.5rem;">
                        <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;font-size:.85rem;color:var(--text-secondary);">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}
                                   style="accent-color:var(--rose);width:16px;height:16px;">
                            Active (visible on pricing page)
                        </label>
                        <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;font-size:.85rem;color:var(--text-secondary);">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1"
                                   {{ old('is_featured', $plan->is_featured ?? false) ? 'checked' : '' }}
                                   style="accent-color:var(--gold);width:16px;height:16px;">
                            Featured (highlighted on pricing page)
                        </label>
                    </div>

                </div>
            </div>

            {{-- Usage Limits --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-sliders"></i> Usage Limits</h3>
                    <span style="font-size:.75rem;color:var(--text-muted);">Total for full plan duration · 0 = Unlimited</span>
                </div>
                <div class="card-body">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

                        <div class="form-group">
                            <label class="form-label">Contact Views</label>
                            <input type="number" name="contact_views" class="form-control"
                                   value="{{ old('contact_views', $plan->contact_views ?? '') }}"
                                   min="0" placeholder="0 = Unlimited">
                            <small style="font-size:.7rem;color:var(--text-muted);">Total contacts viewable during plan</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Interests Limit</label>
                            <input type="number" name="interests_limit" class="form-control"
                                   value="{{ old('interests_limit', $plan->interests_limit ?? '') }}"
                                   min="0" placeholder="0 = Unlimited">
                            <small style="font-size:.7rem;color:var(--text-muted);">Total interests sendable during plan</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Messages Limit</label>
                            <input type="number" name="messages_limit" class="form-control"
                                   value="{{ old('messages_limit', $plan->messages_limit ?? '') }}"
                                   min="0" placeholder="0 = Unlimited">
                            <small style="font-size:.7rem;color:var(--text-muted);">Total messages sendable during plan</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Photo Gallery Limit</label>
                            <input type="number" name="photo_gallery_limit" class="form-control"
                                   value="{{ old('photo_gallery_limit', $plan->photo_gallery_limit ?? '') }}"
                                   min="0" placeholder="0 = Unlimited">
                            <small style="font-size:.7rem;color:var(--text-muted);">Max photos user can upload</small>
                        </div>
                    </div>

                </div>
            </div>

        </div>{{-- /left --}}

        {{-- ── RIGHT COLUMN ── --}}
        <div>

            {{-- Feature Toggles --}}
            <div class="card" style="margin-bottom:1.5rem;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-sparkles"></i> Feature Access</h3>
                </div>
                <div class="card-body">

                    @php
                        $toggleFeatures = [
                            ['field' => 'can_see_contact',        'label' => 'Can See Contact Details',       'icon' => 'fa-phone',          'desc' => 'Members can view full phone/email of matches'],
                            ['field' => 'can_see_full_horoscope', 'label' => 'Can See Full Horoscope',        'icon' => 'fa-star',           'desc' => 'Access to detailed kundali / horoscope data'],
                            ['field' => 'highlight_profile',      'label' => 'Profile Highlighted in Results','icon' => 'fa-highlighter',    'desc' => 'Profile shown with special highlight in search'],
                            ['field' => 'priority_in_search',     'label' => 'Priority in Search Results',    'icon' => 'fa-magnifying-glass','desc' => 'Ranked higher in search & match suggestions'],
                            ['field' => 'whatsapp_support',       'label' => 'WhatsApp Support',              'icon' => 'fa-whatsapp',       'desc' => 'Dedicated WhatsApp support channel access'],
                            ['field' => 'rm_assistance',          'label' => 'Relationship Manager Assistance','icon' => 'fa-user-tie',      'desc' => 'Personal RM assigned to help find matches'],
                        ];
                    @endphp

                    <div style="display:flex;flex-direction:column;gap:1rem;">
                        @foreach($toggleFeatures as $tf)
                            <label style="display:flex;align-items:flex-start;gap:.875rem;cursor:pointer;padding:.75rem;border-radius:10px;border:1px solid var(--border);transition:background .15s;"
                                   onmouseover="this.style.background='var(--bg-secondary)'"
                                   onmouseout="this.style.background='transparent'">
                                <input type="hidden" name="{{ $tf['field'] }}" value="0">
                                <input type="checkbox" name="{{ $tf['field'] }}" value="1"
                                       {{ old($tf['field'], $plan->{$tf['field']} ?? false) ? 'checked' : '' }}
                                       style="accent-color:var(--rose);width:16px;height:16px;margin-top:.15rem;flex-shrink:0;">
                                <div>
                                    <div style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;font-weight:600;color:var(--text-primary);">
                                        <i class="fas {{ $tf['icon'] }}" style="color:var(--rose);width:14px;"></i>
                                        {{ $tf['label'] }}
                                    </div>
                                    <div style="font-size:.73rem;color:var(--text-muted);margin-top:.15rem;">
                                        {{ $tf['desc'] }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                </div>
            </div>

            {{-- Extra Features --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list-check"></i> Extra Features</h3>
                    <span style="font-size:.75rem;color:var(--text-muted);">Shown as bullet points on pricing page</span>
                </div>
                <div class="card-body">

                    <div id="extraFeatures">
                        @php
                            $extras = old('extra_features', $plan->extra_features ?? ['']);
                        @endphp
                        @foreach($extras as $i => $feat)
                            <div class="extra-feat-row" style="display:flex;gap:.5rem;margin-bottom:.6rem;">
                                <input type="text" name="extra_features[]" class="form-control"
                                       value="{{ $feat }}" placeholder="e.g. 'Verified badge on profile'">
                                <button type="button" onclick="this.closest('.extra-feat-row').remove()"
                                        class="btn btn-ghost btn-sm" style="flex-shrink:0;color:var(--danger);">
                                    <i class="fas fa-xmark"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" onclick="addExtraFeature()" class="btn btn-outline btn-sm" style="margin-top:.25rem;">
                        <i class="fas fa-plus"></i> Add Feature
                    </button>

                </div>
            </div>

        </div>{{-- /right --}}

    </div>{{-- /grid --}}

    {{-- Submit bar --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:1.5rem;
                padding:1rem 1.5rem;background:var(--card-bg);border-radius:14px;
                border:1px solid var(--border);box-shadow:0 2px 12px var(--card-shadow);">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Plans
        </a>
        <button type="submit" class="btn btn-rose">
            <i class="fas {{ $isEdit ? 'fa-floppy-disk' : 'fa-plus' }}"></i>
            {{ $isEdit ? 'Save Changes' : 'Create Plan' }}
        </button>
    </div>

</form>

<script>
function addExtraFeature() {
    const wrap = document.getElementById('extraFeatures');
    const row  = document.createElement('div');
    row.className = 'extra-feat-row';
    row.style.cssText = 'display:flex;gap:.5rem;margin-bottom:.6rem;';
    row.innerHTML = `
        <input type="text" name="extra_features[]" class="form-control"
               placeholder="e.g. 'Verified badge on profile'">
        <button type="button" onclick="this.closest('.extra-feat-row').remove()"
                class="btn btn-ghost btn-sm" style="flex-shrink:0;color:var(--danger);">
            <i class="fas fa-xmark"></i>
        </button>
    `;
    wrap.appendChild(row);
    row.querySelector('input').focus();
}
</script>