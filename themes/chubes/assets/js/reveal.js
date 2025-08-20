document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".reveal");
  
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("active");
          
          // Add stagger effect for tech items
          if (entry.target.classList.contains('tech-cloud')) {
            const techItems = entry.target.querySelectorAll('.tech-item');
            techItems.forEach((item, index) => {
              item.style.transitionDelay = `${index * 0.05}s`;
              setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0) scale(1)';
              }, 50);
            });
          }
          
          // Stop observing once revealed
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });
  
    elements.forEach((el) => observer.observe(el));
    
    // Initialize tech items
    const techItems = document.querySelectorAll('.tech-item');
    techItems.forEach(item => {
      item.style.opacity = '0';
      item.style.transform = 'translateY(20px) scale(0.95)';
      item.style.transition = 'all 0.3s ease';
    });
  });
  
  document.addEventListener("DOMContentLoaded", function() {
    const hamburger = document.getElementById("hamburger");
    const overlay   = document.getElementById("overlay");
  
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
  });
  
  
  