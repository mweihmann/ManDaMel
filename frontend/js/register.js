$(document).ready(function () {
    $('#registerForm').on('submit', async function (e) {
        e.preventDefault();

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const zipRegex = /^\d{4,10}$/;

        const password = $('#password').val().trim();
        const confirm = $('#confirm_password').val().trim();
        const email = $('#email').val().trim();
        const zip = $('#zip').val().trim();

        if (!$('#firstname').val().trim() || !$('#username').val().trim()) {
            alert("Please fill in all required fields.");
            return;
        }        

        if (!emailRegex.test(email)) {
            alert("Please enter a valid email.");
            return;
        }

        if (!zipRegex.test(zip)) {
            alert("Please enter a valid postal code.");
            return;
        }

        if (password.length < 6) {
            alert('Password must be at least 6 characters long.');
            return;
        }

        if (password !== confirm) {
            alert("Passwords do not match.");
            return;
        }

        const data = {
            pronouns: $('#salutation').val(),
            given_name: $('#firstname').val(),
            surname: $('#lastname').val(),
            street: $('#street').val(),
            house_number: $('#house_number').val(),
            postal_code: zip,
            city: $('#city').val(),
            country: $('#country').val(),
            telephone: $('#telephone').val() || '',
            email: email,
            username: $('#username').val(),
            password: password,
            confirm_password: confirm
        };

        try {
            const response = await $.ajax({
                url: 'http://localhost:5000/api/register.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
            });

            if (response.token) {
                localStorage.setItem('jwt', response.token);
                alert('Registration successful!');
                window.location.href = 'index.php';
            } else {
                alert(response.message || 'Registration failed.');
            }
        } catch (err) {
            alert(err.responseJSON?.message || "An error occurred during registration.");
            console.error(err);
        }
    });
});
