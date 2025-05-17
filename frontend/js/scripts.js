$(document).ready(function () {
    // Token aus localStorage oder sessionStorage holen
    const jwt = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
    const menu = $('#user-dropdown-menu');

    if (jwt) {
        // Payload aus dem JWT extrahieren und dekodieren (Base64)
        const payloadBase64 = jwt.split('.')[1];
        const decodedPayload = JSON.parse(atob(payloadBase64));
        const role = decodedPayload.data?.role;

        let links


        // console.log('Decoded JWT Payload:', decodedPayload);
        // console.log('User Role:', role);


        // Links abhängig von der Benutzerrolle anzeigen
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

        // Navigationsmenü mit den Links befüllen
        menu.html(links);

        // Logout-Logik
        $('#logout-link').on('click', function (e) {
        e.preventDefault();
        localStorage.removeItem('jwt');                 // JWT aus localStorage entfernen (remove cookie)
        sessionStorage.removeItem('jwt');               // JWT aus sessionStorage entfernen
        fetch('/api/logout.php', { method: 'GET' });    // Logout-API aufrufen
        window.location.href = 'login.php';             // Zur Login-Seite weiterleiten
        });

    } else {
        // Falls kein JWT vorhanden: Login- und Registrierungs-Links anzeigen
        menu.html(`
        <li><a class="dropdown-item" href="login.php">Login</a></li>
        <li><a class="dropdown-item" href="register.php">Register</a></li>
        `);
    }
});
