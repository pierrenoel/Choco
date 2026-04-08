import { defineConfig } from "vite";

export default defineConfig({
  root: "resources",
  build: {
    outDir: "../public/build",
    emptyOutDir: true,
    manifest: true,
  },
});
