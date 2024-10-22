<script setup>
defineProps(['url'])
</script>

<template>
  <NuxtLink class="bg-primary text-black px-6 py-2 rounded" :to="url">
    <slot/>
  </NuxtLink>
</template>

<style scoped>

</style>