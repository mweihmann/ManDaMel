$(document).ready(function () {
  const token = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');

  if (!token) {
    window.location.href = 'login.php';
    return;
  }

  // get user
  $.ajax({
    url: 'http://localhost:5000/api/get_current_user.php',
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`
    },
    success: function (user) {
      $('#account-details').html(`
        <div class="accordion" id="accountAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingView">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseView" aria-expanded="true" aria-controls="collapseView">
                📄 View Profile
              </button>
            </h2>
            <div id="collapseView" class="accordion-collapse collapse show" aria-labelledby="headingView" data-bs-parent="#accountAccordion">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6">
                    <ul class="list-group mb-3">
                      <li class="list-group-item"><strong>Username:</strong> ${user.username}</li>
                      <li class="list-group-item"><strong>Pronouns:</strong> ${user.pronouns}</li>
                      <li class="list-group-item"><strong>Name:</strong> ${user.given_name} ${user.surname}</li>
                      <li class="list-group-item"><strong>Email:</strong> ${user.email}</li>
                      <li class="list-group-item"><strong>Telephone:</strong> ${user.telephone}</li>
                      <li class="list-group-item"><strong>Country:</strong> ${user.country}</li>
                    </ul>
                  </div>
                  <div class="col-md-6">
                    <ul class="list-group mb-3">
                      <li class="list-group-item"><strong>City:</strong> ${user.city}</li>
                      <li class="list-group-item"><strong>Postal Code:</strong> ${user.postal_code}</li>
                      <li class="list-group-item"><strong>Street:</strong> ${user.street}</li>
                      <li class="list-group-item"><strong>House Number:</strong> ${user.house_number}</li>
                      <li class="list-group-item"><strong>User Role:</strong> ${user.role}</li>
                      <li class="list-group-item"><strong>Status:</strong> ${user.user_state}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingEdit">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEdit" aria-expanded="false" aria-controls="collapseEdit">
                ✏️ Edit Profile
              </button>
            </h2>
            <div id="collapseEdit" class="accordion-collapse collapse" aria-labelledby="headingEdit" data-bs-parent="#accountAccordion">
              <div class="accordion-body">
                <form id="account-form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3"><label>Username</label><input type="text" class="form-control" name="username" value="${user.username}"></div>
                      <div class="mb-3"><label>Pronouns</label><input type="text" class="form-control" name="pronouns" value="${user.pronouns}"></div>
                      <div class="mb-3"><label>Given Name</label><input type="text" class="form-control" name="given_name" value="${user.given_name}"></div>
                      <div class="mb-3"><label>Surname</label><input type="text" class="form-control" name="surname" value="${user.surname}"></div>
                      <div class="mb-3"><label>Email</label><input type="email" class="form-control" name="email" value="${user.email}"></div>
                      <div class="mb-3"><label>Telephone</label><input type="text" class="form-control" name="telephone" value="${user.telephone}"></div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3"><label>Country</label><input type="text" class="form-control" name="country" value="${user.country}"></div>
                      <div class="mb-3"><label>City</label><input type="text" class="form-control" name="city" value="${user.city}"></div>
                      <div class="mb-3"><label>Postal Code</label><input type="text" class="form-control" name="postal_code" value="${user.postal_code}"></div>
                      <div class="mb-3"><label>Street</label><input type="text" class="form-control" name="street" value="${user.street}"></div>
                      <div class="mb-3"><label>House Number</label><input type="text" class="form-control" name="house_number" value="${user.house_number}"></div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
                  <div id="save-msg" class="mt-2"></div>
                </form>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingPayment">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayment" aria-expanded="false" aria-controls="collapsePayment">
                💳 Manage Payment Methods
              </button>
            </h2>
            <div id="collapsePayment" class="accordion-collapse collapse" aria-labelledby="headingPayment" data-bs-parent="#accountAccordion">
              <div class="accordion-body" id="payment-section">
                <h5>Add New Payment Method</h5>
                <div id="payment-form-container"></div>
                <hr>
              </div>
            </div>
          </div>
        </div>
      `);

      loadPaymentMethods(token);
      setupPaymentForm(token);

      $('#account-form').on('submit', function (e) {
        e.preventDefault();

        const data = {
          email: $('input[name="email"]').val(),
          telephone: $('input[name="telephone"]').val(),
          city: $('input[name="city"]').val(),
          postal_code: $('input[name="postal_code"]').val(),
          country: $('input[name="country"]').val(),
          street: $('input[name="street"]').val(),
          house_number: $('input[name="house_number"]').val(),
          given_name: $('input[name="given_name"]').val(),
          surname: $('input[name="surname"]').val(),
          pronouns: $('input[name="pronouns"]').val()
        };

        $.ajax({
          url: 'http://localhost:5000/api/update_account.php',
          method: 'POST',
          contentType: 'application/json',
          headers: { 'Authorization': `Bearer ${token}` },
          data: JSON.stringify(data),
          success: function () {
            $('#save-msg').text('Saved successfully.').addClass('text-success').removeClass('text-danger');
          },
          error: function () {
            $('#save-msg').text('Saving failed.').addClass('text-danger').removeClass('text-success');
          }
        });
      });

    },
    error: function (xhr, status, error) {
      console.error('AJAX error:', status, error);
      console.log('Response text:', xhr.responseText);
      $('#account-details').html('<p class="text-danger">Could not load account info. Please log in again.</p>');
    }
  });

  // Load orders
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
    
      let accordion = `<div class="accordion" id="ordersAccordion">`;
    
      response.orders.forEach((order, index) => {
        const collapseId = `collapseOrder${index}`;
        const headingId = `headingOrder${index}`;
    
        let productsHtml = '';
        order.products.forEach(product => {
          productsHtml += `
            <tr>
              <td>${product.name}</td>
              <td>
                <button class="btn btn-sm btn-outline-primary download-btn" data-file-id="${product.id}">
                  Download
                </button>
              </td>
              <td>
                <button class="btn btn-outline-secondary btn-sm invoice-btn" data-order-id="${order.id}">
                  📄 Print Invoice
                </button>
              </td>
            </tr>
          `;
        });
    
        accordion += `
          <div class="accordion-item">
            <h2 class="accordion-header" id="${headingId}">
              <button class="accordion-button ${index !== 0 ? 'collapsed' : ''}" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="${index === 0}" aria-controls="${collapseId}">
                🧾 Order #${order.id} — €${parseFloat(order.total).toFixed(2)} — ${new Date(order.created_at).toLocaleString()}
              </button>
            </h2>
            <div id="${collapseId}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="${headingId}" data-bs-parent="#ordersAccordion">
              <div class="accordion-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Download</th>
                      <th>Invoice</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${productsHtml}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        `;
      });
    
      accordion += `</div>`;
      $('#order-container').html(accordion);

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

  function loadPaymentMethods(token) {
    $.ajax({
      url: 'http://localhost:5000/api/get_payment_info.php',
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`
      },
      success: function (methods) {
        if (!methods || methods.length === 0) {
          $('#payment-methods').html('<p>No payment methods saved yet.</p>');
          return;
        }

        let html = '<ul class="list-group">';
        methods.forEach(method => {
          html += '<li class="list-group-item">';
          html += `<strong>Type:</strong> ${method.method}<br>`;

          if (method.method === 'creditcard') {
            html += `Card: **** **** **** ${method.creditcard_number.slice(-4)} (exp: ${method.creditcard_expiry})`;
          } else if (method.method === 'iban') {
            html += `IBAN: ${method.iban}`;
          } else if (method.method === 'voucher') {
            html += `Voucher: ${method.voucher_code}`;
          }

          html += `</li>`;
        });
        html += '</ul>';

        $('#payment-methods').html(html);
      },
      error: function () {
        $('#payment-methods').html('<p class="text-danger">Failed to load payment methods.</p>');
      }
    });
  }

  function setupPaymentForm(token) {
    $('#payment-form-container').append(`
      <div class="mt-5">
        <form id="payment-form">
          <div class="mb-3">
            <label>Method</label>
            <select name="method" class="form-select">
              <option value="creditcard">Credit Card</option>
              <option value="iban">IBAN</option>
            </select>
          </div>

          <div id="payment-fields"></div>

          <div class="mb-3">
            <label>Holder Name</label>
            <input type="text" name="holder_name" class="form-control">
          </div>

          <button type="submit" class="btn btn-success">Add Payment Method</button>
          <div id="payment-msg" class="mt-2"></div>
        </form>
        <hr>
        <h5>Saved Payment Methods</h5>
        <div id="payment-methods"></div>
      </div>
    `);

    const $fields = $('#payment-fields');
    $('select[name="method"]').on('change', function () {
      const method = $(this).val();
      $fields.html('');

      if (method === 'creditcard') {
        $fields.html(`
          <div class="mb-3"><label>Card Number</label><input name="creditcard_number" class="form-control"></div>
          <div class="mb-3"><label>Expiry (MM/YYYY)</label><input name="creditcard_expiry" class="form-control"></div>
          <div class="mb-3"><label>CVV</label><input name="creditcard_cvv" class="form-control"></div>
        `);
      } else if (method === 'iban') {
        $fields.html(`<div class="mb-3"><label>IBAN</label><input name="iban" class="form-control"></div>`);
      } else if (method === 'voucher') {
        $fields.html(`<div class="mb-3"><label>Voucher Code</label><input name="voucher_code" class="form-control"></div>`);
      }
    }).trigger('change');

    $('#payment-form').on('submit', function (e) {
      e.preventDefault();

      const formData = {
        method: $('select[name="method"]').val(),
        creditcard_number: $('input[name="creditcard_number"]').val(),
        creditcard_expiry: $('input[name="creditcard_expiry"]').val(),
        creditcard_cvv: $('input[name="creditcard_cvv"]').val(),
        iban: $('input[name="iban"]').val(),
        voucher_code: $('input[name="voucher_code"]').val(),
        holder_name: $('input[name="holder_name"]').val(),
      };

      $.ajax({
        url: 'http://localhost:5000/api/add_payment_method.php',
        method: 'POST',
        contentType: 'application/json',
        headers: { 'Authorization': `Bearer ${token}` },
        data: JSON.stringify(formData),
        success: function (res) {
          if (res.success) {
            $('#payment-msg').text('Payment method added.').addClass('text-success').removeClass('text-danger');
            loadPaymentMethods(token);
          } else {
            $('#payment-msg').text('Failed to add.').addClass('text-danger').removeClass('text-success');
          }
        },
        error: function () {
          $('#payment-msg').text('Server error.').addClass('text-danger');
        }
      });
    });
  }

  $('body').on('click', '.invoice-btn', function () {
    const orderId = $(this).data('order-id');
  
    fetch(`http://localhost:5000/api/generate_invoice.php?order_id=${orderId}`, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })
    .then(res => {
      if (!res.ok) throw new Error('Fehler beim Generieren der Rechnung');
      return res.blob();
    })
    .then(blob => {
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `Rechnung_Order_${orderId}.pdf`;
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    })
    .catch(err => alert('Fehler beim Download der Rechnung.'));
  });
  

});