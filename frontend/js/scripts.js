$(document).ready(function () {
    const jwt = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
    const menu = $('#user-dropdown-menu');

    if (jwt) {
        menu.html(`
        <li><a class="dropdown-item" href="/pages/dashboard.php">Dashboard</a></li>
        <li><a class="dropdown-item" href="/pages/my_orders.php">My Orders</a></li>
        <li><a class="dropdown-item" href="#" id="logout-link">Logout</a></li>
        `);

        $('#logout-link').on('click', function (e) {
        e.preventDefault();
        localStorage.removeItem('jwt'); // remove cookies
        sessionStorage.removeItem('jwt');
        fetch('/api/logout.php', { method: 'GET' });
        window.location.href = '/pages/login.php';
        });

    } else {
        menu.html(`
        <li><a class="dropdown-item" href="/pages/login.php">Login</a></li>
        <li><a class="dropdown-item" href="/pages/register.php">Register</a></li>
        `);
    }
});
