let currentSlide = 0;

function moveSlider(direction) {
    const slider = document.querySelector('.movie-slider');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    // Assuming you have 10 movies and display 5 at a time
    const totalSlides = 2; // You have 2 groups: 1-5 and 6-10
    const visibleSlides = 1; // Number of slide groups visible at once
    const maxSlide = totalSlides - visibleSlides; // Maximum slide index

    // Update current slide index based on direction
    currentSlide += direction;

    // Ensure currentSlide stays within bounds
    if (currentSlide < 0) {
        currentSlide = 0;
    } else if (currentSlide > maxSlide) {
        currentSlide = maxSlide;
    }

    // Calculate translation percentage
    const translateXValue = -currentSlide * 100 / visibleSlides;
    slider.style.transform = `translateX(${translateXValue}%)`;

    // Show/hide buttons based on the current slide
    if (currentSlide === 0) {
        prevButton.style.display = 'none'; // Hide left button
    } else {
        prevButton.style.display = 'block'; // Show left button
    }

    if (currentSlide === maxSlide) {
        nextButton.style.display = 'none'; // Hide right button
    } else {
        nextButton.style.display = 'block'; // Show right button
    }
}

// Initial call to set the correct button visibility
moveSlider(0);

function updateSliderUI() {
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');
    const screenWidth = window.innerWidth;

    if (screenWidth < 1050) {
        prevButton.style.display = 'none';
        nextButton.style.display = 'none';
    } else {
        // Initial button visibility based on the current slide
        // moveSlider(0);
         // Just update the button visibility based on the current slide
         if (currentSlide === 0) {
            prevButton.style.display = 'none';
        } else {
            prevButton.style.display = 'block';
        }

        if (currentSlide === totalSlides - 1) {
            nextButton.style.display = 'none';
        } else {
            nextButton.style.display = 'block';
        }
    }
}

// Call updateSliderUI on load and on window resize
window.addEventListener('load', updateSliderUI);
window.addEventListener('resize', updateSliderUI);
























// let currentSlide = 0;

// function moveSlider(direction) {
//     const slider = document.querySelector('.movie-slider');
//     // const totalSlides = slider.children.length;
//     const totalSlides = 2;
//     const visibleSlides = 1; // Number of slides visible at once
//     const maxSlide = totalSlides - visibleSlides; // Maximum slide index

//     currentSlide += direction;
//     // document.write("this is currentslide : ",currentSlide);
//     // document.write("1,2,3,4,5");

//     // Loop around if at the start or end
//     if (currentSlide < 0) {
//         currentSlide = maxSlide;
//     } else if (currentSlide > maxSlide) {
//         currentSlide = 0;
//     }
    
//     // const translateXValue = -currentSlide * 100 / visibleSlides; // Calculate translation percentage
//     const translateXValue = -currentSlide * 100 / visibleSlides; // Calculate translation percentage
//     // document.write("this is translatevalue",translateXValue);
//     slider.style.transform = `translateX(${translateXValue}%)`;
// }
