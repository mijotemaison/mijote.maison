document.addEventListener('DOMContentLoaded', () => {
  const search = document.querySelector('[data-recipe-search]');
  const chips = Array.from(document.querySelectorAll('[data-recipe-filter]'));
  const cards = Array.from(document.querySelectorAll('[data-recipe-card]'));
  const empty = document.querySelector('[data-recipe-empty]');

  if (!search || cards.length === 0) return;

  const currentParams = new URLSearchParams(window.location.search);
  let activeCategory = currentParams.get('category') || 'all';
  if (activeCategory !== 'all' && !chips.some((chip) => chip.dataset.recipeFilter === activeCategory)) {
    activeCategory = 'all';
  }

  function normalize(value) {
    return value.toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '');
  }

  function render() {
    const query = normalize(search.value.trim());
    let visible = 0;

    cards.forEach((card) => {
      const haystack = normalize(card.dataset.search || '');
      const category = card.dataset.category || '';
      const matchQuery = query === '' || haystack.includes(query);
      const matchCategory = activeCategory === 'all' || category === activeCategory;
      const show = matchQuery && matchCategory;
      card.classList.toggle('d-none', !show);
      if (show) visible += 1;
    });

    if (empty) empty.classList.toggle('d-none', visible > 0);
  }

  chips.forEach((chip) => {
    chip.addEventListener('click', (event) => {
      event.preventDefault();
      activeCategory = chip.dataset.recipeFilter || 'all';

      const url = new URL('/recettes', window.location.origin);
      const query = search.value.trim();
      if (query !== '') {
        url.searchParams.set('q', query);
      }
      if (activeCategory !== 'all') {
        url.searchParams.set('category', activeCategory);
      }

      chips.forEach((item) => {
        const active = item === chip;
        item.classList.toggle('btn-primary', active);
        item.classList.toggle('active', active);
        item.classList.toggle('btn-outline-secondary', !active);
      });
      render();
      window.location.href = url.toString();
    });
  });

  search.addEventListener('input', render);
  render();
});
