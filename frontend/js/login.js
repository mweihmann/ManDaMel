$(document).ready(function () {
    // Login-Formular-Submit abfangen
    $('#loginForm').on('submit', async function (e) {
        e.preventDefault(); // Standardverhalten verhindern

        $('#login-error').text(''); // Fehlermeldung zurücksetzen

        const login = $('#login').val().trim();
        const password = $('#password').val().trim();
        const remember = $('#remember').is(':checked');

        // Prüfung: Beide Felder ausgefüllt?
        if (!login || !password) {
            $('#login-error').text("Please enter both login and password.");
            return;
        }

        try {
            // AJAX-Login-Anfrage
            const response = await $.ajax({
                url: 'http://localhost:5000/api/login.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    login: login,
                    password: password,
                    remember: remember
                }),
                xhrFields: {
                    withCredentials: true // Cookie-Unterstützung (optional)
                }
            });

            // Token erhalten → speichern & weiterleiten
            if (response.token) {
                if (remember) {
                    localStorage.setItem('jwt', response.token); // dauerhaft
                } else {
                    sessionStorage.setItem('jwt', response.token); // bis Sitzungsende
                }
                window.location.href = 'index.php'; // Weiterleitung nach Login
            } else {
                $('#login-error').text(response.message || 'Login failed.');
            }
        } catch (err) {
            // Fehlerbehandlung bei Verbindungs- oder Serverfehler
            let msg = 'Login failed. Please try again.';
            if (err.responseJSON && err.responseJSON.message) {
                msg = err.responseJSON.message;
            }
            $('#login-error').text(msg);
            console.error(err);
        }
    });
});
