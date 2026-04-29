/* ============================================
    — Main JavaScript
============================================ */

document.addEventListener('DOMContentLoaded', () => {

  // ── Loading Screen ──
  const loader = document.getElementById('loader');
  if (loader) {
    window.addEventListener('load', () => {
      setTimeout(() => loader.classList.add('hidden'), 600);
    });
  }

  // ── Mobile Menu ──
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      mobileMenu.classList.toggle('open');
      const spans = hamburger.querySelectorAll('span');
      if (mobileMenu.classList.contains('open')) {
        spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
        spans[1].style.opacity = '0';
        spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
      } else {
        spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
      }
    });
  }

  // ── Scroll to Top ──
  const scrollBtn = document.getElementById('scrollTop');
  if (scrollBtn) {
    window.addEventListener('scroll', () => {
      scrollBtn.classList.toggle('visible', window.scrollY > 400);
    });
    scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  // ── Active Nav Link ──
  const page = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a, .mobile-menu a').forEach(link => {
    const href = link.getAttribute('href');
    if (href === page || (page === '' && href === 'index.html')) {
      link.classList.add('active');
    }
  });

  // ── FAQ Accordion ──
  document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
      if (!isOpen) item.classList.add('open');
    });
  });

  // ── Counter Animation ──
  const counters = document.querySelectorAll('[data-count]');
  if (counters.length) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const target = parseInt(el.dataset.count);
          const suffix = el.dataset.suffix || '';
          let current = 0;
          const step = target / 60;
          const timer = setInterval(() => {
            current += step;
            if (current >= target) {
              current = target;
              clearInterval(timer);
            }
            el.textContent = Math.floor(current).toLocaleString() + suffix;
          }, 25);
          observer.unobserve(el);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(c => observer.observe(c));
  }

  // ── Scroll Reveal ──
  const revealElements = document.querySelectorAll('.reveal');
  if (revealElements.length) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    revealElements.forEach(el => revealObserver.observe(el));
  }

  // ── Multi-step Registration ──
  const formSteps = document.querySelectorAll('.form-step');
  const progSteps = document.querySelectorAll('.prog-step');
  let currentStep = 0;

  const goToStep = (n) => {
    formSteps.forEach((s, i) => s.classList.toggle('active', i === n));
    progSteps.forEach((s, i) => {
      s.classList.toggle('active', i === n);
      s.classList.toggle('completed', i < n);
    });
    currentStep = n;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  document.querySelectorAll('[data-next]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (validateStep(currentStep)) {
        goToStep(currentStep + 1);
      }
    });
  });
  document.querySelectorAll('[data-prev]').forEach(btn => {
    btn.addEventListener('click', () => goToStep(currentStep - 1));
  });

  // ── Form Validation ──
  const validateStep = (step) => {
    const active = document.querySelector('.form-step.active');
    if (!active) return true;
    let valid = true;
    active.querySelectorAll('[required]').forEach(field => {
      if (!field.value.trim()) {
        field.style.borderColor = 'var(--primary)';
        field.style.boxShadow = '0 0 0 3px rgba(196,30,58,0.1)';
        valid = false;
        setTimeout(() => {
          field.style.borderColor = '';
          field.style.boxShadow = '';
        }, 2000);
      }
    });
    if (!valid) showToast('Please fill all required fields.', 'error');
    return valid;
  };

  // ── Contact Form ──
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast('✅ Message sent! We\'ll get back to you soon.', 'success');
      contactForm.reset();
    });
  }

  // ── Login Form ──
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const email = loginForm.querySelector('[name="email"]').value;
      const pass = loginForm.querySelector('[name="password"]').value;
      if (!email || !pass) {
        showToast('Please enter your credentials.', 'error');
        return;
      }
      showToast('🔐 Logging in...', 'info');
    });
  }

  // ── Register Final Submit ──
  const registerSubmit = document.getElementById('registerSubmit');
  if (registerSubmit) {
    registerSubmit.addEventListener('click', () => {
      const terms = document.getElementById('terms');
      if (terms && !terms.checked) {
        showToast('Please accept Terms & Conditions.', 'error');
        return;
      }
      showToast('🎉 Registration submitted! Check your email.', 'success');
    });
  }

  // ── File Upload Preview ──
  const fileInput = document.getElementById('profilePhoto');
  const uploadArea = document.getElementById('uploadArea');
  if (fileInput && uploadArea) {
    uploadArea.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          uploadArea.innerHTML = `<img src="${ev.target.result}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
            <p style="margin-top:12px;font-size:0.85rem;color:var(--primary);">✓ Photo selected</p>`;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // ── Toast Notification ──
  window.showToast = (msg, type = 'success') => {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();
    const toast = document.createElement('div');
    toast.className = 'toast';
    const colors = {
      success: 'var(--primary)',
      error: '#dc2626',
      info: 'var(--accent)'
    };
    toast.style.cssText = `
      position:fixed;bottom:90px;right:32px;background:${colors[type]};
      color:#fff;padding:14px 24px;border-radius:12px;font-size:0.9rem;
      font-weight:500;z-index:9998;box-shadow:0 8px 32px rgba(0,0,0,0.2);
      animation:fadeInUp 0.3s ease;max-width:340px;line-height:1.5;
      font-family:'DM Sans',sans-serif;
    `;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
  };

  // ── Search Form ──
  const searchForm = document.getElementById('searchForm');
  if (searchForm) {
    searchForm.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast('🔍 Searching profiles...', 'info');
    });
  }

  // ── Package Buy Now ──
  document.querySelectorAll('.btn-buy').forEach(btn => {
    btn.addEventListener('click', () => {
      const plan = btn.dataset.plan;
      showToast(`💳 Redirecting to ${plan} plan checkout...`, 'info');
    });
  });

  // ── Password Toggle ──
  document.querySelectorAll('.toggle-pass').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.previousElementSibling;
      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
      } else {
        input.type = 'password';
        btn.textContent = '👁️';
      }
    });
  });

  if (formSteps.length > 0) goToStep(0);
});
