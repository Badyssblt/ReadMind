<script setup>
import axios from "axios";

useHead({
  title: 'Mon compte'
})
const { $api } = useNuxtApp();

const books = ref([]);
const token = ref("");
const loadingToken = ref(false);
const copied = ref(false);
const searchTerm = ref("");

const $config = useRuntimeConfig();

const route = useRoute();

const userToken = ref('');

const getBooks = async () => {
  try {
    let response = null;
    if(userToken.value){
      response = await axios.get($config.public.API_URL + '/api/books', {
        params: {
          token: userToken.value
        }
      })

    } else {
      response = await $api.get('/api/books');
    }
    books.value = response.data.member;
  } catch (e) {
    console.log(e);
  }
};

const getToken = async () => {
  loadingToken.value = true;
  try {
    const response = await $api.get('/api/me');
    token.value = response.data.member[0];
    console.log(token.value);
  } catch (e) {
    console.log(e);
  }
  loadingToken.value = false;
};

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(token.value);
    copied.value = true;
  } catch (e) {
    console.log(e);
  }
};

const filteredBooks = computed(() => {
  return books.value.filter(book =>
      book.name.toLowerCase().includes(searchTerm.value.toLowerCase())
  );
});

onMounted(() => {
  userToken.value = route.query.token;

  getBooks();

});
</script>


<template>
  <div class="mx-auto max-w-screen-xl">
    <div class="flex flex-col my-6">
      <p class="text-lg font-semibold">Mon token</p>
      <div class="flex gap-4">
        <p v-if="token" class="border border-white/20 px-4 py-2 rounded">{{ token }}</p>
        <button v-if="token" @click="copyToClipboard" class="flex gap-4 items-center">Copier
          <svg v-if="copied" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
          </svg>
        </button>
        <Button v-else @click="getToken" class="flex items-center gap-4">Obtenir mon token
          <Loader v-if="loadingToken" />
        </Button>
      </div>
      <p class="text-white/60 mt-2">Ce token est à entrer dans l'extension ReadMind</p>
    </div>

    <div class="border-b pb-6 border-white/20">
      <h2 class="font-semibold text-xl">Tous mes livres suivis</h2>
      <form>
        <Input v-model="searchTerm" label="Rechercher par nom" placeholder="One piece" class="mt-4" />
      </form>
    </div>

    <div class="flex flex-wrap justify-center md:justify-start mt-4 gap-6">
      <BookCard v-for="book in filteredBooks" :key="book.id" :book="book" />
    </div>
  </div>
</template>


<style scoped>

</style>