$(document).ready(function () {

    // Anzeige der Zahlungsfelder abhängig von der Auswahl
    $('#payment_method').on('change', function () {
        const method = $(this).val();
        if (method === 'iban') {
            $('#iban_group').removeClass('d-none');
            $('#creditcard_group').addClass('d-none');
        } else if (method === 'creditcard') {
            $('#creditcard_group').removeClass('d-none');
            $('#iban_group').addClass('d-none');
        } else {
            $('#iban_group').addClass('d-none');
            $('#creditcard_group').addClass('d-none');
        }
    });


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
        const ccExpiryRegex = /^(0[1-9]|1[0-2])\/\d{4}$/;     // MM/YYYY mit gültigem Monat
        const ccCvvRegex = /^\d{3,4}$/;                       // 3–4 Ziffern


        // Werte aus dem Formular abrufen und trimmen
        const firstName = $('#firstname').val()?.trim() || '';
        const lastName = $('#lastname').val()?.trim() || '';
        const street = $('#street').val()?.trim() || '';
        const houseNumber = $('#house_number').val()?.trim() || '';
        const zip = $('#zip').val()?.trim() || '';
        const city = $('#city').val()?.trim() || '';
        const country = $('#country').val()?.trim() || '';
        const phone = $('#telephone').val()?.trim() || '';
        const email = $('#email').val()?.trim() || '';
        const username = $('#username').val()?.trim() || '';
        const password = $('#password').val()?.trim() || '';
        const confirm = $('#confirm_password').val()?.trim() || '';
        const holderName = $('#holder_name').val()?.trim() || '';
        const paymentMethod = $('#payment_method').val();
        const ccExpiry = paymentMethod === 'creditcard' ? ($('#creditcard_expiry').val()?.trim() || '') : '';
        const ccCvv = paymentMethod === 'creditcard' ? ($('#creditcard_cvv').val()?.trim() || '') : '';
        
        

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
        if (holderName && !lettersOnlyRegex.test(holderName)) {
            errors.push("Holder name must contain only letters.");
            $('#holder_name').addClass('is-invalid');
        } else {
            $('#holder_name').removeClass('is-invalid');
        }

        if (paymentMethod === 'creditcard') {
            if (!ccRegex.test($('#creditcard_number').val().trim())) {
                errors.push("Credit card number must be 12–19 digits.");
                $('#creditcard_number').addClass('is-invalid');
            } else {
                $('#creditcard_number').removeClass('is-invalid');
            }
            if (!ccExpiryRegex.test(ccExpiry)) {
                errors.push("Credit card expiry must be in MM/YYYY format.");
                $('#creditcard_expiry').addClass('is-invalid');
            } else {
                $('#creditcard_expiry').removeClass('is-invalid');
            }
            if (!ccCvvRegex.test(ccCvv)) {
                errors.push("CVV must be 3 or 4 digits.");
                $('#creditcard_cvv').addClass('is-invalid');
            } else {
                $('#creditcard_cvv').removeClass('is-invalid');
            }
        } else if (paymentMethod === 'iban') {
            if (!ibanRegex.test($('#iban').val().trim())) {
                errors.push("Invalid IBAN format.");
                $('#iban').addClass('is-invalid');
            } else {
                $('#iban').removeClass('is-invalid');
            }
        }
        
        // Falls Fehler vorhanden, abbrechen und anzeigen
        if (errors.length > 0) {
            alert(errors.join("\n"));
            return;
        }

        const data = {
            pronouns: $('#salutation').val(),
            given_name: firstName,
            surname: lastName,
            street,
            house_number: houseNumber,
            postal_code: zip,
            city,
            country,
            telephone: phone,
            email,
            username,
            password,
            confirm_password: confirm,
            holder_name: holderName || (firstName + ' ' + lastName),
            iban: paymentMethod === 'iban' ? $('#iban').val()?.trim() || null : null,
            creditcard_number: paymentMethod === 'creditcard' ? $('#creditcard_number').val()?.trim() || null : null,
            creditcard_expiry: paymentMethod === 'creditcard' ? ccExpiry : null,
            creditcard_cvv: paymentMethod === 'creditcard' ? ccCvv : null
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
