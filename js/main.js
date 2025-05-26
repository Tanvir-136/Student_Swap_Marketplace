// js/main.js

// Mobile Navigation Functionality
function setupMobileNavigation() {
  const navbarToggle = document.querySelector('.navbar-toggle');
  const navbarLinks = document.querySelector('.navbar-links');
  
  if (!navbarToggle || !navbarLinks) return;

  navbarToggle.addEventListener('click', function() {
    navbarLinks.classList.toggle('active');
    // Toggle hamburger/close icon
    const icon = this.querySelector('i');
    if (icon) {
      icon.classList.toggle('fa-bars');
      icon.classList.toggle('fa-times');
    }
  });

  // Close mobile menu when clicking a link
  document.querySelectorAll('.navbar-links a').forEach(link => {
    link.addEventListener('click', () => {
      if (navbarLinks.classList.contains('active')) {
        navbarLinks.classList.remove('active');
        const icon = navbarToggle.querySelector('i');
        if (icon) {
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      }
    });
  });
}

// Dropdown Menu Functionality
function setupDropdownMenu() {
  const dropdownBtn = document.querySelector('.dropdown-btn');
  const dropdownContent = document.querySelector('.dropdown-content');
  
  if (!dropdownBtn || !dropdownContent) return;

  dropdownBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    dropdownContent.classList.toggle('show');
  });

  // Close dropdown when clicking elsewhere
  document.addEventListener('click', function() {
    if (dropdownContent.classList.contains('show')) {
      dropdownContent.classList.remove('show');
    }
  });
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  setupMobileNavigation();
  setupDropdownMenu();
  
  // Add any other initialization calls here
  console.log('StudentSwap JavaScript initialized');
});