module.exports = {
  content: [
    "./public/**/*.php",
    "./admin/**/*.php",
    "./app/**/*.php"
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Nunito", "Avenir Next", "ui-sans-serif", "system-ui", "sans-serif"],
        editorial: ["Georgia", "Cambria", "Times New Roman", "serif"]
      },
      colors: {
        night: "#07111f",
        panel: "#0e1a2e",
        cyber: "#22d3ee",
        violet: "#8b5cf6",
        cream: "#fff7ed",
        tomato: "#d73b1f",
        herb: "#2f6f3e"
      },
      boxShadow: {
        glow: "0 0 40px rgba(34, 211, 238, 0.18)"
      }
    }
  },
  plugins: []
};
