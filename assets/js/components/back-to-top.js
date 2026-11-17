/**
 * Global Back to Top Button
 *
 * Smooth scroll-to-top interaction with throttled scroll listener
 * and accessibility support.
 *
 * @package hoiantravel
 */

export const initBackToTop = () => {
    const btn = document.getElementById('ha-back-to-top') || document.querySelector('.js-back-to-top');
    if (!btn) return;

    // Prevent duplicate event binding
    if (btn.dataset.initialized === 'true') return;
    btn.dataset.initialized = 'true';

    const SCROLL_THRESHOLD = 350;
    let isTicking = false;
    let isVisible = false;

    // Evaluate visibility on scroll with requestAnimationFrame throttling
    const onScroll = () => {
        if (!isTicking) {
            window.requestAnimationFrame(() => {
                const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                const shouldBeVisible = currentScroll > SCROLL_THRESHOLD;

                if (shouldBeVisible !== isVisible) {
                    isVisible = shouldBeVisible;
                    if (isVisible) {
                        btn.classList.add('is-visible');
                    } else {
                        btn.classList.remove('is-visible');
                    }
                }
                isTicking = false;
            });
            isTicking = true;
        }
    };

    // Scroll to top handler
    const scrollToTop = (e) => {
        if (e) e.preventDefault();

        const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        window.scrollTo({
            top: 0,
            behavior: prefersReducedMotion ? 'auto' : 'smooth'
        });

        // Shift focus to skip link or body for screen readers / keyboard navigation
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.focus({ preventScroll: true });
        } else {
            document.body.setAttribute('tabindex', '-1');
            document.body.focus({ preventScroll: true });
            document.body.removeAttribute('tabindex');
        }
    };

    // Keyboard accessibility: trigger on Space or Enter (standard for <button>)
    btn.addEventListener('click', scrollToTop);

    // Passive scroll listener for maximum scrolling performance
    window.addEventListener('scroll', onScroll, { passive: true });

    // Initial check in case page is refreshed while already scrolled down
    onScroll();
};

// Auto initialize on DOMContentLoaded if not loaded via bundler entry
if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBackToTop);
    } else {
        initBackToTop();
    }
}

