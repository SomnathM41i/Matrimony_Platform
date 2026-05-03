
@foreach($parents as $parent)
    @php
        $isDependent = isset($parent['depends_on']);
        $currentVal  = $record ? ($record->{$parent['key']} ?? '') : '';
    @endphp
    <div class="form-group">
        <label class="form-label">
            {{ $parent['label'] }}
            @if($parent['required'] ?? false)
                <span style="color:var(--danger)">*</span>
            @endif
        </label>

        <div class="select-wrap">
            <select
                name="{{ $parent['key'] }}"
                id="{{ $formId }}_{{ $parent['key'] }}"
                class="form-control"
                {{ ($parent['required'] ?? false) ? 'required' : '' }}
                @if($isDependent)
                    data-depends="{{ $parent['depends_on'] }}"
                    data-parent-type="{{ $parent['model'] }}"
                    disabled
                @else
                    data-cascade-for="{{ $parent['cascade_for'] ?? '' }}"
                    data-cascade-url="{{ url('/admin/api/lookups/'.$parent['model']) }}"
                    onchange="handleParentChange(this, '{{ $formId }}')"
                @endif
            >
                <option value="">— Select {{ $parent['label'] }} —</option>
                {{-- Options loaded via AJAX / JS prefill --}}
            </select>
            <i class="fas fa-chevron-down select-arrow"></i>
        </div>

        @if($isDependent)
        <p class="form-hint">
            <i class="fas fa-circle-info"></i>
            Select {{ collect($parents)->firstWhere('cascade_for', $parent['key'])['label'] ?? 'parent' }} first
        </p>
        @endif
    </div>
@endforeach


{{-- ── PRIMARY NAME FIELD ───────────────────────────────────── --}}
@if($primaryField === 'name')
<div class="form-group">
    <label class="form-label">
        Name <span style="color:var(--danger)">*</span>
    </label>
    <input
        type="text"
        name="name"
        class="form-control"
        required
        placeholder="Enter name…"
        value="{{ old('name', $record?->name ?? '') }}"
        autocomplete="off"
    >
    @error('name')
        <p class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
    @enderror
</div>
@endif


{{-- ── EXTRA TYPE-SPECIFIC FIELDS ──────────────────────────── --}}
@foreach($extraFields as $field)
    @if($field['key'] === $primaryField) @continue @endif
    @php
        $fieldVal = old($field['key'], $record?->{$field['key']} ?? ($field['default'] ?? ''));
    @endphp
    <div class="form-group">
        <label class="form-label">
            {{ $field['label'] }}
            @if($field['required'] ?? false)
                <span style="color:var(--danger)">*</span>
            @endif
        </label>

        @if(($field['type'] ?? 'text') === 'textarea')
            <textarea
                name="{{ $field['key'] }}"
                class="form-control"
                rows="3"
                placeholder="{{ $field['label'] }}…"
                {{ ($field['required'] ?? false) ? 'required' : '' }}
            >{{ $fieldVal }}</textarea>
        @else
            <input
                type="{{ $field['type'] ?? 'text' }}"
                name="{{ $field['key'] }}"
                class="form-control"
                placeholder="{{ $field['label'] }}…"
                value="{{ $fieldVal }}"
                {{ ($field['required'] ?? false) ? 'required' : '' }}
                @if(($field['type'] ?? '') === 'number') min="0" @endif
            >
        @endif

        @error($field['key'])
            <p class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
        @enderror
    </div>
@endforeach


{{-- ── LABEL FIELD (for income ranges) ────────────────────── --}}
@if($primaryField === 'label')
<div class="form-group">
    <label class="form-label">
        Label <span style="color:var(--danger)">*</span>
    </label>
    <input
        type="text"
        name="label"
        class="form-control"
        required
        placeholder="e.g. ₹5L – ₹10L per annum"
        value="{{ old('label', $record?->label ?? '') }}"
    >
    @error('label')
        <p class="form-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
    @enderror
</div>

{{-- Income range: min / max side-by-side --}}
@if($type === 'annual-income-ranges')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
    <div class="form-group">
        <label class="form-label">Min Value (₹) <span style="color:var(--danger)">*</span></label>
        <input type="number" name="min_value" class="form-control" required min="0"
            placeholder="0"
            value="{{ old('min_value', $record?->min_value ?? 0) }}">
        @error('min_value')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Max Value (₹)</label>
        <input type="number" name="max_value" class="form-control" min="0"
            placeholder="Leave blank for unlimited"
            value="{{ old('max_value', $record?->max_value ?? '') }}">
    </div>
</div>
<div class="form-group">
    <label class="form-label">Currency</label>
    <div class="select-wrap">
        <select name="currency" class="form-control">
            @foreach(['INR','USD','GBP','EUR','AED','CAD','AUD'] as $cur)
                <option value="{{ $cur }}" {{ old('currency', $record?->currency ?? 'INR') === $cur ? 'selected' : '' }}>
                    {{ $cur }}
                </option>
            @endforeach
        </select>
        <i class="fas fa-chevron-down select-arrow"></i>
    </div>
</div>
@endif
@endif


{{-- ── SORT ORDER (if this type has it) ────────────────────── --}}
@if(in_array('sort_order', $columns))
<div class="form-group">
    <label class="form-label">Sort Order</label>
    <input
        type="number"
        name="sort_order"
        class="form-control"
        min="0"
        placeholder="0 = default"
        value="{{ old('sort_order', $record?->sort_order ?? 0) }}"
        style="max-width:140px;"
    >
    <p class="form-hint"><i class="fas fa-circle-info"></i> Lower numbers appear first</p>
</div>
@endif


{{-- ── IS ACTIVE TOGGLE ─────────────────────────────────────── --}}
<div class="form-group" style="margin-bottom:0;">
    <label class="form-label">Status</label>
    <div class="toggle-row">
        <label class="toggle-switch">
            <input type="hidden" name="is_active" value="0">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                id="{{ $formId }}_is_active"
                {{ old('is_active', $record?->is_active ?? true) ? 'checked' : '' }}
                onchange="
                    this.previousElementSibling.value = this.checked ? '1' : '0';
                    this.closest('.toggle-row').querySelector('.toggle-label').textContent = this.checked ? 'Active' : 'Inactive';
                "
            >
            <span class="toggle-slider"></span>
        </label>
        <span class="toggle-label">
            {{ old('is_active', $record?->is_active ?? true) ? 'Active' : 'Inactive' }}
        </span>
    </div>
</div>


{{-- ── STYLES (scoped to form) ──────────────────────────────── --}}
<style>
.select-wrap {
    position: relative;
}
.select-wrap .form-control {
    appearance: none;
    padding-right: 2.25rem;
    cursor: pointer;
}
.select-arrow {
    position: absolute; right: .875rem; top: 50%;
    transform: translateY(-50%);
    pointer-events: none; color: var(--text-muted); font-size: .75rem;
}
.form-hint {
    font-size: .72rem; color: var(--text-muted);
    margin-top: .3rem; display: flex; align-items: center; gap: .3rem;
}
.form-hint i { font-size: .65rem; }
</style>