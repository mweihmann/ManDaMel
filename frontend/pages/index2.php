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
</body>
</html>