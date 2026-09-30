

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const supportsObserver = () => typeof IntersectionObserver !== 'undefined';

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Reveal an element the first time it scrolls into the viewport.
 * Bound from the Alpine directive below and, as a safety net, to any
 * `[x-reveal]` element on the page after boot (see the end of this file).
 */
const bindReveal = (el) => {
    if (el.dataset.revealBound === 'true') {
        return;
    }

    el.dataset.revealBound = 'true';

    if (prefersReducedMotion() || !supportsObserver()) {
        el.classList.add('is-revealed');

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                el.classList.add('is-revealed');
                observer.unobserve(el);
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -70px 0px' },
    );

    observer.observe(el);
};

Alpine.directive('reveal', (el) => bindReveal(el));

/**
 * Count a number up from zero once it scrolls into view.
 * Usage: <span x-counter="12800">12,800</span>
 */
const bindCounter = (el, target) => {
    if (el.dataset.counterBound === 'true') {
        return;
    }

    el.dataset.counterBound = 'true';

    const format = (value) =>
        new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 }).format(Math.round(value));

    if (prefersReducedMotion() || !supportsObserver()) {
        el.textContent = format(target);

        return;
    }

    el.textContent = format(0);

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                observer.disconnect();

                const duration = 1700;
                const startedAt = performance.now();

                const step = (now) => {
                    const progress = Math.min((now - startedAt) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);

                    el.textContent = format(target * eased);

                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                };

                requestAnimationFrame(step);
            });
        },
        { threshold: 0.4 },
    );

    observer.observe(el);
};

Alpine.directive('counter', (el, { expression }) => bindCounter(el, Number(expression) || 0));

/**
 * Move a soft radial highlight with the pointer.
 * Usage: <div class="..." x-spotlight>
 */
const bindSpotlight = (el) => {
    if (el.dataset.spotlightBound === 'true' || window.matchMedia('(hover: none)').matches) {
        return;
    }

    el.dataset.spotlightBound = 'true';
    el.classList.add('spotlight');

    el.addEventListener('pointermove', (event) => {
        const bounds = el.getBoundingClientRect();

        el.style.setProperty('--spot-x', `${event.clientX - bounds.left}px`);
        el.style.setProperty('--spot-y', `${event.clientY - bounds.top}px`);
    });
};

Alpine.directive('spotlight', (el) => bindSpotlight(el));

/**
 * Simulated live quotes for the marketing hero: prices drift a few
 * basis points every few seconds so the terminal feels alive.
 *
 * Usage: x-data="liveTicker(@js($quotes))"
 */
Alpine.data('liveTicker', (quotes = [], interval = 3200) => ({
    rows: quotes.map((quote) => ({ ...quote, direction: 0 })),
    timer: null,

    init() {
        if (prefersReducedMotion() || this.rows.length === 0) {
            return;
        }

        this.timer = setInterval(() => this.tick(), interval);
    },

    destroy() {
        clearInterval(this.timer);
    },

    tick() {
        this.rows = this.rows.map((row) => {
            const previous = Number(row.price);
            const drift = (Math.random() - 0.45) * 0.005;
            const price = Number(Math.max(1, previous * (1 + drift)).toFixed(2));
            const base = Number(row.previous_close) || previous;

            return {
                ...row,
                price,
                direction: price === previous ? 0 : price > previous ? 1 : -1,
                change: Number((price - base).toFixed(2)),
                change_percent: Number((((price - base) / base) * 100).toFixed(2)),
            };
        });
    },

    priceClass(row) {
        if (row.direction === 1) {
            return 'text-mint-300';
        }

        if (row.direction === -1) {
            return 'text-rose-300';
        }

        return 'text-white';
    },
}));

Alpine.start();

/**
 * Alpine only initialises trees rooted at `x-data`. Anything carrying these
 * directives outside such a tree (or inserted by a template later) would
 * otherwise stay in its pre-reveal state, so bind every occurrence once.
 */
document.querySelectorAll('[x-reveal]').forEach((el) => bindReveal(el));
document.querySelectorAll('[x-counter]').forEach((el) => bindCounter(el, Number(el.getAttribute('x-counter')) || 0));
document.querySelectorAll('[x-spotlight]').forEach((el) => bindSpotlight(el));

/**
 * Signals the inline guard in the page <head> that the bundle is running, so
 * the scroll-reveal styles are safe to keep hiding their targets.
 */
window.__alpineReady = true;
