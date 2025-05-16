$(document).ready(function () {

    // Beim Absenden des Formulars
    $('#registerForm').on('submit', async function (e) {
        e.preventDefault();

        // Reguläre Ausdrücke zur Validierung
        const lettersOnlyRegex = /^[A-Za-zÄäÖöÜüß\s\-]+$/;      // Nur Buchstaben, Leerzeichen, Bindestrich
        const numberRegex = /^[0-9]+$/;                         // Nur Ziffern
        const zipRegex = /^\d{4,10}$/;                          // Postleitzahl (4–10 Ziffern)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;        // E-Mail-Prüfung
        const ibanRegex = /^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$/i; // IBAN-Prüfung
        const ccRegex = /^\d{12,19}$/;                          // Kreditkartennummer: 12–19 Ziffern

        // Werte aus dem Formular abrufen und trimmen
        const firstName = $('#firstname').val().trim();
        const lastName = $('#lastname').val().trim();
        const street = $('#street').val().trim();
        const houseNumber = $('#house_number').val().trim();
        const zip = $('#zip').val().trim();
        const city = $('#city').val().trim();
        const country = $('#country').val().trim();
        const phone = $('#telephone').val().trim();
        const email = $('#email').val().trim();
        const username = $('#username').val().trim();
        const password = $('#password').val().trim();
        const confirm = $('#confirm_password').val().trim();
        const payment = $('#payment').val().trim();

        // IDs aller Pflichtfelder
        const requiredFields = [
            '#salutation', '#firstname', '#lastname', '#street',
            '#house_number', '#zip', '#city', '#country',
            '#email', '#username', '#password', '#confirm_password'
        ];

        let errors = [];

        // Überprüfen ob alle Pflichtfelder ausgefüllt sind
        requiredFields.forEach(selector => {
            const val = $(selector).val().trim();
            if (!val) {
                errors.push(`${selector.replace('#', '')} is required.`);
                $(selector).addClass('is-invalid');
            } else {
                $(selector).removeClass('is-invalid');
            }
        });

        // Einzelvalidierungen

        // Vorname und Nachname nur Buchstaben
        if (!lettersOnlyRegex.test(firstName)) {
            errors.push("First name must contain only letters.");
            $('#firstname').addClass('is-invalid');
        } else {
            $('#firstname').removeClass('is-invalid');
        }

        if (!lettersOnlyRegex.test(lastName)) {
            errors.push("Last name must contain only letters.");
            $('#lastname').addClass('is-invalid');
        } else {
            $('#lastname').removeClass('is-invalid');
        }

        // Straße nur Buchstaben
        if (!lettersOnlyRegex.test(street)) {
            errors.push("Street must contain only letters.");
            $('#street').addClass('is-invalid');
        } else {
            $('#street').removeClass('is-invalid');
        }

        // Hausnummer nur Zahlen
        if (!numberRegex.test(houseNumber)) {
            errors.push("House number must be numeric.");
            $('#house_number').addClass('is-invalid');
        } else {
            $('#house_number').removeClass('is-invalid');
        }

        // Postleitzahl nur Zahlen (4–10 Ziffern)
        if (!zipRegex.test(zip)) {
            errors.push("Please enter a valid postal code.");
            $('#zip').addClass('is-invalid');
        } else {
            $('#zip').removeClass('is-invalid');
        }

        // Stadt und Land nur Buchstaben
        if (!lettersOnlyRegex.test(city)) {
            errors.push("City must contain only letters.");
            $('#city').addClass('is-invalid');
        } else {
            $('#city').removeClass('is-invalid');
        }

        if (!lettersOnlyRegex.test(country)) {
            errors.push("Country must contain only letters.");
            $('#country').addClass('is-invalid');
        } else {
            $('#country').removeClass('is-invalid');
        }

        // Telefonnummer nur Zahlen
        if (phone && !numberRegex.test(phone)) {
            errors.push("Phone number must contain only digits.");
            $('#telephone').addClass('is-invalid');
        } else {
            $('#telephone').removeClass('is-invalid');
        }

        // E-Mail prüfen
        if (!emailRegex.test(email)) {
            errors.push("Please enter a valid email address.");
            $('#email').addClass('is-invalid');
        } else {
            $('#email').removeClass('is-invalid');
        }

        // Benutzername nur Buchstaben
        if (!lettersOnlyRegex.test(username)) {
            errors.push("Username must contain only letters.");
            $('#username').addClass('is-invalid');
        } else {
            $('#username').removeClass('is-invalid');
        }

        // Passwortlänge prüfen
        if (password.length < 6) {
            errors.push("Password must be at least 6 characters long.");
            $('#password').addClass('is-invalid');
        } else {
            $('#password').removeClass('is-invalid');
        }

        // Passwortvergleich
        if (password !== confirm) {
            errors.push("Passwords do not match.");
            $('#confirm_password').addClass('is-invalid');
        } else {
            $('#confirm_password').removeClass('is-invalid');
        }

        // Zahlungsinformationen (mind. IBAN oder Kreditkarte)
        if (payment && !(ibanRegex.test(payment) || ccRegex.test(payment))) {
            errors.push("Please enter valid payment information (IBAN or Credit Card).");
            $('#payment').addClass('is-invalid');
        } else {
            $('#payment').removeClass('is-invalid');
        }

        // Falls Fehler vorhanden, abbrechen und anzeigen
        if (errors.length > 0) {
            alert(errors.join("\n"));
            return;
        }

        // Daten-Objekt für die API
        const data = {
            pronouns: $('#salutation').val(),
            given_name: $('#firstname').val(),
            surname: $('#lastname').val(),
            street: $('#street').val(),
            house_number: $('#house_number').val(),
            postal_code: $('#zip').val(),
            city: $('#city').val(),
            country: $('#country').val(),
            telephone: $('#telephone').val() || '',
            email: $('#email').val(),
            username: $('#username').val(),
            password: $('#password').val(),
            confirm_password: $('#confirm_password').val(),
            payment: $('#payment').val()
        };

        // AJAX-Request an das Backend
        try {
            const response = await $.ajax({
                url: 'http://localhost:5000/api/register.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
            });

            // Erfolgreich registriert
            if (response.token) {
                localStorage.setItem('jwt', response.token); // Token speichern
                alert('Registration successful!');
                window.location.href = 'index.php'; // Weiterleitung
            } else {
                alert(response.message || 'Registration failed.');
            }
        } catch (err) {
            // Fehler bei der Anfrage
            alert(err.responseJSON?.message || "An error occurred during registration.");
            console.error(err);
        }
    });
});
