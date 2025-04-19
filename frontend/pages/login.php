<?php include '../includes/header.php'; ?>


<div class="login-box">
    <h2>Login</h2>
    <form>
        <div class="mb-3 text-start">
            <label for="login" class="form-label">Username or Email</label>
            <input type="text" class="form-control" id="login" placeholder="Username or Email" required>
        </div>
        <div class="mb-3 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" required>
        </div>
        <div class="mb-3 text-end">
            <a href="#">Forgot password?</a>
        </div>
        <button type="submit" class="btn btn-orange w-100">Login</button>
    </form>
    <p class="mt-3">Don't have an account? <a href="register.php">Register</a></p>
</div>


<?php include '../includes/footer.php'; ?>