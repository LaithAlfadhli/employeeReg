<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="clickmenu.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class='formBG text-dark'>

    <?php
    $sql_db_host = "localhost"; // MySQL Hostname
    $sql_db_user = "root"; // MySQL Database User
    $sql_db_pass = ""; // MySQL Database Password
    $sql_db_name = "employee"; // MySQL Database Name
    $sql_table_name = "employees"; // MySQL Table Name
    
    $con = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);
    if (mysqli_connect_errno()) {
        die("connection Failed: " . mysqli_connect_errno());
    }

    $mobileErr = $emailErr = $locationErr = $ageErr = $nameErr = "";
    // define variables and set to empty values
    $name = $age = $gender = $location = $email = $mobile = $vehicle = "";
    $idIncrement= '0'; 
    global $rs;

    $stmt = $con->prepare("INSERT INTO $sql_table_name ( ID,Name, Age, Gender, Location ,Email, Mobile, Vehicle) VALUES (?, ?,  ?,  ?,  ?,  ?, ?, ? )");
    $stmt->bind_param("ssssssss",$idIncrement, $name, $age, $gender, $location, $email, $mobile, $vehicle);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // check if name HAS BEEN SUBMITTED - FORM REQUIRES NAME
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }
        //check if age HAS BEEN SUBMITTED - FORM REQUIRES age
        if (empty($_POST["age"])) {
            $ageErr = "Age is required";
        } else {
            $age = test_input($_POST["age"]);
        }

        //check if location HAS BEEN SUBMITTED - FORM REQUIRES location
        if (empty($_POST["location"])) {
            $locationErr = "Location is required";
        } else {
            $location = test_input($_POST["location"]);
        }

        //check if email HAS BEEN SUBMITTED - FORM REQUIRES email
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }


        $gender = test_input($_POST["gender"]);

        //check if mobile number HAS BEEN SUBMITTED - FORM REQUIRES mobile number
        if (empty($_POST["mobile"])) {
            $mobileErr = "Mobile number is required";
        } else {
            $mobile = test_input($_POST["mobile"]);
        }

        $vehicle = test_input($_POST["vehicle"]);

        $idIncrement = getNextIncrement($sql_table_name, $con, $sql_db_name);
        /*
        $sql = "INSERT INTO '$sql_table_name' ('ID', 'Name', 'Age', 'Gender','Location','Email', 'Mobile', 'Vehicle') VALUES ('$idIncrement','$name',  '$age',  '$gender',  '$location',  '$email', '$mobile', '$vehicle' );" ;
            // insert in database 
            $rs= mysqli_query($con, $sql);
            if($rs)
            {
                echo "Contact Records Inserted";
            }
            */
            
        $stmt->execute();

    }
    function getNextIncrement($table, $conn, $sql_db_name)
    {
        $next_increment = 1;
        $newTable = mysqli_escape_string($conn, $table);
        $sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$sql_db_name' AND TABLE_NAME = '$newTable'";
        $results = $conn->query($sql);
        while ($row = $results->fetch_assoc()) {
            $next_increment = $row['AUTO_INCREMENT'];
        }
        return $next_increment;
    }
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    ?>
    <div class="container-lg logInBox">
        <div class="row mainContent text-center   mx-auto  ">
            <ul class="formBreadCrumb ">
                <li><a href="#">Home</a></li>
                <li><a href="#">Fields</a></li><!--Create a list of all fields as view -->
                <li>Signup</li>
            </ul>
            <h1>Employee Registration Form</h1>
            <div class="row  text-center   mx-auto  ">
                <div class="col-2 d-none d-sm-none d-md-block"></div>
                <div class="col-8  rounded text-center ">
                    <p><span class="text-danger">* required field</span></p>
                    <form method="post" class="form " action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class='inputBox'>
                            <label>Employee Name </label><br>
                            <input type="text"  class="border border-dark rounded" name="name" value='<?php echo $name; ?>'>
                            <span class="text-danger">*
                                <?php echo $nameErr; ?>
                            </span>
                            <br>
                        </div>
                        <div class='inputBox'>
                            <label>Age: </label><br>
                            <input type="number" class="border border-dark rounded"  name="age" value='<?php echo $age; ?>'>
                            <span class="text-danger">*
                                <?php echo $ageErr; ?>
                            </span>
                            <br>
                        </div>
                        <div class='inputBox'>
                            <label>Gender: </label><br>
                            <input type="text"  class="border border-dark rounded" name="gender" value='<?php echo $gender; ?>'><br>
                        </div>
                        <div class='inputBox'>
                            <label>Location </label><br>
                            <input type="text"  class="border border-dark rounded" name="location" value='<?php echo $location; ?>'>
                            <span class="text-danger">*
                                <?php echo $locationErr; ?>
                            </span>
                            <br>
                        </div>
                        <div class='inputBox'>
                            <label>Email </label><br>
                            <input type="email" class="border border-dark rounded" name="email" value='<?php echo $email; ?>'>
                            <span class="text-danger">*
                                <?php echo $emailErr; ?>
                            </span>
                            <br>
                        </div>
                        <div class='inputBox'>
                            <label>Mobile No. </label><br>
                            <input type="number" class="border border-dark rounded" name="mobile" value='<?php echo $mobile; ?>'>
                            <span class="text-danger">*
                                <?php echo $mobileErr; ?>
                            </span>
                            <br>
                        </div>
                        <div class='inputBox'>
                            <label>Vehicle No. </label><br>
                            <input type="text" class="border border-dark rounded" name="vehicle" value='<?php echo $vehicle; ?>'><br>
                        </div><br><br>
                        <input type="submit">
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>

</html>