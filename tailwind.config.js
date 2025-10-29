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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // ✨ Custom colors dari Figma
                primary: {
                    DEFAULT: '#245BCA',
                    light: '#4E82EA',
                    dark: '#00194A',
                },
                success: {
                    DEFAULT: '#00BF63',
                },
                warning: {
                    DEFAULT: '#FFCD29',
                },
                danger: {
                    DEFAULT: '#F2F6FF',
                },
                gray: {
                    light: '#AAAAAA',
                    DEFAULT: '#A0A0A0',
                    dark: '#221112',
                },
            },
        },
    },

    plugins: [forms],
};