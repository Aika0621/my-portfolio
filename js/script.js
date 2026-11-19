// Contact form is submitted by PHP. This file does not intercept or prevent POST submissions.

const themeButton = document.getElementById("themeButton");

if (themeButton) {
    themeButton.addEventListener("click", function () {
        const darkModeEnabled = document.body.classList.toggle("dark-mode");
        themeButton.textContent = darkModeEnabled ? "Light Mode" : "Dark Mode";
        themeButton.setAttribute("aria-pressed", String(darkModeEnabled));
    });
}

const topButton = document.getElementById("topButton");

if (topButton) {
    const updateTopButton = function () {
        topButton.style.display = window.scrollY > 200 ? "block" : "none";
    };

    window.addEventListener("scroll", updateTopButton, { passive: true });
    updateTopButton();

    topButton.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
}

const typingText = document.getElementById("typingText");

if (typingText && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    const text = typingText.textContent.trim();

    if (text) {
        let index = 0;
        typingText.textContent = "";

        const typeNextCharacter = function () {
            if (index < text.length) {
                typingText.textContent += text.charAt(index);
                index += 1;
                window.setTimeout(typeNextCharacter, 55);
            }
        };

        typeNextCharacter();
    }
}

const filterButtons = document.querySelectorAll(".filter-button");
const projectCards = document.querySelectorAll(".project-card[data-category]");

filterButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        const filter = (button.getAttribute("data-filter") || "all").trim().toLowerCase();

        filterButtons.forEach(function (filterButton) {
            filterButton.setAttribute("aria-pressed", String(filterButton === button));
        });

        projectCards.forEach(function (project) {
            const category = (project.getAttribute("data-category") || "").trim().toLowerCase();
            project.hidden = filter !== "all" && category !== filter;
        });
    });
});
