<?php
  session_start();

  require_once "database.php";
  // Check if the user is logged in
  if (!isset($_SESSION["user_type"])) {
      header("Location: login.php"); 
      exit();
  }
  $userType = $_SESSION["user_type"];
  // Retrieve the email from the URL
  if (isset($_GET["email"])) {
      $email = urldecode($_GET["email"]);
      $result = mysqli_query($conn, "SELECT Name as user_name FROM User WHERE Email = '$email'");
      // Check if the query was successful
      if ($result) {
          $user = mysqli_fetch_assoc($result);
          $UserName = $user['user_name'];
      } else {
          // Handle the case where the query fails
          $UserName = "Unknown User";
      }
  } else {
      echo "Email parameter not provided.";
      exit();
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editAccount"])) {
    // Handle the form submission to update the user's information in the database
    $newName = $_POST["newName"];
    $newEmail = $_POST["newEmail"];
    $newAddress = $_POST["newAddress"];

    $updateQuery = "UPDATE User SET Name = '$newName', Email = '$newEmail', Address = '$newAddress' WHERE Email = '$email'";
    
    if (mysqli_query($conn, $updateQuery)) {
        // Update successful, redirect or display a success message
        header("Location: donar_home.php?email=" . urlencode($newEmail));
        exit();
    } else {
        // Handle the case where the update fails
        echo "Update failed: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = urldecode($_GET["email"]);
  $amount = intval($_POST["amount"]);
  $paymentType = $_POST["payment_type"];
  $item = $_POST["donation"];
  $description = $_POST["description"];


  if($item == "money"){
    $result1 = InsertMoney($email, $amount, $paymentType);

    if (isset($result1["Message"])) {
        echo "<div class='alert alert-success'>Thanks for your donation..</div>";
    } else {
        $errorMessage = $result1["Error"];
        echo "<div class='alert alert-danger'>$errorMessage</div>";
    }
  }
  else {
    $result2 = InsertOther($email, $item, $description);

    if (isset($result2["Message"])) {
      echo "<div class='alert alert-success'>Thanks for your donation..</div>";
    } else {
      $errorMessage = $result2["Error"];
      echo "<div class='alert alert-danger'>$errorMessage</div>";
    }
  }
  
}
function InsertMoney($email, $amount, $paymentType)
{
  global $conn;
  $procedureCall = "CALL InsertMoney(?, ?, ?)";
  $stmt = mysqli_prepare($conn, $procedureCall);

  if ($stmt) {
      mysqli_stmt_bind_param($stmt, "sis", $email, $amount, $paymentType);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      return ["Message" => "Successful message"];
  } else {
      return ["Error" => "Failed to prepare statement"];
  }
}
function InsertOther($email,$item,$description){
  global $conn;
  $procedureCall2 = "CALL InsertOthers(?, ?, ?)";
  $stmt = mysqli_prepare($conn, $procedureCall2);

  if($stmt){
    mysqli_stmt_bind_param($stmt,"sss",$email,$item,$description);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return ["Message" => "Successful message"];
  } else{
    return ["Error" => "Failed to prepare statement"];
  }
}

$resultMonDonations = mysqli_query($conn, "SELECT * FROM money_donation where Email = '$email'");
$resultOthDonations = mysqli_query($conn ,"SELECT * FROM other_donation where Email = '$email'");


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Donor Home Page</title>
  <link rel="stylesheet" href="footer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="donar.css">
  <link rel="stylesheet" href="edition.css">
  <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>
</head>
<body>
  <header>
    <h1>Donor Home Page </h1>
        <!-- <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user me-2"></i>Admin
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logput</a></li>
                </ul>
            </li>
        </ul> -->
  </header>

  <main>
    <section class="donor-profile">
      <div class="profile-info">
        <h2>Welcome, <?php echo $UserName; ?></h2>
        <div class="button-group">
          <button class="donate-button" onclick="#">Make a Donation</button><br /><br />
          <button class="history-button" onclick="getdonationInfo()">Donation History</button><br /><br />
          <button class="edit-button" id="toggledit" >Edit Account</button>
          <button class="logout-button" onclick="logout()">Logout</button><br /><br />
          <span class="overlay"></span><br /><br />
          <div class="modal-box" > 
            <form action="donar_home.php?email=<?php echo urlencode($email); ?>" method="post">
              <label for="newName">New Name:</label>
              <input type="text" id="newName" name="newName" required><br>
              
              <label for="newEmail">New Email:</label>
              <input type="email" id="newEmail" name="newEmail" required><br>

              <label for="newEmail">New Address:</label>
              <input type="text" id="newAddress" name="newAddress" required><br>

              <label for="newCd">New Contact details:</label>
              <input type="text" id="newCd" name="newCd" /><br />
              
              <input type="submit" value="Save Changes" name="editAccount">
            </form>
          </div>         
        </div>
      </div>
        <div class="profile-image">
          <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Profile" >
        </div>
    </section>
    <div class="container">
        <h1>Donation Form</h1>
        <form action="donar_home.php?email=<?php echo urlencode($email); ?>" method="post">
            <label for="donation">Donation Type:</label>
            <select id="donation" name="donation" required>
                <option value="select" selected disabled hidden>Choose an option</option>
                <option value="money">Money</option>
                <option value="Clothes">Clothes</option>
                <option value="Medicines">Medicines</option>
            </select>

            <div id="payment-group">
                <label for="amount">Enter amount</label>
                <input type="text" name="amount" value="amount">
                <label>Payment Options:</label>
                <select id="payment-type" name="payment_type" required>
                    <option value="select" selected disabled hidden>Choose an option</option>
                    <option value="UPI">UPI</option>
                    <option value="Credit">Credit</option>
                </select>
            </div>
            <div id="description-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description">
            </div>
            <input type="submit" value="Submit">
        </form>
    </div><br><br>
    <div class="donation_info" id="donation_info" style="display: none;" >
      <h2 style="background-color: black" >Donations</h2>
      <table class="table bg-white rounded shadow-sm  table-hover">
        <thead>
          <tr>
            <th scope="col" >#</th>
            <th scope="col" >Donation ID</th>
            <!-- <th scope="col" >Email</th> -->
            <th scope="col" >Amount</th>
            <th scope="col" >Payment type</th>
            <th scope="col" >Item</th>
            <th scope="col" >Description</th>
          </tr>
        </thead>
        <tbody>
          <?php
             $counter = 1;
             while($MDon = mysqli_fetch_assoc($resultMonDonations)){
              echo "<tr>";
              echo "<td>$counter</td>";
              echo "<td>{$MDon['Donation_id']}</td>";
              // echo "<td>{$MDon['Email']}</td>";
              echo "<td>₹{$MDon['Amount']}</td>";
              echo "<td>{$MDon['Payment_type']}</td>";
              echo "<td>-</td>";
              echo "<td>-</td>";
              echo "</tr>";
              $counter++;
             }
             while($ODon = mysqli_fetch_assoc($resultOthDonations)){
              echo "<tr>";
              echo "<td>$counter</td>";
              echo "<td>{$ODon['Donation_id']}</td>";
              // echo "<td>{$ODon['Email']}</td>";
              echo "<td>-</td>";
              echo "<td>-</td>";
              echo "<td>{$ODon['Item']}</td>";
              echo "<td>{$ODon['Description']}</td>";
              echo "</tr>";
              $counter++;
             }
          ?>
        </tbody>
      </table>

    </div>
  </main>

  <footer>
    <div>
      <div class="footer-content">
        <h3>Contact Us</h3>
        <p>Email: donorweb@example.com</p>
        <p>Phone: 123-456-7890</p>
        <p>Address: 123 Donor Street, City 56789</p>
      </div>
      <div class="footer-content">
        <h3>Follow Us</h3>
        <ul class="social-icons">
          <li><a href="#"><i class="fab fa-facebook"></i></a></li>
          <li><a href="#"><i class="fab fa-twitter"></i></a></li>
          <li><a href="#"><i class="fab fa-instagram"></i></a></li>
          <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
        </ul>
      </div>
    </div>
    <div class="bottom-bar">
      <p>“Giving is not just about making a donation. ..."</p>
      <p>&copy; 2023. All rights reserved</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function logout() {
            window.location.href = 'logout.php';
    }
    var edit = document.getElementById("editdata");
    var toggleButton =document.getElementById("toggledit");
    toggleButton.onclick = function () {
            edit.classList.toggle("toggled");
    };
    const section = document.querySelector("section"),
      overlay = document.querySelector(".overlay"),
      showBtn = document.querySelector(".edit-button");
      showBtn.addEventListener("click", () => section.classList.add("active"));
      overlay.addEventListener("click", () => section.classList.remove("active"));

      const donationSelect = document.getElementById('donation');
        const descriptionGroup = document.getElementById('description-group');
        const paymentGroup = document.getElementById('payment-group');

        donationSelect.addEventListener('change', function () {
            if (donationSelect.value === 'Clothes' || donationSelect.value === 'Medicines') {
                descriptionGroup.style.display = 'block';
                paymentGroup.style.display = 'none';
            } else if (donationSelect.value === 'money') {
                descriptionGroup.style.display = 'none';
                paymentGroup.style.display = 'block';
            } else {
                descriptionGroup.style.display = 'none';
                paymentGroup.style.display = 'none';
            }
        });

        var donationInfo = document.getElementById("donation_info");
        function getdonationInfo(){
          if(donationInfo.style.display == 'none'){
            donationInfo.style.display = 'block';
          } else {
            donationInfo.style.display = 'none';
          }
        }
        
  </script>
</body>
</html>
