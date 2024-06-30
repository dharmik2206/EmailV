<?php
    require 'connection.php';
    
    if(isset($_GET['email']) && isset($_GET['v_code']))
    {
        $query = "select * from `registered_users` where `email`='$_GET[email]' and `verification_code`='$_GET[v_code]'";
        $result = mysqli_query($con, $query);
        if($result)
        {
            if(mysqli_num_rows($result) == 1)
            {
                $result_fetch= mysqli_fetch_assoc($result);
                if ($result_fetch['is_verified'] == 0)
                {
                    $update="update `registered_users` set `is_verified`='1' where `email`='$result_fetch[email]'";
                    if(mysqli_query($con, $update))
                    {
                        echo"
                            <script>
                                alert('Email verified successful');
                                window.location.href='index.php';
                            </script>
                        ";
                    }
                    
                    else 
                    {
                       echo"
                            <script>
                                alert('Email already verified');
                                window.location.href='index.php';
                            </script>
                        "; 
                    }
                }
                
                else
                {
                    echo"
                        <script>
                            alert('Email already verified');
                            window.location.href='index.php';
                        </script>
                    ";
                }
            }
            
            else 
            {
                
            }
        }
        
        else {
            echo"
                <script>
                    alert('cannot run query');
                    window.location.href='index.php';
                </script>
            ";
        }
    }
?>
