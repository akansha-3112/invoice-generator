<?php
ob_start(); // Start output buffering

require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Input values
    $invoice_no = htmlspecialchars($_POST['invoice_no']);
    $invoice_date = htmlspecialchars($_POST['invoice_date']);
    $vendor_name = htmlspecialchars($_POST['vendor_name']);
    $vendor_address = htmlspecialchars($_POST['vendor_address']);
    $vendor_contact = htmlspecialchars($_POST['vendor_contact']);
    $our_name = htmlspecialchars($_POST['our_name']);
    $our_address = htmlspecialchars($_POST['our_address']);
    $our_contact = htmlspecialchars($_POST['our_contact']);
    $item_names = $_POST['item_name'];
    $item_quantities = $_POST['item_quantity'];
    $item_rates = $_POST['item_rate'];
    $item_totals = $_POST['item_total'];

    $subtotal = array_sum($item_totals);
    $discount_percent = isset($_POST['discount_percent']) ? floatval($_POST['discount_percent']) : 0;
    $gst_percent = isset($_POST['gst_percent']) ? floatval($_POST['gst_percent']) : 0;

    $discount_amount = round($subtotal * ($discount_percent / 100), 2);
    $after_discount = round($subtotal - $discount_amount, 2);
    $gst_amount = round($after_discount * ($gst_percent / 100), 2);
    $grand_total = round($after_discount + $gst_amount, 2);

    $notes = htmlspecialchars($_POST['notes']);
    $terms = htmlspecialchars($_POST['terms_conditions']);

    // Handle logo upload
    $logo_path = '';
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $file_type = mime_content_type($_FILES['logo']['tmp_name']);
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);

        $ext = $file_type == 'image/png' ? '.png' : ($file_type == 'image/jpeg' ? '.jpg' : '');
        if ($ext) {
            $logo_name = 'logo_uploaded' . $ext;
            $target_path = 'uploads/' . $logo_name;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                $logo_path = $target_path;
            } else {
                echo "Error uploading logo.";
                exit;
            }
        } else {
            echo "Invalid logo file type. Please upload a PNG or JPEG image.";
            exit;
        }
    }

    // Handle signature upload
    $signature_path = '';
    if (isset($_FILES['signature_image']) && $_FILES['signature_image']['error'] == 0) {
        $file_type = mime_content_type($_FILES['signature_image']['tmp_name']);
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);

        if (in_array($file_type, ['image/png', 'image/jpeg'])) {
            $ext = ($file_type == 'image/png') ? '.png' : '.jpg';
            $signature_path = 'uploads/uploaded_signature' . $ext;
            if (!move_uploaded_file($_FILES['signature_image']['tmp_name'], $signature_path)) {
                echo "Error uploading signature.";
                exit;
            }
        } else {
            echo "Invalid signature file type. Please upload a PNG or JPEG image.";
            exit;
        }
    }

    // Handle drawn signature
    if (empty($signature_path) && !empty($_POST['signature_data'])) {
        $signature_data = $_POST['signature_data'];
        $signature_data = str_replace('data:image/png;base64,', '', $signature_data);
        $signature_data = base64_decode($signature_data);
        $signature_path = 'uploads/signature_drawn.png';
        if (!file_put_contents($signature_path, $signature_data)) {
            echo "Error saving the signature.";
            exit;
        }
    }

    // Initialize PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Rect(5, 5, 200, 287);

    // Title
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 10, 'INVOICE', 0, 1, 'C');
    $pdf->Ln(5);

    // Display Logo
    if ($logo_path && file_exists($logo_path)) {
        $pdf->Image($logo_path, 10, $pdf->GetY(), 30);
    }

    // Invoice Details
    $pdf->SetXY(130, $pdf->GetY());
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 6, "Invoice No: $invoice_no", 0, 2, 'R');
    $pdf->Cell(0, 6, "Invoice Date: $invoice_date", 0, 2, 'R');
    $pdf->Ln(10);

    // Vendor and Company Info
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 6, 'Vendor Information', 0, 0);
    $pdf->Cell(95, 6, 'Our Company Info', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 6, $vendor_name, 0, 0);
    $pdf->Cell(95, 6, $our_name, 0, 1, 'R');
    $pdf->Cell(95, 6, $vendor_address, 0, 0);
    $pdf->Cell(95, 6, $our_address, 0, 1, 'R');
    $pdf->Cell(95, 6, $vendor_contact, 0, 0);
    $pdf->Cell(95, 6, $our_contact, 0, 1, 'R');
    $pdf->Ln(10);

    // Table column widths
    $col_item_name = 60;
    $col_quantity = 30;
    $col_rate = 40;
    $col_total = 50;

    // Calculate margin to center the table horizontally on A4 page width 210mm
    $total_table_width = $col_item_name + $col_quantity + $col_rate + $col_total;
    $marginX = (210 - $total_table_width) / 2;

    // Table Headers
    $pdf->SetFillColor(220, 220, 220);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(50, 50, 100);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetX($marginX);
    $pdf->Cell($col_item_name, 10, 'Item Name', 1, 0, 'C', true);
    $pdf->Cell($col_quantity, 10, 'Quantity', 1, 0, 'C', true);
    $pdf->Cell($col_rate, 10, 'Rate', 1, 0, 'C', true);
    $pdf->Cell($col_total, 10, 'Total', 1, 1, 'C', true);

    // Table Data
    $pdf->SetFont('Arial', '', 12);
    foreach ($item_names as $i => $item_name) {
        $pdf->SetX($marginX);
        $pdf->Cell($col_item_name, 10, $item_name, 1);
        $pdf->Cell($col_quantity, 10, $item_quantities[$i], 1, 0, 'C');
        $pdf->Cell($col_rate, 10, 'Rs. ' . number_format((float)$item_rates[$i], 2), 1, 0, 'C');
        $pdf->Cell($col_total, 10, 'Rs. ' . number_format((float)$item_totals[$i], 2), 1, 1, 'C');
    }

    // Totals
    $pdf->SetX($marginX);
    $pdf->Cell($col_item_name + $col_quantity + $col_rate, 10, 'Subtotal', 1);
    $pdf->Cell($col_total, 10, 'Rs. ' . number_format($subtotal, 2), 1, 1, 'C');

    if ($discount_percent > 0) {
        $pdf->SetX($marginX);
        $pdf->Cell($col_item_name + $col_quantity + $col_rate, 10, "Discount ({$discount_percent}%)", 1);
        $pdf->Cell($col_total, 10, '- Rs. ' . number_format($discount_amount, 2), 1, 1, 'C');
    }

    if ($gst_percent > 0) {
        $pdf->SetX($marginX);
        $pdf->Cell($col_item_name + $col_quantity + $col_rate, 10, "GST ({$gst_percent}%)", 1);
        $pdf->Cell($col_total, 10, 'Rs. ' . number_format($gst_amount, 2), 1, 1, 'C');
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetX($marginX);
    $pdf->Cell($col_item_name + $col_quantity + $col_rate, 10, 'Grand Total', 1);
    $pdf->Cell($col_total, 10, 'Rs. ' . number_format($grand_total, 2), 1, 1, 'C');

    // Notes and Terms
    if (!empty(trim($notes))) {
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Notes:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 6, $notes);
    }

    if (!empty(trim($terms))) {
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Terms & Conditions:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 6, $terms);
    }

    // Signature
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 6, 'Authorized Signature:', 0, 1, 'R');
    if ($signature_path && file_exists($signature_path)) {
        $signatureWidth = 50;
        $x = 210 - $signatureWidth - 10;
        $pdf->Image($signature_path, $x, $pdf->GetY(), $signatureWidth);
    }

    ob_clean(); // Clean any accidental output
    $pdf->Output('I', 'Invoice_' . $invoice_no . '.pdf');
    exit;
}

ob_end_flush(); // End buffering
?>
