/** @type {import('tailwindcss').Config} */
module.exports = {
  important: true,
  content: [
    // "./*.{php,html,js}",
    "./template-parts/**.{php,html,js}",
    "./woocommerce/*.{php,html,js}",
    "./modules/woocommerce/*.{php,html,js}",
    "./assets/src/**/*.{php,html,js}",
    // "./**/*.{php,html,js}"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}