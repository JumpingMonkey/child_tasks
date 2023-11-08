/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/awcodes/filament-curator/resources/**/*.blade.php',
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.vue",
    
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

