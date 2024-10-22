<template>
  <div ref="stickyElement" :class="{ 'is-sticky': isSticky }" class="sticky-container">
    <slot></slot> <!-- Contenu passé à l'intérieur du composant -->
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';

// Référence de l'élément sticky
const stickyElement = ref(null);

// Variable pour gérer l'état sticky
const isSticky = ref(false);

// Stocker la position de départ de l'élément
const offsetTop = ref(0);

// Fonction pour gérer le scroll
const handleScroll = () => {
  // Vérifier la position du scroll et mettre à jour l'état sticky
  if (window.scrollY > offsetTop.value) {
    isSticky.value = true;
  } else {
    isSticky.value = false;
  }
};

// Hook pour l'initialisation
onMounted(() => {
  // Obtenir la position initiale de l'élément sticky
  offsetTop.value = stickyElement.value.offsetTop;

  // Écouter l'événement de scroll sur la fenêtre
  window.addEventListener('scroll', handleScroll);
});

// Retirer l'écouteur de scroll quand le composant est démonté
onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>


<style scoped>
/* Conteneur qui deviendra sticky */
.sticky-container {
  position: sticky;
  height: fit-content;
  top: 0; /* Le point à partir duquel l'élément devient sticky */
  z-index: 100; /* Pour s'assurer que l'élément reste au-dessus d'autres éléments */
  padding: 10px; /* Optionnel : ajuster le padding */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optionnel : donner un léger effet d'ombre lorsqu'il devient sticky */
}
</style>
