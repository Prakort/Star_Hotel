<?php

session_start();
require_once'database.php';

// Unauthorized -> redirect to home page
if(!isset($_SESSION['email'])){
session_unset();
header('Location: index.html');

}
// get login email
$email=$_SESSION['email'];

$con = new mysqli($hn,$un,$pw,$db);
if($con->connect_error)
    die($con->connect_error);

// Add rooms to booking
if(isset($_POST['select-booking-id']))
{

$value = $_POST['soft_skill_list'];
$select_bookingID=$value[0];
$_SESSION['selected_booking']=$select_bookingID;

$query="select * from booking where bookingID=$select_bookingID and status='N/A'";
$result=$con->query($query);

$result->data_seek(0);
$row = $result->fetch_array(MYSQLI_ASSOC);
$bID = $row['room'];
}
else { header('Location: staff_account.php');}

$a = 'Twin Beds Room';
$b = 'Queen Beds Room';
$c = 'Family Beds Room';


?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />


<script>

$(document).ready(function() {
     jQuery('#room_list').multiselect({
  enableFiltering: true,
  maxHeight:500,
  enableCaseInsensitiveFiltering: true,
  nonSelectedText: 'Select Room#',
  numberDisplayed: 2,
  selectAll: false,
 buttonWidth: '500px',
  onChange: function(option, checked) {
    // Get selected options.
    var x=<?php echo $bID ?>;
    var selectedOptions = jQuery('#room_list option:selected');

    if (selectedOptions.length >= x) {
      if (selectedOptions.length >x) {
        alert('Too many selected (' + selectedOptions.length + ')');
      } else {
        // Disable all other checkboxes.
        var nonSelectedOptions = jQuery('#room_list option').filter(function() {
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
      jQuery('#room_list option').each(function() {
        var input = jQuery('input[value="' + jQuery(this).val() + '"]');
        input.prop('disabled', false);
        input.parent('li').addClass('disabled');
      });
    }
  }
});


});

</script>
<div class="containter" style="padding-right:20px;padding-left:20px;">
<div class="row">
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

if(isset($_SESSION['email'])){
$query="select * from booking where bookingID='$select_bookingID'";
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
}
?>
</tbody>
</table>
</div>
</div>
</div>


<div class="container-fluid">
<h3 id="secRoom"style="background-color:#ff8080;width:400px;">Select Rooms for Booking ID : <?php echo $select_bookingID ?><h3>
<form action="staff_account.php" method="POST">
<select name="room_list[]" class="room_list" id="room_list" multiple="multiple" required>

<?php

$query="SELECT * FROM rooms WHERE availablity='yes'";
$result=$con->query($query);
if(!$result) die($conn->error);
$rows= $result->num_rows;
for ( $j=0;$j<$rows ;++$j)
{
$result->data_seek($j);
$row = $result->fetch_array(MYSQLI_ASSOC);
    echo "<option value='".$row['room_number']."'>Room# :  ". $row['room_number']   ."   Type :  ". $row['name']."   Available : ". $row['availablity'] . '</option>';
}
?>
</select>
<button type="submit" name="select-room-confirm"class="btn btn-info">Confirm</button>
</div>
</form>

<div class="d-flex justify-content-center" style="margin-top:150px;text-align:left;padding-left:15px;padding-right:15px;">
<a class="btn btn-primary" href="staff_account.php" role="button">Go Back</a>
</div>
