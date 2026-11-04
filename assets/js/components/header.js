/**
 * Header & Mobile Navigation Menu
 * Hoi An Travel
 */

(function () {
    'use strict';

    function initMobileMenu() {
        const btnHamburger = document.getElementById('btn_hamburger');
        const btnClose = document.getElementById('btn_close_menu');
        const drawer = document.getElementById('mobile-menu-drawer');
        const overlay = document.getElementById('mobile-menu-overlay');

        if (!btnHamburger || !drawer || !overlay) {
            return;
        }

        let isMenuOpen = false;
        let lastFocusedElement = null;

        // Open Drawer
        function openMenu() {
            if (isMenuOpen) return;
            isMenuOpen = true;
            lastFocusedElement = document.activeElement;

            drawer.classList.add('is-open');
            overlay.classList.add('is-open');
            btnHamburger.setAttribute('aria-expanded', 'true');
            drawer.setAttribute('aria-hidden', 'false');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.classList.add('mobile-menu-open');

            // Shift focus into drawer for accessibility
            window.requestAnimationFrame(() => {
                if (btnClose) {
                    btnClose.focus();
                } else {
                    drawer.focus();
                }
            });
        }

        // Close Drawer
        function closeMenu() {
            if (!isMenuOpen) return;
            isMenuOpen = false;

            drawer.classList.remove('is-open');
            overlay.classList.remove('is-open');
            btnHamburger.setAttribute('aria-expanded', 'false');
            drawer.setAttribute('aria-hidden', 'true');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('mobile-menu-open');

            // Restore focus to last active element or hamburger button
            if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                lastFocusedElement.focus();
            } else if (btnHamburger) {
                btnHamburger.focus();
            }
        }

        // Hamburger button click
        btnHamburger.addEventListener('click', (e) => {
            e.preventDefault();
            if (isMenuOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // Close button click
        if (btnClose) {
            btnClose.addEventListener('click', (e) => {
                e.preventDefault();
                closeMenu();
            });
        }

        // Overlay click
        overlay.addEventListener('click', (e) => {
            e.preventDefault();
            closeMenu();
        });

        // Close on Escape key & Focus Trap
        document.addEventListener('keydown', (e) => {
            if (!isMenuOpen) return;

            if (e.key === 'Escape' || e.key === 'Esc') {
                e.preventDefault();
                closeMenu();
                return;
            }

            if (e.key === 'Tab') {
                const focusable = drawer.querySelectorAll(
                    'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
                );

                if (!focusable.length) return;

                const firstEl = focusable[0];
                const lastEl = focusable[focusable.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === firstEl) {
                        lastEl.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastEl) {
                        firstEl.focus();
                        e.preventDefault();
                    }
                }
            }
        });

        // Submenu Accordion Setup
        const nav = drawer.querySelector('.mobile-menu__nav');
        if (nav) {
            const parentItems = nav.querySelectorAll('.menu-item-has-children');
            parentItems.forEach((item, index) => {
                const link = item.querySelector(':scope > a');
                const subMenu = item.querySelector(':scope > .sub-menu');
                if (!subMenu) return;

                // Ensure unique ID for aria-controls
                const subMenuId = subMenu.id || `mobile-sub-menu-${index + 1}`;
                subMenu.id = subMenuId;

                // Create toggle button
                const toggleBtn = document.createElement('button');
                toggleBtn.type = 'button';
                toggleBtn.className = 'mobile-menu__toggle';
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.setAttribute('aria-controls', subMenuId);
                const linkText = link ? link.textContent.trim() : 'submenu';
                toggleBtn.setAttribute('aria-label', `Toggle ${linkText}`);
                toggleBtn.innerHTML = '<span class="mobile-menu__arrow" aria-hidden="true"></span>';

                // Insert toggle button immediately after <a>
                if (link) {
                    link.insertAdjacentElement('afterend', toggleBtn);
                } else {
                    item.insertBefore(toggleBtn, subMenu);
                }

                // Submenu toggle handler
                const handleToggle = (e) => {
                    if (e) e.preventDefault();
                    const willOpen = !item.classList.contains('is-open');
                    item.classList.toggle('is-open', willOpen);
                    toggleBtn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                };

                toggleBtn.addEventListener('click', handleToggle);

                // If link href is empty, '#', or 'javascript:...', treat clicking link as toggle
                if (link) {
                    const href = link.getAttribute('href');
                    if (!href || href === '#' || href.startsWith('javascript:')) {
                        link.addEventListener('click', handleToggle);
                    }
                }

                // If item is active or has active children, expand it by default
                if (
                    item.classList.contains('current-menu-item') ||
                    item.classList.contains('current-menu-parent') ||
                    item.classList.contains('current-menu-ancestor') ||
                    item.classList.contains('current_page_item') ||
                    item.classList.contains('current_page_parent') ||
                    item.classList.contains('current_page_ancestor')
                ) {
                    item.classList.add('is-open');
                    toggleBtn.setAttribute('aria-expanded', 'true');
                }
            });

            // Close drawer when regular navigation links are clicked
            const navLinks = nav.querySelectorAll('a');
            navLinks.forEach((link) => {
                link.addEventListener('click', () => {
                    const href = link.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('javascript:')) {
                        closeMenu();
                    }
                });
            });
        }

        // Close drawer when footer CTA is clicked
        const footerBtn = drawer.querySelector('.mobile-menu-drawer__footer a');
        if (footerBtn) {
            footerBtn.addEventListener('click', () => {
                closeMenu();
            });
        }

        // Auto-close on resize to Desktop (breakpoint XL: 1200px)
        const desktopQuery = window.matchMedia('(min-width: 1200px)');
        const handleDesktopChange = (e) => {
            if (e.matches && isMenuOpen) {
                closeMenu();
            }
        };

        if (typeof desktopQuery.addEventListener === 'function') {
            desktopQuery.addEventListener('change', handleDesktopChange);
        } else if (typeof desktopQuery.addListener === 'function') {
            desktopQuery.addListener(handleDesktopChange);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileMenu);
    } else {
        initMobileMenu();
    }
})();