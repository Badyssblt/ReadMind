<script setup>
import { useAuth } from "~/store/auth.js";

const store = useAuth();
const isAuthenticated = ref(store.isAuthenticated);

const menuVisible = ref(false);

watch(
    () => store.isAuthenticated,
    (newVal) => {
      isAuthenticated.value = newVal;
    }
);

</script>

<template>
  <header class="py-4">
    <menu class="flex justify-between items-center mx-auto max-w-screen-xl">
      <div class="flex gap-4 items-end">
        <NuxtLink to="/"><h1 class="font-bold text-xl">ReadMind</h1></NuxtLink>
      </div>
      <div>
        <NuxtLink to="#how">Comment ça marche ?</NuxtLink>
      </div>
      <div class="flex gap-4 items-center" v-if="!isAuthenticated">
        <NuxtLink to="/login">Se connecter</NuxtLink>
        <NavLink url="/register">S'inscrire</NavLink>
      </div>
      <div class="flex gap-4 items-center" v-else>
        <NavLink url="/dashboard">Mon compte</NavLink>
      </div>
    </menu>
  </header>
</template>
