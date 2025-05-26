// Listings Search and Filter Functionality
function setupListingsFilter() {
  const searchInput = document.getElementById('searchInput');
  const searchButton = document.getElementById('searchButton');
  const categoryFilter = document.getElementById('categoryFilter');
  const itemCards = document.querySelectorAll('.item-card');
  // Add no-items message
  const noItemsMessage = document.createElement('p');
  noItemsMessage.className = 'no-items-message';
  noItemsMessage.textContent = 'No items found.';
  noItemsMessage.style.display = 'none';
  const itemGrid = document.querySelector('.item-grid');
  if (itemGrid) itemGrid.after(noItemsMessage);

  // Exit if not on listings page
  if (!searchInput || !searchButton || !categoryFilter || itemCards.length === 0) return;

  const filterItems = () => {
    const searchTerm = searchInput.value.trim().toLowerCase();
    const selectedCategory = categoryFilter.value.toLowerCase();

    itemCards.forEach(card => {
      const title = card.querySelector('h3').textContent.toLowerCase();
      const description = card.querySelector('.description').textContent.toLowerCase(); // Fixed selector
      const category = card.getAttribute('data-category').toLowerCase();

      const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
      const matchesCategory = selectedCategory === '' || category === selectedCategory;

      card.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
    });

    // Show/hide no-items message
    noItemsMessage.style.display = document.querySelectorAll('.item-card[style="display: block;"]').length === 0 ? 'block' : 'none';
  };

  // Event listeners
  searchButton.addEventListener('click', filterItems); // Search on button click
  categoryFilter.addEventListener('change', filterItems); // Filter on category change
}

// Initialization
document.addEventListener('DOMContentLoaded', function() {
  setupListingsFilter();
  console.log('Listings filter initialized');
});