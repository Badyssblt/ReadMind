<script setup >
import axios from "axios";
import {onMounted} from "vue";

const props = defineProps(['book'])

const image = ref("");

const { $api } = useNuxtApp();

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
  <div class="w-72">
    <div class="w-full h-96 overflow-hidden">
      <img :src="image"
           alt=""
           class="w-full h-full object-cover">
    </div>
    <p class="text-center my-4">{{ book.name }}</p>
    <p>Chapitre en cours: <span>{{ book.currentChapter }}</span></p>
  </div>
</template>

<style scoped>

</style>