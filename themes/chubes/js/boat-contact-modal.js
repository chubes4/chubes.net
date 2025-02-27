document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("boatContactModal");
    const form = document.getElementById("boatContactForm");
    const closeBtn = modal.querySelector(".close");
    const errorMsg = form.querySelector(".contact-error");
    const navLinks = document.querySelectorAll('.section-nav a');
    const offset = 65; // Adjust this to your nav bar height (e.g., 60px)

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
        const formData = new FormData(form);
        fetch(boat_vars.ajaxUrl, {
            method: "POST",
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.innerHTML = "<p style='text-align:center; font-size:18px;'>Thank you! I’ll be in touch soon to discuss your boat website.</p>";
                setTimeout(() => { modal.style.display = "none"; }, 5000);
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
});

// Update the scroll event listener for active state with offset
window.addEventListener("scroll", function () {
    const sections = document.querySelectorAll(".post-body h2"); // Targeting h2 instead of section
    const navLinks = document.querySelectorAll(".section-nav a");
    const offset = 65; // Same offset as above
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