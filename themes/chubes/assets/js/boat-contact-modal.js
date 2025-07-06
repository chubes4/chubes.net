document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("boatContactModal");
    const form = document.getElementById("boatContactForm");
    const closeBtn = modal.querySelector(".close");
    const errorMsg = form.querySelector(".error-message");
    const successMsg = form.querySelector(".success-message");
    const navLinks = document.querySelectorAll('.section-nav a');
    const stickyNav = document.querySelector('.section-nav.sticky');
    const hamburger = document.getElementById("hamburger");
    // Default offset—will update on mobile
    let offset = 65;

    // Determine the static position of the sticky nav (just below the h1 title)
    let navThreshold = 0;
    if (stickyNav) {
        navThreshold = stickyNav.offsetTop;
    }

    // Update offset on resize (if the sticky nav height changes on mobile)
    function updateOffset() {
        if (window.innerWidth < 768 && stickyNav) {
            offset = stickyNav.offsetHeight;
        } else {
            offset = 65;
        }
    }
    updateOffset();
    window.addEventListener("resize", updateOffset);

    // Open modal when clicking CTA buttons
    document.querySelectorAll(".boat-cta").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            modal.style.display = "block";
            document.getElementById("boatName").focus();
        });
    });

    // AJAX form submission
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        errorMsg.style.display = "none";
        successMsg.style.display = "none";
        const formData = new FormData(form);
        fetch(boat_vars.ajaxUrl, {
            method: "POST",
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide form inputs
                const formInputs = form.querySelectorAll('input:not([type="hidden"]), textarea, button');
                formInputs.forEach(el => el.style.display = 'none');
                
                // Show success message
                successMsg.textContent = "Thank you! I'll be in touch soon to discuss your boat website.";
                successMsg.style.display = "block";
                
                setTimeout(() => { 
                    modal.style.display = "none";
                    // Reset form for next use
                    form.reset();
                    formInputs.forEach(el => el.style.display = '');
                    successMsg.style.display = "none";
                }, 5000);
            } else {
                errorMsg.textContent = data.data.message || "An error occurred. Please try again.";
                errorMsg.style.display = "block";
            }
        })
        .catch(error => {
            console.error("Error:", error);
            errorMsg.textContent = "An error occurred. Please try again.";
            errorMsg.style.display = "block";
        });
    });

    // Close modal on close button click
    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Close modal on outside click
    window.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // Handle navigation link clicks with offset
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                const offsetPosition = elementPosition - offset;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Hide the hamburger once the user scrolls past the sticky nav's static position,
    // and show it only when they scroll back above that threshold.
    function updateHamburgerVisibility() {
        if (window.scrollY >= navThreshold) {
            hamburger.style.display = "none";
        } else {
            hamburger.style.display = "block";
        }
    }
    window.addEventListener("scroll", updateHamburgerVisibility);
    // Run once on load in case the page is not at the top
    updateHamburgerVisibility();

    // Existing scroll event for active state in nav links (if needed)
    window.addEventListener("scroll", function () {
        const sections = document.querySelectorAll(".post-body h2"); // Targeting h2 instead of section
        let current = "";
    
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top + window.scrollY - offset;
            if (window.scrollY >= sectionTop) {
                current = section.getAttribute("id");
            }
        });
    
        navLinks.forEach(link => {
            link.classList.remove("active");
            if (link.getAttribute("href") === `#${current}`) {
                link.classList.add("active");
            }
        });
    });
});
