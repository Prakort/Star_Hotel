<?php
session_start();
$_SESSION['email']=$_POST['email'];
require_once'database.php';
$con = new mysqli($hn,$un,$pw,$db);

// Update info
if( isset($_POST['t0'])&&
isset($_POST['t1'])&&
isset($_POST['t2'])&&
isset($_POST['t3'])&&
isset($_POST['t4'])
){
$t5=$_POST['t5'];
$t0=$_POST['t0'];
$t1=$_POST['t1'];
$t2=(int)$_POST['t2'];
$t3=(int)$_POST['t3'];
$t4=(int)$_POST['t4'];
}


if($con->connect_error)
    die($con->connect_error);

// Check user verification

if (    isset($_POST['email']) &&
        isset($_POST['psw'])&&
        isset($_POST['select_user']))
    {
        $pass=get_post($con,'psw');
        $user =get_post($con,'select_user');
        $email =get_post($con,'email');

        $salt="w@w#w$".$pass."$_$";
        $safe=hash('ripemd128',$salt);

echo <<<_END

<body style="text-align:center">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

_END;



    if($user=="1"){

        $query="select * from guest";
        $result=$con->query($query);

        if (!$result) die ("Database access failed: " . $connect->error);

        $rows = $result->num_rows;
        $tryagain="false";
        $email_exist ="false";

        for ($j = 0 ; $j < $rows ; ++$j)
        {
           $result->data_seek($j);
           $row = $result->fetch_array(MYSQLI_NUM);
           if((strtolower($row[0])==strtolower($email))&& $row[3]==$safe)
           {
               $email_exist="true";
               break;
           }

        }
        if ($email_exist=="true")
        {
            $query="insert into booking (email,checkin,checkout,guests,kid,room,type,status)values('$email','$t0','$t1',$t2,$t3,$t4,'$t5','N/A')";
            $result=$con->query($query);
if (!$result) die ("Database access failed: " . $connect->error);

        $query="select * from booking";
$result=$con->query($query);
$rows = $result->num_rows;
$result->data_seek($rows-1);
$row = $result->fetch_array(MYSQLI_NUM);
echo "<h1></br></br></br>Your Booking Number is #".$row[0]."</br></br><h1>";
 echo "<a class="."'download-button btn btn-lg btn-dark'"." href="."'user_account.php'"." type="."'link'".">Go to your Account</a>";


        }else {

            echo "<h1></br></br></br>Incorrect Username/Password!!! </br></br><h1>";
   echo "<a class="."'download-button btn btn-lg btn-dark'"." href="."'search_to_login.php'"." type="."'link'".">Try Again</a>";
        }

    }

}




function get_post($connect, $var)  {
       return $connect->real_escape_string($_POST[$var]); }

?>
