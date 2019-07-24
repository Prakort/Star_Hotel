<?php
require_once'database.php';
$con = new mysqli($hn,$un,$pw,$db);

if($con->connect_error)
    die($con->connect_error);

if (    isset($_POST['fname']) &&
        isset($_POST['lname'])&&
        isset($_POST['email']) &&
        isset($_POST['pass'])&&
        isset($_POST['select_user']))
    {
        $firstname=get_post($con,'fname');
        $lastname =get_post($con,'lname');
        $email =strtolower(get_post($con,'email'));
        $pass = get_post($con,'pass');
        $salt="w@w#w$".$pass."$_$";
        $safe=hash('ripemd128',$salt);


        $user=get_post($con,'select_user');


    if($user=="1"){


    $query="select * from guest";
    $result = $con->query($query);

    if (!$result) die ("Database access failed: " . $connect->error);
    $rows = $result->num_rows;
    $tryagain="false";
    $email_exist ="false";
    for ($j = 0 ; $j < $rows ; ++$j)
    {
       $result->data_seek($j);
       $row = $result->fetch_array(MYSQLI_NUM);
       if($row[0]==$email)
       {
           $email_exist="true";
       }
    }

     if($email_exist=="false")
       {

        $query = "insert into guest(email,firstname,lastname,password)values('$email','$firstname','$lastname','$safe')";
        $result = $con->query($query);


                if (!$result) {echo "INSERT failed: $query<br>" .
                  $con->error . "<br><br>";}


        }
        else
        {
            $tryagain="true";

        }

        $email_exits="false";



}else if ($user=="2"){

    $query="select * from employee";
    $result = $con->query($query);

    if (!$result) die ("Database access failed: " . $connect->error);
    $rows = $result->num_rows;
    $tryagain="false";
    $email_exist ="false";
    for ($j = 0 ; $j < $rows ; ++$j)
    {
       $result->data_seek($j);
       $row = $result->fetch_array(MYSQLI_NUM);
       if($row[0]==$email)
       {
           $email_exist="true";
       }
    }

     if($email_exist=="false")
       {

        $query = "insert into employee(email,firstname,lastname,password)values('$email','$firstname','$lastname','$safe')";
        $result = $con->query($query);


                if (!$result)
               {echo "INSERT failed: $query<br>" .
                  $con->error . "<br><br>";}


        }
        else
        {
            $tryagain="true";

        }

        $email_exits="false";

}}

echo <<<_END

<body style="text-align:center">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

_END;

if($tryagain=="true")
{
   echo "<h1></br></br></br>This email has been taken. Please Kindly Sign up again!!! </br></br><h1>";
   echo "<a class="."'download-button btn btn-lg btn-dark'"." href="."'signup.html'"." type="."'link'".">Sign Up Again</a>";






}
else
{
echo "<h1></br></br></br>Successfully Registered!!</br></br><h1>";
 echo "<a class="."'download-button btn btn-lg btn-dark'"." href="."'login.html'"." type="."'link'".">Return to Login</a>";



}






function get_post($connect, $var)  {
       return $connect->real_escape_string($_POST[$var]); }

 ?>
