/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './index.html',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                'white': '#f2f2f2',
            },
        },
    },
    plugins: [],
}
