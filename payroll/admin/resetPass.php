<?php
    
    session_start();
    if (!isset($_POST['pass'])) {
        $currentuser = trim($_POST['user']);
        echo <<< _END
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link rel="stylesheet" href="index.css">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="index.html">Home</a>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                        <a class="nav-link" href="https://www.youtube.com/watch?v=gq-hnrCn8Ms">Video <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                        
                        </li>
                    </ul>
                    
                </div>
            </nav>
            <div class="container mt-5">
                <div class="row">
                    <div class="pl-5 col-md-2">
                        <form method='POST' action='' enctype='multipart/form-data'>
                            <div class="form-group row-md-2">
                                <input type="hidden" name="user" value="{$currentuser}">
                                <label for='pass'>New Password: </label>
                                <input type='password' name='pass' id='pass' class="form-control" placeholder="password" required>
                            </div>
                                <input type='submit' class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        
        
_END;
    }
    else {
        require_once "../authenticate/login.php";
        $conn = mysqli_connect($hostname, $username, $password, $database);

        if(!$conn)
            die("Error while connecting to database.".mysqli_connect_error());
    
        $newPass = trim($_POST['pass']);
        $currentuser = trim($_POST['user']);
        $query = "UPDATE auth SET pass = '{$newPass}' WHERE username = '{$currentuser}'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            // echo "There was some error while updating password. Try again later";
            echo "<script>alert('There was some error while updating password. Try again later')</script>";
            header('Refresh:01; url=adminProfile.php');
        }
        else {
            // echo "Successfully updated password."; 
            echo "<script>alert('Successfully updated password')</script>";
            header('Refresh:01; url=adminProfile.php');
        }

        // mysqli_close($conn);
    }


?>
