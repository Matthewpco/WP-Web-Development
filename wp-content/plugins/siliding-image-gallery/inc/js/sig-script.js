const slider = document.querySelector('.slider');
const images = slider ? slider.querySelectorAll('.slider-img') : null;
let currentOffset = 0;
let shortcodeIsActive = slider && images;

if (shortcodeIsActive) {
    setInterval(() => {
        // Update the current offset
        currentOffset -= 100 / images.length;
        slider.style.transform = `translateX(${currentOffset}%)`;

        // Check if the first visible image has moved one image width to the left
        if (currentOffset <= -100 / images.length) {
            // Reset the position of the first visible image
            currentOffset += 100 / images.length;
            const firstVisibleImage = slider.querySelector('.slider-img');
            firstVisibleImage.style.animation = 'fade-out 1s'; // Add a fade-out animation

            // Wait for the fade-out animation to complete
            setTimeout(() => {
                slider.style.transition = 'none'
                slider.appendChild(firstVisibleImage); // Move the first visible image to the end of the slider
                slider.style.transform = `translateX(${currentOffset}%)`;
                firstVisibleImage.style.animation = ''; // Remove the fade-out animation
            }, 1000);

            // Reset the transition property after a short delay
            setTimeout(() => {
                slider.style.transition = '';
            }, 1100);
        }
    }, 3000);
}