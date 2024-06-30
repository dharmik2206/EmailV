<?php
    $con = mysqli_connect("localhost","root","","testing");

    if (mysqli_connect_errno()) {
        echo"<script>alert('not connect')</script>";
        exit();
    }
?>