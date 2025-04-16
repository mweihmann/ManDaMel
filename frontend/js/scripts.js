$(document).ready(function () {
const jwt = localStorage.getItem('jwt');
const menu = $('#user-dropdown-menu');

if (jwt) {
    menu.html(`
    <li><a class="dropdown-item" href="/pages/dashboard.php">Dashboard</a></li>
    <li><a class="dropdown-item" href="#" id="logout-link">Logout</a></li>
    `);

    $('#logout-link').on('click', function (e) {
    e.preventDefault();
    localStorage.removeItem('jwt');
    fetch('/api/logout.php', { method: 'GET' }); // optional
    window.location.href = '/pages/login.php';
    });

} else {
    menu.html(`
    <li><a class="dropdown-item" href="/pages/login.php">Login</a></li>
    <li><a class="dropdown-item" href="/pages/register.php">Register</a></li>
    `);
}
});
