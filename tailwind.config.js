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
                white: '#f2f2f2',
                'bg-zinc-900': '#191919',
                'pure-white': '#ffffff',
                'orange-500': '#FF730D',
                'green-muted': '#62825B',
            },
            fontFamily: {
                main: ['"Inter"', 'sans-serif'],
                decorative: ['"Source Serif 4"', 'serif'],
            },
            fontSize: {
                '2m': '22px',
            },
        },
    },
    plugins: [],
}
