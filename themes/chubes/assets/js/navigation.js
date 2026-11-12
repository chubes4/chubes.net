/**
 * Mobile Navigation Menu System
 * 
 * Handles hamburger menu toggle, overlay display, and dropdown submenu functionality.
 * Includes smooth open/close animations with cascade effects for multiple menu items.
 */
document.addEventListener("DOMContentLoaded", function() {
  const hamburger = document.getElementById("hamburger");
  const overlay   = document.getElementById("overlay");
  const searchInput = overlay.querySelector(".search-input");

  hamburger.addEventListener("click", function() {
    if (overlay.classList.contains("open")) {
      // Start closing: remove .open, add .closing for reverse cascade
      overlay.classList.remove("open");
      overlay.classList.add("closing");
      hamburger.classList.remove("open");
      
      // Close all open submenus
      const openSubmenus = overlay.querySelectorAll('.submenu-open');
      openSubmenus.forEach(submenu => {
        submenu.classList.remove('submenu-open');
      });
      
      // After reverse cascade is done, remove .closing class
      setTimeout(() => {
        overlay.classList.remove("closing");
      }, 800); // Extended timeout for more menu items
    } else {
      // Open the menu
      overlay.classList.add("open");
      hamburger.classList.add("open");
      
      // Focus search input after animation completes
      if (searchInput) {
        setTimeout(() => {
          searchInput.focus();
        }, 1000);
      }
    }
  });

  // Handle dropdown menu clicks using event delegation
  overlay.addEventListener('click', function(e) {
    const target = e.target;
    
    // Check if clicked element is a parent menu item with children
    if (target.matches('.menu-item-has-children > a') || 
        target.closest('.menu-item-has-children > a')) {
      
      e.preventDefault();
      const menuLink = target.matches('.menu-item-has-children > a') 
        ? target 
        : target.closest('.menu-item-has-children > a');
      
      const parentLi = menuLink.closest('li');
      const submenu = parentLi.querySelector('.sub-menu');
      
      if (submenu) {
        // Toggle the submenu
        if (parentLi.classList.contains('submenu-open')) {
          parentLi.classList.remove('submenu-open');
          submenu.classList.remove('submenu-open');
        } else {
          // Close other open submenus first
          const otherOpenItems = overlay.querySelectorAll('.menu-item-has-children.submenu-open');
          otherOpenItems.forEach(item => {
            if (item !== parentLi) {
              item.classList.remove('submenu-open');
              const otherSubmenu = item.querySelector('.sub-menu');
              if (otherSubmenu) {
                otherSubmenu.classList.remove('submenu-open');
              }
            }
          });
          
          // Open this submenu
          parentLi.classList.add('submenu-open');
          submenu.classList.add('submenu-open');
        }
      }
    }
  });

  // Handle escape key to close overlay
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) {
      // Trigger the close sequence
      overlay.classList.remove("open");
      overlay.classList.add("closing");
      hamburger.classList.remove("open");
      
      // Close all open submenus
      const openSubmenus = overlay.querySelectorAll('.submenu-open');
      openSubmenus.forEach(submenu => {
        submenu.classList.remove('submenu-open');
      });
      
      // Remove .closing class after animation
      setTimeout(() => {
        overlay.classList.remove("closing");
      }, 800);
    }
  });
});