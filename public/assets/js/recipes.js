document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-print-recipe]').forEach((button) => {
    button.addEventListener('click', () => window.print());
  });

  const search = document.querySelector('[data-recipe-search]');
  const chips = Array.from(document.querySelectorAll('[data-recipe-filter]'));
  const cards = Array.from(document.querySelectorAll('[data-recipe-card]'));
  const empty = document.querySelector('[data-recipe-empty]');

  if (!search || cards.length === 0) return;

  let activeCategory = 'all';

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
      card.classList.toggle('hidden', !show);
      if (show) visible += 1;
    });

    if (empty) empty.classList.toggle('hidden', visible > 0);
  }

  chips.forEach((chip) => {
    chip.addEventListener('click', () => {
      activeCategory = chip.dataset.recipeFilter || 'all';
      chips.forEach((item) => {
        const active = item === chip;
        item.classList.toggle('bg-tomato', active);
        item.classList.toggle('text-white', active);
        item.classList.toggle('border-tomato', active);
        item.classList.toggle('bg-white', !active);
        item.classList.toggle('text-stone-700', !active);
        item.classList.toggle('border-orange-200', !active);
      });
      render();
    });
  });

  search.addEventListener('input', render);
  render();
});
