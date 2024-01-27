<?php
include 'Connect.php';

// Function to sanitize user input
function sanitizeInput($input)
{
    global $con; // Assuming $con is your database connection
    return mysqli_real_escape_string($con, htmlspecialchars(trim($input)));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['insert'])) {
        // Insert operation
        $insertCategory = sanitizeInput($_POST['Category']);
        $insertBrand = sanitizeInput($_POST['Brand']);

        if (!empty($insertCategory)) {
            // Check if the category already exists
            $checkCategoryQuery = "SELECT Category_Name FROM category WHERE Category_Name = '$insertCategory'";
            $checkCategoryResult = mysqli_query($con, $checkCategoryQuery);

            if (mysqli_num_rows($checkCategoryResult) == 0) {
                $insertCategoryQuery = "INSERT INTO category (Category_Name) VALUES ('$insertCategory')";
                mysqli_query($con, $insertCategoryQuery);
                echo '<div class="alert alert-danger container mt-4">Category Inserted</div>';

            } else {
                // Category already exists, show error message
                $errorMessage = "Category already exists!";
            }
        }

        if (!empty($insertBrand)) {
            // Check if the brand already exists
            $checkBrandQuery = "SELECT Brand_Name FROM brand WHERE Brand_Name = '$insertBrand'";
            $checkBrandResult = mysqli_query($con, $checkBrandQuery);

            if (mysqli_num_rows($checkBrandResult) == 0) {
                $insertBrandQuery = "INSERT INTO brand (Brand_Name) VALUES ('$insertBrand')";
                mysqli_query($con, $insertBrandQuery);
                echo '<div class="alert alert-danger container mt-4">Brand Inserted</div>';
            } else {
                // Brand already exists, show error message
                $errorMessage = "Brand already exists!";
            }
        }

        
    }
                
    

 }

 ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <title>Styled Bootstrap Dropdown Example</title>
</head>
<body>



    <?php
    if (isset($errorMessage)) {
        echo '<div class="alert alert-danger container mt-4">' . $errorMessage . '</div>';
    }
    ?>
    <form method="post" action="Category-Brand.php" class="container my-5 border rounded">
    <h2 class="text-center my-2">Category/Brand Insertion</h2>
        <!-- Input field for Category -->
        <div class="form-group">
            <label for="Category">Category:</label>
            <input class="form-control" id="Category" name="Category" />
            
        </div>
        <button type="submit" name="insert" class="btn btn-primary">Insert Category</button>

        

        <!-- Input field for Brand -->
        <div class="form-group mt-4">
            <label for="Brand">Brand:</label>
            <input class="form-control" id="Brand" name="Brand" />
        </div>
        <button type="submit" name="insert" class="btn btn-primary">Insert Brand</button>

        
        <div class="d-flex justify-content-end mb-4">
        <button class='btn btn-primary '><a class='text-light' href="Display.php">Product Database</a></button>
       
        </div>
    </form>
    


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>
