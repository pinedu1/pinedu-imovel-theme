/* eslint-disable max-len */
import MoveTo from 'moveto';

const backToTop = () => {
  // Back to top button
  const moveToTop = new MoveTo({
    duration: 300,
    easing: 'easeOutQuart',
  });
  const topButton = document.getElementById('top');
  const focusableElements = document.querySelectorAll(
    'button, a, input, select, textarea, [tabindex]:not([tabindex="-1"])',
  );

  function trackScroll() {
    const scrolled = window.pageYOffset;
    const viewportHeight = window.innerHeight;
    const scrollTrigger = viewportHeight;

    if (scrolled > scrollTrigger) {
      topButton.classList.add('is-visible');
    } else {
      topButton.classList.remove('is-visible');
    }
  }
  function scroll(focusVisible) {
    // Check if user prefers reduced motion, if so, just scroll to top
    const prefersReducedMotion = window.matchMedia(
      '(prefers-reduced-motion: reduce)',
    ).matches;

    if (prefersReducedMotion) {
      focusableElements[0].focus({ focusVisible });
      return;
    }

    // Move smoothly to the first focusable element on the page
    moveToTop.move(focusableElements[0]);

    // Focus too, if on keyboard
    focusableElements[0].focus({ preventScroll: true, focusVisible });
  }

  if (topButton) {
    topButton.addEventListener('click', (event) => {
      // Don't add hash in the end of the url
      event.preventDefault();

      // Focus without visibility (as user is not using keyboard)
      scroll(false);
    });

    topButton.addEventListener('keydown', (event) => {
      // Don't propagate keydown event to click event
      event.preventDefault();

      // Scroll with focus visible
      scroll(true);
    });
  }

  window.addEventListener('scroll', trackScroll);
};

export default backToTop;
