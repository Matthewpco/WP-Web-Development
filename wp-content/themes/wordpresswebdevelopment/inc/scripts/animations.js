// Select all figure elements with the img-slide-left class
const imagesSlideLeft = document.querySelectorAll('figure.img-slide-left');
const imagesSlideRight = document.querySelectorAll('figure.img-slide-right');


// Create a function to check if an element is in view
function isInView(el) {
  const rect = el.getBoundingClientRect();
  const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.left <= (window.innerWidth || document.documentElement.clientWidth) &&
    rect.top <= viewportHeight / 1.3
  );
}

// Create a function to add the animation class to all images in view
function animateImages() {
  imagesSlideLeft.forEach(img => {
    if (isInView(img)) {
		img.classList.remove('img-slide-left')
    	img.classList.add('animate-left');
    }
  });
	imagesSlideRight.forEach(img => {
		if (isInView(img)) {
			img.classList.remove('img-slide-right');
			img.classList.add('animate-right');
		}
	});
}

// Listen for scroll events and run the animateImages function
window.addEventListener('scroll', animateImages);