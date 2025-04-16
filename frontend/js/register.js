$(document).ready(function () {
    $('#registerForm').on('submit', async function (e) {
        e.preventDefault();

        const password = $('#password').val();
        const confirm = $('#confirm_password').val();

        // if (password.length < 6) {
        //     alert('Password must be at least 6 characters long.');
        //     return;
        // }

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
            postal_code: $('#zip').val(),
            city: $('#city').val(),
            country: $('#country').val(),
            telephone: $('#telephone').val() ||'',
            email: $('#email').val(),
            username: $('#username').val(),
            password: $('#password').val(),
            confirm_password: $('#confirm_password').val()
        };

        try {
            const response = await $.ajax({
                url: 'http://localhost:5000/api/register.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data)
            });

            if (response.token) {
                localStorage.setItem('jwt', response.token);
                alert('Registration successful!');
                window.location.href = 'index.php';
            } else {
                alert(response.message || 'Registration failed.');
            }
        } catch (err) {
            alert("An error occurred during registration.");
            console.error(err);
        }
    });
});
