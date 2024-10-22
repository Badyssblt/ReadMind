<script setup >
import axios from "axios";
import {onMounted} from "vue";

const props = defineProps(['book'])

const image = ref("");

const { $api } = useNuxtApp();

const isWebView = () => {
  const userAgent = navigator.userAgent || navigator.vendor || window.opera;

  return /wv/.test(userAgent) || /iPhone|iPad|iPod/.test(userAgent) && /Safari/.test(userAgent) || /Android/.test(userAgent) && /Chrome/.test(userAgent);
};



const getImage = async () => {
  try {
    const response = await $api.get('/api/manga', {
      params: {
        title: props.book.name,
        limit: 1,
        includes: 'cover_art',
      },
      responseType: 'blob' // Indique que tu attends un blob
    });

    const imageUrl = URL.createObjectURL(response.data);

    image.value = imageUrl;

  } catch (error) {
    console.error('Erreur lors de la récupération de l\'image:', error);
  }
};


onMounted(() => {
  getImage()
})
</script>

<template>
  <NuxtLink :to="isWebView() ? '' : book.currentURL" target="_blank" rel="noopener noreferrer">
    <div class="w-72">
      <div class="w-full h-96 overflow-hidden">
        <img :src="image"
             alt=""
             class="w-full h-full object-cover">
      </div>
      <p class="text-center my-4">{{ book.name }}</p>
      <p>Chapitre en cours: <span>{{ book.currentChapter }}</span></p>
    </div>
  </NuxtLink>

</template>

<style scoped>

</style>