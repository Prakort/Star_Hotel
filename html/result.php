<?php
$hide_all="true";
if( isset($_POST['from'])&&
isset($_POST['to'])&&
isset($_POST['room'])&&
isset($_POST['adult'])&&
isset($_POST['kid'])
){

    $hide_all="false";
}
$checkin = $_POST['from'];
$checkout= $_POST['to'];
$room=$_POST['room'];
$adult=$_POST['adult'];
$kid =$_POST['kid'];

require_once'database.php';

$conn = new mysqli($hn,$un,$pw,$db);

if ($conn->connect_error) die($conn->connect_error);

$query="select name, count(*) as cnt from rooms where availablity='yes' group by name";
$result=$conn->query($query);

if (!$result) echo "INSERT failed: $query<br>" .
  $conn->error . "<br><br>";

$rows = $result->num_rows;
$free_room=array();
for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    $free_room[$j]=$row[1];
}
$twin="false";
$queen="false";
$king="false";
$hide_king="false";

if($free_room[0]<$room){
$twin="true";
}
if($free_room[1]<$room){
$queen="true";
}
if($free_room[2]<$room){
$king="true";
}

$checkSmall = $adult-(4*$room);
$checkKing= $adult-(5*$room);


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Star Hotel</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">

  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/styles.css">

  <!-- Font Awesome -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>

  <!-- Bootstrap Scripts -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
   <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
        $(function() {
            $( "#from" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 2,
                onClose: function( selectedDate ) {
                    $( "#to" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#to" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 2,
                onClose: function( selectedDate ) {
                    $( "#from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
});


var twin ="<?php echo $twin ?>";
var queen ="<?php echo $queen ?>";
var king ="<?php echo $king ?>";

var checkSmall="<?php echo $checkSmall ?>";
var checkKing="<?php echo $checkKing ?>";
var all="<?php echo $hide_all ?>";
if(all=="true")
{
$(function(){
$('#divpic1').addClass('d-none');
$('#divpic2').addClass('d-none');
$('#divpic3').addClass('d-none');
$('#result_next').addClass('d-none');
$('#none').html('Please, enter all fields in the search box !!!');
});
}
if(twin=="true" && queen=="true" && king=="true"){
$(function(){
$('#divpic1').addClass('d-none');
$('#divpic2').addClass('d-none');
$('#divpic3').addClass('d-none');
$('#result_next').addClass('d-none');
$('#none').html('Sorry, we are fully BOOKED !!');
});}else if(twin=="true" && queen=="true"){
$(function(){
$('#divpic1').addClass('d-none');
$('#divpic2').addClass('d-none');
$('#divpic3').addClass('col-lg-12');
});
}else if(twin=="true" && king=="true"){
$(function(){
$('#divpic1').addClass('d-none');
$('#divpic3').addClass('d-none');
$('#divpic2').addClass('col-lg-12');
});}else if(queen=="true" && king=="true"){
$(function(){
$('#divpic2').addClass('d-none');
$('#divpic3').addClass('d-none');
$('#divpic1').addClass('col-lg-12');
});
}else if(twin=="true"){
$(function(){
$('#divpic1').addClass('d-none');
$('#divpic3').addClass('col-lg-6');
$('#divpic2').addClass('col-lg-6');
});
}else if(queen=="true"){
$(function(){
$('#divpic2').addClass('d-none');
$('#divpic1').addClass('col-lg-6');
$('#divpic3').addClass('col-lg-6');
});}
else if(king=="true"){
$(function(){
$('#divpic3').addClass('d-none');
$('#divpic1').addClass('col-lg-6');
$('#divpic2').addClass('col-lg-6');
});}

if(checkKing <1 && 0<checkSmall ){
$(function(){
$('#divpic2').addClass('d-none');
$('#divpic1').addClass('d-none');
$('#divpic3').addClass('col-lg-12');
});}
else if(checkKing >= 1){
$(function(){
$('#divpic2').addClass('d-none');
$('#divpic1').addClass('d-none');
$('#divpic3').addClass('d-none');
$('#result_next').addClass('d-none');
$('#none').html('Number of room is not enough for the number of guests !!! Please increase number of the room');
});}
    </script>
  </head>

<body>

  <section class="colored-section" id="title">
    <div class="container-fluid">

      <nav class="navbar navbar-expand-lg navbar-dark">

          <a class="navbar-brand" href="#title">
            <img src="../images/logo.png" width="180" height="150" class="d-inline-block align-top" alt="logo">
          </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">

          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Aboutus.html">About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#pricing">Rooms</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.html">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Aboutus.html#contact-info">Contact</a>
            </li><li class="nav-item">
              <a class="nav-link" href="#features">Book Now</a>
            </li>
          </ul>

        </div>
      </nav>


  </section>

  <section class="white-section" id="features">
              <div class="container">
                  <div class="row">
                      <div class="col">
                          <div class="home_content text-center">
                              <div class="home_title"><h1>Choose your stay</h1></div>
                              <div class="home_text">We have amazing selection of rooms</br>Every room is for you and you will enjoy the experience of satisfaction</div>
                          </div>
                      </div>
                  </div>
              </div>

    <div class="container-fluid">
        <div class="row" style="width:100%;margin-left:0;margin-right:0;">

                <image class="middle-image1" src="../images/search.jpg" alt="middle-image1"></image>

        </div>


    </div>

  </section>
 <form method="POST" action="result.php" autocomplete="off">
  <section class="colored-section" id="testimonials">
      <div class="container_fluid" id="search_box_container">
          <div class="row">

               <div class="eachbox col-lg-2 col-md-6 col-sm-12">
                             <input id="from" name='from' type="text" class="box" placeholder="Check In" value="<?php echo $checkin; ?>" required/>
               </div>
              <div class="eachbox col-lg-2 col-md-6 col-sm-12">

                          <input id="to" name='to' type="text" class="box" placeholder="Check Out" value="<?php echo $checkout; ?>" required/>

              </div>
              <div class="eachbox col-lg-2 col-md-6 col-sm-12">


                         <input id="adult" class="box" name="adult" type="number" placeholder="Adults" value="<?php echo $adult;?>" min="1" max="20" step="1" required/>
            </div>
            <div class="eachbox col-lg-2 col-md-6 col-sm-12">


                           <input name="kid" class="box" type="number" placeholder="Children" min="0" max="20" step="1" value="<?php echo $kid; ?>" required/>

              </div>
             <div class="eachbox col-lg-2 col-md-6 col-sm-12">


                           <input id='room' name='room' class="box" placeholder="Rooms" type="number" min="1" max="20" step="1" value="<?php echo $room; ?>"required/>

              </div>
             <div class="eachbox col-lg-2 col-md-6 col-sm-12">

                          <button id="search-btn" class="sbox btn" type="submit">Search</button>

              </div>


              </div>
          </div>
</form>
<form method="POST" action="search_to_login.php">
  </section>

  <section class="white-section" id="pricing">
    <h2 class="section-heading">Search Results:</h2>
    <p id="none">Best affordable price rooms for you and your family.</p>

    <div class="row">

      <div class="pricing-column col-lg-4 col-md-6" id="divpic1">
         <div class="room-div">
        <img class="room-pic" src="../images/d.jpg" alt="double">
         </div>
        <div class="card">
          <div class="card-header">
            <h3>Twin Beds Room</h3>
          </div>
          <div class="card-body">
            <h2 class="price-text">99$/Night</h2>
            <p>Bed: Two twin beds</p>
            <p>Capacity: 4 people</p>
            <p>Room Size: 55 meter square</p>
            <p>Extra: TV, fridge, hair dryer</p>
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
     <label class="btn btn-secondary active">
      <input type="radio" name="options" value="A" autocomplete="off" checked>Select
      </label>
     </div>
          </div>
        </div>
      </div>

      <div class="pricing-column col-lg-4 col-md-6" id="divpic2">
          <div class="room-div">
         <img class="room-pic" src="../images/twin.jpg" alt="twin">
          </div>
        <div class="card">
          <div class="card-header">
              <h3>Queen Beds Room</h3>
            </div>
            <div class="card-body">
              <h2 class="price-text">125$/Night</h2>
              <p>Bed: Two Queen beds</p>
              <p>Capacity: 4 people</p>
              <p>Room Size: 65 meter square</p>
              <p>Extra: TV, fridge, hair dryer and massage chair</p>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
     <label class="btn btn-secondary active">
      <input type="radio" name="options" value="B" autocomplete="off">Select
      </label>
     </div>
          </div>
        </div>
      </div>

      <div class="pricing-column col-lg-4" id="divpic3">
          <div class="room-div">
         <img class="room-pic" src="../images/King.jpg" alt="King">
          </div>
        <div class="card">
          <div class="card-header">
              <h3>Family Beds Room</h3>
            </div>
            <div class="card-body">
              <h2 class="price-text">199$/Night</h2>
              <p>Bed: One King & Two Queen beds</p>
              <p>Capacity: 5 people</p>
              <p>Room Size: 90 meter square</p>
              <p>Extra: TV, fridge, hair dryer, massage chair and jacuzzi</p>
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
     <label class="btn btn-secondary active">
      <input type="radio" name="options" value="C" autocomplete="off">Select
      </label>
     </div>
          </div>
        </div>
      </div>
    </div>


<div class="btn-toolbar mb-3 d-flex justify-content-center" id="temp"role="toolbar" aria-label="Toolbar with button groups">
  <div class="input-group">
    <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Check In</div>
    </div>
    <input type="text" name="t0" class="form-control" value="<?php echo $checkin; ?>" style="width:110px;background-color: transparent;" aria-describedby="btnGroupAddon" readonly>
    <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Check Out</div>
    </div>
    <input type="text" name="t1" class="form-control" value="<?php echo $checkout ?>" style="width:115px;background-color: transparent;" aria-describedby="btnGroupAddon" readonly>

<div class="input-group-prepend ">
      <div class="input-group-text" id="btnGroupAddon">Guests</div>
    </div>
    <input type="text" name="t2"class="form-control" value="<?php echo $adult ?>" style="width:40px;background-color: transparent;" aria-describedby="btnGroupAddon" readonly>

  <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Kid</div>
    </div>
    <input type="text" name="t3" class="form-control" value="<?php echo $kid ?>" style="width:40px;background-color: transparent;"  aria-describedby="btnGroupAddon" readonly>

   <div class="input-group-prepend">
      <div class="input-group-text" id="btnGroupAddon">Room</div>
    </div>
    <input type="text" name="t4" class="form-control" value="<?php echo $room ?>" style="width:40px;background-color: transparent;" aria-describedby="btnGroupAddon" readonly>

</div>
</div>
<button id="result_next" type="submit" class="btn btn-info ml-auto" style="width:200px;margin-top:45px;">Next</button>

  </section>
</form>



  <section class="colored-section" id="cta">

    <div class="container-fluid">

      <h3 class="big-heading">Find the True Comfort of Your Stay Today.</h3>
      <a class="download-button btn btn-lg btn-dark" href="login.html" type="link"><i class="fas fa-sign-in-alt"></i> Login </a>
      <a class="download-button btn btn-lg btn-dark" href="#features" type="link"><i class="fas fa-bed"></i> Book</a>
    </div>
  </section>


  <footer class="white-section" id="footer">
    <div class="container-fluid">
      <i class="social-icon fab fa-facebook-f"></i>
      <i class="social-icon fab fa-twitter"></i>
      <i class="social-icon fab fa-instagram"></i>
      <i class="social-icon fas fa-envelope"></i>
    </div>
  </footer>


</body>

</html>
