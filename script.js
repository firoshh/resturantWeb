document.addEventListener('DOMContentLoaded', function() {
    // Form validation for reservation
    const reservationForm = document.querySelector('form[action="reservation.php"]');
    
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(event) {
            const name = document.getElementById('name').value.trim();
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;
            const people = document.getElementById('people').value;
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();

            // Basic validation
            if (!name || !date || !time || !people || !email || !phone) {
                alert('Please fill out all fields.');
                event.preventDefault(); // Prevent form submission
            }
        });
    }

    let slideIndex = 0;
    showSlides(); // Initial call to start the slideshow
    
    function showSlides() {
        let slides = document.getElementsByClassName("mySlides");
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none"; // Hide all slides
        }
        slideIndex++;
        if (slideIndex > slides.length) { slideIndex = 1 } // Reset to the first slide
        slides[slideIndex - 1].style.display = "block"; // Show the current slide
        setTimeout(showSlides, 6000); // Change slide every 7 seconds
    }
    
    // Function to manually navigate slides
    window.plusSlides = function(n) {
        slideIndex += n;
        if (slideIndex > document.getElementsByClassName("mySlides").length) { slideIndex = 1 }
        if (slideIndex < 1) { slideIndex = document.getElementsByClassName("mySlides").length }
        showSlidesManually();
    }
    
    // Function to show slides manually (without auto transition)
    function showSlidesManually() {
        let slides = document.getElementsByClassName("mySlides");
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex - 1].style.display = "block";
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const cuisineFilter = document.getElementById('cuisine');
    const menuItems = document.querySelectorAll('.menu-item');

    cuisineFilter.addEventListener('change', function() {
        const selectedCuisine = cuisineFilter.value;
        
        menuItems.forEach(item => {
            if (selectedCuisine === 'all' || item.dataset.cuisine === selectedCuisine) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
