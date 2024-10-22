<script setup >
useHead({
  title: 'Accueil'
})

const isMobile = ref(false);

onMounted(() => {
  const userAgent = navigator.userAgent.toLowerCase();
  isMobile.value = /android|iphone|ipad|ipod/.test(userAgent)
})
</script>

<template>
  <div class="w-32 h-32 bg-primary rounded-full blur-3xl absolute top-24"></div>
  <div class="w-32 h-32 bg-primary rounded-full blur-3xl absolute bottom-1/2 right-48"></div>
  <div class="flex flex-col items-center md:mt-24 min-h-screen">
    <h2 class="text-6xl font-medium text-center">Souvenez vous de vos lectures avec ReadMind<br/></h2>
    <p class="text-center opacity-60 my-6">L'extension ReadMind vous permet de garder en mémoire tous vos livres et les derniers chapitres que vous avez lus.</p>
    <div class="flex gap-4">
      <NavLink to="/register">Inscrivez vous</NavLink>
      <a v-if="!isMobile" href="/data/readmind.zip" class="flex items-center">Télécharger l'extension</a>
      <a v-else href="/data/readmind.apk" class="flex items-center">Télécharger l'application</a>
    </div>
  </div>
  <div class="mx-auto max-w-screen-xl min-h-screen" id="how">
    <h3 class="text-4xl font-bold text-center">Comment ça marche ?</h3>
    <div class="flex mt-24">
      <Sticky class="flex flex-col gap-20 border-r pr-8 border-white items-start">
        <NuxtLink href="#account" class="font-bold" @click="changeSection">1. Créer votre compte</NuxtLink>
        <NuxtLink href="#token" class="font-bold" @click="changeSection">2. Obtenez votre token</NuxtLink>
        <NuxtLink href="#extension" class="font-bold text-start" @click="changeSection">3. Entrez dans l'extension ReadMind</NuxtLink>
        <NuxtLink href="#website" class="font-bold text-start" @click="changeSection">4. Rendez-vous sur les sites web pris en charge</NuxtLink>
      </Sticky>
      <div class="flex flex-col items-center w-full">
        <div class="text-center h-screen" id="account">
          <h4 class="text-xl font-semibold">Créer mon compte</h4>
          <div class="mt-2">
            <p>Tout d'abords, rendez-vous sur <a href="https://readmind.badyssblilita.fr/login" class="underline text-primary">la page connexion</a> ou
              <a href="https://readmind.badyssblilita.fr" class="underline text-primary">d'inscription</a> pour accéder à votre compte.</p>
          </div>
        </div>
        <div class="text-center h-screen" id="token">
          <h4 class="text-xl font-semibold">Obtenir mon token</h4>
          <div class="mt-2">
            <p>Après avoir créer votre compte, vous pouvez accéder à <a href="https://readmind.badyssblilita.fr/dashboard" class="underline text-primary">votre compte (ou dashboard)</a> </p>
            <p>En accédant à votre compte, cliquez sur "Obtenir mon token" pour afficher votre token.</p>
            <img src="/images/img1.png" alt="Une image d'illustration pour montrer comment obtenir son token pour l'extension ReadMind" class="mt-4">
            <p class="my-4">Ensuite, cliquez simplement sur "Copier"</p>
          </div>
        </div>
        <div class="text-center h-screen" id="extension">
          <h4 class="text-xl font-semibold">Entrer mon token dans l'extension ReadMind</h4>
          <div class="mt-2">
            <p>Maintenant que votre token est copié, cliquez sur vos extensions dans votre navigateur, puis cliquez sur l'extension ReadMind. </p>
            <div class="flex justify-center my-6">
              <img src="/images/extension1.png" alt="">
            </div>
            <p>Rentrez maintenant votre token dans le champ "Token" puis cliquez sur envoyer.</p>
            <div class="flex justify-center my-6">
              <img src="/images/extension2.png" alt="Une image d'illustration pour montrer comment obtenir son token pour l'extension ReadMind" class="mt-4">
            </div>
          </div>
        </div>
        <div class="text-center h-screen" id="website">
          <h4 class="text-xl font-semibold">Site compatible avec ReadMind</h4>
          <div class="mt-2">
            <p class="mt-6">Voici une liste où l'extension ReadMind fonctionne :</p>
            <ul class="list-disc mt-8 text-start">
              <li><a href="https://phenixscans.fr">PhénixScan</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>


<style scoped>

</style>