document.addEventListener('DOMContentLoaded', () => {
  const deck = document.querySelector('[data-deck]');
  if (!deck) return;

  const slides = Array.from(deck.querySelectorAll('[data-slide]'));
  const prev = document.querySelector('[data-prev]');
  const next = document.querySelector('[data-next]');
  const counter = document.querySelector('[data-counter]');
  const progress = document.querySelector('[data-progress]');
  let index = 0;

  function render() {
    slides.forEach((slide, i) => {
      slide.classList.toggle('hidden', i !== index);
      slide.classList.toggle('grid', i === index);
    });
    if (counter) counter.textContent = `Slide ${index + 1} / ${slides.length}`;
    if (progress) progress.value = index + 1;
  }

  function go(delta) {
    index = Math.min(Math.max(index + delta, 0), slides.length - 1);
    render();
  }

  prev?.addEventListener('click', () => go(-1));
  next?.addEventListener('click', () => go(1));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft') go(-1);
    if (event.key === 'ArrowRight') go(1);
  });

  render();
});
