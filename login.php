<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
}
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $_SESSION['system']['name'] ?></title>
  <?php include('./header.php'); ?>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      background-image: url('rent.png'); /* Add the path to your background image */
      background-size: cover; /* Cover the entire background */
      background-position: center; /* Center the background image */


      margin: 0;
      padding: 0;
    }

    main#main {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    #login-left {
      flex: 1;
      background: url('assets/uploads/blood-cells.jpg') center center/cover;
      position: relative;
    }

    #login-right {
      flex: 1;
      padding: 20px;
    }

    .card {
      max-width: 400px;
      margin: 0 auto;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      padding: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ced4da;
      border-radius: 5px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background-color: #007bff;
      color: #fff;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #0056b3;
    }

    .alert-danger {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <main id="main">
    <div id="login-left"></div>

    <div id="login-right">
      <div class="w-100">
        <h4 class="text-center mb-4"><?php echo $_SESSION['system']['name'] ?></h4>
        <div class="card">
          <div class="card-body">
            <form id="login-form">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control">
              </div>
              <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <?php if(isset($_SESSION['login_id'])): ?>
              <div class="alert alert-danger">You are already logged in.</div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    $('#login-form').submit(function(e) {
      e.preventDefault();
      var form = $(this);
      form.find('button[type="submit"]').attr('disabled', true).html('Logging in...');
      if (form.find('.alert-danger').length > 0) form.find('.alert-danger').remove();
      $.ajax({
        url: 'ajax.php?action=login',
        method: 'POST',
        data: form.serialize(),
        error: function(err) {
          console.log(err);
          form.find('button[type="submit"]').removeAttr('disabled').html('Login');
        },
        success: function(resp) {
          if (resp == 1) {
            location.href = 'index.php?page=home';
          } else {
            form.prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
            form.find('button[type="submit"]').removeAttr('disabled').html('Login');
          }
        }
      });
    });
  </script>
</body>
</html>
