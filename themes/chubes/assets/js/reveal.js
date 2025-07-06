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
        // After reverse cascade is done, remove .closing class
        setTimeout(() => {
          overlay.classList.remove("closing");
        }, 600); // Adjust based on your longest delay (0.6s here)
      } else {
        // Open the menu
        overlay.classList.add("open");
        hamburger.classList.add("open");
      }
    });
  });
  
  
  