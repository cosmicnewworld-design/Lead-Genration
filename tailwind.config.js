
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#4F46E5',
                secondary: '#EC4899',
                accent: '#10B981',
                'base-100': '#FFFFFF',
                'base-200': '#F9FAFB',
                'base-300': '#F3F4F6',
            },
            backgroundImage: {
                'noise': "url('/images/noise.png')",
            },
            boxShadow: {
                'deep': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
                'glow': '0 0 15px 5px rgba(79, 70, 229, 0.5)',
            }
        },
    },

    plugins: [forms],
};
