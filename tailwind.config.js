import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Sora', 'Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                // Deep space background shades used by the marketing pages.
                ink: {
                    900: '#05060f',
                    800: '#0a0d1c',
                    700: '#101529',
                    600: '#171d36',
                },
                // Primary accent (indigo/violet) used for CTAs and glows.
                brand: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                },
                // "Money green" used for gains, badges and highlights.
                mint: {
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                },
            },

            boxShadow: {
                glow: '0 0 70px -18px rgba(99, 102, 241, 0.65)',
                'glow-mint': '0 0 70px -18px rgba(16, 185, 129, 0.6)',
                panel: '0 40px 90px -45px rgba(2, 6, 23, 0.95)',
                'panel-soft': '0 24px 60px -32px rgba(2, 6, 23, 0.8)',
                'inner-line': 'inset 0 1px 0 0 rgba(255, 255, 255, 0.06)',
            },

            backgroundImage: {
                'grid-line':
                    'linear-gradient(to right, rgba(148, 163, 184, 0.09) 1px, transparent 1px), linear-gradient(to bottom, rgba(148, 163, 184, 0.09) 1px, transparent 1px)',
                'radial-top': 'radial-gradient(ellipse at top, rgba(99, 102, 241, 0.28), transparent 62%)',
            },

            backgroundSize: {
                grid: '58px 58px',
            },

            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-16px)' },
                },
                'float-x': {
                    '0%, 100%': { transform: 'translateX(0)' },
                    '50%': { transform: 'translateX(14px)' },
                },
                'gradient-pan': {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
                'pulse-ring': {
                    '0%': { transform: 'scale(0.85)', opacity: '0.75' },
                    '70%': { transform: 'scale(1.75)', opacity: '0' },
                    '100%': { transform: 'scale(1.75)', opacity: '0' },
                },
                rise: {
                    from: { opacity: '0', transform: 'translateY(26px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                'spin-slow': {
                    to: { transform: 'rotate(360deg)' },
                },
                shimmer: {
                    '100%': { transform: 'translateX(100%)' },
                },
            },

            animation: {
                float: 'float 6s ease-in-out infinite',
                'float-slow': 'float 9s ease-in-out infinite',
                'float-x': 'float-x 11s ease-in-out infinite',
                'gradient-pan': 'gradient-pan 9s ease-in-out infinite',
                'pulse-ring': 'pulse-ring 2.8s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                rise: 'rise 0.75s cubic-bezier(0.22, 1, 0.36, 1) both',
                'spin-slow': 'spin-slow 26s linear infinite',
                shimmer: 'shimmer 2.6s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
