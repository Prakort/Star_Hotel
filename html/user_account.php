<?php
session_start();

if(!isset($_SESSION['email']))
{

header('Location: login.html');


}
$email=$_SESSION['email'];
$delete=array();
require_once'database.php';
$con = new mysqli($hn,$un,$pw,$db);
if($con->connect_error)
    die($con->connect_error);

$query="select * from guest where email='$email'";
$result=$con->query($query);
$rows = $result->num_rows;
$result->data_seek(0);
$row = $result->fetch_array(MYSQLI_ASSOC);
$firstname = strtoupper($row['firstname']);
$lastname=strtoupper($row['lastname']);
$address=$row['address'];
$city=$row['city'];
$state=$row['state'];
$zip=$row['zip'];


//////////////// UPDATE ACCOUNT INFO ///////////////////

if(isset($_POST['updateLname'])&&
isset($_POST['updateFname'])&&
isset($_POST['updateAddress'])&&
isset($_POST['updateCity'])&&
isset($_POST['updateState'])&&
isset($_POST['updateZip'])

)
{

$updateFname=$_POST['updateFname'];
$updateLname=$_POST['updateLname'];
$updateCity=$_POST['updateCity'];
$updateAddress=$_POST['updateAddress'];
$updateZip=$_POST['updateZip'];
$updateState=$_POST['updateState'];

$query="update guest
set firstname='$updateFname',lastname='$updateLname',address='$updateAddress',city='$updateCity',zip='$updateZip',state='$updateState' where email='$email'";
$result=$con->query($query);

$firstname = strtoupper($updateFname);
$lastname=strtoupper($updateLname);
$address=$updateAddress;
$city=$updateCity;
$state=$updateState;
$zip=$updateZip;

}

////////////////  CANCEL THE BOOKING ///////////////////
if(isset($_POST['cancel-btn']))
{
if(!empty($_POST['box'])){
$id =implode(',',$_POST['box']);

$query=$con->query("update booking set status='cancelled' where email='$email' and bookingID in($id)");
if (!$result) echo "Update failed: $query<br>" .
      $con->error . "<br>error<br>";

}
}



if(isset($_POST['signout'])){

session_unset();
header('Location: '.'index.html');

}


?>
<!DOCTYPE html>
<html>
<head>

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
<h1 style="text-align: center">Star Hotel</h1>
<h2 style="text-align: center">Hi, <?php echo $firstname ?></h2>

  <div class="imgcontainer d-flex justify-content-center">
    <img src="../images/login.png" alt="Avatar" class="avatar">
  </div>

  <div id="accordion" >
    <div class="card">
      <div class="card-header" style="text-align:center" id="headingOne">
        <h5 class="mb-0">
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            Account Info
          </button>
        </h5>
      </div>

      <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputEmail4">First Name</label>
        <input type="text" class="form-control" name="outputFname"id="inputEmail4" value="<?php echo $firstname ?>" readonly>
      </div>
      <div class="form-group col-md-6">
        <label for="inputPassword4">Last Name</label>
        <input type="text" class="form-control" name="outputLname" id="inputPassword4" value="<?php echo $lastname ?>" readonly>
      </div>
    </div>
    <div class="form-group">
      <label for="inputAddress">Email</label>
      <input type="email" name="outputEmail"class="form-control" id="inputAddress" value="<?php echo $email ?>" readonly>
    </div>
    <div class="form-group">
      <label for="inputAddress2">Address</label>
      <input type="text" name="outputAddress"class="form-control" id="inputAddress2" value="<?php echo $address ?>" readonly>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputCity">City</label>
        <input type="text" name="outputCity" class="form-control" value="<?php echo $city ?>" id="inputCity" readonly>
      </div>
      <div class="form-group col-md-4">
        <label for="inputState">State</label>
        <input type="text" name="outputState" class="form-control" value="<?php echo $city ?>" id="inputCity" readonly>
      </div>
      <div class="form-group col-md-2">
        <label for="inputZip">Zip</label>
        <input type="text" name="outputZip"class="form-control" value="<?php echo $zip ?>" id="inputZip" readonly>
      </div>
    </div>


        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header" style="text-align:center"  id="headingTwo">
        <h5 class="mb-0">
          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Booking Info
          </button>
        </h5>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
<div class="table-responsive-md">

            <table class="table table-grey">
      <thead>
        <tr>
          <th scope="col">#Booking</th>
          <th scope="col">Check In</th>
          <th scope="col">Check Out</th>
          <th scope="col">Guests</th>
          <th scope="col">Kids</th>
          <th scope="col">Rooms</th>
          <th scope="col">Type</th>
          <th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
<?php

$query="select * from booking where email='$email'";
$result=$con->query($query);
if(!$result) die($conn->error);
$rows= $result->num_rows;

for ( $j=$rows-1;$j>=0 ;--$j)
{

   echo '<tr>';
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    echo '<td>'   . $row['bookingID']   . '</td>';
    echo '<td>'    . $row['checkin']    . '</td>';
    echo '<td>'    . $row['checkout']    . '</td>';
    echo '<td>'    . $row['guests']    . '</td>';
    echo '<td>'    . $row['kid']    . '</td>';
    echo '<td>'    . $row['room']    . '</td>';
    echo '<td>'    . $row['type']    . '</td>';
    echo '<td>'    . $row['status']    . '</td>';
    echo '</tr>';

}

?>

      </tbody>
    </table>
</div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header" style="text-align:center" id="headingThree">
        <h5 class="mb-0">
          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Update Info
          </button>
        </h5>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">

<form action='user_account.php' method="POST" form="updateInfo" name="updateInfo">


            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputEmail4">First Name</label>
                <input type="text" class="form-control" name="updateFname"id="inputEmail4" placeholder="<?php echo $firstname ?>" required>
              </div>
              <div class="form-group col-md-6">
                <label for="inputPassword4">Last Name</label>
                <input type="text" class="form-control" name="updateLname" id="inputPassword4" placeholder="<?php echo $lastname ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="inputAddress2">Address</label>
              <input type="text" name="updateAddress"class="form-control" id="inputAddress2" placeholder="<?php echo $address ?>"  required>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputCity">City</label>
                <input type="text" name="updateCity" class="form-control"  placeholder="<?php echo $city ?>" required >
              </div>
              <div class="form-group col-md-4">
                <label for="inputState">State</label>
                <input type="text" name="updateState" class="form-control" placeholder="<?php echo $state ?>" required>
              </div>
              <div class="form-group col-md-2">
                <label for="inputZip">Zip</label>
                <input type="text" name="updateZip"class="form-control" placeholder="<?php echo $zip ?>" required >
              </div>
            </div>
            <div class="form-group row d-flex justify-content-center">
                <div class="col-sm-10 d-flex justify-content-center">
                  <button type="submit" class="btn btn-primary">Update Now</button>
                </div>
              </div>

</form>

        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header"style="text-align:center"  id="headingFour">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Cancellation
        </button>
      </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
      <div class="card-body">

        <form action="user_account.php" method="POST" id="cancel_form" name="cancel_form">
<p style="text-align:center"> Cancellation is allowed only 24 HOURS before the Check In date.</br> Possible Bookings To Be Canceled:</p>
<div class="table-responsive-xl">

            <table class="table table-grey">
      <thead>
        <tr>
          <th scope="col">#Booking</th>
          <th scope="col">Check In</th>
          <th scope="col">Check Out</th>
          <th scope="col">Guests</th>
          <th scope="col">Kids</th>
          <th scope="col">Rooms</th>
          <th scope="col">Type</th>
          <th scope="col">Status</th>
         <th scope="col">Check</th>
        </tr>
      </thead>
      <tbody>
<?php

$query="select * from booking where email='$email' and status='N/A'";
$result=$con->query($query);
if(!$result) die($conn->error);
$rows= $result->num_rows;
$i=0;
for ( $j=$rows-1;$j>=0 ;--$j)
{
$result->data_seek($j);
$row = $result->fetch_array(MYSQLI_ASSOC);
$thedate=$row['checkin'];
$current = date("Y-m-d");
$date=str_replace('/','-',$thedate);
$newDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$1-$2",$date);
$daysdiffernce = date_diff(date_create($current),date_create($newDate));
$d_is=$daysdiffernce->format("%R%a");
$hour=15-date('H');
  if($d_is>1 || (($d_is==1 && $hour>0))){
$id_t=$row['bookingID'];
    echo '<tr>';
    echo '<td>'   . $row['bookingID']   . '</td>';
    echo '<td>'    . $row['checkin']    . '</td>';
    echo '<td>'    . $row['checkout']    . '</td>';
    echo '<td>'    . $row['guests']    . '</td>';
    echo '<td>'    . $row['kid']    . '</td>';
    echo '<td>'    . $row['room']    . '</td>';
    echo '<td>'    . $row['type']    . '</td>';
    echo '<td>'    . $row['status']    . '</td>';
echo '<td><input type='."'checkbox'".'name='."'box[]'"."   value='".$id_t ."'></td>";
    echo '</tr>';
}
}

?>

      </tbody>
    </table>
</div>
            <div class="d-flex justify-content-center">
                  <button type="submit" name="cancel-btn"class="btn btn-primary">DELETE THE BOOKING</button>
                </div>
        </div>
      </div>
    </div>






        </form>
      </div>
    </div>
  </div>
</div>
<form action="user_account.php" method="POST" >
<div class="d-flex justify-content-center" style="margin-top:60px;">
                  <button type="submit" name="signout" class="btn btn-primary">SIGN OUT</button>
                </div>
</form>



</body>
</html>
