document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('add-product-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);

        fetch('http://localhost:5000/api/add_product.php', {
            method: 'POST',
            body: formData
        })
        .then(async res => {
            const responseText = await res.text();
            console.log('Antwort vom Server:', responseText);
        
            try {
                const data = JSON.parse(responseText);
                if (data.status === 'success') {
                    alert('Produkt erfolgreich hinzugefügt!');
                    form.reset();
                } else {
                    alert('Fehler beim Hinzufügen: ' + (data.message || 'Unbekannter Fehler'));
                }
            } catch (e) {
                alert('Fehler beim Verarbeiten der Serverantwort.');
                console.error('JSON Parse Error:', e, responseText);
            }
        })
        .catch(error => {
            console.error('Netzwerkfehler:', error);
            alert('Fehler beim Hochladen.');
        });
        
    });
});
