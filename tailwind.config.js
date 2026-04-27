import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#fbbf24', // Amber-400
                    hover: '#f59e0b',   // Amber-500
                    light: '#fcd34d',   // Amber-300
                },
                navy: {
                    900: '#0B1120',
                    950: '#020617',
                }
            },
            boxShadow: {
                'glow-blue': '0 0 20px rgba(22, 86, 209, 0.4)',
                'glow-orange': '0 0 20px rgba(234, 88, 12, 0.4)',
            },
        },
    },

    plugins: [forms],
};
