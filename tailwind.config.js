export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#0b1f3a",      // navy
        secondary: "#2563eb",    // blue action
        success: "#16a34a",
        warning: "#facc15",
        danger: "#dc2626",
        muted: "#f1f5f9",
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}
