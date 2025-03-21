document.addEventListener("DOMContentLoaded", function () {
  const serviceItems = document.querySelectorAll(".service-item");
  const servicesGrid = document.querySelector(".services-grid");
  let expandedItem = null;
  let hashProcessed = false;

  // Check for hash in URL to expand the corresponding service
  function checkUrlHash() {
    const hash = window.location.hash;
    if (hash && !hashProcessed) {
      hashProcessed = true;
      const targetService = document.getElementById(hash.substring(1));
      if (targetService && targetService.classList.contains('service-item')) {
        // Allow layout to settle first
        setTimeout(() => {
          // Wait for all page elements to be fully loaded and positioned
          if (document.readyState === 'complete') {
            expandService(targetService, true);
          } else {
            window.addEventListener('load', () => {
              expandService(targetService, true);
            });
          }
        }, 300);
      }
    }
  }

  // Add will-change hints for better performance
  serviceItems.forEach(item => {
    item.style.willChange = "transform, opacity, width, left, top";
    item.addEventListener("click", function (e) {
      // Check if the clicked element is a link, button, or inside one
      if (e.target.tagName === 'A' || 
          e.target.tagName === 'BUTTON' || 
          e.target.closest('a') || 
          e.target.closest('button')) {
        // Don't prevent default for links and buttons
        return;
      }
      
      // Otherwise prevent default and handle service expansion
      e.preventDefault();
      if (this === expandedItem) {
        resetGrid();
      } else {
        expandService(this);
      }
    });
  });

  function expandService(selected, fromHash = false) {
    // Reset any previously expanded item
    if (expandedItem) {
      resetGrid(fromHash);
    }
    expandedItem = selected;
    
    // Add expanded class directly - no additional animation classes
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

    // Animate other items off-screen using translate3d with directional movement
    serviceItems.forEach(item => {
      if (item !== selected) {
        if (window.innerWidth < 768) {
          // On mobile, fade out and slide down with translate3d
          item.style.transition = "opacity 0.6s cubic-bezier(0.2, 0.82, 0.2, 1), transform 0.6s cubic-bezier(0.2, 0.82, 0.2, 1)";
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
          item.style.transition = "all 0.6s cubic-bezier(0.2, 0.82, 0.2, 1)";
          item.style.transform = transformStr;
          item.style.opacity = "0";
        }
      }
    });

    // Animate the selected item into place with refined easing
    selected.style.transition = "left 0.5s cubic-bezier(0.2, 0.82, 0.2, 1), top 0.5s cubic-bezier(0.2, 0.82, 0.2, 1), width 0.5s cubic-bezier(0.2, 0.82, 0.2, 1)";
    selected.style.left = "0";
    selected.style.top = "0";
    selected.style.width = "100%";

    // Update the URL hash without scrolling
    const serviceId = selected.id;
    if (serviceId && !fromHash) {
      history.replaceState(null, null, '#' + serviceId);
    }

    // Reveal content with slight delay for sequential animation
    setTimeout(() => {
      const content = selected.querySelector(".service-content");
      if (content) {
        // Pre-calculate the needed height for smoother animation
        content.style.opacity = "0";
        content.style.maxHeight = "0";
        
        // Get accurate height measurement
        content.style.position = "absolute";
        content.style.visibility = "hidden";
        content.style.display = "block";
        const contentHeight = content.scrollHeight;
        content.style.position = "";
        content.style.visibility = "";
        content.style.display = "";
        
        // Force browser reflow
        content.getBoundingClientRect();
        
        // Apply optimized animation
        content.style.transition = "max-height 0.5s cubic-bezier(0.2, 0.82, 0.2, 1), opacity 0.5s cubic-bezier(0.2, 0.82, 0.2, 1)";
        content.style.maxHeight = contentHeight + "px"; 
        content.style.opacity = "1";
      }
      
      // Scroll handling logic remains the same
      if (fromHash) {
        setTimeout(() => {
          // Get the position relative to the viewport
          const rect = selected.getBoundingClientRect();
          // Get the current scroll position
          const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
          // Calculate the absolute position on the page
          const absoluteY = rect.top + scrollTop - 80; // offset by 80px for header
          
          // Scroll to the position
          window.scrollTo({
            top: absoluteY,
            behavior: 'smooth'
          });
        }, 300);
      }
      else if (window.innerWidth < 768) {
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

    // Hide hamburger menu when a service grid is expanded.
    const hamburger = document.getElementById("hamburger");
    if (hamburger) {
      hamburger.style.display = "none";
    }
  }

  function resetGrid(fromHash = false) {
    if (!expandedItem) {
      serviceItems.forEach(item => {
        if (window.innerWidth < 768) {
          item.style.transition = "opacity 0.6s cubic-bezier(0.2, 0.82, 0.2, 1), transform 0.6s cubic-bezier(0.2, 0.82, 0.2, 1)";
        } else {
          item.style.transition = "all 0.6s cubic-bezier(0.2, 0.82, 0.2, 1)";
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

    // Remove the hash from URL when collapsing, unless triggered from hash navigation
    if (!fromHash) {
      history.replaceState(null, null, ' ');
    }

    // Add collapsing class for specific animations
    expandedItem.classList.add("collapsing");
    
    // Collapse the inner content first
    if (content) {
      content.style.transition = "max-height 0.3s cubic-bezier(0.2, 0.82, 0.2, 1), opacity 0.3s cubic-bezier(0.2, 0.82, 0.2, 1)";
      content.style.maxHeight = "0";
      content.style.opacity = "0";
    }

    // Delay the container animation slightly
    setTimeout(() => {
      // Remove expanded after content collapses
      expandedItem.classList.remove("expanded");
      
      // Force reflow for browser to recognize state change
      expandedItem.getBoundingClientRect();

      // Apply easing that feels natural for closing
      expandedItem.style.transition = "left 0.5s cubic-bezier(0.2, 0.82, 0.2, 1), top 0.5s cubic-bezier(0.2, 0.82, 0.2, 1), width 0.5s cubic-bezier(0.2, 0.82, 0.2, 1)";
      expandedItem.style.left = originalLeft + "px";
      expandedItem.style.top = originalTop + "px";
      expandedItem.style.width = originalWidth + "px";

      const onTransitionEnd = event => {
        if (event.propertyName !== "width") return;
        
        // Clean up by removing listener immediately
        expandedItem.removeEventListener("transitionend", onTransitionEnd);
        
        // Remove collapsing class
        expandedItem.classList.remove("collapsing");

        // Clean up inline styles in a batch for better performance
        const stylesToClear = ["position", "left", "top", "width", "zIndex", "transition"];
        stylesToClear.forEach(style => {
          expandedItem.style[style] = "";
        });
        
        // Clean up dataset properties
        delete expandedItem.dataset.originalLeft;
        delete expandedItem.dataset.originalTop;
        delete expandedItem.dataset.originalWidth;

        // Bring other items back in with the same directional movement
        serviceItems.forEach(item => {
          if (item !== expandedItem) {
            // Use the same easing as for expand
            if (window.innerWidth < 768) {
              item.style.transition = "opacity 0.6s cubic-bezier(0.2, 0.82, 0.2, 1), transform 0.6s cubic-bezier(0.2, 0.82, 0.2, 1)";
            } else {
              item.style.transition = "all 0.6s cubic-bezier(0.2, 0.82, 0.2, 1)";
            }
            item.style.transform = "";
            item.style.opacity = "1";
          }
        });
        
        // Clear the expanded item reference
        expandedItem = null;

        // Show hamburger again when the grid resets.
        const hamburger = document.getElementById("hamburger");
        if (hamburger) {
          hamburger.style.display = "block";
        }
      };

      expandedItem.addEventListener("transitionend", onTransitionEnd);
    }, 250);
  }

  // Check for hash navigation on page load
  checkUrlHash();

  // Listen for hash changes
  window.addEventListener('hashchange', function() {
    hashProcessed = false;
    checkUrlHash();
  });
});


document.addEventListener("DOMContentLoaded", function() {
  const quoteModal = document.getElementById("quoteModal");
  const quoteModalTitle = document.getElementById("quoteModalTitle");
  const quoteServiceInput = document.getElementById("quoteService");
  const quoteForm = document.getElementById("quoteForm");
  const errorMsg = quoteForm.querySelector(".error-message");
  const successMsg = quoteForm.querySelector(".success-message");
  const closeBtn = quoteModal.querySelector(".close");
  
  // Open modal and set service title when a "Get a Quote" button (including global) is clicked
  const getQuoteBtns = document.querySelectorAll(".get-quote");
  getQuoteBtns.forEach(btn => {
    btn.addEventListener("click", function(e) {
      e.preventDefault();
      let serviceTitle = "a";
      
      // Check if the button is within a service item (specific service) or global
      if (this.closest(".service-item")) {
        serviceTitle = this.closest(".service-item").querySelector("h3").textContent;
      }
      
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
    successMsg.style.display = "none"; // Hide any previous success messages
    
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
          // Show success message and hide form inputs
          const formInputs = quoteForm.querySelectorAll('input:not([type="hidden"]), textarea, button');
          formInputs.forEach(el => el.style.display = 'none');
          
          successMsg.textContent = "Thanks for your submission! I'll be in touch soon to discuss taking your digital presence to the next level.";
          successMsg.style.display = "block";
          
          setTimeout(function() {
            quoteModal.style.display = "none";
            // Reset form for next use
            quoteForm.reset();
            formInputs.forEach(el => el.style.display = '');
            successMsg.style.display = "none";
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





