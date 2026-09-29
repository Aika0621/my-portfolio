const form = document.getElementById("contactForm");

if (form) {

    form.addEventListener("submit", function(event) {

        event.preventDefault();

        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const message = document.getElementById("message").value;
        const formMessage = document.getElementById("formMessage");

        if (name === "" || email === "" || message === "") {
            formMessage.textContent = "Please fill in all fields.";
        } else {
            formMessage.textContent = "Message sent successfully!";
        }

    });

}
const themeButton = document.getElementById("themeButton");

if (themeButton) {

    themeButton.addEventListener("click", function() {

        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode")) {
            themeButton.textContent = "Light Mode";
        } else {
            themeButton.textContent = "Dark Mode";
        }

    });

}
const topButton = document.getElementById("topButton");

if (topButton) {

    window.addEventListener("scroll", function() {

        if (window.scrollY > 200) {
            topButton.style.display = "block";
        } else {
            topButton.style.display = "none";
        }

    });

    topButton.addEventListener("click", function() {

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

    });

}
const typingText = document.getElementById("typingText");

if (typingText) {

    const text = "Information Technology Student";
    let index = 0;

    function typeText() {

        if (index < text.length) {
            typingText.textContent += text.charAt(index);
            index++;

            setTimeout(typeText, 100);
        }

    }

    typeText();

}
const filterButtons = document.querySelectorAll(".filter-button");
const projectCards = document.querySelectorAll(".project-card");

filterButtons.forEach(function(button) {

    button.addEventListener("click", function() {

        const filter = button.getAttribute("data-filter");

        projectCards.forEach(function(project) {

            const category = project.getAttribute("data-category");

            if (filter === "all" || category === filter) {
                project.style.display = "block";
            } else {
                project.style.display = "none";
            }

        });

    });

});