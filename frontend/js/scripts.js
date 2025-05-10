$(document).ready(function () {
    const jwt = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
    const menu = $('#user-dropdown-menu');

    if (jwt) {

        // Rolle aus dem jwt Token extrahieren (Base64 decode)
        const payloadBase64 = jwt.split('.')[1];
        const decodedPayload = JSON.parse(atob(payloadBase64));
        const role = decodedPayload.data?.role;

        let links

        // console.log('Decoded JWT Payload:', decodedPayload);
        // console.log('User Role:', role);

        if (role === 'admin') {
            links = `
                <li><a class="dropdown-item" href="manage_products.php">Edit Products</a></li>
                <li><a class="dropdown-item" href="manage_accounts.php">Edit Customers</a></li>
                <li><a class="dropdown-item" href="manage_vouchers.php">Edit Vouchers</a></li>
                <li><a class="dropdown-item" href="#" id="logout-link">Logout</a></li>
            `;
        } else if (role === 'user') {
            links = `
                <li><a class="dropdown-item" href="my_account.php">My Account</a></li>
                <li><a class="dropdown-item" href="#" id="logout-link">Logout</a></li>
            `;
        }

        menu.html(links);

        $('#logout-link').on('click', function (e) {
        e.preventDefault();
        localStorage.removeItem('jwt'); // remove cookies
        sessionStorage.removeItem('jwt');
        fetch('/api/logout.php', { method: 'GET' });
        window.location.href = 'login.php';
        });

    } else {
        menu.html(`
        <li><a class="dropdown-item" href="login.php">Login</a></li>
        <li><a class="dropdown-item" href="register.php">Register</a></li>
        `);
    }
});
