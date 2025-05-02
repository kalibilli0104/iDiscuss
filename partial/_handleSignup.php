<?php
    $showError="false";
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        include '_dbconnect.php';
        $user_email=$_POST['signupEmail'];
        $pass=$_POST['signuppassword'];
        $cpass=$_POST['signupcpassword'];
        $showAlert=false;
        

        //chech whether this email exists
        $existSql="select * from `users` where user_email='$user_email'";
        $result=mysqli_query($conn,$existSql);
        $numRow=mysqli_num_rows($result);
        if($numRow>0)
        {
        header("Location: /index.php?showalert=true");

           
        }
        else
        {
            if($pass==$cpass)
            {
                $hash=password_hash($pass,PASSWORD_DEFAULT);
                $sql="insert into `users` (user_email,user_pass) values ('$user_email','$hash')";
                $result=mysqli_query($conn,$sql);
                if($result)
                {
                    header("Location: /index.php?signupsuccess=true");
                    exit();
                }
            }
            else
            {
                header("Location: /index.php?showerror=true");
               
            }
        }
       
    }

?>