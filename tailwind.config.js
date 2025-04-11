import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f5f7ff',
                    100: '#ecf0ff',
                    200: '#d9e0ff',
                    300: '#b9c6ff',
                    400: '#8c9eff',
                    500: '#6b78ff',
                    600: '#4f58f5',
                    700: '#4546dd',
                    800: '#3a3bb3',
                    900: '#33368e',
                    950: '#232254',
                },
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '2rem',
            }
        },
    },

    plugins: [forms, typography],
};
