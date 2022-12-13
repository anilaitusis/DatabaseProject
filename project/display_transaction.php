<html>
<?php
session_start();

include "dbconfig.php";
$con = mysqli_connect($host, $username, $password, $dbname) 
or die("<br>Cannot connect to Database");
echo '<a href = "logout.php">User Logout</a>';
$cid=$_SESSION['customer_id'];
$result = mysqli_query($con,"SELECT sum(amount) as value_sum FROM CPS3740_2022F.Money_laitusia WHERE cid=$cid");
        $row = mysqli_fetch_assoc($result); 
        $sum = $row['value_sum'];
        $_SESSION['balance']= $sum;
$sum = mysqli_query($con,"SELECT sum(amount) as sum FROM CPS3740_2022F.Money_laitusia WHERE cid=$cid");
$sql = mysqli_query($con, "SELECT a.mid,a.code,a.cid,a.sid,a.type, a.amount, a.mydatetime,a.note, b.name FROM CPS3740_2022F.Money_laitusia a, CPS3740.Sources b where a.cid = b.id AND b.id = $cid");
echo "You can only update Note column";
if($sql) {
    if(mysqli_num_rows($sql)>0) {
        echo "<TABLE border=1>
        <TR>
        <TH>id
        <TH>code
        <TH>type
        <TH>amount
        <TH>source
        <TH>date&time
        <TH>note
        <TH>delete";

        while($row = mysqli_fetch_array($sql)){
            $mid=$row['mid'];
            $note=$row['note'];
            $code=$row['code'];
            $type = $row['type'];
            $amount=$row['amount'];
            $now=$row['mydatetime'];
            $source= $row['name'];
         

                if($type == 'W'){
    
                    
                    echo "<form action='update_transaction.php' method='post'>
                    <TR>
                    <TD>$mid <input type='hidden' name='mid' value='$mid'>
                    <TD>$code
                    <TD>Withdraw<TD>
                    <font color='red'>$amount</font>
                    <TD>$source<input type='hidden' name='source[]' value='$source'>
                    <TD>$now
                    <TD bgcolor='yellow'>
                    <input type='text' name='changed[]' value='$note'>
                    <TD>
                    <input type='checkbox' name='delete[]' value='$mid'>
                    </td>";
                }
                else {
                    
                    echo "<form action='update_transaction.php' method='post'>
                    <TR>
                    <TD>$mid<input type='hidden' name='mid[]' value='$mid'>
                    <TD>$code
                    <TD>Deposit<TD>
                    <font color ='blue'>$amount</font>
                    <TD>$source<input type='hidden' name='source[]' value='$source'>
                    <TD>$now
                    <TD bgcolor='yellow'>
                    <input type='text' name='changed[]' value='$note'>
                    <TD>
                    <input type='checkbox' name='deleted[]' value='$mid'></TD>";
                }
               
        }
         echo"</TABLE>";
        echo "<br>Total balance: ". $_SESSION['balance'] . ".";
        echo "<br><input type='submit' name='deleted'  value='Update'>
        </form>";
}
    else{
    echo "no entries";
    mysqli_free_result($sql);
                    }
}
?>
</html>