<?php
require_once "database.php";

// Assuming you have a User table with columns: Name, Email, Address, User_type
$sqlDonors = "SELECT * FROM User WHERE User_type = 'Donar'";
$resultDonors = mysqli_query($conn, $sqlDonors);

$sqlDonees = "SELECT * FROM User WHERE User_type = 'Donee'";
$resultDonees = mysqli_query($conn, $sqlDonees);

$resultMonDonations = mysqli_query($conn, "SELECT * FROM money_donation");

$resultOthDonations = mysqli_query($conn , "SELECT * FROM other_donation");

$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as user_count FROM User"))['user_count'];

$donationCount1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as donation_count FROM money_donation"))['donation_count'];
$donationCount2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as donation_count FROM other_donation"))['donation_count'];

$donationCount = $donationCount1 + $donationCount2;
$requestCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as request_count FROM Request_donation"))['request_count'];

$resultRequest = mysqli_query($conn, "SELECT * FROM Request_donation");


$donationAmount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Amount) as donation_amount FROM money_donation"))['donation_amount'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["request_id"]) && isset($_POST["UpdateStatus"])) {
        $requestId = $_POST["request_id"];
        $newStatus = ($_POST["updateStatus"] === "approve") ? "Approved" : "Rejected";
        // Update the Status column in the database
        $updateQuery = "UPDATE Request_donation SET Status = '$newStatus' WHERE Request_id = " . strval($requestId);
        mysqli_query($conn, $updateQuery);
        // Redirect to the same page after processing the form
        header("Location: admin.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="admin_styles.css" />
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                    class="fas fa-handshake me-2"></i>Charity and Donation</div>
            <div class="list-group list-group-flush my-3">
                <a href="#" class="list-group-item list-group-item-action bg-transparent second-text active"><i
                        class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" onclick="fetchDonors()"><i
                        class="fas fa-user me-2"></i>Donars</a>
                <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" onclick="fetchDonees()"><i
                        class="fas fa-user me-2"></i>Donee</a>
                <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" onclick="fetchRequests()"><i
                        class="fas fa-user me-2"></i>Requests</a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold"><i
                        class="fas fa-power-off me-2"></i>Logout</a>
            </div>
        </div>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">Dashboard</h2>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i>Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="logout.php">Logput</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid px-4">
                <div class="row g-3 my-2">
                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $userCount; ?></h3>
                                <p class="fs-5">Users</p>
                            </div>
                            <i class="fas fa-gift fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">₹<?php echo $donationAmount; ?></h3>
                                <p class="fs-5">Donation recieved</p>
                            </div>
                            <i
                                class="fas fa-hand-holding-usd fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $requestCount; ?></h3>
                                <p class="fs-5">Donation sent</p>
                            </div>
                            <i class="fas fa-hand-holding-usd fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $donationCount; ?></h3>
                                <p class="fs-5">Total donations</p>
                            </div>
                            <i class="fas fa-chart-line fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                </div>
                <div class="row my-5">
                    <!-- <h3 class="fs-4 mb-3">Recent Donations</h3> -->
                    <div class="col" id="donar_info" style="display: none" >
                    <h3 class="fs-4 mb-3">Donors</h3>
                    <table class="table bg-white rounded shadow-sm table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Address</th>
                                <th scope="col">Contact details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $counter = 1;
                                while ($donor = mysqli_fetch_assoc($resultDonors)) {
                                    echo "<tr>";
                                    echo "<td>$counter</td>";
                                    echo "<td>{$donor['Name']}</td>";
                                    echo "<td>{$donor['Email']}</td>";
                                    echo "<td>{$donor['Address']}</td>";
                                    echo "<td>{$donor['Contact_details']}</td>";
                                    echo "</tr>";
                                    $counter++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="col" id="donee_info" style="display: none" >
                    <h3 class="fs-4 mb-3">Donees</h3>
                    <table class="table bg-white rounded shadow-sm  table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Address</th>
                                <th scope="col">Contact details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $counter = 1;
                                while ($donee = mysqli_fetch_assoc($resultDonees)) {
                                    echo "<tr>";

                                    echo "<td>$counter</td>";
                                    echo "<td>{$donee['Name']}</td>";
                                    echo "<td>{$donee['Email']}</td>";
                                    echo "<td>{$donee['Address']}</td>";
                                    echo "<td>{$donee['Contact_details']}</td>";
                                    echo "</tr>";
                                    $counter++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col" id="donation_info"  >
                    <h3 class="fs-4 mb-3">Donations</h3>
                    <table class="table bg-white rounded shadow-sm  table-hover">
                        <thead>
                            <tr>
                                <th scope="col" >#</th>
                                <th scope="col">Donation ID</th>
                                <th scope="col">Email</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Type of payment</th>
                                <th scope="col">Item</th>
                                <th scope="col">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $counter = 1;
                                while ($donation = mysqli_fetch_assoc($resultMonDonations)) {
                                    echo "<tr>";
                                    echo "<td>$counter</td>";
                                    echo "<td>{$donation['Donation_id']}</td>";
                                    echo "<td>{$donation['Email']}</td>";
                                    echo "<td>₹{$donation['Amount']}</td>";
                                    echo "<td>{$donation['Payment_type']}</td>";
                                    echo "<td>-</td>";
                                    echo "<td>-</td>";
                                    echo "</tr>";
                                    $counter++;
                                }
                                while ($OthDon = mysqli_fetch_assoc($resultOthDonations)) {
                                    echo "<tr>";
                                    echo "<td>$counter</td>";
                                    echo "<td>{$OthDon['Donation_id']}</td>";
                                    echo "<td>{$OthDon['Email']}</td>";
                                    echo "<td>-</td>";
                                    echo "<td>-</td>";
                                    echo "<td>{$OthDon['Item']}</td>";
                                    echo "<td>{$OthDon['Description']}</td>";
                                    echo "</tr>";
                                    $counter++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col" id="request_info" style="display: none" >
                    <h3 class="fs-4 mb-3">Requests</h3>
                    <table class="table bg-white rounded shadow-sm  table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Request ID</th>
                                <th scope="col">Email</th>
                                <th scope="col">Item</th>
                                <th scope="col">Description</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while ($request = mysqli_fetch_assoc($resultRequest)) {
                                    echo "<tr>";
                                    echo "<td>$counter</td>";
                                    echo "<td>{$request['Request_id']}</td>";
                                    echo "<td>{$request['Email']}</td>";
                                    echo "<td>{$request['Item']}</td>";
                                    echo "<td>{$request['Description']}</td>";
                                    echo "<td>{$request['Status']}</td>";
                                    echo "<td>";
                                    // echo "<button onclick=\"handleAction('".strval($request['Request_id'])."', 'approve')\">Approve</button>";
                                    // echo "<button onclick=\"handleAction('".strval($request['Request_id'])."', 'reject')\">Reject</button>";
                                    echo "<form action=\"admin.php\" method=\"POST\" >";
                                    echo "<input type=\"hidden\" name=\"request_id\" value=\"$request[Request_id]\">";
                                    echo "<select id=\"UpdateStatus\" name=\"updateStatus\">";
                                    echo "<option value=\"select\">Select</option>";
                                    echo "<option value=\"approve\">Approve</option>";
                                    echo "<option value=\"reject\">Reject</option>";
                                    echo "</select>";
                                    echo "<input type=\"submit\" value=\"Update\">";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                    $counter++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");
        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };
        function fetchDonors() {
            var donar = document.getElementById("donar_info");
            var donee = document.getElementById("donee_info");
            var donation = document.getElementById("donation_info");
            var requestInfo =document.getElementById("request_info");

            if (donar.style.display === "none" || donee.style.display === "display" || requestInfo.style.display === "block") {
                donee.style.display = "none";
                donation.style.display = "none";
                requestInfo.style.display = "none";
                donar.style.display = "block";
            } else {
                donar.style.display = "none";
                donation.style.display = "block";
            } 
        }
        function fetchDonees() {
            var donar = document.getElementById("donar_info");
            var donee = document.getElementById("donee_info");
            var donation =document.getElementById("donation_info");
            var requestInfo =document.getElementById("request_info");

            if (donee.style.display === "none" || donar.style.display === "block" || requestInfo.style.display === "block"  ) {
                donar.style.display = "none";
                donation.style.display = "none";
                requestInfo.style.display = "none";
                donee.style.display = "block";
            } else {
                donee.style.display = "none";
                donation.style.display = "block";
            }
        }
        function fetchRequests() {
            var donar = document.getElementById("donar_info");
            var donee = document.getElementById("donee_info");
            var donation =document.getElementById("donation_info");
            var requestInfo = document.getElementById("request_info");

            if (requestInfo.style.display === "none" || donee.style.display === "block" || donar.style.display === "block" ) {
                donar.style.display = "none";
                donation.style.display = "none";
                donee.style.display = "none";
                requestInfo.style.display = "block";
            } else {
                requestInfo.style.display = "none";
                donee.style.display = "none";
                donation.style.display = "block";
            }
        }
    </script>
</body>
</html>
