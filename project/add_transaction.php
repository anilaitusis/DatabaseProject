<?php
session_start();

include "dbconfig.php";
$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

echo '<a href = "logout.php">User Logout</a>';
$selsrc = "SELECT a.id, a.name FROM CPS3740.Sources a";
$sqlsrc = mysqli_query($con, $selsrc);
$namecus = $_SESSION['customer_name'];
$currbal = $_SESSION['balance'];

echo "<h2> Add Transaction</h2>
  $namecus balance is $currbal
 <form action = 'insert_transaction.php' method = 'post'>
 <br><label> Code: </label> 
 <input type = 'text' name='code' required='required'>
<br> <input type = 'radio'  name = 'selection'  value = 'D' >Deposit 
 <input type = 'radio' name = 'selection' value = 'W' > Withdraw
 <br><label> Amount: </label> 
 <input type = 'number' min = 1 name='amount' required='required'>
<br><label> Select a Source </label> 
<select name='source' id = 'source' required>
         <option value = ''></option>";
        if($sqlsrc > 0) {
        
                while($src = mysqli_fetch_array($sqlsrc)){
        
                echo "<option value = '" .$src["id"]. "'>".$src['name']."</option>";
                 }
         }
        echo '</select>';
        
        mysqli_free_result($sqlsrc);
        echo "<br> <label> Note: </label>
        <input type= 'text' name = 'note'>
        <br><input type='submit' value='Submit'> </form>"; 
         mysqli_close($con);
?>
         