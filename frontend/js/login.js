$(document).ready(function () {
    $('#loginForm').on('submit', async function (e) {
        e.preventDefault();

        const login = $('#login').val().trim();
        const password = $('#password').val().trim();
        const remember = $('#remember').is(':checked');

        if (!login || !password) {
            alert("Please enter both login and password.");
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
                alert('Login successful!');
                window.location.href = 'index.php';
            } else {
                alert(response.message || 'Login failed.');
            }
        } catch (err) {
            alert("Login error. Check console for details.");
            console.error(err);
        }
    });
});
