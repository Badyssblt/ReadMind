<template>
  <div style="width: 400px">
    <p>Merci d'utiliser ReadMind !</p>

    <form @submit.prevent="saveToken">
      <p>Pour commencer, veuillez entrez votre token ici</p>
      <p>Pas de token ? <a href="http://localhost:3000/register" class="link" target="_blank">En obtenir un</a></p>
      <div class="input-label">
        <label for="token">Token</label>
        <input
            type="text"
            id="token"
            placeholder="Entrer votre token"
            v-model="token"
        />
      </div>

      <button type="submit">Envoyer</button>
    </form>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";

const token = ref('');
const savedToken = ref('');


const saveToken = () => {
  if (token.value.trim() === "") {
    console.error("Le token ne peut pas être vide.");
    return;
  }

  chrome.storage.local.set({ token: token.value }, () => {
    if (chrome.runtime.lastError) {
      console.error("Erreur lors de l'enregistrement du token : ", chrome.runtime.lastError);
    } else {
      savedToken.value = token.value;
    }
  });
};


const loadToken = () => {
  chrome.storage.local.get('token', (data) => {
    if (data.token) {
      token.value = data.token;
      savedToken.value = data.token;
    }
  });
};

onMounted(() => {
  loadToken();
});
</script>


<style scoped>

* {
  box-sizing: border-box;
}

.input-label {
  display: flex;
  flex-direction: column;
  margin-top: 10px;
}

.link {
  text-decoration: underline;
  color: #6FFF90;
}

input {
  background: none;
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: white;
  padding: 5px;
  border-radius: 2px;
}

button {
  width: 100%;
  background-color: #6fff90;
  border: none;
  padding: 5px 10px;
  color: black;
  border-radius: 2px;
  margin-top: 10px;
}
</style>