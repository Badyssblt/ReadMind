<script setup>
import { jwtDecode } from "jwt-decode";
import {useAuth} from "~/store/auth.js";

useHead({
  title: "S'inscrire"
})

const { $api } = useNuxtApp();

const email = ref('');
const password = ref('');

const store = useAuth();

const register = async () => {
  try {
    const response = await $api.post('/api/users', {
      email: email.value,
      password: password.value
    });

    navigateTo('/login');
  }catch (e) {
    console.log(e)
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <h2 class="text-center text-3xl font-bold my-4">Inscrivez vous sur ReadMind .</h2>
    <form @submit.prevent="register" class="w-1/2 m-auto">
      <Input label="Votre email" placeholder="example@gmail.com" v-model="email" class="mb-4"/>
      <Input label="Votre mot de passe" placeholder="*******" type="password" v-model="password" class="mb-8"/>
      <Button class="w-full">Je m'inscris</Button>
      <p class="text-center my-4">Ou</p>
      <NuxtLink class="underline underline-offset-4 flex justify-center text-primary font-semibold" to="/register">Je me connecte</NuxtLink>
    </form>

  </div>

</template>

<style scoped>

</style>