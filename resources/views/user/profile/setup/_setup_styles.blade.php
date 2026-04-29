{{-- _setup_styles.blade.php — include at bottom of every setup step --}}
<style>
/* ── Setup Section Wrapper ── */
.setup-section {
  background: linear-gradient(135deg, var(--bg-light), #FFF8F8);
  min-height: calc(100vh - 72px);
  padding: 40px 0 80px;
}

/* ── Setup Card ── */
.setup-card {
  background: #fff;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
  border: 1px solid var(--border);
  padding: 40px 44px;
  max-width: 820px;
  margin: 0 auto;
}

.setup-card-header {
  display: flex;
  align-items: center;
  gap: 18px;
  margin-bottom: 36px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--border);
}
.setup-step-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: linear-gradient(135deg, rgba(196,30,58,0.08), rgba(196,30,58,0.16));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  flex-shrink: 0;
}
.setup-card-header h2 {
  font-size: 1.5rem;
  color: var(--text);
  margin-bottom: 4px;
}
.setup-card-header p {
  font-size: 0.9rem;
  margin: 0;
}

/* ── Form Sections ── */
.form-section {
  margin-bottom: 36px;
  padding-bottom: 32px;
  border-bottom: 1px solid var(--bg-soft);
}
.form-section:last-of-type { border-bottom: none; }

.form-section-title {
  font-size: 0.85rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--primary);
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: var(--font-body);
}

/* ── Form Row overrides ── */
.form-row-3 { grid-template-columns: repeat(3, 1fr); }
.form-row-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
@media (max-width: 768px) {
  .form-row-3, .form-row-4 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 480px) {
  .form-row-3, .form-row-4 { grid-template-columns: 1fr; }
}

/* ── Field error & hint ── */
.field-error {
  display: block;
  font-size: 0.82rem;
  color: #dc2626;
  margin-top: 5px;
}
.field-hint {
  display: block;
  font-size: 0.8rem;
  color: var(--text-light);
  margin-top: 5px;
  line-height: 1.5;
}
.is-invalid {
  border-color: #dc2626 !important;
  box-shadow: 0 0 0 3px rgba(220,38,38,0.1) !important;
}
.req { color: var(--primary); }

/* ── Checkbox chips ── */
.checkbox-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}
.checkbox-chip {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: var(--bg-light);
  border: 1.5px solid var(--border);
  border-radius: 100px;
  padding: 7px 16px;
  font-size: 0.88rem;
  color: var(--text-muted);
  cursor: pointer;
  transition: var(--transition);
  user-select: none;
}
.checkbox-chip:hover {
  border-color: var(--primary);
  color: var(--primary);
  background: rgba(196,30,58,0.04);
}
.checkbox-chip input { display: none; }
.checkbox-chip:has(input:checked) {
  background: linear-gradient(135deg, rgba(196,30,58,0.08), rgba(196,30,58,0.14));
  border-color: var(--primary);
  color: var(--primary);
  font-weight: 600;
}
/* Fallback for browsers without :has() */
.checkbox-chip input:checked ~ span {
  color: var(--primary);
  font-weight: 600;
}

/* ── Checkbox stack (vertical) ── */
.checkbox-stack {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  color: var(--text-muted);
  cursor: pointer;
}
.checkbox-label input[type="checkbox"] { accent-color: var(--primary); width: 16px; height: 16px; }

/* ── Setup Actions ── */
.setup-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 36px;
  padding-top: 28px;
  border-top: 1px solid var(--border);
}

/* ── Alerts ── */
.alert {
  padding: 14px 18px;
  border-radius: var(--radius-sm);
  margin-bottom: 24px;
  font-size: 0.92rem;
  line-height: 1.6;
}
.alert ul { margin: 6px 0 0 18px; }
.alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.alert-info    { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
.alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* ── Skip notice ── */
.skip-notice {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  background: rgba(212,160,23,0.07);
  border: 1px solid rgba(212,160,23,0.3);
  border-radius: var(--radius-sm);
  padding: 16px 18px;
  margin-bottom: 32px;
  font-size: 0.9rem;
  color: var(--text-muted);
}
.skip-notice-icon { font-size: 1.3rem; flex-shrink: 0; }
.skip-notice strong { color: var(--text); }

/* ── Upload drag-over state ── */
.upload-area.drag-over {
  border-color: var(--primary);
  background: rgba(196,30,58,0.04);
}

/* ── Responsive ── */
@media (max-width: 768px) {
  .setup-card { padding: 24px 20px; }
  .setup-card-header { gap: 12px; }
  .setup-step-icon { width: 48px; height: 48px; font-size: 1.4rem; }
  .setup-card-header h2 { font-size: 1.2rem; }
  .setup-actions { flex-wrap: wrap; gap: 12px; }
  .setup-actions .btn-lg { width: 100%; justify-content: center; }
}
</style>