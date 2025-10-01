<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Bill</title>
</head>
<body>
  <h1>Create Bill</h1>
  <form action="generate_bill.php" method="post">
    <label for="customer_name">Customer Name</label>
    <input type="text" name="customer_name" id="customer_name" required><br><br>

    <label for="customer_address">Customer Address</label>
    <textarea name="customer_address" id="customer_address" required></textarea><br><br>

    <label for="customer_contact">Customer Contact Info</label>
    <input type="text" name="customer_contact" id="customer_contact" required><br><br>

    <label for="customer_type">Customer Type</label>
    <select name="customer_type" id="customer_type">
      <option value="wholesale">Wholesale</option>
      <option value="normal">Normal</option>
    </select><br><br>

    <label for="product">Product</label>
    <select name="product" id="product" required>
      <?php
        $query = "SELECT * FROM products";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
          echo "<option value='".$row['id']."' data-price='".$row['normal_price']."'>".$row['product_name']."</option>";
        }
      ?>
    </select><br><br>

    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" id="quantity" required oninput="calculateSubtotal()"><br><br>

    <label for="price">Price</label>
    <input type="number" name="price" id="price" step="0.01" required oninput="calculateSubtotal()"><br><br>

    <label for="subtotal">Subtotal</label>
    <input type="number" name="subtotal" id="subtotal" step="0.01" required oninput="updateTotal()"><br><br>

    <label for="total">Total</label>
    <input type="number" name="total" id="total" step="0.01" required><br><br>

    <button type="submit">Generate Bill</button>
  </form>
  <script>
    // Set price when product changes
    document.getElementById('product').addEventListener('change', function() {
      var productPrice = this.options[this.selectedIndex].getAttribute('data-price');
      document.getElementById('price').value = productPrice;
      calculateSubtotal();
    });
    // Auto-calculate subtotal
    function calculateSubtotal() {
      let price = parseFloat(document.getElementById('price').value) || 0;
      let quantity = parseFloat(document.getElementById('quantity').value) || 0;
      let subtotal = price * quantity;
      document.getElementById('subtotal').value = subtotal.toFixed(2);
      updateTotal();
    }
    // Update total (can add discount logic here)
    function updateTotal() {
      let subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
      document.getElementById('total').value = subtotal.toFixed(2);
    }
    // Allow manual edit of price/subtotal/total
    document.getElementById('price').removeAttribute('readonly');
    document.getElementById('subtotal').removeAttribute('readonly');
    document.getElementById('total').removeAttribute('readonly');
  </script>
</body>
</html>
