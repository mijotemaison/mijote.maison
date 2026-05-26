document.addEventListener('DOMContentLoaded', () => {
  const search = document.querySelector('[data-recipe-search]');
  const cards = Array.from(document.querySelectorAll('[data-recipe-card]'));
  const empty = document.querySelector('[data-recipe-empty]');

  if (!search || cards.length === 0) return;

  function normalize(value) {
    return value.toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '');
  }

  function render() {
    const query = normalize(search.value.trim());
    let visible = 0;

    cards.forEach((card) => {
      const haystack = normalize(card.dataset.search || '');
      const matchQuery = query === '' || haystack.includes(query);
      card.classList.toggle('d-none', !matchQuery);
      if (matchQuery) visible += 1;
    });

    if (empty) empty.classList.toggle('d-none', visible > 0);
  }

  search.addEventListener('input', render);
  render();
});
