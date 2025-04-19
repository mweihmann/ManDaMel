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
              <th>Product</th>
              <th>Total</th>
              <th>Date</th>
              <th>Downloads</th>
            </tr>
          </thead>
          <tbody>
      `;

      response.orders.forEach(order => {
        order.products.forEach(product => {
          table += `
            <tr>
              <td>${order.id}</td>
              <td>${product.name}</td>
              <td>€${parseFloat(order.total).toFixed(2)}</td>
              <td>${new Date(order.created_at).toLocaleString()}</td>
              <td>
                <button class="btn btn-sm btn-outline-primary download-btn" data-file-id="${product.id}">
                  Download
                </button>
              </td>
            </tr>
          `;
        });  
      });

      table += '</tbody></table>';
      $('#order-container').html(table);

      $('.download-btn').on('click', function () {
        const fileId = $(this).data('file-id');
      
        fetch(`http://localhost:5000/api/download.php?file_id=${fileId}`, {
          method: 'GET',
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })
          .then(response => {
            if (!response.ok) throw new Error('Download failed');

            // get filename from header
            const disposition = response.headers.get('Content-Disposition');
            let filename = 'downloaded_file';
      
            if (disposition) {
              // extract utf-8 type first
              const utfMatch = disposition.match(/filename\*\=UTF-8''([^;\n]+)/);
              const simpleMatch = disposition.match(/filename=\"?([^\";\n]+)\"?/);
          
              if (utfMatch) {
                filename = decodeURIComponent(utfMatch[1]);
              } else if (simpleMatch) {
                filename = simpleMatch[1];
              }
            }
      
            return response.blob().then(blob => ({ blob, filename }));
          })
          .then(({ blob, filename }) => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url); // clean up
          })
          .catch(err => {
            alert('Download failed or access denied.');
            console.error(err);
          });
      });      
    },
    error: function () {
      $('#order-container').html('<p class="text-danger">Could not load orders. Please log in again.</p>');
    }
  });
});