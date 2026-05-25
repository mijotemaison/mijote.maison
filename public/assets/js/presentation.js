document.addEventListener('DOMContentLoaded', () => {
  const deck = document.querySelector('[data-deck]');
  if (!deck) return;

  const slides = Array.from(deck.querySelectorAll('[data-slide]'));
  const prev = document.querySelector('[data-prev]');
  const next = document.querySelector('[data-next]');
  const counter = document.querySelector('[data-counter]');
  const progress = document.querySelector('[data-progress]');
  let index = 0;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  let resizeTimer = null;

  // a11y : annonce changements de slide
  if (counter) {
    counter.setAttribute('aria-live', 'polite');
    counter.setAttribute('aria-atomic', 'true');
  }

  slides.forEach((slide) => {
    slide.setAttribute('tabindex', '-1');
  });

  function slideHeight(slide) {
    const wasHidden = slide.classList.contains('d-none');
    const hadGrid = slide.classList.contains('d-grid');
    const previous = {
      position: slide.style.position,
      visibility: slide.style.visibility,
      pointerEvents: slide.style.pointerEvents,
      inset: slide.style.inset,
      width: slide.style.width
    };

    slide.classList.remove('d-none');
    slide.classList.add('d-grid');
    slide.style.position = 'absolute';
    slide.style.visibility = 'hidden';
    slide.style.pointerEvents = 'none';
    slide.style.inset = '0';
    slide.style.width = '100%';

    const height = slide.scrollHeight;

    slide.style.position = previous.position;
    slide.style.visibility = previous.visibility;
    slide.style.pointerEvents = previous.pointerEvents;
    slide.style.inset = previous.inset;
    slide.style.width = previous.width;
    slide.classList.toggle('d-none', wasHidden);
    slide.classList.toggle('d-grid', hadGrid);

    return height;
  }

  function syncDeckHeight() {
    if (!slides.length) return;
    const maxHeight = Math.max(...slides.map(slideHeight));
    deck.style.minHeight = `${maxHeight}px`;
  }

  function preservePagePosition(y) {
    window.requestAnimationFrame(() => {
      window.scrollTo({ top: y, left: window.scrollX, behavior: 'auto' });
    });
  }

  function show(i, preserveScroll = false) {
    const y = window.scrollY;
    slides.forEach((slide, k) => {
      const active = k === i;
      slide.classList.toggle('d-none', !active);
      slide.classList.toggle('d-grid', active);
      if (active) {
        slide.classList.remove('is-leaving');
      }
    });
    if (counter) counter.textContent = `Slide ${i + 1} / ${slides.length}`;
    if (progress) progress.value = i + 1;
    const active = slides[i];
    if (active && document.activeElement && active.contains(document.activeElement) === false) {
      // léger focus management — uniquement si l'utilisateur n'est pas sur un input
      const tag = (document.activeElement.tagName || '').toLowerCase();
      if (tag !== 'input' && tag !== 'textarea' && tag !== 'select') {
        active.focus({ preventScroll: true });
      }
    }
    if (preserveScroll) {
      preservePagePosition(y);
    }
  }

  function go(delta) {
    const target = Math.min(Math.max(index + delta, 0), slides.length - 1);
    if (target === index) return;
    if (reduce) {
      index = target;
      show(index, true);
      return;
    }
    const current = slides[index];
    if (current) current.classList.add('is-leaving');
    window.setTimeout(() => {
      index = target;
      show(index, true);
    }, 200);
  }

  prev?.addEventListener('click', (event) => {
    event.preventDefault();
    go(-1);
  });
  next?.addEventListener('click', (event) => {
    event.preventDefault();
    go(1);
  });
  document.addEventListener('keydown', (event) => {
    const tag = (document.activeElement?.tagName || '').toLowerCase();
    if (tag === 'input' || tag === 'textarea') return;
    if (event.key === 'ArrowLeft') go(-1);
    if (event.key === 'ArrowRight') go(1);
  });

  syncDeckHeight();
  show(0);

  window.addEventListener('resize', () => {
    window.clearTimeout(resizeTimer);
    resizeTimer = window.setTimeout(() => {
      const y = window.scrollY;
      syncDeckHeight();
      preservePagePosition(y);
    }, 120);
  });

  /* ============ Mode présentateur (toggle + chrono + plein écran) ============ */

  const bar = document.querySelector('[data-presenter-bar]');
  if (!bar) return;

  const STORAGE_KEY = 'mijote-presenter-state';
  const presenterToggle = bar.querySelector('[data-presenter-toggle]');
  const fullscreenBtn = bar.querySelector('[data-presenter-fullscreen]');
  const timerEl = bar.querySelector('[data-presenter-timer]');
  const resetBtn = bar.querySelector('[data-presenter-reset]');

  let timerStart = null;
  let timerRaf = null;

  function loadState() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return { presenter: false, timerStart: null };
      const parsed = JSON.parse(raw);
      return {
        presenter: !!parsed.presenter,
        timerStart: parsed.timerStart || null
      };
    } catch (e) {
      return { presenter: false, timerStart: null };
    }
  }

  function saveState() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify({
        presenter: document.body.classList.contains('is-presenter'),
        timerStart: timerStart
      }));
    } catch (e) { /* localStorage indispo : silencieux */ }
  }

  function formatTime(ms) {
    const total = Math.floor(ms / 1000);
    const m = Math.floor(total / 60).toString().padStart(2, '0');
    const s = (total % 60).toString().padStart(2, '0');
    return `${m}:${s}`;
  }

  function tickTimer() {
    if (!timerStart || !timerEl) return;
    timerEl.textContent = formatTime(Date.now() - timerStart);
    timerRaf = window.setTimeout(tickTimer, 500);
  }

  function startTimerIfNeeded() {
    if (timerStart) return;
    timerStart = Date.now();
    saveState();
    tickTimer();
  }

  function resetTimer() {
    if (timerRaf) window.clearTimeout(timerRaf);
    timerRaf = null;
    timerStart = null;
    if (timerEl) timerEl.textContent = '00:00';
    saveState();
  }

  function setPresenter(on) {
    const y = window.scrollY;
    document.body.classList.toggle('is-presenter', !!on);
    if (presenterToggle) presenterToggle.setAttribute('aria-pressed', on ? 'true' : 'false');
    syncDeckHeight();
    preservePagePosition(y);
    saveState();
  }

  // restaure
  const state = loadState();
  setPresenter(state.presenter);
  if (state.timerStart) {
    timerStart = state.timerStart;
    tickTimer();
  }

  presenterToggle?.addEventListener('click', () => {
    setPresenter(!document.body.classList.contains('is-presenter'));
  });

  fullscreenBtn?.addEventListener('click', () => {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen?.();
    } else {
      document.exitFullscreen?.();
    }
  });

  resetBtn?.addEventListener('click', () => {
    resetTimer();
  });

  // démarre le chrono dès la première navigation
  prev?.addEventListener('click', startTimerIfNeeded);
  next?.addEventListener('click', startTimerIfNeeded);
  document.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') startTimerIfNeeded();
  });
});

document.addEventListener('click', async (event) => {
  const button = event.target.closest('[data-copy-code]');
  if (!button) return;

  const code = document.getElementById(button.dataset.copyCode);
  if (!code) return;

  try {
    await navigator.clipboard.writeText(code.textContent.trim());
    const previous = button.textContent;
    button.textContent = 'Copié';
    setTimeout(() => {
      button.textContent = previous;
    }, 1400);
  } catch {
    button.textContent = 'Sélectionnez le code';
  }
});
