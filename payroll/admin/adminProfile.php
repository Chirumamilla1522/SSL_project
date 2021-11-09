<?php
    session_start();
    require_once "../authenticate/login.php";
    $conn = mysqli_connect($hostname, $username, $password, $database);
    if(!$conn){
        die("Error connecting to server. Please try after sometime.".mysqli_connect_error());
        header('url=../index.php');
        exit();
    }

    if($_SESSION['loggedIn'] == false || ($_SESSION['isAdmin'] == false && $_SESSION['isHr'] == false)){
        // echo "Error 404. <br> The page you requested does not exists.";
        echo "<script>alert('The page you requested does not exists.')</script>";
        header('Refresh:01; url=../index.php');
        exit();
    }


?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Homepage</title>
    <link rel="stylesheet" href="../index.css">
</head>
<body>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="../index.html">Home</a>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <a class="nav-link" href="https://www.youtube.com/watch?v=gq-hnrCn8Ms">Video <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <?php
                if($_SESSION['loggedIn'] == true)
                    echo '<a class="nav-link" href="../user/logout.php">Logout</a>';
                else
                    echo '<a class="nav-link" href="../index.php">Logout</a>';
            ?>
          </li>
          <li class="nav-item">
          </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="../filter.php">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filter</button>
        </form>
        </div>
    </nav>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 ">
                <?php
                    if($_SESSION['isAdmin'] == true && $_SESSION['loggedIn'] == true){
                        echo <<< _END
                            <img class="img-fluid" id="userImage" src="../images/emp.jpg">
                            </img>
                            <form action="createUser.php">
                                <div class="row">
                                    <button type="submit" class="btn btn-primary" style="margin: 5px; margin-left:30px">Add User</button>
                                </div>
                            </form>
                            <br><hr><br>
_END;
                    }

                    if($_SESSION['isAdmin'] == true && $_SESSION['loggedIn'] == true){
                        echo <<< _END
                            <img class="img-fluid" id="userImage" src="../images/ceo.jpg">
                            </img>
                            <form action="createHr.php">
                                <div class="row">
                                    <button type="submit" class="btn btn-primary" style="margin: 5px; margin-left:30px">Add HR</button>
                                </div>
                            </form>
_END;
                    }
                    if($_SESSION['isHr'] == true && $_SESSION['loggedIn'] == true){
                        $rate = "SELECT rate FROM hr_table WHERE username='{$_SESSION['user']}'";
                        $rate = mysqli_query($conn, $rate);
                        $rate = mysqli_fetch_row($rate)[0];
                        echo <<< _END
                            <div class="container-fluid">
                                <img class="img-fluid" id="userImage" src='../images/richie.jpg' ></img>
                                <div class="row">
                                    <div class="col-md-2">
                                        <form method='POST' action='changeRate.php' enctype='multipart/form-data'>
                                            <div class="form-group row-md-2">    
                                                ChangeSalaryRate: <input type='text' name='rate' placeholder='\${$rate} (per 20 sec)' id='rate' required>
                                            </div>
                                                <input type='submit' class="btn btn-primary">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <form method="POST" target="_blank" action="calcAllSalary.php">
                                <div class="row">
                                    <div style="margin-left: 10px;">All Employees Salary:</div>
                                    <button type="submit" class="btn btn-primary" style="margin: 5px; margin-left:30px">Dowload</button>
                                </div>
                            </form>
_END;
                    }
                ?>
            </div>

            <div class="col-md-4 ">
                <?php

                    $conn = mysqli_connect($hostname, $username, $password, $database);
                    if(!$conn){
                        die("Error connecting to server. Please try after sometime.".mysqli_connect_error());
                        header('url=../index.php');
                        exit();
                    }

                    $show_all_emp_query = "SELECT * FROM auth WHERE isAdmin='no' AND isHr='no'";
                    $show_all_emp_result = mysqli_query($conn, $show_all_emp_query);

                    $number_of_emp = mysqli_num_rows($show_all_emp_result);
                    echo "<h5>Number of employees : <span class='counter-count'>{$number_of_emp}</span> </h5><br>";

                    echo "<div class='list-group'>";
                    for($i=0;$i<$number_of_emp;$i+=1){
                        $cur_emp_details = mysqli_fetch_row($show_all_emp_result);
                        echo "<a class='list-group-item list-group-item-action border-primary' href='displayUserStats.php?user={$cur_emp_details[0]}' >{$cur_emp_details[0]}</a><br> ";
                    }

                    echo "</div>";

                ?>
            </div>

            <div class="col-md-6">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                  </ol>
                  <div class="carousel-inner">
                    <div class="carousel-item active">
                      <img class="img-fluid d-block w-100" src="../images/employment.png" alt="First slide" style="max-width:100%; height: 550px;">
                    </div>
                    <div class="carousel-item">
                      <img class="img-fluid d-block w-100" src="../images/meeting.jpg" alt="Second slide" style="max-width:100%; height: 550px;">
                    </div>
                    <div class="carousel-item">
                      <img class="img-fluid d-block w-100" src="../images/hr.jpg" alt="Third slide" style="max-width:100%; height: 550px;">
                    </div>
                  </div>
                  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
            </div>

        </div>
    </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script>
        $('.counter-count').each(function () {
            $(this).prop('Counter',0).animate({
                Counter: $(this).text()
            }, {
                duration: 1000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
    </script>

</body>
</html>
