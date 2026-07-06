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
              50: '#ecf7ff', 100: '#d8efff', 200: '#b9e4ff', 300: '#85d4ff',
              400: '#45b8ff', 500: '#1495f0', 600: '#0a78d3', 700: '#0a61ab',
              800: '#0f528d', 900: '#124575'
            },
            health: '#008b58',
            mint: '#13c7a1',
            skyplus: '#55c6ff',
            violetplus: '#7b6dff',
            peach: '#ffb86b'
          },
          boxShadow: {
            soft: '0 20px 50px rgba(16, 72, 138, .12)',
            card: '0 14px 34px rgba(16, 72, 138, .10)',
            neon: '0 12px 30px rgba(20,149,240,.28)'
          },
          backgroundImage: {
            gridline: 'linear-gradient(rgba(255,255,255,.26) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.26) 1px, transparent 1px)'
          }
        },
    },

    plugins: [forms],
};


