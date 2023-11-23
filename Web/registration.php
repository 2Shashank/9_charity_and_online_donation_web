
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h1 style="margin-top: 20px;">Register form</h1>
    <?php
        session_start();

        if (isset($_SESSION["user"])) {
        header("Location: home.php");
        exit();
        }

        require_once "database.php";

        if (isset($_POST["submit"])) {
            $userType = $_POST["UserType"];
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
            $contactDetails = $_POST["contactDetails"];
            $address = $_POST["address"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();
            if ($userType !== 'donar' && $userType !== 'donee') {
                array_push($errors, "Invalid user type");
            }
            if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat) || empty($contactDetails)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($password !== $passwordRepeat) {
                array_push($errors, "Password does not match");
            }
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO User (Name, Email, Password, Address, User_type, Contact_details) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssssss", $fullName, $email, $passwordHash, $address, $userType, $contactDetails);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                } else {
                    die("Something went wrong");
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_close($conn);
        }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <select name="UserType" class="form-control" id="type">
                    <option value="select">Select</option>
                    <option value="donar">Donar</option>
                    <option value="donee">Donee</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Confirm Password">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="contactDetails" placeholder="Contact details" >
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="address" placeholder="Contact address" >
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
            <p>Already Registered? <a href="login.php">Login Here</a></p>
        </div>
    </div>
</body>
</html>
