<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ManDaMel Shop – Users</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <h1>User List</h1>
  <ul id="userList"></ul>

  <script>
    $(document).ready(function () {
      $.ajax({
        url: "http://localhost:5000/api/serviceHandler.php?method=getAllUsers",
        method: "GET",
        dataType: "json",
        success: function (data) {
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
</body>
</html>