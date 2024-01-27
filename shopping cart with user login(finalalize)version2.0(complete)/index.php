<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {
 echo '<pre>';
//  print_r($_POST);
 echo '</pre>';
        // echo 'Form Submitted';
    $product_name = $_POST['Product_Name'];
    $product_price = $_POST['Product_Price'];
    $product_image = $_POST['Product_Image'];
    $product_quantity = $_POST['Product_Quantity'];
    
    


    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE Product_Name = '$product_name' AND User_Id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'Product already added to cart!';
    } else {
        $sql="INSERT INTO `cart`(User_Id, Product_Name, Product_Price, Product_Image, Product_Quantity) VALUES('$user_id', '$product_name', $product_price, '$product_image', $product_quantity)";
        echo $sql;
        // die();
        mysqli_query($conn, $sql) or die('query failed');
        $message[] = 'Product added to cart!';
    }
}

if (isset($_POST['update_cart'])) {
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];
    mysqli_query($conn, "UPDATE `cart` SET Product_Quantity = '$update_quantity' WHERE Cart_Id = '$update_id'") or die('query failed');
    $message[] = 'Cart quantity updated successfully!';
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE Cart_Id = '$remove_id'") or die('query failed');
    header('location:index.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE User_Id = '$user_id'") or die('query failed');
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
 
    <title>Shopping Cart</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>

    <div class="container">

        <div class="user-profile">

            <?php
            $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE User_Id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select_user) > 0) {
                $fetch_user = mysqli_fetch_assoc($select_user);
            };
            ?>

            <p>Username: <span><?php echo $fetch_user['User_Name']; ?></span></p>
            <p>Email: <span><?php echo $fetch_user['User_Email']; ?></span></p>
            <div class="flex">
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="option-btn">Register</a>
                <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to logout?');" class="delete-btn">Logout</a>
            </div>

        </div>

        <div class="products">

            <h1 class="heading">Latest Products</h1>
            <!-- SELECTING -->
            <div class="container">
            <form action="" method="post" class="row g-3 align-items-center">
            <div class="col">
    <label class="my-2 form-label" for="category">Category:</label>
    <select class="form-control" name="category">
        <option value="">-- Select Category --</option>
        <?php
        // Database connection
        // include 'config.php';

        // Fetch category names from the Category table
        $categoryQuery = "SELECT Category_Name FROM category";
        $categoryResult = mysqli_query($conn, $categoryQuery);

        if ($categoryResult) {
            while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                echo "<option value='{$categoryRow['Category_Name']}'>{$categoryRow['Category_Name']}</option>";
            }
        }

        mysqli_free_result($categoryResult);
        ?>
    </select>
    </div>

    <div class="col">
    <label class="my-2 form-label" for="brand">Brand:</label>
    <select class="form-control" name="brand">
        <option value="">-- Select Brand --</option>
        <?php
        // Fetch brand names from the Brand table
        $brandQuery = "SELECT Brand_Name FROM brand";
        $brandResult = mysqli_query($conn, $brandQuery);

        if ($brandResult) {
            while ($brandRow = mysqli_fetch_assoc($brandResult)) {
                echo "<option value='{$brandRow['Brand_Name']}'>{$brandRow['Brand_Name']}</option>";
            }
        }

        mysqli_free_result($brandResult);
        ?>
    </select>
    </div>


        <div class="col">
    <label class="my-2 form-label" for="price">Price:</label>
    <select class="form-control" name="price">
        <option value="">-- Select Price Order --</option>
        <option value="High-to-low">High to Low</option>
        <option value="Low-to-High">Low to High</option>
    </select>
    </div>
    <div class="col-auto">
            <button class="btn btn-primary mx-1" type="submit">Apply</button>
        </div>
 </form>
</div>


            <div >
               
            <?php

             
// include 'config.php';
 
      

// Initialize variables to hold form data
$category = isset($_POST['category']) ? $_POST['category'] : '';
$brand = isset($_POST['brand']) ? $_POST['brand'] : '';
$priceOrder = isset($_POST['price']) ? $_POST['price'] : '';
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$cardsPerPage = 12;

// Construct the base SQL query
$sqlCount = "SELECT COUNT(*) AS total FROM product p";

// Add conditions based on the selected options
if (!empty($category)) {

    $sqlCount .= " JOIN category  c ON p.Category_Id = c.Category_Id AND c.Category_Name = '$category'";
    
}

if (!empty($brand)) {
    $sqlCount .= " JOIN brand b ON p.Brand_Id = b.Brand_Id AND b.Brand_Name = '$brand'";
}
// echo $sqlCount;

// Execute the count query
$countResult = mysqli_query($conn, $sqlCount);

if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $totalCards = $countRow['total'];
} else {
    // Handle count query execution error
    echo "Count query execution error: " . mysqli_error($conn);
}

// Calculate the starting index for the pagination
$startIndex = ($currentPage - 1) * $cardsPerPage;

// Construct the base SQL query for fetching product data
$sql = "SELECT 
            p.Product_Id,
            p.Product_Name,
            p.Product_Description,  -- Corrected column name
            p.Price,
            p.ProductImage1
            
        FROM 
            product p";

// Add conditions based on the selected options
if (!empty($category)) {
    $sql .= " JOIN category c ON p.Category_Id = c.Category_Id AND c.Category_Name = '$category'";
}

if (!empty($brand)) {
    $sql .= " JOIN brand b ON p.Brand_Id = b.Brand_Id AND b.Brand_Name = '$brand'";
}

// Add random ordering
$sql .= " ORDER BY RAND()";

// Limit the results for pagination
$sql .= " LIMIT $startIndex, $cardsPerPage";

// Execute the query for fetching product data
$result = mysqli_query($conn, $sql);

if ($result) {
    // Check if there are rows in the result
    if (mysqli_num_rows($result) > 0) {
        // Output the cards based on the query result
       
            


while ($row = mysqli_fetch_assoc($result)) {
    // print_r($row);
    // Retrieve values from the row
    $productName = $row['Product_Name'];
    $productDescription = $row['Product_Description'];
    $productPrice = $row['Price'];
    $productImage = $row['ProductImage1'] ;
    // $productImage = $row['ProductImage1'] ? $row['ProductImage1'] : '';
    
  ?>  
    <form action="" method="post">
        <?php
        // echo $productImage;
        // die();
        
    echo '<div class="card" style="width: 18rem;">';
    echo '  <img src="' . $productImage . '" name="Product_Image" class="card-img-top" alt="...">';
    // echo '  <input type="file" name="Product_Image" class="card-img-top"> ';
    echo '  <input type="hidden" name="Product_Image" value="'.$productImage.'"> ';
    echo '  <div class="card-body">';
    echo '    <h3 class="card-title" name="Product" value="' . $productName . '">' . $productName . '</h3>';
    echo '      <input type="hidden" name="Product_Name" value="' . $productName . '">';
    echo '    <h5 class="card-title"  name="Product_Price" value="' . $productPrice . '">' . $productPrice . '</h5>';
    echo '      <input type="hidden" name="Product_Price" value="' . $productPrice . '">';
    // echo '    <p class="card-text">' . $productDescription . '</p>';
    // echo '      <input type="hidden" name="Product_" value="' . $productDescription . '">';
    echo'   <input class="form-control" type="number" min="1" name="Product_Quantity" value="1">';
    echo '     <input type="submit" value="Add to Cart" name="add_to_cart" class="btn btn-primary">';
    echo '  </div>';
    echo '</div>';
    ?>
    </form>
    <?php
    
}



// echo '<form action="" method="post">';
// echo '  <!-- ... Other product details ... -->';
// echo '  <input type="text" name="Product_Name" value="' . $productName . '" style="display:none;">';
// echo '  <input type="text" name="Product_Price" value="' . $productPrice . '" style="display:none;">';
// echo '  <input type="text" name="Product_Image" value="' . $productImage . '" style="display:none;">';
// echo '  <input class="form-control" type="number" min="1" name="Product_Quantity" value="1">';
// echo '  <input type="submit" value="Add to Cart" name="add_to_cart" class="btn btn-primary">';
// echo '</form>';






            
        
    } else {
        echo "No results found.";
    }
} else {
    // Handle query execution error
    echo "Query execution error: " . mysqli_error($conn);
}

// Close the database connection


// Pagination links
$totalPages = ceil($totalCards / $cardsPerPage);

// ... Your previous code ...

// Pagination links
echo '<nav aria-label="...">
    <ul class="pagination">';

// Disable "Previous" link on the first page
$prevClass = ($currentPage == 1) ? 'disabled' : '';
echo '<li class="page-item ' . $prevClass . '">
        <a class="page-link" href="?page=' . max($currentPage - 1, 1) . '">Previous</a>
    </li>';

// Generate numeric pagination links
for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = ($i == $currentPage) ? 'active' : '';
    echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
}

// Disable "Next" link on the last page
$nextClass = ($currentPage == $totalPages) ? 'disabled' : '';
echo '<li class="page-item ' . $nextClass . '">
        <a class="page-link" href="?page=' . min($currentPage + 1, $totalPages) . '">Next</a>
    </li>';

echo '</ul>
</nav>';
?>









            </div>


        </div>

        <div class="shopping-cart">

            <h1 class="heading">Shopping Cart</h1>

            <table>
                <thead>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                    
                </thead>
                <tbody>
                    <?php
                    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE User_Id = '$user_id'") or die('query failed');
                    $grand_total = 0;
                    if (mysqli_num_rows($cart_query) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                    ?>
                            <tr>
                                <td><img src="<?php echo $fetch_cart['Product_Image']; ?>" height="100" alt=""></td>
                                <?php
                                echo'';
                                
                                ?>
                                <td><?php echo $fetch_cart['Product_Name']; ?></td>
                                <td>$<?php echo $fetch_cart['Product_Price']; ?>/-</td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['Cart_Id']; ?>">
                                        <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['Product_Quantity']; ?>">
                                        <input type="submit" name="update_cart" value="Update" class="option-btn">
                                    </form>
                                </td>
                                <td>$<?php echo $sub_total = ($fetch_cart['Product_Price'] * $fetch_cart['Product_Quantity']); ?>/-</td>
                                <td><a href="index.php?remove=<?php echo $fetch_cart['Cart_Id']; ?>" class="delete-btn" onclick="return confirm('Remove item from cart?');">Remove</a></td>
                            </tr>
                    <?php
                            $grand_total += $sub_total;
                        }
                    } else {
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">No item added</td></tr>';
                    }
                    mysqli_close($conn);
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4">Grand Total:</td>
                        <td>$<?php echo $grand_total; ?>/-</td>
                        <td><a href="index.php?delete_all" onclick="return confirm('Delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Delete All</a></td>
                    </tr>
                </tbody>
            </table>

            <div class="cart-btn">
                <a href="#" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
            </div>

        </div>

    </div>
   
</body>

</html>
