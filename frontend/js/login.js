$(document).ready(function () {
    $('#loginForm').on('submit', async function (e) {
        e.preventDefault();

        const login = $('#login').val().trim();
        const password = $('#password').val().trim();

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
                    password: password
                })
            });

            if (response.token) {
                localStorage.setItem('jwt', response.token);
                alert('Login successful!');
                window.location.href = 'dashboard.php';
            } else {
                alert(response.message || 'Login failed.');
            }
        } catch (err) {
            alert("Login error. Check console for details.");
            console.error(err);
        }
    });
});
