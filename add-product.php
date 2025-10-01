<?php
include('config.php');

if (isset($_POST['save_product'])) {
  $product_name = $_POST['product_name'];
  $dealer_price = $_POST['dealer_price'];
  $discount_percent = $_POST['discount_percent'];
  $normal_price = $_POST['normal_price'];
  $quantity = $_POST['quantity'];

  // Calculate the Cost Price
  $cost_price = $dealer_price - ($dealer_price * ($discount_percent / 100));

  // Check if the product already exists
  $query = "SELECT * FROM products WHERE product_name = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $product_name);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // If product exists, update the quantity
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;
    $update_query = "UPDATE products SET quantity = ?, cost_price = ?, normal_price = ? WHERE product_name = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("idss", $new_quantity, $cost_price, $normal_price, $product_name);
    $update_stmt->execute();
    echo "Product updated successfully!";
  } else {
    // If product doesn't exist, insert a new product
  $insert_query = "INSERT INTO products (product_name, dealer_price, discount_percent, cost_price, normal_price, quantity) VALUES (?, ?, ?, ?, ?, ?)";
  $insert_stmt = $conn->prepare($insert_query);
  $insert_stmt->bind_param("sddddi", $product_name, $dealer_price, $discount_percent, $cost_price, $normal_price, $quantity);
  $insert_stmt->execute();
  echo "Product added successfully!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product</title>
</head>
<body>
  <h1>Add Product</h1>
  <form method="POST" action="add-product.php">
    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="dealer_price">Dealer Price:</label>
    <input type="number" id="dealer_price" name="dealer_price" step="0.01" required><br><br>

    <label for="discount_percent">Discount %:</label>
    <input type="number" id="discount_percent" name="discount_percent" step="0.01" required><br><br>

    <label for="normal_price">Normal Price:</label>
    <input type="number" id="normal_price" name="normal_price" step="0.01" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit" name="save_product">Save Product</button>
  </form>
  <br>
  <a href="products.php">View Product List</a>
</body>
</html>
