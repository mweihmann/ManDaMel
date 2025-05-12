$(document).ready(function () {
    $('#loginForm').on('submit', async function (e) {
        e.preventDefault();

        $('#login-error').text('');

        const login = $('#login').val().trim();
        const password = $('#password').val().trim();
        const remember = $('#remember').is(':checked');

        if (!login || !password) {
            $('#login-error').text("Please enter both login and password.");
            return;
        }

        try {
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
                    withCredentials: true
                }
            });

            if (response.token) {
                if (remember) {
                    localStorage.setItem('jwt', response.token);
                } else {
                    sessionStorage.setItem('jwt', response.token);
                }
                window.location.href = 'index.php';
            } else {
                $('#login-error').text(response.message || 'Login failed.');
            }
        } catch (err) {
            let msg = 'Login failed. Please try again.';
            if (err.responseJSON && err.responseJSON.message) {
                msg = err.responseJSON.message;
            }
            $('#login-error').text(msg);
            console.error(err);
        }
    });
});
