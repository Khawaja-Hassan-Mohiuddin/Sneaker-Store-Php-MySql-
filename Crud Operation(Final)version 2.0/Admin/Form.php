<?php

include 'Connect.php';

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
    
    $Brand_Name = $_POST['Brand'];
    $Category_Name = $_POST['Category'];
    $Product_Name = capitalizeFirstLetter($_POST['Product_Name']);
    $Product_Description = capitalizeSentences($_POST['Product_Description']);
    $Gender=$_POST['Gender'];
    $Price=$_POST['Price'];
    $Discount=$_POST['Discount'];

     // Check for duplicate Product Name
    $checkProductNameQuery = "SELECT `Product_Name` FROM `product` WHERE LOWER(`Product_Name`) = LOWER('$Product_Name')";
    $productNameResult = mysqli_query($con, $checkProductNameQuery);

if (mysqli_num_rows($productNameResult) > 0) {
    echo'<div class="alert alert-danger d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
    <div>
    Error: Product with the same name already exists. Please choose a different name.
    </div>
  </div>';
    exit();
}

// Check for duplicate Product Description
$checkProductDescriptionQuery = "SELECT `Product_Description` FROM `product` WHERE LOWER(`Product_Description`) = LOWER('$Product_Description')";
$productDescriptionResult = mysqli_query($con, $checkProductDescriptionQuery);

if (mysqli_num_rows($productDescriptionResult) > 0) {
    echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
    <div>
    Error: Product with the same description already exists. Please choose a different description.
    </div>
  </div>';
    exit();
}


  
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


// Fetch Brand_Id based on Brand_Name
$brandName = mysqli_real_escape_string($con, $Brand_Name);
$brandQuery = "SELECT Brand_Id FROM brand WHERE Brand_Name = '$brandName'";
$brandResult = mysqli_query($con, $brandQuery);

if (!$brandResult) {
    die(mysqli_error($con));
}

$row = mysqli_fetch_assoc($brandResult);
$Brand_Id = $row['Brand_Id'];

// Fetch Category_Id based on Category_Name
$categoryName = mysqli_real_escape_string($con, $Category_Name);
$categoryQuery = "SELECT Category_Id FROM category WHERE Category_Name = '$categoryName'";
$categoryResult = mysqli_query($con, $categoryQuery);

if (!$categoryResult) {
    die(mysqli_error($con));
}

$row = mysqli_fetch_assoc($categoryResult);
$Category_Id = $row['Category_Id'];

// Insert data into the product table
$sql = "INSERT INTO `product` (Brand_Id, Category_Id, Product_Name, Product_Description, Gender, Price, Discount, ProductImage1, ProductImage2, ProductImage3) 
        VALUES ('$Brand_Id', '$Category_Id', '$Product_Name', '$Product_Description', '$Gender', '$Price', '$Discount', '{$UPLOADED_IMAGE['ProductImage1']}', '{$UPLOADED_IMAGE['ProductImage2']}', '{$UPLOADED_IMAGE['ProductImage3']}')";

$result = mysqli_query($con, $sql);

if ($result) {
    header("Location: Display.php");
    exit();
} else {
    die(mysqli_error($con));
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
    <title>PRoduct Information form</title>
</head>
<body>


  <form class="container my-5 border rounded"  method="post" enctype="multipart/form-data" >
      
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
            <input type="text" class="form-control" name="Product_Name" id="Product_Name" placeholder="Product Name" autocomplete="off" required>
        </div>
    

        <div class="mb-2">
            <label for="Product_Description" class="form-label">Product_Description:</label>
            <textarea class="form-control" id="Product_Description" rows="3" name="Product_Description" placeholder="Product_Description" required></textarea>
        </div>

      <div class="row">
        <div class="form-group col-lg-4 col-md-4 col-sm-4 mb-2">
            <label for="Gender">Choose Gender:</label>
            <select class="form-control " id="Gender" name="Gender" required>
                <option selected value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

        </div>
       

        <div class=" form-group col-lg-4 col-md-4 col-sm-44 mb-2">
            <label for="Price">Price:</label>
            <input class="form-control" type="number" id="Price" name="Price" placeholder="Price" autocomplete="off" required>
        </div>
        
        <div class=" form-group col-lg-4 col-md-4 col-sm-4 mb-2">
            <label for="Discount">Discount:</label>
            <input  class="form-control" type="number" id="Discount" name="Discount" placeholder="Discount" autocomplete="off">
        </div>
      </div>

        <div class="mb-2">
            <label for="ProductImage1" class="form-label">ProductImage-1:</label>
            <input class="form-control" type="file" name="ProductImage1" id="ProductImage1" accept="image/*" required>
        </div>
        <div class="mb-2">
            <label for="ProductImage2" class="form-label">ProductImage-2:</label>
            <input class="form-control" type="file" name="ProductImage2" id="ProductImage2" accept="image/*" required >
        </div>
        <div class="mb-1">
            <label for="ProductImage3" class="form-label">ProductImage-3:</label>
            <input class="form-control" type="file" name="ProductImage3" id="ProductImage3" accept="image/*" required>
        </div>
        <div class="d-grid gap-2">
            <button name="Submit" type="Submit" class="btn btn-primary my-2" onclick="location.href='Display.php'">Submit</button>
        </div>
  </form>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  

</body>
</html>