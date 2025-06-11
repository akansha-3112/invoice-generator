
<?php
session_start();

if (empty($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>
<?php

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$our_name = $_SESSION['business_name'] ?? '';
$our_address = $_SESSION['address'] ?? '';
$our_contact = $_SESSION['phone'] ?? '';
?>



<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #eef1f5;
        margin: 0;
        padding: 0;
    }

    .container {
        margin: 30px auto;
        max-width: 1000px;
        background: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    h2 {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #343a40;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 10px;
    }

    h5 {
        font-size: 18px;
        font-weight: 500;
        margin: 20px 0 10px;
        color: #495057;
    }

    label {
        font-weight: 500;
        margin-top: 10px;
        color: #495057;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        margin-top: 5px;
        margin-bottom: 15px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #dee2e6;
        padding: 10px;
        text-align: left;
        vertical-align: middle;
        font-size: 14px;
    }

    th {
        background-color: #f1f3f5;
        font-weight: 600;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #a71d2a;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #545b62;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 60px;
    }

    canvas {
        display: block;
        border: 1px solid #ccc;
        margin-top: 10px;
        border-radius: 4px;
        background: #fff;
    }

    #draw_section,
    #upload_section {
        margin-top: 15px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .form-row .col {
        flex: 1;
        min-width: 250px;
    }

    .form-group {
        margin-top: 20px;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .text-center {
        text-align: center;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }
    }
</style>


<div class="container mt-4">
    <h2 class="text-center mb-4">Purchase Order</h2>
    <form action="create_pdf.php" method="post" enctype="multipart/form-data" id="invoiceForm">

        <div class="form-row mb-3">
            <div class="col">
                <label>Purchase Order No:</label>
                <input type="text" name="invoice_no" class="form-control" required>
            </div>
            <div class="col">
                <label>Date:</label>
                <input type="date" name="invoice_date" class="form-control" required>
            </div>
            <div class="col">
                <label>Upload Logo:</label>
                <input type="file" name="logo" accept="image/png, image/jpeg" class="form-control">
            </div>
        </div>

        <div class="form-row mb-3">
            <div class="col">
                <h5>Order By</h5>
                <label>Company Name:</label>
                <input type="text" name="our_name" class="form-control" value="<?= htmlspecialchars($our_name) ?>" readonly>

                <label>Company Address:</label>
                <textarea name="our_address" class="form-control" readonly><?= htmlspecialchars($our_address) ?></textarea>

                <label>Company Contact:</label>
                <input type="text" name="our_contact" class="form-control" value="<?= htmlspecialchars($our_contact) ?>" readonly>
            </div>
            <div class="col">
                <h5>Order To</h5>
                <label>Vendor Name:</label>
                <input type="text" name="vendor_name" class="form-control" required>

                <label>Vendor Address:</label>
                <textarea name="vendor_address" class="form-control" required></textarea>

                <label>Vendor Contact:</label>
                <input type="text" name="vendor_contact" class="form-control" required>
            </div>
        </div>

        <h5>Items</h5>
        <table class="table table-bordered" id="itemsTable">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="itemsBody">
                <tr>
                    <td><input type="text" name="item_name[]" class="form-control" required></td>
                    <td><input type="number" name="item_quantity[]" class="form-control qty" min="1" value="1" required></td>
                    <td><input type="number" name="item_rate[]" class="form-control rate" min="0" step="0.01" required></td>
                    <td><input type="text" name="item_total[]" class="form-control total" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary mb-3" onclick="addItem()">Add Item</button>

        <div class="form-row">
            <div class="col">
                <label>GST (%)</label>
                <input type="number" name="gst_percent" id="gstPercent" class="form-control" step="0.01">
            </div>
            <div class="col">
                <label>Discount (%)</label>
                <input type="number" name="discount_percent" id="discountPercent" class="form-control" step="0.01">
            </div>
        </div>

        <div class="form-row mt-3">
            <div class="col">
                <label>GST Amount</label>
                <input type="text" id="gstAmount" class="form-control" readonly>
            </div>
            <div class="col">
                <label>Discount Amount</label>
                <input type="text" id="discountAmount" class="form-control" readonly>
            </div>
            <div class="col">
                <label>Grand Total</label>
                <input type="text" name="grand_total" id="grandTotal" class="form-control" readonly>
            </div>
        </div>

        <div class="form-group mt-3">
            <label>Notes:</label>
            <textarea name="notes" class="form-control"></textarea>

            <label for="terms_conditions">Terms & Conditions:</label>
<textarea 
    id="terms_conditions" 
    name="terms_conditions" 
    class="form-control" 
    rows="4" 
    placeholder="Example: 
- Payment will be made 15 days after final delivery.
- Please quote invoice number when remitting funds.
- Late payments may incur a fee.">
</textarea>


            <label>Attachments:</label>
            <input type="file" name="attachments[]" multiple class="form-control">
        </div>

        <div class="form-group">
            <label>Choose Signature Method:</label>
            <select id="signature_option" name="signature_option" class="form-control" onchange="toggleSignatureMethod()" required>
                <option value="">--Select--</option>
                <option value="draw">Draw Signature</option>
                <option value="upload">Upload Signature</option>
            </select>
        </div>

        <div id="draw_section" style="display: none;">
            <label>Draw Signature:</label><br>
            <canvas id="signaturePad" width="300" height="100" style="border:1px solid #000;"></canvas><br>
            <button type="button" class="btn btn-secondary" onclick="clearSignature()">Clear</button>
            <input type="hidden" name="signature_data" id="signatureData">
        </div>

        <div id="upload_section" style="display: none;">
            <label>Upload Signature Image:</label>
            <input type="file" name="signature_image" accept="image/png, image/jpeg" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-4">Generate PDF</button>
    </form>
</div>

<script>
function toggleSignatureMethod() {
    const selected = document.getElementById('signature_option').value;
    document.getElementById('draw_section').style.display = selected === 'draw' ? 'block' : 'none';
    document.getElementById('upload_section').style.display = selected === 'upload' ? 'block' : 'none';
}

function clearSignature() {
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function addItem() {
    const row = `<tr>
        <td><input type="text" name="item_name[]" class="form-control" required></td>
        <td><input type="number" name="item_quantity[]" class="form-control qty" min="1" value="1" required></td>
        <td><input type="number" name="item_rate[]" class="form-control rate" min="0" step="0.01" required></td>
        <td><input type="text" name="item_total[]" class="form-control total" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
}

function removeRow(btn) {
    btn.closest('tr').remove();
    updateTotals();
}

function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value) || 0;
        const total = qty * rate;
        row.querySelector('.total').value = total.toFixed(2);
        subtotal += total;
    });

    const gstPercent = parseFloat(document.getElementById('gstPercent').value) || 0;
    const discountPercent = parseFloat(document.getElementById('discountPercent').value) || 0;

    const discountAmount = (subtotal * discountPercent) / 100;
    const afterDiscount = subtotal - discountAmount;
    const gstAmount = (afterDiscount * gstPercent) / 100;
    const grandTotal = afterDiscount + gstAmount;

    document.getElementById('gstAmount').value = gstAmount.toFixed(2);
    document.getElementById('discountAmount').value = discountAmount.toFixed(2);
    document.getElementById('grandTotal').value = grandTotal.toFixed(2);
}

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty') ||
        e.target.classList.contains('rate') ||
        e.target.id === 'gstPercent' ||
        e.target.id === 'discountPercent') {
        updateTotals();
    }
});

document.getElementById('invoiceForm').addEventListener('submit', function() {
    if (document.getElementById('signature_option').value === 'draw') {
        const canvas = document.getElementById('signaturePad');
        const signatureData = canvas.toDataURL('image/png');
        document.getElementById('signatureData').value = signatureData;
    }
});

(function initSignaturePad() {
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    canvas.addEventListener('mousedown', () => drawing = true);
    canvas.addEventListener('mouseup', () => {
        drawing = false;
        ctx.beginPath();
    });
    canvas.addEventListener('mouseout', () => drawing = false);
    canvas.addEventListener('mousemove', function(e) {
        if (!drawing) return;
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';
        const rect = canvas.getBoundingClientRect();
        ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
    });
})();
</script>

