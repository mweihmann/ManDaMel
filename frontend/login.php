<?php include 'includes/header.php'; ?>


<div class="login-box">
    <h2>Login</h2>
    <form id="loginForm">
        <div class="mb-3 text-start">
            <label for="login" class="form-label">Username or Email</label>
            <input type="text" class="form-control" id="login" placeholder="Username or Email" required>
        </div>
        <div class="mb-3 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" required>
        </div>
        <div class="form-check mb-3 text-start">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <button type="submit" class="btn btn-orange w-100">Login</button>
        <div id="login-error" class="text-danger mt-2"></div>
    </form>
    <p class="mt-3">Don't have an account? <a href="register.php">Register</a></p>
</div>


<?php include 'includes/footer.php'; ?>