<?php include 'includes/header.php'; ?>


<div class="login-box">
    <h2>Login</h2>

    <!-- Login-Formular -->
    <form id="loginForm">

        <!-- Eingabefeld für Benutzername oder E-Mail -->
        <div class="mb-3 text-start">
            <label for="login" class="form-label">Username or Email</label>
            <input type="text" class="form-control" id="login" placeholder="Username or Email" required>
        </div>

        <!-- Eingabefeld für Passwort -->
        <div class="mb-3 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" required>
        </div>

        <!-- Checkbox zum Merken des Logins -->
        <div class="form-check mb-3 text-start">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <!-- Absenden-Button -->
        <button type="submit" class="btn btn-orange w-100">Login</button>

        <!-- Fehlermeldung bei Login -->
        <div id="login-error" class="text-danger mt-2"></div>
    </form>

    <!-- Link zur Registrierung -->
    <p class="mt-3">Don't have an account? <a href="register.php">Register</a></p>
</div>


<?php include 'includes/footer.php'; ?>