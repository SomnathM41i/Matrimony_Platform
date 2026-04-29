{{--
  _progress.blade.php
  Reusable step-progress header for profile setup.
  Pass: $step (int 1–7)
--}}

@php
$stepLabels = [
    1 => ['icon' => '👤', 'label' => 'Basic Info'],
    2 => ['icon' => '🛕', 'label' => 'Religion'],
    3 => ['icon' => '🌙', 'label' => 'Horoscope'],
    4 => ['icon' => '🎓', 'label' => 'Education'],
    5 => ['icon' => '🏠', 'label' => 'Location'],
    6 => ['icon' => '💑', 'label' => 'Partner Pref'],
    7 => ['icon' => '📷', 'label' => 'Photos'],
];
@endphp

<div class="setup-progress-header">
  <div class="container">
    <div class="setup-top-row">
      <div class="setup-title">
        <h1>Complete Your Profile</h1>
        <p>Step {{ $step }} of 7 — {{ $stepLabels[$step]['label'] }}</p>
      </div>
      <div class="setup-completion">
        <div class="completion-ring" style="--pct: {{ round(($step - 1) / 7 * 100) }}%">
          <span>{{ round(($step - 1) / 7 * 100) }}%</span>
        </div>
        <span class="completion-label">Complete</span>
      </div>
    </div>

    <div class="setup-steps-track">
      @foreach ($stepLabels as $n => $info)
        <div class="setup-step-dot
          {{ $n < $step ? 'done' : ($n == $step ? 'active' : 'pending') }}">
          <div class="dot-circle">
            @if ($n < $step)
              ✓
            @else
              {{ $info['icon'] }}
            @endif
          </div>
          <span class="dot-label">{{ $info['label'] }}</span>
        </div>
        @if ($n < 7)
          <div class="step-connector {{ $n < $step ? 'done' : '' }}"></div>
        @endif
      @endforeach
    </div>
  </div>
</div>

<style>
.setup-progress-header {
  background: linear-gradient(135deg, var(--primary-dark) 0%, #1A1A2E 100%);
  padding: 32px 0 28px;
  position: relative;
  overflow: hidden;
}
.setup-progress-header::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 80% 50%, rgba(196,30,58,0.25), transparent 70%);
}
.setup-progress-header .container { position: relative; z-index: 1; }

.setup-top-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 28px;
  gap: 16px;
}
.setup-title h1 {
  color: #fff;
  font-size: 1.6rem;
  margin-bottom: 4px;
}
.setup-title p {
  color: rgba(255,255,255,0.6);
  font-size: 0.9rem;
  margin: 0;
}

.setup-completion {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  flex-shrink: 0;
}
.completion-ring {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: conic-gradient(var(--gold) var(--pct), rgba(255,255,255,0.15) var(--pct));
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
.completion-ring::before {
  content: '';
  position: absolute;
  inset: 6px;
  border-radius: 50%;
  background: #1A1A2E;
}
.completion-ring span {
  position: relative;
  z-index: 1;
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--gold-light);
}
.completion-label {
  font-size: 0.7rem;
  color: rgba(255,255,255,0.5);
}

.setup-steps-track {
  display: flex;
  align-items: center;
  overflow-x: auto;
  scrollbar-width: none;
  padding-bottom: 4px;
}
.setup-steps-track::-webkit-scrollbar { display: none; }

.setup-step-dot {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  flex-shrink: 0;
}
.dot-circle {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  border: 2px solid rgba(255,255,255,0.2);
  background: rgba(255,255,255,0.08);
  color: rgba(255,255,255,0.5);
  transition: all 0.3s ease;
}
.setup-step-dot.done .dot-circle {
  background: rgba(255,255,255,0.2);
  border-color: rgba(255,255,255,0.5);
  color: #fff;
  font-size: 0.9rem;
  font-weight: 700;
}
.setup-step-dot.active .dot-circle {
  background: var(--gold);
  border-color: var(--gold);
  color: #1A1A2E;
  font-size: 0.85rem;
  box-shadow: 0 0 0 4px rgba(212,160,23,0.3);
}
.dot-label {
  font-size: 0.65rem;
  color: rgba(255,255,255,0.4);
  white-space: nowrap;
}
.setup-step-dot.active .dot-label { color: rgba(255,255,255,0.85); }
.setup-step-dot.done .dot-label { color: rgba(255,255,255,0.6); }

.step-connector {
  flex: 1;
  min-width: 20px;
  height: 2px;
  background: rgba(255,255,255,0.12);
  margin: 0 4px;
  margin-bottom: 26px;
  transition: background 0.3s ease;
}
.step-connector.done { background: rgba(255,255,255,0.4); }
</style>