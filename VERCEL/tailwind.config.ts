import type { Config } from "tailwindcss";

const config: Config = {
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        uptpc: {
          blue: "#0d4ea3",
          dark: "#082f6d",
          light: "#3b82f6",
          soft: "#edf4ff",
        },
      },
    },
  },
  plugins: [],
};
export default config;
