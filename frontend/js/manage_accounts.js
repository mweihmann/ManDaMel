$(document).ready(function () {
    const token = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
    if (!token) return location.href = 'login.php';
  
    $.ajax({
      url: 'http://localhost:5000/api/get_all_users.php',
      method: 'GET',
      headers: { Authorization: `Bearer ${token}` },
      success: function (users) {
        const container = $('#admin-user-list');
        if (!users.length) return container.html('<p>No users found.</p>');
  
        let accordionHtml = `<div class="accordion" id="adminAccordion">`;
  
        users.forEach((user, i) => {
          const collapseId = `collapseUser${user.id}`;
          const headingId = `headingUser${user.id}`;
  
          accordionHtml += `
            <div class="accordion-item">
              <h2 class="accordion-header" id="${headingId}">
                <button class="accordion-button ${i !== 0 ? 'collapsed' : ''}" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="${i === 0}" aria-controls="${collapseId}">
                  👤 ${user.username} (${user.email})
                </button>
              </h2>
              <div id="${collapseId}" class="accordion-collapse collapse ${i === 0 ? 'show' : ''}" aria-labelledby="${headingId}" data-bs-parent="#adminAccordion">
                <div class="accordion-body">
                  <form class="user-form" data-id="${user.id}">
                    <div class="row g-3">
                      ${renderInput('Username', 'username', user.username)}
                      ${renderInput('Email', 'email', user.email)}
                      ${renderInput('Given Name', 'given_name', user.given_name)}
                      ${renderInput('Surname', 'surname', user.surname)}
                      ${renderInput('Pronouns', 'pronouns', user.pronouns)}
                      ${renderInput('Telephone', 'telephone', user.telephone)}
                      ${renderInput('Country', 'country', user.country)}
                      ${renderInput('City', 'city', user.city)}
                      ${renderInput('Postal Code', 'postal_code', user.postal_code)}
                      ${renderInput('Street', 'street', user.street)}
                      ${renderInput('House Number', 'house_number', user.house_number)}
                      <div class="col-md-4">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role">
                          <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                          <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">User Status</label>
                        <select class="form-select" name="user_state">
                          <option value="active" ${user.user_state === 'active' ? 'selected' : ''}>Active</option>
                          <option value="inactive" ${user.user_state === 'inactive' ? 'selected' : ''}>Inactive</option>
                        </select>
                      </div>
                      <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">💾 Save Changes</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          `;
        });
  
        accordionHtml += `</div>`;
        container.html(accordionHtml);
  
        $('.user-form').on('submit', function (e) {
          e.preventDefault();
          const id = $(this).data('id');
          const $form = $(this);
  
          const data = {
            id,
            username: $form.find('[name="username"]').val(),
            email: $form.find('[name="email"]').val(),
            given_name: $form.find('[name="given_name"]').val(),
            surname: $form.find('[name="surname"]').val(),
            telephone: $form.find('[name="telephone"]').val(),
            country: $form.find('[name="country"]').val(),
            city: $form.find('[name="city"]').val(),
            postal_code: $form.find('[name="postal_code"]').val(),
            street: $form.find('[name="street"]').val(),
            house_number: $form.find('[name="house_number"]').val(),
            pronouns: $form.find('[name="pronouns"]').val(),
            role: $form.find('[name="role"]').val(),
            user_state: $form.find('[name="user_state"]').val()
          };
  
          $.ajax({
            url: 'http://localhost:5000/api/update_user_admin.php',
            method: 'POST',
            contentType: 'application/json',
            headers: { Authorization: `Bearer ${token}` },
            data: JSON.stringify(data),
            success: res => alert(res.success ? '✔️ User updated.' : '❌ Update failed'),
            error: () => alert('⚠️ Error while saving user.')
          });
        });
      },
      error: () => $('#admin-user-list').html('<p class="text-danger">Failed to load users.</p>')
    });
  
    function renderInput(label, name, value = '') {
      return `
        <div class="col-md-4">
          <label class="form-label">${label}</label>
          <input class="form-control" name="${name}" value="${value || ''}" />
        </div>
      `;
    }
  });  