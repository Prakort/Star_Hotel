<?php
session_start();
require_once'database.php';
$con = new mysqli($hn,$un,$pw,$db);
if ($con->connect_error) die($con->connect_error);
if( isset($_POST['t0'])&&
isset($_POST['t1'])&&
isset($_POST['t2'])&&
isset($_POST['t3'])&&
isset($_POST['t4'])
){
$select=$_POST['options'];

$t0=$_POST['t0'];
$t1=$_POST['t1'];
$t2=$_POST['t2'];
$t3=$_POST['t3'];
$t4=$_POST['t4'];

$query="update search set checkin='$t0',checkout='$t1',guests='$t2',kid='$t3',room='$t4',type='$select' where id=1";
 $result = $con->query($query);

                if (!$result) {echo "INSERT failed: $query<br>" .
                  $con->error . "<br><br>";}

}
$query="select* from search where id=1";
$result=$con->query($query);
$rows = $result->num_rows;
$result->data_seek(0);
$row = $result->fetch_array(MYSQLI_ASSOC);
$t0=$row['checkin'];
$t1=$row['checkout'];
$t2=$row['guests'];
$t3=$row['kid'];
$t4=$row['room'];
$select=$row['type'];
$type="A";
if($select=="A"){$type="Twin Beds Room";}
else if($select=="B") {$type="Queen Beds Room";}
else {$type="Family Beds Room";}


function get_post($connect, $var)  {
       return $connect->real_escape_string($_POST[$var]); }


?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="../css/login.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">

  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


  <!-- Font Awesome -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>

  <!-- Bootstrap Scripts -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>

      <!--------------------------------------------   Avatar  ------------------------------------------->
<h1 style="text-align: center">Star Hotel</h1>
<h2 style="text-align: center">Confirmation</h2>

<form action="login2.php" method="POST" id="loginform" autocomplete="off">
<div class="container" id="first-heading">
<div class="btn-toolbar mb-3 d-flex justify-content-center" id="temp"role="toolbar" aria-label="Toolbar with button groups">
  <div class="input-group">
    <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Check In</div>
    </div>
    <input type="text" name="t0" class="form-control" value="<?php echo $t0; ?>" style="width:125px;background-color: transparent;margin-top:0;" aria-describedby="btnGroupAddon" readonly>
    <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Check Out</div>
    </div>
    <input type="text" name="t1" class="form-control" value="<?php echo $t1 ?>" style="width:125px;background-color: transparent;margin-top:0;" aria-describedby="btnGroupAddon" readonly>

   <div class="input-group-prepend ">
      <div class="input-group-text" id="btnGroupAddon">Guests</div>
    </div>
    <input type="text" name="t2"class="form-control" value="<?php echo $t2 ?>" style="width:60px;background-color: transparent;margin-top:0;" aria-describedby="btnGroupAddon" readonly>

    <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Kid</div>
    </div>
    <input type="text" name="t3" class="form-control" value="<?php echo $t3 ?>" style="width:60px;background-color: transparent;margin-top:0;"  aria-describedby="btnGroupAddon" readonly>

   <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Room</div>
    </div>
    <input type="text" name="t4" class="form-control" value="<?php echo $t4 ?>" style="width:60px;background-color: transparent;margin-top:0;" aria-describedby="btnGroupAddon" readonly>
 <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Type</div>
    </div>
    <input type="text" name="t5" class="form-control" value="<?php echo $type ?>" style="width:180px;background-color: transparent;margin-top:0;" aria-describedby="btnGroupAddon" readonly>

</div>
</div>
</div>

    <!--------------------------------------------  Login------------------------------------------->

  <div class="imgcontainer">
    <img src="../images/login.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="email" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

<label for="psw"><b>User</b></label>
    <select class="browser-default custom-select" name="select_user" type="select-box" required>
      <option value="1" selected>Guest</option>
</select>
    </br>
    <button type="submit">Login</button>
  </div>
   <!--------------------------------------------   Cancel ------------------------------------------->
  <div class="container" id="cancel-create">
      <div class="row">
          <div class="col" id="cancel">
              <a class="download-button btn btn-lg btn-dark" href="result.php" type="link"></i>Cancel</a>
          </div>
        <div class="col" id="create">
            <a class="download-button btn btn-lg btn-dark" href="signup2.html" type="link"></i>Create Account</a>
          </div>
      </div>

</form>


</body>
</html>
