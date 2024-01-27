<?php

include 'Connect.php';

// ID IS COMING FROM THE DISPLAY.PHP THAT THIS ID NEEDS TO BE UPDATED
$id=$_GET['updateid'];

// SELECTING THAT RECORD WHERE ID IS THIS
$sql="SELECT * FROM `product` WHERE Product_Id=$id";
$result=mysqli_query($con,$sql);

// FETCHING THAT RECORD EACH COLUMN RECORD
$row=mysqli_fetch_assoc($result);
$Brand_Id = $row['Brand_Id'];  
$Category_Id = $row['Category_Id'];

    $brandQuery = "SELECT Brand_Name FROM brand WHERE Brand_Id = '$Brand_Id'";
    $brandResult = mysqli_query($con, $brandQuery);
    $brandRow = mysqli_fetch_assoc($brandResult);
$Brand_Name = $brandRow['Brand_Name'];

    // Fetch Category_Name based on Category_Id
    $categoryQuery = "SELECT Category_Name FROM category WHERE Category_Id = '$Category_Id'";
    $categoryResult = mysqli_query($con, $categoryQuery);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
$Category_Name = $categoryRow['Category_Name'];

$Product_Name=$row['Product_Name'];
$Product_Description=$row['Product_Description'];
$Gender=$row['Gender'];
$Price=$row['Price'];
$Discount=$row['Discount'];
$ProductImage1=$row['ProductImage1'];
$ProductImage2=$row['ProductImage2'];
$ProductImage3=$row['ProductImage3'];

// Function to capitalize the first letter of each word
function capitalizeFirstLetter($text) {
    return ucwords(strtolower($text));
}

// Function to capitalize the first letter of each sentence
function capitalizeSentences($description) {
    $sentences = preg_split('/(?<=[.?!])\s+/', $description, -1, PREG_SPLIT_NO_EMPTY);
    $capitalizedSentences = array_map('ucfirst', $sentences);
    return implode(' ', $capitalizedSentences);
}

if(isset($_POST['Submit'])){
    $Brand_Name = capitalizeFirstLetter($_POST['Brand_Name']);
    $Product_Name = capitalizeFirstLetter($_POST['Product_Name']);
    $Product_Description = capitalizeSentences($_POST['Product_Description']);
    $Gender=$_POST['Gender'];
    $Price=$_POST['Price'];
    $Discount=$_POST['Discount'];

    // FOR LOOP FOR THE FILES UPLOAD
  
    $UPLOADED_IMAGE=[];

   for ($i = 1; $i <= 3; $i++){
    $ProductImage=$_FILES['ProductImage'.$i];
    $imagefilename=$ProductImage['name'];
    $imagefiletmp = $ProductImage['tmp_name']; 
    $imagefileerror = $ProductImage['error']; 

    $filename_Seperate=explode('.',$imagefilename);

    $fileExtension=strtolower(end($filename_Seperate));

    $extension=array('jpeg','jpg','png');

    if(in_array($fileExtension,$extension)){
        $upload_image='images/'.$imagefilename;
        move_uploaded_file($imagefiletmp,$upload_image);
        $UPLOADED_IMAGE["ProductImage$i"]=$upload_image;
        
    }else {
        echo "Invalid file format for Image $i <br>";
    }
   }
    // UPDATE QUERY FOR THE TABLE
    $sql = $sql = "UPDATE `product` SET 
    Brand_Id='$Brand_Id',  
    Category_Id='$Category_Id', 
    Product_Name='$Product_Name',
    Product_Description='$Product_Description',
    Gender='$Gender',
    Price='$Price',
    Discount='$Discount',
    ProductImage1='{$UPLOADED_IMAGE['ProductImage1']}',
    ProductImage2='{$UPLOADED_IMAGE['ProductImage2']}',
    ProductImage3='{$UPLOADED_IMAGE['ProductImage3']}'
    WHERE Product_Id=$id";


        $result=mysqli_query($con,$sql);
    
        if ($result) {
            header("Location: Display.php");
            exit();
        } else {
            echo "Update failed: " . mysqli_error($con);
        }
        

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- BOOTSTRAP LINK -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <title>Product Information form</title>
</head>
<body>


  <form class="container my-5 border rounded"  method="post" enctype="multipart/form-data">
      
        <h2 class="text-center my-2">Product Information</h2>
  
        <div class="form-group  mb-2">
            <label for="Brand">Choose your Brand:</label>
            <select class="form-control" id="Brand" name="Brand" required>
                <option selected>-----</option>
                <?php
                $sql = "SELECT Brand_Name FROM brand";
                $result = mysqli_query($con, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    $Brand = $row['Brand_Name'];
                    echo '<option value="' . $Brand . '">' . $Brand . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group  mb-2">
            <label for="Category">Choose your Category:</label>
            <select class="form-control" id="Category" name="Category" required>
                <option selected >-----</option>
                <?php
                $sql = "SELECT Category_Name FROM category";
                $result = mysqli_query($con, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    $Category = $row['Category_Name'];
                    echo '<option value="' . $Category . '">' . $Category . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="mb-2">
            <label for="Product_Name" class="form-label">Product Name:</label>
            <input type="text" class="form-control" name="Product_Name" id="Product_Name" placeholder="Product Name" autocomplete="off" value="<?php echo $Product_Name;?>" required>
        </div>
    
        <div class="mb-2">
            <label for="Product_Description" class="form-label">Product_Description:</label>
           <textarea class="form-control" id="Product_Description" rows="3" name="Product_Description" placeholder="Product_Description" required><?php echo $Product_Description;?></textarea>

        </div>

      <div class="row">
      <div class="form-group col-lg-4 col-md-4 col-sm-4 mb-2">
            <label for="Gender">Choose Gender:</label>
            <select class="form-control " id="Gender" name="Gender" required>
                <option selected value="Male">Male</option>
                <option value="Female">Female</option>
                
            </select>

        </div>

        <div class="form-group col-lg-4 col-md-4 col-sm-4 mb-2">
            <label for="Price">Price:</label>
            <input class="form-control " type="number" id="Price" name="Price" placeholder="Price" autocomplete="off" value=<?php echo $Price;?> required>
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-4 mb-2">
            <label for="Discount">Discount:</label>
            <input class="form-control " type="number" id="Discount" name="Discount" placeholder="Discount" autocomplete="off" value=<?php echo $Discount;?>>
        </div>
      </div>

        <div class="mb-2">
            <label for="ProductImage1" class="form-label">ProductImage-1:</label>
            <input class="form-control" type="file" name="ProductImage1" id="ProductImage1" accept="image/*">
           
        </div>
        <div class="mb-2">
            <label for="ProductImage2" class="form-label">ProductImage-2:</label>
            <input class="form-control" type="file" name="ProductImage2" id="ProductImage2" accept="image/*"   >
        </div>
        <div class="mb-1">
            <label for="ProductImage3" class="form-label">ProductImage-3:</label>
            <input class="form-control" type="file" name="ProductImage3" id="ProductImage3" accept="image/*"  >
        </div>
        <div class="d-grid gap-2">
            <button name="Submit" type="Submit" class="btn btn-primary my-2">Update</button>
        </div>
  </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  

</body>
</html>