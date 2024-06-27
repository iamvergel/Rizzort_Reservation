<?php
session_start();
$alert = '';

if (isset($_POST["login"])) {
  $userName = $_POST['user'];
  $passWord = $_POST['pass'];

  $validUsername = 'admin2024';
  $validPassword = 'admin2024';

  if ($userName == $validUsername && $passWord == $validPassword) {
    $_SESSION['username'] = $userName;
    $_SESSION['status'] = 'valid';
    header('Location: admin.php');
    exit;
  } else if ($userName == '' && $passWord == '') {
    $alert = "<div class='alert mt-5 py-2 text-center fw-normal ' role='alert' style='background-color: #4778b3; letter-spacing: 1px; font-size: 12px'>Please input Username and Password</div>";
  } else {
    $alert = "<div class='alert mt-5 py-2 text-center fw-normal' role='alert' style='background-color: #4778b3; letter-spacing: 1px; font-size: 12px'>Invalid Username or Password.</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="shortcut icon" href="assets\images\resort_image.webp" type="image/x-icon" />
  <title>RIZZORT</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

  <link rel="stylesheet" href="\..\..\Rizzort_Reservation\css\login.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poly:ital@0;1&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
</head>

<body>
  <div class="container-fluid bg-success image">
    <img src="assets\images\resort_image.webp" class="img-fluid" alt="" />
    <div class="overlay"></div>

    <div class="content">
      <form action="login.php" method="POST">
        <div class="mb-3">
          <label for="guestEmail" class="form-label">Username :</label>
          <input type="text" class="form-control" id="user" name="user" />
        </div>
        <div class="mb-3">
          <label for="guestEmail" class="form-label a">Password :</label>
          <input type="password" class="form-control" id="pass" name="pass" />
        </div>
        <div class="mb-3">
          <button type="submit" name="login">LOGIN</button>
          <?php echo $alert; ?>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script>
    setTimeout(function () {
      document.querySelector('.alert').innerHTML = '';
      document.querySelector('.alert').style.display = 'none';
    }, 2000);
  </script>
</body>

</html>