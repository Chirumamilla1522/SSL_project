<?php
    session_start();
    require_once "../authenticate/login.php";
    $_SESSION['queriedUser'] = $_GET['user'];

    if(!isset($_SESSION['queriedUser']) || (!isset($_SESSION['isAdmin']) && !isset($_SESSION['isHr'])) || ($_SESSION['isAdmin'] == false &&  $_SESSION['isHr'] == false)){
        die("Error 404 <br> The page you requested doesn't exist.");

        header('Refresh:01; url=../index.php');
        exit();
    }
    $conn = mysqli_connect($hostname, $username, $password, $database);
    if(!$conn){
        die("Error connecting to server. Please try after sometime.".mysqli_connect_error());

        header('url=../index.php');
        exit();
    }
    $today = 0;             
    if(isset($_GET['date'])){
        $today = $_GET['date'];
        $today = substr($today,0,-3);
    }
    else 
        $today = date('Y-m');
    $today = (string)$today.'%';
    
    $userQuery = "SELECT DISTINCT * FROM {$_SESSION['queriedUser']} WHERE date_ LIKE '{$today}' AND latitude != 'NA'";
    $userResult = mysqli_query($conn, $userQuery);

    if(!$userResult)
        die("Error fetching user deatils<br>".mysqli_error($conn));
    $number_of_rows = mysqli_num_rows($userResult);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UserDetails</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- For Js Calender -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="path/to/css/cal-heatmap.css" />
    <script type="text/javascript" src="path/to/cal-heatmap.min.js"></script>

    <script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.css"/>

</head>
<body>

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
            <a class="nav-link" href="adminProfile.php">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../user/logout.php">Logout</a>
          </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="../filter.php">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filter</button>
        </form>
        </div>
    </nav>


    <div class="container">
        <div class="row">
            <h3 class="col-8 p-3 text-center">The details for <?php echo $_GET['user']; ?> are : <br></h3>    
            <div class="col float-right p-3">
                <!-- <button type="submit" action="calcSalary.php?user=<?php echo $_GET['user']; ?>" class="btn btn-primary" value="Calc Salary">  -->
                <form method="POST" action="calcSalary.php?date=<?php echo $today; ?>">
                    <input type="hidden" name="user" value="<?php echo $_GET['user']; ?>">
                    <input type="submit" class="btn btn-primary" value="Calculate Salary" >
                </form>
            </div>
            <?php
                $user = $_GET['user'];
                if ($_SESSION['isAdmin'] == true && $_SESSION['loggedIn'] == true) {
                    echo <<< _END
                        <div class="col float-right p-3">
                            <form method="POST" action="resetPass.php">
                                <input type="hidden" name="user" value="{$user}">
                                <input type="submit" class="btn btn-primary" value="Reset Password" >
                            </form>
                        </div>
_END;
                }
            ?>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S. No.</th>
                    <th scope="col">Date</th>
                    <th scope="col">Latitude</th>
                    <th scope="col">Longitude</th>
                    <th scope="col">Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    for($i=0;$i<$number_of_rows;$i++){
                        $row = mysqli_fetch_row($userResult);
                        echo <<< _END
                            <tr>
                                <th scope="row">{$i}</th>
                                <td>{$row[0]}</td>
                                <form type="post" action="" enctype="multipart/form-data">
                                    <td><input type="hidden" value="$row[1]" name="lat">{$row[1]}</td>
                                    <td><input type="hidden" value="$row[2]" name="lng">{$row[2]}</td>
                                </form>
                                <form type="post" action="showImage.php" enctype="multipart/form-data">
                                    <input type="hidden" value="{$row[0]}" name="date">
                                    <input type="hidden" value="{$_GET['user']}" name="emp">
                                    <td><button type="submit" class="btn btn-primary">Show Images</button></td>
                                </form>
                            </tr>
_END;
                    }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>

