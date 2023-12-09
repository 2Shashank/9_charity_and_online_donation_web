<?php 
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$to = "nagarajam01011974@gmail.com";
$subject = "Mail form Donation web";

$headers = "From: " .$name. "\r\n" ;
$txt = "You recieved message form :" .$name ."\r\nEmail:" .$email ."\r\nMessage: " . $message;
$message = "Messaging from ..";
if($email != NULL){
    mail($to, $subject, $txt, $email);
}
header('Location: index.php');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
      /* Style inputs with type="text", select elements and textareas */
      body {
        font-family: Arial, sans-serif;
        background-image: url("https://st2.depositphotos.com/1005893/6834/i/450/depositphotos_68349399-stock-photo-money-jar-with-donations-label.jpg");
        background-size: 100% auto;
        background-repeat: no-repeat;
        background-attachment: fixed;
      }
      input[type="text"],
      select,
      textarea {
        width: 100%; /* Full width */
        padding: 12px; /* Some padding */
        border: 1px solid #ccc; /* Gray border */
        border-radius: 4px; /* Rounded borders */
        box-sizing: border-box; /* Make sure that padding and width stays in place */
        margin-top: 6px; /* Add a top margin */
        margin-bottom: 16px; /* Bottom margin */
        resize: vertical; /* Allow the user to vertically resize the textarea (not horizontally) */
      }
      input[type="email"],
      select,
      textarea {
        width: 100%; /* Full width */
        padding: 12px; /* Some padding */
        border: 1px solid #ccc; /* Gray border */
        border-radius: 4px; /* Rounded borders */
        box-sizing: border-box; /* Make sure that padding and width stays in place */
        margin-top: 6px; /* Add a top margin */
        margin-bottom: 16px; /* Bottom margin */
        resize: vertical; /* Allow the user to vertically resize the textarea (not horizontally) */
      }

      /* Style the submit button with a specific background color etc */
      input[type="submit"] {
        background-color: #04aa6d;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }

      /* When moving the mouse over the submit button, add a darker green color */
      input[type="submit"]:hover {
        background-color: #45a049;
      }

      /* Add a background color and some padding around the form */
      .container {
        border-radius: 5px;
        background-color: transparent;
        padding: 20px;
      }
    </style>
    <title>User feedback</title>
  </head>
  <body>
    <div class="container">
      <form action="contact.php" method="post">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Your name.." />

        <!-- <label for="lname">Last Name</label>
          <input type="text" id="lname" name="lastname" placeholder="Your last name.."> -->
        <label for="email">Emial</label>
        <input
          type="email"
          name="email"
          id="email"
          placeholder="example@gmail.com"
        />

        <label for="country">Country</label>
        <select id="country" name="country">
          <option value="australia">India</option>
        </select>

        <label for="message">Subject</label>
        <textarea
          id="message"
          name="message"
          placeholder="Write your message.."
          style="height: 200px"
        ></textarea>

        <input type="submit" value="Submit" />
      </form>
    </div>
  </body>
</html>
