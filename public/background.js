chrome.runtime.onMessage.addListener(async (message, sender, sendResponse) => {
    if (message.action === 'setCurrentChapter') {

        try {
            const response = await fetch(`https://readmind.badyssblilita.fr/v1/api/book/${message.data.slug}?token=${message.token}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/merge-patch+json',
                },
                body: JSON.stringify({
                    chapter: parseInt(message.data.chapterNumber)
                })
            });

            if (response.ok) {
                const result = await response.json();
                console.log("Succès:", result);
                sendResponse({ success: true, result });
            } else {
                console.log("Erreur de réponse:", response);
                sendResponse({ success: false, status: response.status });
            }
        } catch (e) {
            console.log("Erreur dans la requête:", e);
            sendResponse({ success: false, error: e.message });
        }

        return true;
    }
    console.log("current")

});
