<html>
<?php
session_start();

include "dbconfig.php";
$con = mysqli_connect($host, $username, $password, $dbname) 
or die("<br>Cannot connect to Database");

$cid=$_SESSION['customer_id'];
echo '<a href = "logout.php">User Logout</a><br>';

$code=$_POST['code'];
$source=$_POST['source'];
$type=$_POST['selection'];
$amt=$_POST['amount'];
$note=$_POST['note'];
$sqlc = mysqli_query($con,"SELECT sum(amount) as sum FROM CPS3740_2022F.Money_laitusia WHERE cid=$cid");
       //get current balance
    if(mysqli_num_rows($sqlc)>0){
        
        while($row = mysqli_fetch_array($sqlc))
        {

  		$newBal = $row['sum'];
  					}
  				}

        //amount less than 0 or more than bal
        if($type == 'W' && ($amt > $newBal)){
            
            echo "Cannot take out more than current Balance";
        }
        else if($amt <= 0){
            echo "Amount can not be less than or equal to 0.";
        }

        

        else {
            $k = mysqli_query($con,"SELECT code FROM CPS3740_2022F.Money_laitusia WHERE code like '%$code%'");
            $sqlk = mysqli_num_rows($k);

            if(($sqlk > 0)){ 
                echo "Code already used.";
            }
            else{

                if($type == 'D') {
                    $sqlins=mysqli_query($con,"INSERT INTO CPS3740_2022F.Money_laitusia (mid, code, cid, sid, type,amount , mydatetime , note)
                    VALUES ('' , '$code' , '$cid' , '$source' , '$type' , '$amt' , now() , '$note')");

                    $sqlc3 = mysqli_query($con,"SELECT sum(amount) as amountt FROM CPS3740_2022F.Money_laitusia WHERE cid=$cid");
                            if(mysqli_num_rows($sqlc3)>0){

                            while($row = mysqli_fetch_array($sqlc3)){
                                $newBal3 = $row['amountt'];

                            }
                            echo "Success";
                            echo "New Balance: $newBal3";
                            
                        }
                }
                else if ($type == 'W') {
                    
                    $sqlins=mysqli_query($con,"INSERT INTO CPS3740_2022F.Money_laitusia (mid, code, cid, sid, type,amount , mydatetime , note)
                    VALUES ('' , '$code' , '$cid' , '$source' , '$type' , -'$amt' , now() , '$note')");
                    $sqlc2 = mysqli_query($con,"SELECT sum(amount) as amounttt FROM CPS3740_2022F.Money_laitusia WHERE cid=$cid");
                        
                    if(mysqli_num_rows($sqlc2)>0){
                        
                            while($row = mysqli_fetch_array($sqlc2)){
                                $newBal2 = $row['amounttt'];
                            }
                            echo "Update was successful, your balance is now: $newBal2";
                        }
                }      
                    else {
                        echo "Update wasnt successful";
                    }
	}
}

mysqli_close($con);
	
?>
</HTML>