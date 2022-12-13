<?php
session_start();
  include "dbconfig.php";
  $con = mysqli_connect($host, $username, $password, $dbname)
  or die("<br>Cannot connect to DB:$dbname on $host, error:" .mysqli_connect_error());
  
 

if(isset($POST['dbusername']))
$user=$_POST['username'];


$user=$_POST['username'];
$pass=$_POST['password'];

echo '<a href = "logout.php">User Logout</a>';

//declare variables
$sql="select * from CPS3740.Customers where login = '$user' ;";
$resultend= mysqli_query($con, $sql);
$var = mysqli_fetch_array($resultend);


$id = $var['id'];

$sqlt= "SELECT a.mid, a.code, c.name, a.type, a.amount, a.mydatetime, a.note
from CPS3740_2022F.Money_laitusia a, CPS3740.Customers b, CPS3740.Sources c
where a.cid = b.id AND a.sid = c.id AND b.id = $id";
$result = mysqli_query($con, $sqlt);

$_SESSION['customer_name']= $var['name'];


$_SESSION['customer_id']= $id;
echo $_SESSION['customer_id'];




//check for IP
if ($resultend){
  if (mysqli_num_rows($resultend)>0){ 
    if ($var['password'] ==$pass){ 
      if (!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ipv=$_SERVER['HTTP_CLIENT_IP']; 
      }
      elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ipv = $_SERVER['HTTP_X_FORWARDED_FOR']; 
      }
      else { $ipv = $_SERVER['REMOTE_ADDR']; 
      }
   //display IP     
  echo "<br>Your IP: $ipv\n <br>";
  $host = gethostbyaddr($ipv);
  $IPv4= explode(".",$ipv);
   
  echo $_SERVER['HTTP_USER_AGENT'];
       $host = gethostbyaddr($ipv);
       $IPv4= explode(".",$ipv);

  echo $_SERVER['HTTP_USER_AGENT'];
      
    if($IPv4[0]=='10')
      echo "<br> You are from Kean University wifi domain.\n";
      else {
            echo"<br> You are NOT from Kean University.\n";
           }
    echo '<br><img src="data:image/jpeg;base64,'.base64_encode($var['img']).'"/>';

    echo "<br> Welcome Customer: <b>" .$var['name']. "</b>";

   // setcookie('customer', 'customer_id',time()+600);
    
    //get customer info
    $DoB = $var["DOB"];
     $sqlyrdob=" select year(DOB)as DOB from CPS3740.Customers where id=".$var['id'];
      
           $rage = mysqli_query($con, $sqlyrdob);
           $roage = mysqli_fetch_array($rage); 
    
           $age=2022-$roage[0];
           echo "Age:".$age;
           $str=$var['street'];
           $cy=$var['city'];
           $zcode=$var['zipcode'];
           echo " Address: ".$str.$cy." , ".$zcode;
          }
        
     }

else
  echo "<br> Login ".$user. "exist, but password doesn't match\n";
}
else {
  echo "Something is wrong with SQL:" . mysqli_error($con); 
}

 mysqli_free_result($resultend);

//money table 
if($result){
    if(mysqli_num_rows($result)>0){

        echo "<TABLE border=1>\n";
        echo "<TR>
              <TH>ID</TH>
                <TH>Code</TH>
                <TH>Type</TH>
                <TH>Amount</TH>
                <TH>Source</TH>
                <TH>Date and Time</TH>
                <TH>Note</TH>\n";

        while($row = mysqli_fetch_array($result))
        {

            $mid = $row["mid"];
            $code=$row["code"];
            $cid=$var["id"]; 
            $type=$row["type"];
            $amount=$row["amount"];
            $now=$row["mydatetime"];
            $source= $row["name"];
            $note=$row["note"];
            
                if($type == 'W'){
                   
            
                  echo "<TR>
                    <TD>$mid
                    <input type='hidden' name='mid[]' value='$mid'>

                    <TD>$code
                    <TD>Deposit<TD><font color ='red'>$amount</font>

                    <TD>$source
                    <input type='hidden' name='source[]' value='$source' style=>

                   
                    <TD>$now
                
                    <TD>$note
                    <input type='hidden' name='changed[]' value='$note' style=>

                    </td>";
                }
                else{
                    echo "<TR>
                    <TD>$mid
                    <input type='hidden' name='mid[]' value='$mid'>

                    <TD>$code
                    <TD>Deposit<TD><font color ='blue'>$amount</font>

                    <TD>$source
                    <input type='hidden' name='source[]' value='$source' style=>

                   
                    <TD>$now
                   
                    
                    <TD>$note
                    <input type='hidden' name='changed[]' value='$note' style=>

                    </td>";
                }

                
        }
       

        echo "</TABLE>\n";
        $result = mysqli_query($con,"SELECT sum(amount) as value_sum FROM CPS3740_2022F.Money_laitusia WHERE cid=$cid");
        $row = mysqli_fetch_assoc($result); 
        $sum = $row['value_sum'];
        $_SESSION['balance']= $sum;

        
        echo "<br>Total balance: ". $_SESSION['balance'] . ".";


        mysqli_free_result($result);
        //buttons 
       echo"<form>
              <a href='add_transaction.php' target='_blank'><button type='button'>Add transaction </button>
              <p><a href='display_transaction.php'>Display and Update Transaction</a> <br>  <a href=''>Display stores</a></p>
              </form><br>";
            
               
          echo "<form name='form' action='search.php' method='get'>
          <input type='text' name='keyword' required='required'>
          <input type='submit' value='Search'></form>";

    }   
    else {

        echo "<br> Cannot find record. \n";

    }

}
else 
    {
    echo "<br> Something wrong in the query. $sql\n";
}


 mysqli_close($con);

?>


