// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      API_URL: "http://localhost:8215",
      // API_URL: "https://readmind.badyssblilita.fr/v1"
    }
  },
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  router: {
    scrollBehavior(to, from, savedPosition) {
      if (savedPosition) {
        return savedPosition;
      } else if (to.hash) {
        return {
          el: to.hash,
          behavior: 'smooth',
        };
      } else {
        return { top: 0, behavior: 'smooth' };
      }
    },
  },
  css: ['~/assets/css/main.css'],
  modules: ['@pinia/nuxt', '@pinia-plugin-persistedstate/nuxt'],
})