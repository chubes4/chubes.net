document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".reveal");
  
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("active");
          // Stop observing once revealed
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });
  
    elements.forEach((el) => observer.observe(el));
  });
  