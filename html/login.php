<?php
session_start();

// get email from login

$_SESSION['email']=$_POST['email'];
require_once'database.php';
$con = new mysqli($hn,$un,$pw,$db);

if($con->connect_error)
    die($con->connect_error);


echo <<<_END

<body style="text-align:center">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

_END;

if (    isset($_POST['email']) &&
        isset($_POST['psw'])&&
        isset($_POST['select_user']))
    {
        $pass=get_post($con,'psw');
        $user =get_post($con,'select_user');
        $email =get_post($con,'email');

        $salt="w@w#w$".$pass."$_$";
        $safe=hash('ripemd128',$salt);

    if($user=="1"){// user login

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
            header('Location: '.'user_account.php');

        }else {

             echo "<h1></br></br></br>Incorrect Username/Password!!! </br></br><h1>";
   echo "<a class="."'download-button btn btn-lg btn-dark'"." href="."'login.html'"." type="."'link'".">Try Again</a>";


        }

    }else if($user=="2"){//staff login

        $query="select * from employee";
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

        header('Location: '.'staff_account.php');


        }else {

           echo "<h1></br></br></br>Incorrect Username/Password!!! </br></br><h1>";
   echo "<a class="."'download-button btn btn-lg btn-dark'"." href="."'login.html'"." type="."'link'".">Try Again</a>";


        }

    }
}



function get_post($connect, $var)  {
       return $connect->real_escape_string($_POST[$var]); }

?>
