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
                'reddit-bg': '#DAE0E6',
                primary: {
                    50: '#f2f9ff',
                    100: '#e6f3ff',
                    200: '#bfe0ff',
                    300: '#99ccff',
                    400: '#66b3ff',
                    500: '#3399ff',
                    600: '#0080ff',
                    700: '#0066cc',
                    800: '#004c99',
                    900: '#003366',
                },
                secondary: {
                    DEFAULT: '#3399ff',
                    50: '#f2f9ff',
                    100: '#e6f3ff',
                    200: '#bfe0ff',
                    300: '#99ccff',
                    400: '#66b3ff',
                    500: '#3399ff',
                    600: '#0080ff',
                    700: '#0066cc',
                    800: '#004c99',
                    900: '#003366',
                },
                beige: {
                    50: '#fbfaf7',
                    100: '#f7f4ec',
                    200: '#efe6d4',
                    300: '#e6d6b8',
                    400: '#d8c19a',
                    500: '#c9ad78',
                    600: '#ae8f5f',
                    700: '#8f6f47',
                    800: '#654d32',
                    900: '#3b2d1c',
                },
            },
        },
    },

    plugins: [forms],
};