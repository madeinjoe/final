/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.{php,html,js}",
    "./template-parts/*.{php,html,js}",
    "./woocommerce/*.{php,html,js}",
    "./woocommerce/**/*.{php,html,js}",
    "./**/*.{php,html,js}"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}