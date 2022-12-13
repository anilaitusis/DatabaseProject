<!DOCTYPE html>
<html>

<?php
session_start();

include "dbconfig.php";
$con = mysqli_connect($host, $username, $password, $dbname) 
or die("<br>Cannot connect to Database");
$cid=$_SESSION['customer_id'];

    echo '<a href = "logout.php">User Logout</a>';
        
        $addDeleted = $_POST['deleted'];
        $countDeleted = count($addDeleted);
        $changeAll = $_POST['changed'];
        $countChanged= count($changeAll);
        $countdel = 0;

if($countDeleted > 0){
    for($o = 0; $o < $countDeleted; $o++){
	
            $midDelete = $_POST['deleted'][$o];
            $sqld="DELETE FROM CPS3740_2022F.Money_laitusia WHERE mid='$midDelete'";
            $result = mysqli_query($con,$sqld);
            echo "you deleted entry.<br>";
            $countdel++;
            
            }
            mysqli_free_result($result);
}

mysqli_close($con);
        

$con2 = mysqli_connect($host, $username, $password, $dbname) or die("<br>Cannot connect to Database");

$grab=mysqli_query($con2, "SELECT note, code, FROM CPS3740_2022F.Money_laitusia");

$row2=mysqli_num_rows($grab);
 
echo $row2;

$countnote = 0;

    if($countChanged > 0){
       
        for($v = 0; $v < $row2; $v++){
            
            $row = mysqli_fetch_array($grab);
            $changed = $_POST['changed'][$v];
            $mid = $_POST['mid'][$v];
            $code = $row['code'];

        if($changed != $row['note']) {
                $updateNote = "UPDATE CPS3740_2022F.Money_laitusia SET note = '$changed' WHERE mid='$mid'";
                $newnnote = mysqli_query($con2,$updateNote); 
                echo "Update Successful:";
                $countnote++;
                }
                    }
			
		}
	

echo "$countdel Transactions deleted, $countnote Transactions updated.";

mysqli_close($con2);

?>

</html>