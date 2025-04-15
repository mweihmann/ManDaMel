<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User List</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function () {
    $.ajax({
      url: "http://localhost:5000/api/serviceHandler.php?method=getAllUsers",
      method: "GET",
      dataType: "json",
      success: function (data) {
        console.log("User data:", data);

        let userList = $("#userList");
        data.forEach(user => {
          userList.append(`<li>${user.given_name} ${user.surname} (${user.email})</li>`);
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching users:", error);
      }
    });
  });
  </script>
</head>
<body>
  <h1>Users</h1>
  <ul id="userList"></ul>


  <div class="container">
    <h2>User Registration</h2>
    <form action="register.php" method="post">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <input type="submit" value="Register">
      </div>
    </form>
  </div>
</body>
</html>