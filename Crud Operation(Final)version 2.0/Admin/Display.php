
<?php
include 'Connect.php';
function sanitizeInput($input)
{
    global $con; // Assuming $con is your database connection
    return mysqli_real_escape_string($con, htmlspecialchars(trim($input)));
}
$errorMessage=array();
if (isset($_POST['deleteCategory'])) {
  // Delete operation for Category
  $deleteCategory = sanitizeInput($_POST['deleteCategory']);
  
  // Check if the category is used in the product table
  $checkProductQuery = "SELECT * FROM product WHERE Category_Id = (SELECT Category_Id FROM category WHERE Category_Name = '$deleteCategory')";
  $checkProductResult = mysqli_query($con, $checkProductQuery);

  if (mysqli_num_rows($checkProductResult) > 0) {
      // Category is used in the product table, show error message
      $errorMessage[] = "Cannot delete category. It is used in the product table.";
  } else {
      // Delete the category from the category table
      $deleteCategoryQuery = "DELETE FROM `category` WHERE Category_Name = '$deleteCategory'";
      mysqli_query($con, $deleteCategoryQuery);
  }

  
}

if (isset($_POST['deleteBrand'])) {
  // Delete operation for Brand
  $deleteBrand = sanitizeInput($_POST['deleteBrand']);
  
  // Check if the brand is used in the product table
  $checkProductQuery = "SELECT * FROM product WHERE Brand_Id = (SELECT Brand_Id FROM brand WHERE Brand_Name = '$deleteBrand')";
  $checkProductResult = mysqli_query($con, $checkProductQuery);

  if (mysqli_num_rows($checkProductResult) > 0) {
      // Brand is used in the product table, show error message
      $errorMessage[]= "Cannot delete brand. It is used in the product table.";
  } else {
      // Now you can safely delete from the brand table
      $deleteBrandQuery = "DELETE FROM brand WHERE Brand_Name = '$deleteBrand'";
      mysqli_query($con, $deleteBrandQuery);
  }

  
}




?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
      img{
        width:100px;
      }
    </style>
  </head>
  <body>
 


  <section class=" container-fluid my-5">
    

  <div class="container-fluid my-5">
    <div class="row d-flex align-items-center">
        <div class="col-sm-6 col-md-4 col-lg-4 text-center">
          <button type="button" class="btn btn-primary" onclick="location.href='Form.php'">Add Product</button>
            <button type="button" class="btn btn-primary" onclick="location.href='Category-Brand.php'">Category/Brand</button>
            
        </div>
        <div class="col-sm-6 col-md-8 col-lg-8 text-start">
           <h1 class="display-3">PRODUCT DATABASE</h1>
        </div>
    </div>
</div>

<?php
if (!empty($errorMessages)) {
  // Display error messages
  echo '<div class="alert alert-danger container mt-4">';
  foreach ($errorMessages as $errorMessage) {
      echo $errorMessage . '<br>';
  }
  echo '</div>';
}
?>


  <table class="table table-striped table-bordered" >
  <thead class="table-dark">
    <tr>
      <th scope="col" >Product_Id</th>
      <th scope="col">Brand</th>
      <th scope="col">Category</th>
      <th scope="col">Product_Name</th>
      <th scope="col">Product_Description</th>
      <th scope="col">Gender</th>
      <th scope="col">Price</th>
      <th scope="col">Discount</th>
      <th scope="col">ProductImage1</th>
      <th scope="col">ProductImage2</th>
      <th scope="col">ProductImage3</th>
      <th scope="col">Operations</th>
    </tr>
  </thead>
  <tbody>

  <?php
  
  $sql="SELECT * from `product` ";
  $result=mysqli_query($con,$sql);

  while($row=mysqli_fetch_assoc($result)){
    $Product_Id=$row['Product_Id'];
    $Brand_Id = $row['Brand_Id'];  
    $Category_Id = $row['Category_Id']; 
    $Product_Name=$row['Product_Name'];
    $Product_Description=$row['Product_Description'];
    $Gender=$row['Gender'];
    $Price=$row['Price'];
    $Discount=$row['Discount'];
    $ProductImage1=$row['ProductImage1'];
    $ProductImage2=$row['ProductImage2'];
    $ProductImage3=$row['ProductImage3'];

    // Fetch Brand_Name based on Brand_Id
    $brandQuery = "SELECT Brand_Name FROM brand WHERE Brand_Id = '$Brand_Id'";
    $brandResult = mysqli_query($con, $brandQuery);
    $brandRow = mysqli_fetch_assoc($brandResult);
    $Brand_Name = $brandRow['Brand_Name'];

    // Fetch Category_Name based on Category_Id
    $categoryQuery = "SELECT Category_Name FROM category WHERE Category_Id = '$Category_Id'";
    $categoryResult = mysqli_query($con, $categoryQuery);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
    $Category_Name = $categoryRow['Category_Name'];

    echo '
    <tr>
        <td class="fw-bolder">'.$Product_Id.'</td>
        <td>'.$Brand_Name.'</td>
        <td>'.$Category_Name.'</td>
        <td>'.$Product_Name.'</td>
        <td>'.$Product_Description.'</td>
        <td>'.$Gender.'</td>
        <td>'.$Price.'</td>
        <td>'.$Discount.'</td>
        <td><img src="' . $ProductImage1 . '"/></td>
        <td><img src="' . $ProductImage2 . '"/></td>
        <td><img src="' . $ProductImage3 . '"/></td>
        <td class="d-flex justify-content-center align-items-center ">
            <button type="button" class="btn btn-primary m-1"><a class="text-light text-decoration-none" href="Update.php?updateid=' . $Product_Id . '">Update</a></button>
            <button type="button" class="btn btn-danger">  <a class="text-light text-decoration-none" href="Delete.php?deleteid=' . $Product_Id . '" onclick="return confirm(\'Are you sure?\');">Delete</a></button>
        </td>
    </tr>';
}

  ?>
  
  </tbody>
</table>
<!-- Display Category Table -->
<form action="" method="post">
        <h2>Category Table</h2>

        
        <table class="table table-striped table-bordered mt-2">
            <thead class="table-dark">
            <tr>
                <th scope="col">Category Id</th>
                <th scope="col">Category Name</th>
                <th scope="col">Operation</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $categoryQuery = "SELECT Category_Id, Category_Name FROM category";
            $categoryResult = mysqli_query($con, $categoryQuery);
            while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
              $Category_Id = $categoryRow['Category_Id'];
              $Category_Name = $categoryRow['Category_Name'];

              echo '
                  <tr>
                    <td>'.$Category_Id.'</td>
                    <td>'.$Category_Name.'</td>
                    <td><button type="submit" name="deleteCategory" class="btn btn-danger" value="'.$Category_Name.'">Delete</button></td>
                  </tr>';
    
            }
            ?>
            </tbody>
        </table>
          </form>
    

    <!-- Display Brand Table -->
    <form action="" method="post">
        <h2>Brand Table</h2>
         

        
        <table class="table table-striped table-bordered mt-2">
            <thead class="table-dark">
            <tr>
                <th scope="col">Brand Id</th>
                <th scope="col">Brand Name</th>
                <th scope="col">Operation</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $brandQuery="SELECT Brand_Id, Brand_Name FROM brand";
            $brandResult = mysqli_query($con, $brandQuery);
            while ($brandRow = mysqli_fetch_assoc($brandResult)) {
              $Brand_Id = $brandRow['Brand_Id'];
              $Brand_Name = $brandRow['Brand_Name'];

              

                 echo '
                <tr>
                    <td>'.$Brand_Id.'</td>
                    <td>'.$Brand_Name.'</td>
                    <td><button type="submit" name="deleteBrand" class="btn btn-danger" value="'.$Brand_Name.'">Delete</button></td>
                </tr>';
            }
            ?>
            </tbody>
        </table>
        </form>
    



  </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>