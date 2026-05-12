module.exports = {
  content: [
    "./public/**/*.php",
    "./admin/**/*.php",
    "./app/**/*.php",
    "./src/**/*.php"
  ],
  theme: {
    extend: {
      fontFamily: {
        display: ["Fraunces", "Georgia", "Cambria", "Times New Roman", "serif"],
        serif: ["Fraunces", "Georgia", "Cambria", "Times New Roman", "serif"],
        editorial: ["Fraunces", "Georgia", "Cambria", "serif"],
        sans: ["Inter", "Nunito", "Avenir Next", "ui-sans-serif", "system-ui", "sans-serif"],
        mono: ["JetBrains Mono", "ui-monospace", "SFMono-Regular", "Menlo", "monospace"]
      },
      colors: {
        night: "#07111f",
        panel: "#0e1a2e",
        cyber: "#22d3ee",
        violet: "#8b5cf6",
        cream: "#fff7ed",
        tomato: "#d73b1f",
        herb: "#2f6f3e",
        parchment: "#fbf3e3",
        ivory: "#fffdf7",
        ink: "#1a1311",
        copper: "#b04827",
        saffron: "#e89e3c",
        olive: "#5a6d3f",
        fog: "#f4ebd9",
        embers: {
          50: "#fff5ec",
          100: "#fde4d0",
          200: "#fac9a4",
          300: "#f4a373",
          400: "#ec7748",
          500: "#d73b1f",
          600: "#b82d15",
          700: "#9c2a14",
          800: "#7a1f0f",
          900: "#5a1a0c"
        }
      },
      boxShadow: {
        "soft-1": "0 1px 2px rgba(26,19,17,0.04), 0 1px 1px rgba(26,19,17,0.03)",
        "soft-2": "0 4px 8px -2px rgba(26,19,17,0.06), 0 2px 4px -2px rgba(26,19,17,0.04)",
        "soft-3": "0 12px 24px -8px rgba(26,19,17,0.10), 0 4px 8px -4px rgba(26,19,17,0.06)",
        "soft-4": "0 24px 48px -12px rgba(26,19,17,0.18), 0 8px 16px -8px rgba(26,19,17,0.10)",
        editorial: "0 30px 60px -20px rgba(176,72,39,0.25), 0 8px 24px -8px rgba(26,19,17,0.10)",
        "inner-warm": "inset 0 1px 0 rgba(255,255,255,0.6), inset 0 -1px 0 rgba(176,72,39,0.08)",
        "glow-tomato": "0 0 0 1px rgba(215,59,31,0.20), 0 12px 32px -6px rgba(215,59,31,0.30)",
        "glow-saffron": "0 0 0 1px rgba(232,158,60,0.25), 0 12px 32px -6px rgba(232,158,60,0.25)",
        glow: "0 0 40px rgba(34, 211, 238, 0.18)"
      },
      backgroundImage: {
        grain: "url('/assets/img/textures/grain.svg')",
        paper: "url('/assets/img/textures/paper.svg')",
        "warm-radial":
          "radial-gradient(1200px 600px at 80% -10%, rgba(232,158,60,0.18), transparent 60%), radial-gradient(800px 500px at 0% 100%, rgba(215,59,31,0.10), transparent 55%)",
        "ink-radial":
          "radial-gradient(1200px 700px at 80% -10%, rgba(232,158,60,0.10), transparent 60%), radial-gradient(800px 500px at 0% 100%, rgba(176,72,39,0.10), transparent 55%)",
        "gold-hairline":
          "linear-gradient(90deg, transparent 0%, rgba(232,158,60,0.55) 50%, transparent 100%)"
      },
      keyframes: {
        "fade-up": {
          "0%": { opacity: "0", transform: "translateY(10px)" },
          "100%": { opacity: "1", transform: "translateY(0)" }
        },
        shimmer: {
          "0%": { backgroundPosition: "-200% 0" },
          "100%": { backgroundPosition: "200% 0" }
        },
        "pulse-warm": {
          "0%, 100%": { boxShadow: "0 0 0 0 rgba(215,59,31,0.0)" },
          "50%": { boxShadow: "0 0 0 8px rgba(215,59,31,0.15)" }
        }
      },
      animation: {
        "fade-up": "fade-up 600ms cubic-bezier(0.22, 1, 0.36, 1) both",
        shimmer: "shimmer 2.4s linear infinite",
        "pulse-warm": "pulse-warm 2.4s ease-in-out infinite"
      },
      transitionTimingFunction: {
        editorial: "cubic-bezier(0.22, 1, 0.36, 1)"
      }
    }
  },
  plugins: []
};
