// Wenn das DOM vollständig geladen ist
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('add-product-form');

    // Wenn kein Formular vorhanden ist, abbrechen
    if (!form) return;

    // Beim Absenden des Formulars
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Standardverhalten (Neuladen) verhindern

        const formData = new FormData(form); // Formulardaten sammeln

        // Anfrage an die API zum Hinzufügen des Produkts senden
        fetch('http://localhost:5000/api/add_product.php', {
            method: 'POST',
            body: formData
        })
            .then(async res => {
                const responseText = await res.text();
                console.log('Antwort vom Server:', responseText); // Serverantwort im Log anzeigen

                try {
                    const data = JSON.parse(responseText); // Antwort als JSON parsen
                    if (data.status === 'success') {
                        alert('Produkt erfolgreich hinzugefügt!');
                        form.reset(); // Formular zurücksetzen
                    } else {
                        alert('Fehler beim Hinzufügen: ' + (data.message || 'Unbekannter Fehler'));
                    }
                } catch (e) {
                    // Fehler beim Parsen der JSON-Antwort
                    alert('Fehler beim Verarbeiten der Serverantwort.');
                    console.error('JSON Parse Error:', e, responseText);
                }
            })
            .catch(error => {
                // Netzwerkfehler behandeln
                console.error('Netzwerkfehler:', error);
                alert('Fehler beim Hochladen.');
            });

    });
});
