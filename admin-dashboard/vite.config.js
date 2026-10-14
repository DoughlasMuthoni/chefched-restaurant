import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'

// Builds straight into ../admin so the output can be deployed to
// https://yourdomain.com/admin/ as plain static files — no Node server
// needed on the shared host, only the PHP API is server-side.
export default defineConfig({
  plugins: [react()],
  base: '/admin/',
  build: {
    outDir: '../admin',
    emptyOutDir: true,
  },
  server: {
    proxy: {
      '/api': 'http://localhost:8080',
    },
  },
})
