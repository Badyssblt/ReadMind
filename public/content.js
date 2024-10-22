const currentUrl = window.location.href;

let token = "";
let slug = 0;


chrome.storage.local.get('token', (data) => {
    token = data.token;
    extractChapterInfo(currentUrl);
});

// const extractChapterInfo = (url) => {
//     const urlPattern = /^(https?:\/\/[^\/]+)\/(?:v\d{6}-(.+?)-chapitre-(\d+)(?:-v\d+)?\/|(.+?)-chapitre-(\d+)(?:-v\d+)?\/)/;
//     const match = url.match(urlPattern);
//
//     if (match) {
//         const domain = match[1];
//         const slug = match[2] || match[4];
//         const chapterNumber = match[3] || match[5];
//
//         setCurrentChapter({ domain, slug, chapterNumber });
//     } else {
//         console.log("L'URL ne correspond pas au pattern attendu");
//     }
// };

const extractChapterInfo = (url) => {
    // Dictionnaire des patterns pour chaque site, avec plusieurs formats possibles
    const patterns = {
        'manga-scantrad.io': [
            /^(https?:\/\/manga-scantrad\.io\/manga\/(.+?)\/chapitre-(\d+)\/)/
        ],
        'phenixscans.fr': [
            /^(https?:\/\/phenixscans\.fr\/v\d{6}-(.+?)-chapitre-(\d+)(?:-v\d+)?\/)/,
            /^(https?:\/\/phenixscans\.fr\/(.+?)-chapitre-(\d+)(?:-v\d+)?\/)/
        ]
    };

    // Extraire le domaine de l'URL
    const domainPattern = /https?:\/\/([^\/]+)/;
    const domainMatch = url.match(domainPattern);
    if (!domainMatch) {
        console.log("URL invalide");
        return;
    }

    const domain = domainMatch[1];

    // Vérifier si le domaine a des patterns correspondants
    const domainPatterns = patterns[domain];
    if (!domainPatterns) {
        console.log("Aucun pattern pour ce domaine");
        return;
    }

    // Essayer chaque pattern jusqu'à ce qu'on trouve une correspondance
    for (const urlPattern of domainPatterns) {
        const match = url.match(urlPattern);
        if (match) {
            const slug = match[2] || match[4];
            const chapterNumber = match[3] || match[5];

            setCurrentChapter({ domain, slug, chapterNumber });
            return;
        }
    }

    console.log("L'URL ne correspond à aucun des patterns pour ce domaine");
};





const setCurrentChapter = async (data) => {
    chrome.runtime.sendMessage({
        action: 'setCurrentChapter',
        token: token,
        data: {
            slug: data.slug,
            chapterNumber: data.chapterNumber
        }
    }, response => {
        // Vérifie que response n'est pas undefined avant de lire ses propriétés
        if (response && response.success) {
            console.log('Succès:', response.result);
        } else if (response) {
            console.error('Erreur:', response.error || response.status);
        } else {
            console.error('Erreur: Aucune réponse reçue du background script');
        }
    });
}

