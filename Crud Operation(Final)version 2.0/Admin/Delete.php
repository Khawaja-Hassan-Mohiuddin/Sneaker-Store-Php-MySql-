<?php
include 'Connect.php';
if(isset($_GET['deleteid'])){
    $id=$_GET['deleteid'];

    $sql="DELETE FROM `product` WHERE Product_Id=$id";
    $result=mysqli_query($con,$sql);

    if($result){
        header('location:Display.php');
    }
    else{
        die(mysqli_error($con));
    }
}
?>