<?php include '../includes/header.php'; ?>

<div class="container auth-form-container">
    <div class="w-100" style="max-width: 500px;">

        <div class="orange-line"></div>
        <h2 class="text-center mb-4">Register</h2>

        <form id="registerForm" method="post">
            <div class="mb-3">
            <label for="salutation" class="form-label">Title</label>
            <select class="form-select" id="salutation" name="salutation" required>
                <option value="">Please select</option>
                <option value="Mr">Mr</option>
                <option value="Mrs">Mrs</option>
                <option value="Ms">Ms</option>
                <option value="Other">Other</option>
            </select>
            </div>

            <div class="row">
            <div class="col-md-6 mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required>
            </div>
            </div>

            <div class="row">
            <div class="col-md-8 mb-3">
                <label for="street" class="form-label">Street</label>
                <input type="text" class="form-control" id="street" name="street" placeholder="Street name" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="house_number" class="form-label">No.</label>
                <input type="text" class="form-control" id="house_number" name="house_number" placeholder="House No." required>
            </div>
            </div>

            <div class="row">
            <div class="col-md-4 mb-3">
                <label for="zip" class="form-label">ZIP Code</label>
                <input type="text" class="form-control" id="zip" name="zip" placeholder="ZIP Code" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="Country" required>
            </div>
            </div>

            <div class="mb-3">
            <label for="telephone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Optional">
            </div>

            <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username" required>
            </div>

            <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Choose a password" required>
            </div>

            <div class="mb-3">
            <label for="confirm_password" class="form-label">Repeat Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
            </div>

            <div class="mb-3">
            <label for="payment" class="form-label">Payment Information</label>
            <input type="text" class="form-control" id="payment" name="payment" placeholder="IBAN or Credit Card" required>
            </div>

            <button type="submit" class="btn w-100 text-white" style="background-color: #fd7625;">Register</button>
        </form>

        <p class="mt-3 text-center">
            Already have an account? <a href="login.php" style="color: #fd7625;">Login</a>
        </p>
    </div>
</div>




<?php include '../includes/footer.php'; ?>