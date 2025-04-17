$(document).ready(function () {
    const token = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
  
    if (!token) {
      window.location.href = '/pages/login.php';
      return;
    }
  
    $.ajax({
      url: 'http://localhost:5000/api/orders.php',
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`
      },
      success: function (response) {
        if (!response.orders || response.orders.length === 0) {
          $('#order-container').html('<p>You have no orders yet.</p>');
          return;
        }
  
        let table = `
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Total</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
        `;
  
        response.orders.forEach(order => {
          table += `
            <tr>
              <td>${order.id}</td>
              <td>€${parseFloat(order.total).toFixed(2)}</td>
              <td>${new Date(order.created_at).toLocaleString()}</td>
            </tr>
          `;
        });
  
        table += '</tbody></table>';
        $('#order-container').html(table);
      },
      error: function () {
        $('#order-container').html('<p class="text-danger">Could not load orders. Please log in again.</p>');
      }
    });
  });  