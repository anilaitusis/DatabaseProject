<?php
session_start();

include "dbconfig.php";
$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

echo '<a href = "logout.php">User Logout</a>';
echo $_SESSION['customer_id'];
$namecus = $_SESSION['customer_name'];
$currbal = $_SESSION['balance'];



$sql="select * from CPS3740.Customers where login = '$namecus' ;";
$resultend= mysqli_query($con, $sql);
$var = mysqli_fetch_array($resultend);
$id = $_SESSION['customer_id'];

$keyword = $_GET['keyword'];
$ksearch = mysqli_query($con, "SELECT a.mid, a.code, b.name, a.type, a.amount, a.mydatetime, a.note
from CPS3740_2022F.Money_laitusia as a, CPS3740.Customers as c, CPS3740.Sources as b
where a.cid = c.id AND a.sid = b.id AND c.id = $id AND a.note LIKE concat('%','$keyword','%')");

$sqlt= "SELECT a.mid, a.code, c.name, a.type, a.amount, a.mydatetime, a.note
from CPS3740_2022F.Money_laitusia a, CPS3740.Customers b, CPS3740.Sources c
where a.cid = b.id AND a.sid = c.id AND b.id = $id";
$result = mysqli_query($con, $sqlt);

	if($ksearch) {
		if(mysqli_num_rows($ksearch) > 0){
 echo "<TABLE border=1>\n";
    echo "<TR>
          <TH>ID</TH>
            <TH>Code</TH>
            <TH>Type</TH>
            <TH>Amount</TH>
            <TH>Source</TH>
            <TH>Date and Time</TH>
            <TH>Note</TH>\n";
        while($row = mysqli_fetch_array($ksearch)){
            $mid=$row['mid'];
            $code=$row['code'];
            $type = $row['type'];
            $amount=$row['amount'];
            $now=$row['mydatetime'];
            $note=$row['note'];
            $source=$row['name'];
                  
                if($type == 'W'){
                    echo "<TR>
                    <TD>$mid
                    <TD>$code
                    <TD>Withdraw<TD>
                    <font color='red'>$amount</font>
                    <TD>$now
                    <TD>$note
                    <TD>$source";
                }
                else{
                    echo "<TR>
                    <TD>$mid
                    <TD>$code
                    <TD>Deposit<TD>
                    <font color ='blue'>$amount</font>
                    <TD>$now
                    <TD>$note
                    <TD>$source";
                }

        }
        echo "</TABLE>";
    }
   else if($keyword== '*'){
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
            }
        }

    }
    else {
        echo"no results found";
    }
}
    mysqli_close($con)
?>