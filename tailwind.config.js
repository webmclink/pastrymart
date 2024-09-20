import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      fontFamily: {
          sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
    }
  },
  plugins: [
      require('flowbite/plugin')
  ],
}

