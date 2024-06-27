<?php
session_start();

include "database\db.php";
include "db_connection\connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "assets/PHPMailer/src/Exception.php";
require "assets/PHPMailer/src/PHPMailer.php";
require "assets/PHPMailer/src/SMTP.php";

$tblGuest = "SELECT * FROM TblGuest";
$result1 = $conn->query($tblGuest);

$tblReserve = "SELECT * FROM Tblreserve";
$result2 = $conn->query($tblReserve);

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'valid') {
  header('Location: login.php');

  exit;
}

if (isset($_POST["update"])) {
  $id = intval($_POST["guestId"]);
  $arrivalDate = $_POST["arrivalDate"];
  $guestName = $_POST["guestName"];
  $guestEmail = $_POST["guestEmail"];
  $room = $_POST["guestRoom"];
  $type = $_POST["guestType"];
  $guests = intval($_POST["guestNumber"]);
  $price = floatval($_POST["guestPrice"]);

  $updateGuest = $conn->prepare("UPDATE TblGuest SET Arrival_Date = ?, Guest_Name = ?, Guest_Email = ? WHERE ID = ?");
  $updateGuest->bind_param("sssi", $arrivalDate, $guestName, $guestEmail, $id);

  $updateGuest->execute();

  $updateReservation = $conn->prepare("UPDATE Tblreserve SET Guest_Room = ?, Guest_Type = ?, Guest_Number = ?, Guest_Price = ? WHERE ID = ?");
  $updateReservation->bind_param("ssidi", $room, $type, $guests, $price, $id);

  $updateReservation->execute();

  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "rizzort9@gmail.com";
    $mail->Password = "yncmrsjgigcrgqxd";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom("rizzort9@gmail.com", "Rizzort");
    $mail->addAddress($_POST["guestEmail"]);

    $mail->isHTML(true);
    $mail->Subject = "Reservation Update and Payment Details";
    $mail->Body = "
        <h1 style='font-size: 25px; letter-spacing: 2px; text-align: center; font-weight: bold; font-family: Archive; color: #fff; margin: 25px 0 10px 0;'>
          RIZZORT
        </h1>

        <img style='width: 100%; height: auto;' src='https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' class='img-fluid logo'
        alt='RIZZORT logo' />

        <br>

        <h1>RIZZORT</h1>
        <p>Dear $guestName,</p>
        <p>Your reservation details have been updated successfully.</p>
        <p><strong>Arrival Date:</strong> $arrivalDate</p>
        <p><strong>Guest Room:</strong> $room</p>
        <p>Your total price is: $price</p>
        <p>Please pay via GCash to the following number: <strong>09363007584 / VERGEL M.</strong></p>

      <br>

      <p>Thank you for choosing our resort.</p>
      <p>Best regards,<br>Rizzort Team</p>
      ";

    $mail->send();
    echo "<script>
      alert('Email sent successfully');
      window.location.href = 'admin.php';
    </script>";

  } catch (Exception $e) {
    echo "<script>alert('Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "');</script>";
  }
}

if (isset($_POST["delete"])) {
  $id = $_POST["guestId"];

  $deleteRow = $conn->prepare("DELETE FROM TblGuest WHERE ID = ?");
  $deleteRow->bind_param("i", $id);
  $deleteRow->execute();

  $deleteRow = $conn->prepare("DELETE FROM Tblreserve WHERE ID = ?");
  $deleteRow->bind_param("i", $id);
  $deleteRow->execute();

  echo "<script>
    window.location.href = 'admin.php';
  </script>";
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

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" />

  <link rel="stylesheet" href="css\admin.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poly:ital@0;1&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
</head>

<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <img src="assets\images\resort_image.webp" class="img-fluid" alt="" />
      <div class="overlay"></div>
      <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="bi bi-box-arrow-left fw-bold"></i> Logout
      </button>
    </div>
  </nav>

  <div class="modal fade" id="logoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="logoutModalLabel">Are you sure you want to logout?</h5>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" name="submit" id="logout">YES</button>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid image">
    <div class="container-fluid table">
      <div class="table1">
        <Table>
          <tr>
            <th>ID NUMBER</th>
            <th>ARRIVAL DATE</th>
            <th>GUEST NAME</th>
            <th>GUEST EMAIL</th>
            <th>CONATACT NO.</th>
            <th>ROOM</th>
            <th>TYPE</th>
            <th>GUESTS</th>
            <th>PRICE</th>
          </tr>
          <?phP
          if ($result1->num_rows > 0 && $result2->num_rows > 0) {
            while (($row = $result1->fetch_assoc()) && ($row1 = $result2->fetch_assoc())) {
              echo "<tr class='data'>";
              echo "<td>" . $row["ID"] . "</td>";
              echo "<td>" . $row["Arrival_Date"] . "</td>";
              echo "<td>" . $row["Guest_Name"] . "</td>";
              echo "<td>" . $row["Guest_Email"] . "</td>";
              echo "<td>" . $row["Guest_Contact"] . "</td>";

              echo "<td>" . $row1["Guest_Room"] . "</td>";
              echo "<td>" . $row1["Guest_Type"] . "</td>";
              echo "<td>" . $row1["Guest_Number"] . "</td>";
              echo "<td>" . $row1["Guest_Price"] . "</td>";
              echo "</tr>";
            }
          }
          ?>
        </Table>
      </div>
    </div>
    <div class="container-fluid form">
      <form action="admin.php" method="post">
        <div class="row">
          <div class="container-fluid form-group col-10">
            <label for="guestId">Guest Id</label>
            <input type="number" class="form-control" id="guestId" name="guestId" required />
          </div>
          <div class="container-fluid form-group col-10 d-none">
            <label for="arrivalDate">Arrival Date</label>
            <input type="date" class="form-control" id="arrivalDate" name="arrivalDate" required />
          </div>
          <div class="container-fluid form-group col-10">
            <label for="guestName">Guest Name</label>
            <input type="text" class="form-control" id="guestName" name="guestName" required />
          </div>
          <div class="container-fluid form-group col-10 d-none">
            <label for="guestEmail">Guest Email</label>
            <input type="email" class="form-control" id="guestEmail" name="guestEmail" required />
          </div>
          <div class="container-fluid form-group col-10">
            <label for="guestRoom">Guest Room</label>
            <select class="form-select" id="guestRoom" name="guestRoom">
              <option value="" disabled selected>Select Room</option>
              <option value="R100">R100</option>
              <option value="R101">R101</option>
              <option value="R102">R102</option>
              <option value="R103">R103</option>
              <option value="R104">R104</option>
              <option value="R105">R105</option>
              <option value="R106">R106</option>
              <option value="R107">R107</option>
              <option value="R108">R108</option>
              <option value="R109">R109</option>
              <option value="R110">R110</option>
              <option value="R111">R111</option>
              <option value="R112">R112</option>
              <option value="R113">R113</option>
              <option value="R114">R114</option>
              <option value="R115">R115</option>
              <option value="R116">R116</option>
              <option value="R117">R117</option>
              <option value="R118">R118</option>
              <option value="R119">R119</option>
              <option value="R120">R120</option>
              <option value="R121">R121</option>
              <option value="R122">R122</option>
              <option value="R123">R123</option>
              <option value="R124">R124</option>
              <option value="R125">R125</option>
              <option value="R126">R126</option>
              <option value="R127">R127</option>
              <option value="R128">R128</option>
              <option value="R129">R129</option>
              <option value="R130">R130</option>
            </select>
          </div>
          <div class="container-fluid form-group col-10">
            <label for="guestType">Guest Type</label>
            <input type="text" class="form-control" id="guestType" name="guestType" required />
          </div>
          <div class="container-fluid form-group col-10">
            <label for="guestNumber">Number of Guests</label>
            <input type="number" class="form-control" id="guestNumber" name="guestNumber" required />
          </div>
          <div class="container-fluid form-group col-10">
            <label for="guestPrice">Price</label>
            <input type="number" class="form-control" id="guestPrice" name="guestPrice" required />
          </div>
          <div class="container-fluid form-group col-10 button">
            <button type="submit" name="update" class="btn mx-3 px-4">Update</button>
            <button type="submit" name="delete" class="btn mx-3 px-4">Delete</button>
          </div>
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
    let logout = document.getElementById("logout");

    logout.addEventListener("click", () => {
      window.location.href = "login.php";
    });

    let rows = document.querySelectorAll(".data");

    rows.forEach((row) => {
      row.addEventListener("click", () => {

        rows.forEach((tb) => {
          tb.style.backgroundColor = "";
          tb.style.color = "";
        });

        row.style.backgroundColor = "#4778b380";
        row.style.color = "#fff";

        let rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());

        document.querySelector("#guestId").value = rowData[0];
        document.querySelector("#arrivalDate").value = rowData[1];
        document.querySelector("#guestName").value = rowData[2];
        document.querySelector("#guestEmail").value = rowData[3];
        document.querySelector("#guestRoom").value = rowData[5];
        document.querySelector("#guestType").value = rowData[6];
        document.querySelector("#guestNumber").value = rowData[7];
        document.querySelector("#guestPrice").value = rowData[8];
      });
    });
  </script>
</body>

</html>