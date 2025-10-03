
<?php
// create-bill.php - Bill creation form with due system
include('config.php');
// Fetch customers
$customers = $conn->query("SELECT * FROM customers ORDER BY name");
// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY product_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Bill</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body{font-family: system-ui, Arial, sans-serif; max-width: 900px; margin: 24px auto;}
    h1{margin-bottom:16px}
    .row{display:grid; grid-template-columns: 180px 1fr; gap:12px; align-items:center; margin-bottom:10px;}
    .card{border:1px solid #e5e7eb; border-radius:8px; padding:16px; margin-bottom:16px;}
    table{width:100%; border-collapse:collapse; margin-top:8px}
    th,td{border:1px solid #e5e7eb; padding:8px; text-align:left}
    tfoot td{font-weight:700}
    .actions{display:flex; gap:8px}
    button{cursor:pointer; padding:8px 12px; border-radius:6px; border:1px solid #d1d5db; background:#fff}
    button.primary{background:#111827; color:#fff; border-color:#111827}
    button.warn{background:#dc2626; color:#fff; border-color:#dc2626}
    .due{color:red; font-weight:bold; margin-left:10px;}
    @media (max-width:640px){ .row{grid-template-columns:1fr} }
  </style>
</head>
<body>
  <h1>Create Bill</h1>
  <form id="billForm" action="save-bill.php" method="post" onsubmit="return beforeSubmit()">
    <div class="card">
      <h3>Customer Information</h3>
      <div class="row" style="position:relative;">
        <label for="customer_name">Customer Name</label>
        <input type="text" name="customer_name" id="customer_name" autocomplete="off" oninput="suggestCustomer()" required>
        <div id="customer_suggestions" style="position:absolute;top:38px;left:180px;z-index:10;background:#fff;border:1px solid #ccc;max-height:120px;overflow-y:auto;display:none;width:calc(100% - 180px);"></div>
      </div>
      <div class="row">
        <label for="customer_contact">Contact</label>
        <input type="text" name="customer_contact" id="customer_contact" autocomplete="off" oninput="suggestCustomerContact()">
      </div>
      <div class="row">
        <label for="customer_address">Address</label>
        <input type="text" name="customer_address" id="customer_address">
      </div>
      <span id="dueInfo" class="due"></span>
    </div>
    <div class="card">
      <h3>Add Products</h3>
      <table id="product-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align:right;">Total</td>
            <td id="totalCell">0.00</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
      <button type="button" onclick="addProductRow()">Add Product</button>
    </div>
    <div class="card">
      <div class="row">
        <label for="paid_amount">Paid Amount</label>
        <input type="number" name="paid_amount" id="paid_amount" value="0" min="0" step="0.01" oninput="updateDue()">
      </div>
      <div class="row">
        <label for="due_amount">Due Amount</label>
        <input type="number" name="due_amount" id="due_amount" value="0" readonly>
      </div>
    </div>
    <div class="actions">
      <button type="submit" class="primary">Save Bill</button>
    </div>
  </form>
  <script>
  let productCount = 0;
  const products = <?php
    $pArr = [];
    $products2 = $conn->query("SELECT * FROM products");
    while($row = $products2->fetch_assoc()) $pArr[] = $row;
    echo json_encode($pArr);
  ?>;

  // --- Customer Autocomplete ---
  function suggestCustomer() {
    const name = document.getElementById('customer_name').value;
    if (name.length < 1) { document.getElementById('customer_suggestions').style.display = 'none'; return; }
    fetch('suggest_customer.php?name=' + encodeURIComponent(name))
      .then(res => res.json())
      .then(data => {
        const box = document.getElementById('customer_suggestions');
        if (data.length === 0) { box.style.display = 'none'; return; }
        box.innerHTML = data.map(c => `<div style='padding:6px;cursor:pointer' onclick='selectCustomer(${JSON.stringify(c)})'>${c.name} (${c.contact||''})</div>`).join('');
        box.style.display = 'block';
      });
  }
  function selectCustomer(c) {
    document.getElementById('customer_name').value = c.name;
    document.getElementById('customer_contact').value = c.contact||'';
    document.getElementById('customer_address').value = c.address||'';
    document.getElementById('customer_suggestions').style.display = 'none';
    fetchDueByName(c.name);
  }
  document.addEventListener('click', function(e){
    if (!e.target.closest('#customer_suggestions') && e.target.id !== 'customer_name') {
      document.getElementById('customer_suggestions').style.display = 'none';
    }
  });

  function suggestCustomerContact() {
    const contact = document.getElementById('customer_contact').value;
    if (contact.length < 1) return;
    fetch('suggest_customer.php?contact=' + encodeURIComponent(contact))
      .then(res => res.json())
      .then(data => {
        if (data.length > 0) {
          const c = data[0];
          document.getElementById('customer_name').value = c.name;
          document.getElementById('customer_address').value = c.address||'';
        }
      });
  }

  function fetchDueByName(name) {
    fetch('get_due.php?customer_name=' + encodeURIComponent(name))
      .then(res => res.json())
      .then(data => {
        document.getElementById('dueInfo').textContent = data.due > 0 ? `Previous Due: ${data.due}` : '';
      });
  }
    const tbody = document.getElementById('product-table').querySelector('tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>
        <select name="products[${productCount}][product_id]" id="product_${productCount}" onchange="updatePrice(${productCount})">
          ${products.map(p => `<option value="${p.id}" data-price="${p.normal_price}">${p.product_name}</option>`).join('')}
        </select>
      </td>
      <td><input type="number" name="products[${productCount}][quantity]" id="quantity_${productCount}" min="1" value="1" oninput="calculateSubtotal(${productCount})"></td>
      <td><input type="number" name="products[${productCount}][price]" id="price_${productCount}" step="0.01" required oninput="calculateSubtotal(${productCount})"></td>
      <td><input type="number" name="products[${productCount}][subtotal]" id="subtotal_${productCount}" step="0.01" required readonly></td>
      <td><button type="button" onclick="removeProductRow(this)">Remove</button></td>
    `;
    tbody.appendChild(row);
    setAutoPriceFromSelection(productCount);
    productCount++;
    updateTotal();
  }
  function updatePrice(index) {
    setAutoPriceFromSelection(index);
    calculateSubtotal(index);
  }
  function setAutoPriceFromSelection(index) {
    const productSelect = document.getElementById('product_' + index);
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    let productPrice = selectedOption.getAttribute('data-price');
    document.getElementById('price_' + index).value = productPrice;
    calculateSubtotal(index);
  }
  function calculateSubtotal(index) {
    const price = parseFloat(document.getElementById('price_' + index).value) || 0;
    const quantity = parseFloat(document.getElementById('quantity_' + index).value) || 0;
    const subtotal = price * quantity;
    document.getElementById('subtotal_' + index).value = subtotal.toFixed(2);
    updateTotal();
  }
  function updateTotal() {
    let total = 0;
    for (let i = 0; i < productCount; i++) {
      const subtotalField = document.getElementById('subtotal_' + i);
      if (subtotalField) total += parseFloat(subtotalField.value) || 0;
    }
    document.getElementById('totalCell').textContent = total.toFixed(2);
    document.getElementById('due_amount').value = (total - (parseFloat(document.getElementById('paid_amount').value) || 0)).toFixed(2);
  }
  function removeProductRow(btn) {
    btn.closest('tr').remove();
    updateTotal();
  }
  function updateDue() {
    const total = parseFloat(document.getElementById('totalCell').textContent) || 0;
    const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
    document.getElementById('due_amount').value = (total - paid).toFixed(2);
  }
  // (No longer needed: fetchDue for dropdown)
  window.onload = function() { addProductRow(); }
  function beforeSubmit() {
    // Basic validation
    if (!document.getElementById('customer_id').value) { alert('Select customer'); return false; }
    if (document.querySelectorAll('#product-table tbody tr').length === 0) { alert('Add at least one product'); return false; }
    return true;
  }
  </script>
</body>
</html>
