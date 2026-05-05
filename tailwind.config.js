module.exports = {
  content: [
    "./public/**/*.php",
    "./admin/**/*.php",
    "./app/**/*.php"
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Inter", "ui-sans-serif", "system-ui", "sans-serif"]
      },
      colors: {
        night: "#07111f",
        panel: "#0e1a2e",
        cyber: "#22d3ee",
        violet: "#8b5cf6"
      },
      boxShadow: {
        glow: "0 0 40px rgba(34, 211, 238, 0.18)"
      }
    }
  },
  plugins: []
};
