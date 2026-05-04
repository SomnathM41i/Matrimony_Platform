{{-- resources/views/admin/users/assign-plan.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Assign Plan')

@section('content')

<style>
    .assign-plan-wrap { max-width: 680px; }

    .plan-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: .875rem;
        margin-top: .75rem;
    }
    .plan-option {
        border: 2px solid var(--border);
        border-radius: 14px;
        padding: 1rem 1rem .875rem;
        cursor: pointer;
        transition: all .2s ease;
        background: var(--card-bg);
        position: relative;
    }
    .plan-option:hover { border-color: var(--rose-light); background: var(--blush); }
    .plan-option.selected {
        border-color: var(--rose);
        background: var(--blush);
        box-shadow: 0 0 0 3px rgba(200,113,90,.12);
    }
    .plan-option input[type="radio"] { position:absolute; opacity:0; pointer-events:none; }
    .plan-option-check {
        position: absolute; top: .6rem; right: .6rem;
        width: 18px; height: 18px; border-radius: 50%;
        border: 2px solid var(--border); background: var(--card-bg);
        display: flex; align-items: center; justify-content: center;
        transition: all .2s ease; font-size: .6rem; color: #fff;
    }
    .plan-option.selected .plan-option-check { background: var(--rose-grad); border-color: var(--rose); }
    .plan-option-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1rem; font-weight: 700;
        color: var(--text-primary); margin-bottom: .2rem;
    }
    .plan-option-price { font-size: .75rem; font-weight: 700; color: var(--rose); margin-bottom: .1rem; }
    .plan-option-dur   { font-size: .7rem; color: var(--text-muted); }

    .expiry-preview {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .45rem 1rem; border-radius: 50px;
        background: rgba(74,140,106,.1); color: #4a8c6a;
        font-size: .78rem; font-weight: 600;
        border: 1px solid rgba(74,140,106,.2);
        transition: opacity .2s ease;
    }
    .expiry-preview.hidden { opacity: 0; pointer-events: none; }

    .current-sub-notice {
        display: flex; align-items: flex-start; gap: .875rem;
        padding: 1rem 1.125rem;
        background: rgba(201,168,108,.1);
        border: 1px solid rgba(201,168,108,.35);
        border-radius: 12px; margin-bottom: 1.5rem;
    }
    .current-sub-notice i { color: var(--gold-dark); font-size: 1rem; margin-top: .1rem; }
    .current-sub-notice strong { display:block; font-size:.8rem; font-weight:700; color:var(--text-primary); margin-bottom:.2rem; }
    .current-sub-notice span  { font-size:.78rem; color:var(--text-secondary); }

    .section-label {
        font-size: .7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.5px; color: var(--text-muted);
        margin-bottom: .5rem; display: block;
    }
    .date-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    @media (max-width: 500px) {
        .date-row            { grid-template-columns: 1fr; }
        .plan-card-grid      { grid-template-columns: 1fr 1fr; }
    }
</style>

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">Users › {{ $user->name }}</div>
            <h1 class="page-title">Assign <em>Plan</em></h1>
            <p class="page-subtitle">Manually assign a subscription plan to this member</p>
        </div>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>
</div>

<div class="assign-plan-wrap">

    {{-- Current subscription warning --}}
    @if($activeSubscription)
    <div class="current-sub-notice">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Active subscription will be replaced</strong>
            <span>
                Current plan: <strong>{{ $activeSubscription->package->name ?? '—' }}</strong>
                &nbsp;·&nbsp; expires
                <strong>{{ $activeSubscription->expires_at?->format('d M Y') ?? 'N/A' }}</strong>
            </span>
        </div>
    </div>
    @endif

    {{-- User pill row --}}
    <div class="pill-row">
        <div class="pill"><i class="fas fa-user"></i><strong>{{ $user->name }}</strong></div>
        <div class="pill"><i class="fas fa-envelope"></i>{{ $user->email }}</div>
        @if($user->is_premium)
            <span class="badge badge-premium"><i class="fas fa-crown"></i>&nbsp;Premium</span>
        @else
            <span class="badge badge-free">Free</span>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-crown"></i> Subscription Plan</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.assign-plan.post', $user) }}" method="POST">
                @csrf

                {{-- ── Plan selector ── --}}
                <div class="form-group">
                    <label class="section-label">
                        Select Plan <span style="color:var(--danger)">*</span>
                    </label>

                    @error('plan_id')
                        <p class="form-error" style="margin-bottom:.5rem;">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror

                    @if($plans->isEmpty())
                        <p style="font-size:.85rem;color:var(--text-muted);padding:.5rem 0;">
                            No active plans found.
                            <a href="{{ route('admin.plans.create') }}" style="color:var(--rose);">Create one →</a>
                        </p>
                    @else
                        <div class="plan-card-grid" id="planGrid">
                            @foreach($plans as $plan)
                            <label class="plan-option {{ old('plan_id') == $plan->id ? 'selected' : '' }}"
                                   for="plan_{{ $plan->id }}"
                                   data-duration="{{ $plan->duration_days }}">
                                <input type="radio" name="plan_id" id="plan_{{ $plan->id }}"
                                       value="{{ $plan->id }}"
                                       {{ old('plan_id') == $plan->id ? 'checked' : '' }}>
                                <div class="plan-option-check">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="plan-option-name">{{ $plan->name }}</div>
                                <div class="plan-option-price">
                                    @if($plan->price > 0)
                                        ₹{{ number_format($plan->price, 0) }}
                                    @else
                                        Free
                                    @endif
                                </div>
                                <div class="plan-option-dur">{{ $plan->duration_days }} days</div>
                            </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                <hr class="divider">

                {{-- ── Dates ── --}}
                <div class="form-group">
                    <div class="date-row">
                        <div>
                            <label class="section-label" for="start_date">
                                Start Date <span style="color:var(--danger)">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date"
                                   value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                   class="form-control" required>
                            @error('start_date')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="section-label">Expiry Date</label>
                            <div style="display:flex;align-items:center;padding-top:.5rem;">
                                <span class="expiry-preview hidden" id="expiryPreview">
                                    <i class="fas fa-calendar-check" style="font-size:.7rem;"></i>
                                    <span id="expiryText"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="divider">

                {{-- ── Note ── --}}
                <div class="form-group">
                    <label class="section-label" for="note">
                        Admin Note
                        <span style="color:var(--text-light);font-weight:400;text-transform:none;letter-spacing:0;font-size:.7rem;"> (optional)</span>
                    </label>
                    <textarea id="note" name="note" rows="3" maxlength="500"
                              class="form-control"
                              placeholder="e.g. Complimentary plan, gifted by support team…">{{ old('note') }}</textarea>
                    @error('note')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Actions ── --}}
                <div class="form-actions">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-rose">
                        <i class="fas fa-crown"></i> Assign Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>{{-- /assign-plan-wrap --}}

@push('scripts')
<script>
(function () {
    const grid       = document.getElementById('planGrid');
    const startInput = document.getElementById('start_date');
    const preview    = document.getElementById('expiryPreview');
    const previewTxt = document.getElementById('expiryText');

    function selectedDuration() {
        const sel = grid && grid.querySelector('.plan-option.selected');
        return sel ? parseInt(sel.dataset.duration || 0, 10) : 0;
    }

    function updateExpiry() {
        const dur = selectedDuration(), start = startInput.value;
        if (!dur || !start) { preview.classList.add('hidden'); return; }
        const d = new Date(start);
        d.setDate(d.getDate() + dur);
        previewTxt.textContent = d.toLocaleDateString('en-IN', { day:'2-digit', month:'short', year:'numeric' });
        preview.classList.remove('hidden');
    }

    if (grid) {
        grid.querySelectorAll('.plan-option').forEach(function (label) {
            label.addEventListener('click', function () {
                grid.querySelectorAll('.plan-option').forEach(function (l) { l.classList.remove('selected'); });
                label.classList.add('selected');
                label.querySelector('input[type="radio"]').checked = true;
                updateExpiry();
            });
        });
    }

    startInput.addEventListener('change', updateExpiry);
    updateExpiry();
})();
</script>
@endpush

@endsection