<?php
session_start();
require_once 'includes/conn.php'; // Assuming you have a database connection file

if (isset($_SESSION['admin'])) {
  header('location: admin/home.php');
}

if (isset($_SESSION['voter'])) {
  header('location: home.php');
}

if (isset($_POST['register'])) {
  $id = $_POST['id'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $filename = $_FILES['photo']['name'];
  
  // Check if any field is empty
  if (empty($id) || empty($firstname) || empty($lastname) || empty($password) || empty($filename)) {
    $_SESSION['error'] = 'Please fill all the fields';
  } else {
    move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $filename);

    $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $voter = substr(str_shuffle($set), 0, 15);

    $sql = "INSERT INTO voters (id, voters_id, password, firstname, lastname, photo) VALUES ('$id', '$voter', '$password', '$firstname', '$lastname', '$filename')";
    if ($conn->query($sql)) {
      $_SESSION['success'] = 'Registration successful. Please see the administrator to get your voter\'s ID which would serve as your USERNAME.';
    } else {
      $_SESSION['error'] = $conn->error;
    }
  }
} else {
  $_SESSION['error'] = $conn->error;
}
?>

<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <b>Voting System</b>
    </div>

    <div class="login-box-body">
      <p class="login-box-msg">Register as a new voter</p>

      <form action="register.php" method="POST" enctype="multipart/form-data">
        <div class="form-group has-feedback">
          <input type="id" class="form-control" name="id" id="id" placeholder="ID NUMBER" required>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name" required>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name" required>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="file" class="form-control" name="photo" id="photo" accept="image/*" required>
          <span class="glyphicon glyphicon-file form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat" name="register"><i class="fa fa-user-plus"></i> Register</button>
          </div>
        </div>
      </form>
      <div class="text-center mt20">
        <a href="index.php">Back to Login</a>
      </div>
    </div>
    <?php
    if (isset($_SESSION['error'])) {
      echo "
        <div class='callout callout-danger text-center mt20'>
          <p>" . $_SESSION['error'] . "</p> 
        </div>
      ";
      unset($_SESSION['error']);
    }

    if (isset($_SESSION['success'])) {
      echo "
        <div class='callout callout-success text-center mt20'>
          <p>" . $_SESSION['success'] . "</p> 
        </div>
      ";    
      unset($_SESSION['success']);
    }
    ?>
  </div>

  <?php include 'includes/scripts.php' ?>
</body>
</html>
