<?php

session_start();

$email=$_SESSION['email'];
$delete=array();
require_once'database.php';
$con = new mysqli($hn,$un,$pw,$db);
if($con->connect_error)
    die($con->connect_error);

// unauthorized -> redirect to login

if(!isset($_SESSION['email'])){
header('Location: login.html');
}


$query="select * from employee where email='$email'";
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

// update info

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

$query="update employee
set firstname='$updateFname',lastname='$updateLname',address='$updateAddress',city='$updateCity',zip='$updateZip',state='$updateState' where email='$email'";
$result=$con->query($query);

$firstname = strtoupper($updateFname);
$lastname=strtoupper($updateLname);
$address=$updateAddress;
$city=$updateCity;
$state=$updateState;
$zip=$updateZip;

}

// cancel the bookings

if(isset($_POST['cancel-btn']))
{
if(!empty($_POST['box'])){
$id =implode(',',$_POST['box']);

$query=$con->query("delete from booking where email='$email' and bookingID in($id)");
if (!$result) echo "DELETE failed: $query<br>" .
      $con->error . "<br>error<br>";

}
}

// sign out redirect to home page

if(isset($_POST['signout'])){

session_unset();
header('Location: '.'index.html');

}



if(isset($_POST['checkin_confirm']))
{

if(!empty($_POST['boxconfirm'])){
$id =implode(',',$_POST['boxconfirm']);

$query=$con->query("update booking set status='confirmed' where bookingID in($id)");

}
}

// comfirm the booking and add rooms to booking

if(isset($_POST['select-room-confirm']))
{
$id = $_POST['room_list'];
$number = implode(',',$id);
$query=$con->query("update rooms set availablity='occupied' where room_number in($number)");

$selected=$_SESSION['selected_booking'];
$query=$con->query("update booking set status='confirmed' where bookingID=$selected");


foreach($id as $n)
{

$query=$con->query("insert into bookings_to_room (bookingId,room_number,status) value($selected,$n,'confirmed')");
}
unset($_SESSION['selected_booking']);
}

// check out the room
if(isset($_POST['checkout-button']))
{
$c_out = implode(',',$_POST['boxcheckout']);
$query=$con->query("update rooms set availablity='yes' where room_number in ($c_out)");
$query=$con->query("update bookings_to_room set status='checked out' where room_number in ($c_out)");
}


?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>

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


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
     jQuery('#example-select-onChange').multiselect({
  enableFiltering: true,
  maxHeight:300,
  enableCaseInsensitiveFiltering: true,
  nonSelectedText: 'Select Booking ID',
  numberDisplayed: 2,
  selectAll: false,
 buttonWidth: '200px',
  onChange: function(option, checked) {
    // Get selected options.

    var selectedOptions = jQuery('#example-select-onChange option:selected');

    if (selectedOptions.length >= 1) {
      if (selectedOptions.length >1) {
        alert('Too many selected (' + selectedOptions.length + ')');
      } else {
        // Disable all other checkboxes.
        var nonSelectedOptions = jQuery('#example-select-onChange option').filter(function() {
          return !jQuery(this).is(':selected');
        });

        nonSelectedOptions.each(function() {
          var input = jQuery('input[value="' + jQuery(this).val() + '"]');
          input.prop('disabled', true);
          input.parent('li').addClass('disabled');
        });
      }
    } else {
      // Enable all checkboxes.
      jQuery('#example-select-onChange option').each(function() {
        var input = jQuery('input[value="' + jQuery(this).val() + '"]');
        input.prop('disabled', false);
        input.parent('li').addClass('disabled');
      });
    }
  }
});


});


</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Name', 'Count'],
      <?php
     $result=$con->query("select name,availablity,count(*)as cnt from rooms group by name,availablity");
      if($result->num_rows > 0){
          while($row = $result->fetch_assoc()){
            echo "['".$row['name'].' Availability: '.$row['availablity']  ."', ".$row['cnt']."],";
          }
      }
      ?>
    ]);

    var options = {
        title: 'Rooms Chart By Availabilty',
        width: 1000,
        height: 600,
    };
  var data_booking = google.visualization.arrayToDataTable([
      ['Name', 'Count'],
      <?php
     $result=$con->query("select status,count(*)as cnt from booking group by status");
      if($result->num_rows > 0){
          while($row = $result->fetch_assoc()){
            echo "['".$row['status'] ."', ".$row['cnt']."],";
          }
      }
      ?>
    ]);
 var data_room = google.visualization.arrayToDataTable([
      ['Name', 'Count'],
      <?php
     $result=$con->query("select name,count(*)as cnt from rooms where availablity='occupied' group by name");
      if($result->num_rows > 0){
          while($row = $result->fetch_assoc()){
            echo "['".$row['name'] ."', ".$row['cnt']."],";
          }
      }
      ?>
    ]);


var options_booking = {
        title: 'Booking Chart By Confirmation',
        width: 1000,
        height: 600,
    };
var options_room = {
        title: 'Best Room Chart By Popularity',
        width: 1000,
        height: 600,
    };


    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    var chart1 = new google.visualization.PieChart(document.getElementById('piechart_booking'));
    var chart2 = new google.visualization.PieChart(document.getElementById('piechart_room'));

    chart.draw(data, options);
chart1.draw(data_booking,options_booking);
chart2.draw(data_room,options_room);
}
</script>



</head>
<body>
<h1 style="text-align: center">Star Hotel</h1>
<h2 style="text-align: center">Hi, <?php echo $firstname ?></h2>

  <div class="imgcontainer d-flex justify-content-center">
    <img src="../images/login.png" alt="Avatar" class="avatar">
  </div>

                           
<!--staff log in -->

<ul class="nav nav-pills d-flex justify-content-center" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-booking-tab" data-toggle="pill" href="#pills-booking" role="tab" aria-controls="pills-booking" aria-selected="false">Booking</a>
  </li>
<li class="nav-item">
    <a class="nav-link " id="pills-checkin-tab" data-toggle="pill" href="#pills-checkin" role="tab" aria-controls="pills-checkin" aria-selected="false">Check In</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-checkout-tab" data-toggle="pill" href="#pills-checkout" role="tab" aria-controls="pills-checkout" aria-selected="false">Check Out</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-change-tab" data-toggle="pill" href="#pills-change" role="tab" aria-controls="pills-change" aria-selected="false" >Data Analysis</a>
  </li>
 <li class="nav-item">
    <a class="nav-link" id="pills-cancel-tab" data-toggle="pill" href="#pills-cancel" role="tab" aria-controls="pills-cancel" aria-selected="false" >Cancellation</a>
  </li>
</ul>
<!--home-->
<div class="tab-content" id="pills-tabContent" style="padding-left:20px;padding-right:20px;">
  <div class="tab-pane fade show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
   <p style="text-align:center;color:#ff8080;"></br></br>Staff Personal Information:
</p>
<!--nested start -->
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
          <li class="nav-item">
         <a class="nav-link" id="pills-staffacc-tab" data-toggle="pill" href="#pills-staffacc" role="tab" aria-controls="pills-staffacc" aria-selected="false">Account Info</a>
         </li>
         <li class="nav-item">
         <a class="nav-link" id="pills-staffchange-tab" data-toggle="pill" href="#pills-staffchange" role="tab" aria-controls="pills-staffchange" aria-selected="false">Update Info</a>
        </li>

        </ul>
       <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade" id="pills-staffacc" role="tabpanel" aria-labelledby="pills-staffacc-tab">

<!-- staff acc-->
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

<!--end of staff acc-->







               </div>
    <div class="tab-pane fade" id="pills-staffchange" role="tabpanel" aria-labelledby="pills-staffchange-tab">
<!--staff update -->
      <form action='staff_account.php' method="POST" form="updateInfo" name="updateInfo">


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





<!--end of staff update-->




    </div>
    </div>


<!---nested end-->

</div>
<!--booking-->
  <div class="tab-pane fade" id="pills-booking" role="tabpanel" aria-labelledby="pills-booking-tab">
<p style="text-align:center"></br></br>All Booking List:</p>
       <div class="table-responsive-md">

       <table class="table table-grey">
      <thead>
        <tr>
          <th scope="col">#Booking</th>
        <th scope="col">Email</th>
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

$query="select * from booking";
$result=$con->query($query);
if(!$result) die($conn->error);
$rows= $result->num_rows;

for ( $j=0;$j<$rows ;++$j)
{

   echo '<tr>';
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    echo '<td>'   . $row['bookingID']   . '</td>';
    echo '<td>'   . $row['email']   . '</td>';
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


<!--checkin-->
  <div class="tab-pane fade" id="pills-checkin" role="tabpanel" aria-labelledby="pills-checkin-tab">
  <p style="text-align:center"></br></br>Today's Check In List:</p>

      <div class="table-responsive-xl">

           <table class="table table-grey">
      <thead>
        <tr>
          <th scope="col">#Booking</th>
          <th scope="col">Email</th>
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

$query="select * from booking where status='N/A' or status='confirmed'";
$result=$con->query($query);
if(!$result) die($conn->error);
$rows= $result->num_rows;
$i=0;
for ( $j=0;$j<$rows ;++$j)
{
$result->data_seek($j);
$row = $result->fetch_array(MYSQLI_ASSOC);
$thedate=$row['checkin'];
$current = date("Y-m-d");
$date=str_replace('/','-',$thedate);
$newDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$1-$2",$date);
$daysdiffernce = date_diff(date_create($current),date_create($newDate));
$d_is=$daysdiffernce->format("%a");
$status=$row['status'];

if($d_is==0){

$id_t=$row['bookingID'];
    echo '<tr>';
    echo '<td>'   . $row['bookingID']   . '</td>';
     echo '<td>'   . $row['email']   . '</td>';
    echo '<td>'    . $row['checkin']    . '</td>';
    echo '<td>'    . $row['checkout']    . '</td>';
    echo '<td>'    . $row['guests']    . '</td>';
    echo '<td>'    . $row['kid']    . '</td>';
    echo '<td>'    . $row['room']    . '</td>';
    echo '<td>'    . $row['type']    . '</td>';
    echo '<td>'    . $row['status']    . '</td>';
    echo '</tr>';
}
}

?>

      </tbody>
    </table>
<!--dd room into booking id --->

      <div class="container" id="select-id">
      <form action="add_room.php" method="POST">
     <p style="background-color:#ff8080;width:200px;">One Booking ID at a time:<p>
    <select name="soft_skill_list[]" class="soft_skill_list" id="example-select-onChange" multiple="multiple" required>
    <?php

$query="select * from booking where status='N/A' or status='confirmed'";
$result=$con->query($query);
if(!$result) die($conn->error);
$rows= $result->num_rows;
$i=0;
for ( $j=0;$j<$rows ;++$j)
{
$result->data_seek($j);
$row = $result->fetch_array(MYSQLI_ASSOC);
$thedate=$row['checkin'];
$current = date("Y-m-d");
$date=str_replace('/','-',$thedate);
$newDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$1-$2",$date);
$daysdiffernce = date_diff(date_create($current),date_create($newDate));
$d_is=$daysdiffernce->format("%a");
$status=$row['status'];
if($d_is==0 && $status=='N/A'){
$id_t=$row['bookingID'];
    echo "<option value='".$id_t."'>Booking# :  ". $row['bookingID']   . '</option>';
}}?>
</select>
<button type="submit" name="select-booking-id" class="btn btn-info">Click Here To Add Rooms</button>
</div>
</div>
</div>
</form>
<!--end of add room into booking id-->



<!--checkout-form --->

  <div class="tab-pane fade" id="pills-checkout" role="tabpanel" aria-labelledby="pills-checkout-tab">
  <p style="text-align:center"></br></br>Today's Checkout List:</p>
<form action="staff_account.php" method="POST">
    <div class="table-responsive-xl">

            <table class="table table-grey">
      <thead>
        <tr>
<th scope="col">BookingID#</th>
          <th scope="col">Room#</th>
<th scope="col">Checkout</th>
          <th scope="col">Type</th>
          <th scope="col">Status</th>
         <th scope="col">Check Out</th>
        </tr>
      </thead>
      <tbody>
<?php

$query="select * from rooms r join bookings_to_room b join booking k where k.bookingId = b.bookingID and r.room_number=b.room_number and r.availablity='occupied'";
$result=$con->query($query);
if(!$result) die($conn->error);
$rows= $result->num_rows;
for ( $j=0;$j<$rows ;++$j)
{
$result->data_seek($j);
$row = $result->fetch_array(MYSQLI_ASSOC);
$room=$row['room_number'];

    echo '<tr>';
 echo '<td>'    . $row['bookingID']    . '</td>';
    echo '<td>'    . $row['room_number']    . '</td>';
  echo '<td>'    . $row['checkout']    . '</td>';
    echo '<td>'    . $row['name']    . '</td>';
    echo '<td>'    . $row['availablity']    . '</td>';
echo '<td><input type='."'checkbox'".'name='."'boxcheckout[]'"."   value='".$room."'></td>";
    echo '</tr>';

}

?>

      </tbody>
    </table>
<div class="d-flex justify-content-center" style="margin-top:120px;">
<button type="submit" name="checkout-button" class="btn btn-primary">Comfirm</button>
</div>

</div>



</div>
</form>
<!--change-->
  <div class="tab-pane fade" id="pills-change" role="tabpanel" aria-labelledby="pills-change-tab">
    <p style="text-align:center"></br></br>The results of the database:</p>
 <div id="piechart"></div>
<div id="piechart_booking"></div>
<div id="piechart_room"></div>

 </div>
<!--cancel-->
<div class="tab-pane fade" id="pills-cancel" role="tabpanel" aria-labelledby="pills-cancel-tab">

<p style="text-align:center"></br>Cancellation is allowed only 24 HOURS before the Check In date.</br> Possible Bookings To Be Canceled:</p>

<div class="table-responsive-xl">

            <table class="table table-grey">
      <thead>
        <tr>
          <th scope="col">#Booking</th>
 <th scope="col">Email</th>
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

$query="select * from booking where status='N/A'";
$result=$con->query($query);
if(!$result) die($conn->error);
$rows= $result->num_rows;
$i=0;
for ( $j=0;$j<$rows ;++$j)
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
echo '<td>'   . $row['email']   . '</td>';
    echo '<td>'    . $row['checkin']    . '</td>';
    echo '<td>'    . $row['checkout']    . '</td>';
    echo '<td>'    . $row['guests']    . '</td>';
    echo '<td>'    . $row['kid']    . '</td>';
    echo '<td>'    . $row['room']    . '</td>';
    echo '<td>'    . $row['type']    . '</td>';
    echo '<td>'    . $row['status']    . '</td>';
echo '<td><input type='."'checkbox'".'name='."'boxcancel[]'"."   value='".$id_t ."'></td>";
    echo '</tr>';
}
}

?>

      </tbody>
    </table>


</div>


</div>



</div>


<form action="staff_account.php" method="POST" >
<div class="d-flex justify-content-center" style="margin-top:120px;">
<button type="submit" name="signout" class="btn btn-primary">SIGN OUT</button>
</div>
</form>




</body>
</html>
