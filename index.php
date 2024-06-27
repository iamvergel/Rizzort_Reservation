<?php
include "database\db.php";
include "db_connection\connection.php";

$tblGuest = "SELECT * FROM TblGuest";
$result1 = $conn->query($tblGuest);

$tblReserve = "SELECT * FROM Tblreserve";
$result2 = $conn->query($tblReserve);

if (isset($_POST["submit"])) {
  $arrivalDate = $_POST['arrivalDate'];
  $guestName = $_POST['guestName'];
  $guestEmail = $_POST['guestEmail'];
  $guestContact = $_POST['guestContact'];
  $guestType = $_POST['guestType'];
  $guestNumber = $_POST['guestNumber'];
  $guestRoom = "--";

  switch ($guestType) {
    case 'VIP guests':
      $guestPrice = $guestNumber * 50000;
      break;
    case 'Luxury travelers':
      $guestPrice = $guestNumber * 25000;
      break;
    case 'Family groups':
      $guestPrice = $guestNumber * 5000;
      break;
    default:
      $guestPrice = $guestNumber * 10000;
      break;
  }

  $addGuest = $conn->prepare("INSERT INTO TblGuest (Arrival_Date, Guest_Name, Guest_Email, Guest_Contact) VALUES (?, ?, ?, ?)");
  $addGuest->bind_param("ssss", $arrivalDate, $guestName, $guestEmail, $guestContact);
  $addGuest->execute();

  $guestId = $conn->insert_id;

  $addReservation = $conn->prepare("INSERT INTO Tblreserve (Guest_Id, Guest_Room, Guest_Type, Guest_Number, Guest_Price) VALUES (?, ?, ?, ?, ?)");
  $addReservation->bind_param("issii", $guestId, $guestRoom, $guestType, $guestNumber, $guestPrice);
  $addReservation->execute();

  echo "<script>
          alert('Your reservation has been submitted. Please wait for admin approval. Confirmation will be sent to your Gmail.');

          window.location.href = 'index.php';
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

  <link rel="stylesheet" href="\..\..\Rizzort_Reservation\css\style.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poly:ital@0;1&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
</head>

<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand py-0 fw-bold" href="#">RIZZORT</a>
      <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#reservationModal">
        book now
      </button>
    </div>
  </nav>

  <div class="modal fade" id="reservationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="reservationModalLabel">
            Reservation Form
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="reservationForm" action="index.php" method="POST">
          <div class="modal-body">
            <div class="mb-3">
              <label for="arrivalDate" class="form-label">*Arrival Date</label>
              <input type="date" class="form-control custom-date-input" id="arrivalDate" name="arrivalDate" required
                onchange="disablePastDates()" />
            </div>
            <div class="mb-3">
              <label for="guestName" class="form-label">*Guest Name</label>
              <input type="text" class="form-control" id="guestName" name="guestName" required />
            </div>
            <div class="mb-3">
              <label for="guestEmail" class="form-label">*Guest Email</label>
              <input type="email" class="form-control" id="guestEmail" name="guestEmail" style="text-transform: none"
                required />
            </div>
            <div class="mb-3">
              <label for="guestContact" class="form-label">*Guest Contact</label>
              <input type="number" class="form-control" id="guestContact" name="guestContact" required />
            </div>
            <div class="mb-3">
              <label for="guestType" class="form-label">*Guest Type</label>
              <select class="form-select" id="guestType" name="guestType" required>
                <option value="" disabled selected>Select guest type</option>
                <option value="Conventional tourists">Conventional tourists</option>
                <option value="Family groups">Family groups</option>
                <option value="Luxury travelers">Luxury travelers</option>
                <option value="Budget travelers">Budget travelers</option>
                <option value="Solo travelers">Solo travelers</option>
                <option value="Special occasion guests">Special occasion guests</option>
                <option value="VIP guests">VIP guests</option>
                <option value="Extended stay guests">Extended stay guests</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="guestNumber" class="form-label">*Number of Guests</label>
              <input type="number" class="form-control" id="guestNumber" name="guestNumber" required />
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="container-fluid bg-success image">
    <img src="assets\images\resort_image.webp" class="img-fluid" alt="" />
    <div class="overlay"></div>

    <div class="container-fluid content">
      <div>
        <h1 class="fs-1 fw-bold logo px-0 px-lg-5">Rizzort</h1>
        <p class="fw-normal px-0 px-lg-5">
          <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent
            vitae lacus a arcu venenatis scelerisque. Sed augue velit, congue
            eget lorem non, pharetra iaculis justo. Phasellus maximus aliquet
            urna, a euismod neque maximus non. Fusce mattis, ligula a aliquet
            imperdiet, dolor magna convallis metus, nec fringilla lacus est
            vitae augue. Praesent aliquet bibendum viverra. Proin tempor
            lectus consequat vulputate vulputate. Donec id sapien a quam
            vulputate fermentum vel vel lacus. Praesent condimentum nibh
            metus, ac luctus neque sodales sit amet. Aliquam in iaculis est,
            eu faucibus quam. Proin pretium, mi eget rhoncus finibus, quam
            mauris fermentum urna, dignissim varius leo ipsum at massa.
            Vestibulum a molestie leo, sit amet rutrum sem. Aliquam eu
            vehicula purus. Suspendisse luctus dolor a faucibus maximus. Etiam
            pharetra porttitor mauris mollis rutrum.</small>
        </p>
      </div>
    </div>
  </div>

  <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img
          src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100" alt="aaa" />
        <div class="overlay-image"></div>
      </div>
      <div class="carousel-item">
        <img
          src="https://images.unsplash.com/photo-1615880484746-a134be9a6ecf?q=80&w=1674&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100" alt="bbb" />
        <div class="overlay-image"></div>
      </div>
      <div class="carousel-item">
        <img
          src="https://plus.unsplash.com/premium_photo-1681922761648-d5e2c3972982?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100" alt="ccc" />
        <div class="overlay-image"></div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const guestType = document.getElementById("guestType");
      const guestNumber = document.getElementById("guestNumber");

      guestType.addEventListener("change", function () {
        if (guestType.value === "Solo travelers") {
          guestNumber.value = 1;
        } else {
          guestNumber.value = null;
        }
      });

      function submitReservation() {
        const form = document.getElementById("reservationForm");

        if (form.checkValidity()) {
          const formData = new FormData(form);
          const data = {};
          formData.forEach((value, key) => (data[key] = value));

          alert(`Form Data Submitted:\n${JSON.stringify(data, null, 2)}`);

          const reservationModal = new bootstrap.Modal(
            document.getElementById("reservationModal")
          );
          reservationModal.hide();
        } else {
          form.reportValidity();
        }
      }

      window.submitReservation = submitReservation;
    });

    function disablePastDates() {
      let input = document.getElementById("arrivalDate");
      let today = new Date().toISOString().split("T")[0];

      input.setAttribute("min", today);
    }
  </script>
</body>

</html>