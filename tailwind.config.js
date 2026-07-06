import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
             colors: {
                    brand: {
                    50: '#eef8ff', 100: '#d8efff', 200: '#b8e3ff', 300: '#89d0ff',
                    400: '#4ab4ff', 500: '#1894ef', 600: '#0876d1', 700: '#0660aa',
                    800: '#0a528d', 900: '#0d4775'
                    },
                    health: '#008b58'
                },
                boxShadow: {
                    soft: '0 18px 45px rgba(15, 82, 141, .10)',
                    card: '0 10px 28px rgba(2, 48, 92, .09)'
                }
        },
    },

    plugins: [forms],
};


