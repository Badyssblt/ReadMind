import { jwtDecode } from "jwt-decode";

export const useAuth = defineStore(
    "auth",
    () => {
        const user = ref(null);
        const token = ref("");

        const isAuthenticated = computed(() => user.value !== null && !isTokenExpired());

        const authenticate = (newUser, newToken) => {
            user.value = newUser;
            token.value = newToken;
        };

        const logout = () => {
            user.value = null;
            token.value = null;
        };

        const isTokenExpired = () => {
            if (!token.value) return true; // Si pas de token, il est considéré comme expiré
            const decodedToken = jwtDecode(token.value);
            const currentTime = Date.now() / 1000; // Temps actuel en secondes
            return decodedToken.exp < currentTime; // Vérifie si le token a expiré
        };

        return {
            user,
            authenticate,
            token,
            isAuthenticated,
            logout,
            isTokenExpired
        };
    },
    {
        persist: {
            storage: persistedState.cookiesWithOptions({
                sameSite: "strict",
            }),
        },
    }
);
