<?php

require ('connection.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $v_code) {
    require ("PHPMailer/PHPMailer.php");
    require ("PHPMailer/SMTP.php");
    require ("PHPMailer/Exception.php");

    $mail = new PHPMailer(true);

    try {                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'dharmikdonda2206@gmail.com';                     //SMTP username
        $mail->Password = 'mrbg hyyu lnvc uaqm';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //Recipients
        $mail->setFrom('22bmiit010@gmail.com', 'Hopeful hearts');
        $mail->addAddress($email);     //Add a recipient

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email verification from hopeful hearts';
        $mail->Body = "Thanks for registraion click the link below to verify the email address 
                        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

#for login
if (isset($_POST['login'])) {
    $query = "SELECT * FROM `registered_users` WHERE `email`='$_POST[email_username]' OR `username`='$_POST[email_username]'";
    $result = mysqli_query($con, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $result_fetch = mysqli_fetch_assoc($result);
            if($result_fetch['is_verified']==1)
            {
                if (password_verify($_POST['password'], $result_fetch['password'])) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $result_fetch['username'];
                    header("location: index.php");
                } else {
                    echo"
                        <script>
                        alert('Password is not right');
                        window.location.href='index.php';
                        </script>
                    ";
                }
            }
            
            else
            {
                echo"
                    <script>
                    alert('email not verified');
                    window.location.href='index.php';
                    </script>
                ";
            }
        } else {
            echo"
                    <script>
                    alert('Email or username not registered');
                    window.location.href='index.php';
                    </script>
                ";
        }
    } else {
        echo"
                <script>
                    alert('cannot run query');
                    window.location.href='index.php';
                </script>
            ";
    }
}


#for registration
if (isset($_POST['register'])) {

    $user_exist_query = "SELECT * FROM `registered_users` WHERE `username`='$_POST[username]' OR `email`='$_POST[email]'";
    $result = mysqli_query($con, $user_exist_query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $result_fetch = mysqli_fetch_assoc($result);
            if ($result_fetch['username'] == $_POST['username']) {
                echo"
                        <script>
                        alert('$result_fetch[username] - username alreadt taken');
                        window.location.href='index.php';
                        </script>
                    ";
            } else {
                echo"
                        <script>
                        alert('$result_fetch[email] - E-mail alreadt taken');
                        window.location.href='index.php';
                        </script>
                    ";
            }
        } else {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $v_code = bin2hex(random_bytes(6));
            $query = "INSERT INTO `registered_users`(`full_name`, `username`, `email`, `password`, `verification_code`, `is_verified`) VALUES ('$_POST[fullname]','$_POST[username]','$_POST[email]','$password','$v_code','0')";
            if (mysqli_query($con, $query) && sendMail($_POST['email'], $v_code) ) {
                echo"
                        <script>
                            alert('Registration successfully');
                            window.location.href='index.php';
                        </script>
                    ";
            } else {
                echo"
                        <script>
                            alert('Server Down');
                            window.location.href='index.php';
                        </script>
                    ";
            }
        }
    } else {
        echo"
                <script>
                    alert('cannot run query');
                    window.location.href='index.php';
                </script>
            ";
    }
}
?>
