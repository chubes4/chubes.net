document.addEventListener("DOMContentLoaded", function () {
  const serviceItems = document.querySelectorAll(".service-item");
  const servicesGrid = document.querySelector(".services-grid");
  let expandedItem = null;

  // Add will-change hints for better performance
  serviceItems.forEach(item => {
    item.style.willChange = "transform, opacity, width, left, top";
    item.addEventListener("click", function () {
      if (this === expandedItem) {
        resetGrid();
      } else {
        expandService(this);
      }
    });
  });

  function expandService(selected) {
    // Reset any previously expanded item
    if (expandedItem) {
      resetGrid();
    }
    expandedItem = selected;
    selected.classList.add("expanded");

    // Store original position and width using FLIP technique
    selected.dataset.originalLeft = selected.offsetLeft;
    selected.dataset.originalTop = selected.offsetTop;
    selected.dataset.originalWidth = selected.offsetWidth;

    // Set absolute positioning
    selected.style.position = "absolute";
    selected.style.left = selected.dataset.originalLeft + "px";
    selected.style.top = selected.dataset.originalTop + "px";
    selected.style.width = selected.dataset.originalWidth + "px";
    selected.style.zIndex = "10";

    // Force reflow once
    selected.getBoundingClientRect();

    // Animate other items off-screen using translate3d for acceleration
    serviceItems.forEach(item => {
      if (item !== selected) {
        if (window.innerWidth < 768) {
          // On mobile, fade out and slide down with translate3d
          item.style.transition = "opacity 0.6s ease, transform 0.6s ease";
          item.style.transform = "translate3d(0, 50px, 0)";
          item.style.opacity = "0";
        } else {
          const selectedRect = selected.getBoundingClientRect();
          const itemRect = item.getBoundingClientRect();
          const deltaX = itemRect.left - selectedRect.left;
          const deltaY = itemRect.top - selectedRect.top;
          let transformStr = "";
          if (Math.abs(deltaX) >= Math.abs(deltaY)) {
            // Horizontal movement using translate3d
            transformStr = deltaX < 0 ? "translate3d(-150%, 0, 0)" : "translate3d(150%, 0, 0)";
          } else {
            // Vertical movement using translate3d
            transformStr = deltaY < 0 ? "translate3d(0, -150%, 0)" : "translate3d(0, 150%, 0)";
          }
          item.style.transition = "all 0.6s ease";
          item.style.transform = transformStr;
          item.style.opacity = "0";
        }
      }
    });

    // Animate the selected item into place
    selected.style.transition = "left 0.6s ease, top 0.6s ease, width 0.6s cubic-bezier(0.25, 0.8, 0.25, 1)";
    selected.style.left = "0";
    selected.style.top = "0";
    selected.style.width = "100%";

    // Reveal content after a short delay
setTimeout(() => {
  const content = selected.querySelector(".service-content");
  if (content) {
    // Calculate the needed height
    const contentHeight = content.scrollHeight;
    content.style.transition = "max-height 0.6s ease, opacity 0.6s ease";
    content.style.maxHeight = contentHeight + "px"; 
    content.style.opacity = "1";
  }
  if (window.innerWidth < 768) {
// Get the .post-content container
const servicesGrid = document.querySelector('.post-content');
const gridRect = servicesGrid.getBoundingClientRect();
const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;

// Calculate the target scroll position
const targetY = gridRect.bottom + scrollTop;

// Smoothly scroll to the calculated position
window.scrollTo({ top: targetY, behavior: 'smooth' });
  }
}, 300);
  }

  function resetGrid() {
    if (!expandedItem) {
      serviceItems.forEach(item => {
        if (window.innerWidth < 768) {
          item.style.transition = "opacity 0.6s ease, transform 0.6s ease";
        } else {
          item.style.transition = "all 0.6s ease";
        }
        item.style.transform = "";
        item.style.opacity = "1";
      });
      return;
    }
  
    const originalLeft = expandedItem.dataset.originalLeft;
    const originalTop = expandedItem.dataset.originalTop;
    const originalWidth = expandedItem.dataset.originalWidth;
    const content = expandedItem.querySelector(".service-content");
  
    // Collapse the inner content concurrently
    if (content) {
      content.style.transition = "max-height 0.3s ease, opacity 0.3s ease";
      content.style.maxHeight = "0";
      content.style.opacity = "0";
    }
  
    // Delay grid item animation until content collapse finishes
    setTimeout(() => {
      expandedItem.classList.remove("expanded");
      expandedItem.getBoundingClientRect(); // Force reflow
  
      expandedItem.style.transition = "left 0.6s ease, top 0.6s ease, width 0.6s cubic-bezier(0.25, 0.8, 0.25, 1)";
      expandedItem.style.left = originalLeft + "px";
      expandedItem.style.top = originalTop + "px";
      expandedItem.style.width = originalWidth + "px";
  
      const onTransitionEnd = event => {
        if (event.propertyName !== "width") return;
        expandedItem.removeEventListener("transitionend", onTransitionEnd);
  
        // Clean up inline styles
        expandedItem.style.position = "";
        expandedItem.style.left = "";
        expandedItem.style.top = "";
        expandedItem.style.width = "";
        expandedItem.style.zIndex = "";
        delete expandedItem.dataset.originalLeft;
        delete expandedItem.dataset.originalTop;
        delete expandedItem.dataset.originalWidth;
  
        // Bring other items back into view with the same directional nuance
        serviceItems.forEach(item => {
          if (item !== expandedItem) {
            if (window.innerWidth < 768) {
              item.style.transition = "opacity 0.6s ease, transform 0.6s ease";
            } else {
              item.style.transition = "all 0.6s ease";
            }
            item.style.transform = "";
            item.style.opacity = "1";
          }
        });
        expandedItem = null;
      };
  
      expandedItem.addEventListener("transitionend", onTransitionEnd);
    }, 350);
  }
  
  
});



document.addEventListener("DOMContentLoaded", function() {
  const quoteModal = document.getElementById("quoteModal");
  const quoteModalTitle = document.getElementById("quoteModalTitle");
  const quoteServiceInput = document.getElementById("quoteService");
  const closeBtn = quoteModal.querySelector(".close");
  const quoteForm = document.getElementById("quoteForm");
  const errorMsg = quoteForm.querySelector(".quote-error");
  
  // Open modal and set service title when a "Get a Quote" button is clicked
  const getQuoteBtns = document.querySelectorAll(".get-quote");
  getQuoteBtns.forEach(btn => {
    btn.addEventListener("click", function(e) {
      e.preventDefault();
      const serviceTitle = this.closest(".service-item").querySelector("h3").textContent;
      quoteModalTitle.textContent = "Request " + serviceTitle + " Quote";
      quoteServiceInput.value = serviceTitle;
      
      quoteModal.style.display = "block";
      document.getElementById("quoteName").focus();
    });
  });
  
  // AJAX form submission
  quoteForm.addEventListener("submit", function(e) {
    e.preventDefault();
    errorMsg.style.display = "none"; // Hide any previous error messages
    
    const formData = new FormData(quoteForm);
    
    fetch(chubes_vars.ajaxUrl, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.text())
    .then(text => {
      console.log("Server response text:", text);
      try {
        const data = JSON.parse(text);
        if (data.success) {
          // Replace the form with a success message
          quoteForm.innerHTML = "<p style='text-align:center; font-size:18px;'>Thanks for your submission, I'll be in touch soon to discuss taking your digital presence to the next level.</p>";
          setTimeout(function() {
            quoteModal.style.display = "none";
          }, 6000);
        } else {
          errorMsg.textContent = "There was an error processing your request. Please try again.";
          errorMsg.style.display = "block";
        }
      } catch (err) {
        throw new Error("Invalid JSON response: " + text);
      }
    })
    .catch(error => {
      console.error("Error:", error);
      errorMsg.textContent = "There was an error processing your request. Please try again.";
      errorMsg.style.display = "block";
    });
  });
  
  // Close modal when clicking the close button
  closeBtn.addEventListener("click", function() {
    quoteModal.style.display = "none";
  });
  
  // Close modal when clicking outside the modal content
  window.addEventListener("click", function(e) {
    if (e.target == quoteModal) {
      quoteModal.style.display = "none";
    }
  });
});





