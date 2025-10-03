<?php
include('config.php');

// পণ্য লিস্ট বের করি (normal & wholesale উভয় প্রাইস অ্যাট্রিবিউট হিসেবে দিব)
$products = [];
$res = $conn->query("SELECT id, product_name, normal_price, cost_price FROM products ORDER BY product_name");
while ($row = $res->fetch_assoc()) {
  $products[] = $row;
}
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
    @media (max-width:640px){ .row{grid-template-columns:1fr} }
  </style>
</head>
<body>
  <h1>Create Bill</h1>

  <form id="billForm" action="generate_bill.php" method="post" onsubmit="return beforeSubmit()">
    <!-- Customer Info -->
    <div class="card">
      <h3>Customer Information</h3>
      <div class="row">
        <label for="customer_name">Customer Name</label>
        <input type="text" name="customer_name" id="customer_name" required>
      </div>
      <div class="row">
        <label for="customer_address">Customer Address</label>
        <textarea name="customer_address" id="customer_address" required></textarea>
      </div>
      <div class="row">
        <label for="customer_contact">Customer Contact Info</label>
        <input type="text" name="customer_contact" id="customer_contact" required>
      </div>
      <div class="row">
        <label for="customer_type">Customer Type</label>
        <select name="customer_type" id="customer_type">
          <option value="normal">Normal</option>
          <option value="wholesale">Wholesale</option>
        </select>
      </div>
    </div>

    <!-- Add Product Form -->
    <div class="card">
      <h3>Add Product</h3>
      <div class="row">
        <label for="product_select">Product</label>
        <select id="product_select">
          <?php foreach($products as $p): ?>
            <option
              value="<?= htmlspecialchars($p['id']) ?>"
              data-name="<?= htmlspecialchars($p['product_name']) ?>"
              data-price-normal="<?= htmlspecialchars($p['normal_price']) ?>"
              data-price-wholesale="<?= htmlspecialchars($p['cost_price']) ?>"
            >
              <?= htmlspecialchars($p['product_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="row">
        <label for="quantity_input">Quantity</label>
        <input type="number" id="quantity_input" min="1" placeholder="e.g. 1">
      </div>

      <div class="row">
        <label for="price_input">Price (per unit)</label>
        <input type="number" id="price_input" step="0.01" placeholder="Auto / Manual">
      </div>

      <div class="row">
        <label for="subtotal_input">Subtotal</label>
        <input type="number" id="subtotal_input" step="0.01" placeholder="Auto" readonly>
      </div>

      <div class="actions">
        <button type="button" class="primary" id="addBtn">Add Product</button>
        <button type="button" class="primary" id="saveEditBtn" style="display:none;">Save Changes</button>
        <button type="button" id="resetBtn">Reset</button>
      </div>
    </div>

    <!-- Product List -->
    <div class="card">
      <h3>Product List</h3>
      <table id="itemsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody><!-- rows render here --></tbody>
        <tfoot>
          <tr>
            <td colspan="4" style="text-align:right;">Total</td>
            <td id="totalCell">0.00</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Hidden field: items as JSON -->
    <input type="hidden" name="items_json" id="items_json">
    <input type="hidden" name="total" id="total_hidden">

    <div class="actions">
      <button type="submit" class="primary">Generate Bill</button>
    </div>
  </form>

  <script>
    // ==== Data & State ====
    const productSelect = document.getElementById('product_select');
    const qtyInput = document.getElementById('quantity_input');
    const priceInput = document.getElementById('price_input');
    const subtotalInput = document.getElementById('subtotal_input');
    const customerType = document.getElementById('customer_type');

    const addBtn = document.getElementById('addBtn');
    const saveEditBtn = document.getElementById('saveEditBtn');
    const resetBtn = document.getElementById('resetBtn');

    const itemsTableBody = document.querySelector('#itemsTable tbody');
    const totalCell = document.getElementById('totalCell');

    const itemsJson = document.getElementById('items_json');
    const totalHidden = document.getElementById('total_hidden');

    let items = [];        // {product_id, product_name, quantity, price, subtotal}
    let editIndex = null;  // null => add mode; number => edit mode

    // ==== Helpers ====
    function toNum(v) { return isNaN(parseFloat(v)) ? 0 : parseFloat(v); }

    function setAutoPriceFromSelection() {
      const opt = productSelect.options[productSelect.selectedIndex];
      const type = customerType.value; // 'normal' or 'wholesale'
      let price = type === 'wholesale'
        ? toNum(opt.getAttribute('data-price-wholesale'))
        : toNum(opt.getAttribute('data-price-normal'));
      // Always set the price based on selection
      priceInput.value = price.toFixed(2);
      calcSubtotal();
    }

    function calcSubtotal() {
      const qty = toNum(qtyInput.value);
      const price = toNum(priceInput.value);
      subtotalInput.value = (qty * price).toFixed(2);
    }

    function clearProductForm() {
      qtyInput.value = '';
      priceInput.value = '';
      subtotalInput.value = '';
      productSelect.selectedIndex = 0;
      editIndex = null;
      saveEditBtn.style.display = 'none';
      addBtn.style.display = 'inline-block';
      setAutoPriceFromSelection();
    }

    function renderTable() {
      itemsTableBody.innerHTML = '';
      let total = 0;
      items.forEach((it, idx) => {
        total += toNum(it.subtotal);
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${idx + 1}</td>
          <td>${escapeHtml(it.product_name)}</td>
          <td>${it.quantity}</td>
          <td>${Number(it.price).toFixed(2)}</td>
          <td>${Number(it.subtotal).toFixed(2)}</td>
          <td>
            <button type="button" onclick="onEdit(${idx})">Edit</button>
          </td>
        `;
        itemsTableBody.appendChild(tr);
      });
      totalCell.textContent = total.toFixed(2);
    }

    // Simple escape function to handle HTML encoding
    function escapeHtml(s){return String(s).replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m]))}

    // ==== Actions ====
    addBtn.addEventListener('click', () => {
      const opt = productSelect.options[productSelect.selectedIndex];
      const product_id = opt.value;
      const product_name = opt.getAttribute('data-name');
      const quantity = toNum(qtyInput.value);
      const price = toNum(priceInput.value);
      const subtotal = toNum(subtotalInput.value);

      if (!product_id || quantity <= 0 || price <= 0) {
        alert('Product, Quantity and Price must be provided.');
        return;
      }

      items.push({ product_id, product_name, quantity, price, subtotal });
      renderTable();
      clearProductForm();
    });

    saveEditBtn.addEventListener('click', () => {
      if (editIndex === null) return;
      const opt = productSelect.options[productSelect.selectedIndex];
      const product_id = opt.value;
      const product_name = opt.getAttribute('data-name');
      const quantity = toNum(qtyInput.value);
      const price = toNum(priceInput.value);
      const subtotal = toNum(subtotalInput.value);

      if (!product_id || quantity <= 0 || price <= 0) {
        alert('Product, Quantity and Price must be provided.');
        return;
      }

      items[editIndex] = { product_id, product_name, quantity, price, subtotal };
      renderTable();
      clearProductForm();
    });

    resetBtn.addEventListener('click', clearProductForm);

    // Edit a product row
    window.onEdit = function(index){
      const it = items[index];
      editIndex = index;
      // Load the form with current product's data
      for (let i = 0; i < productSelect.options.length; i++) {
        if (productSelect.options[i].value == it.product_id) {
          productSelect.selectedIndex = i;
          break;
        }
      }
      qtyInput.value = it.quantity;
      priceInput.value = Number(it.price).toFixed(2);
      subtotalInput.value = Number(it.subtotal).toFixed(2);

      addBtn.style.display = 'none';
      saveEditBtn.style.display = 'inline-block';
    }

    // Dynamic calculation
    customerType.addEventListener('change', setAutoPriceFromSelection);
    productSelect.addEventListener('change', setAutoPriceFromSelection);
    qtyInput.addEventListener('input', calcSubtotal);
    priceInput.addEventListener('input', calcSubtotal);

    // Initialize price for the first product form field
    setAutoPriceFromSelection();

    // Before submit, prepare items as JSON
    function beforeSubmit(){
      if(items.length === 0){
        alert('Please add at least one product.');
        return false;
      }
      const total = items.reduce((acc, it) => acc + toNum(it.subtotal), 0);
      document.getElementById('total_hidden').value = total.toFixed(2);
      document.getElementById('items_json').value = JSON.stringify(items);
      return true;
    }
    window.beforeSubmit = beforeSubmit;
  </script>
</body>
</html>
