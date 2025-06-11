# invoice-generator
# 🧾 Invoice Generator using PHP & FPDF

This project is a professional **Invoice Generator** built with PHP, HTML, CSS, JavaScript, and the FPDF library. It allows users to dynamically create and download customized invoices in PDF format — complete with itemized entries, tax/discount handling, company logos, and digital signatures.

## ✨ Features

- 🧠 Dynamic form with add/remove item rows
- 📅 Auto-generated invoice date and number
- 📤 Upload logo and signature for personalization
- 📊 Auto-calculation of subtotal, tax, discount, and total
- 📄 Professionally formatted PDF output using FPDF
- 📁 Clean and organized file structure
- 💬 Error handling and form validation


## 🚀 Technologies Used

- **Frontend**: HTML, CSS (Bootstrap), JavaScript
- **Backend**: PHP
- **PDF Generation**: [FPDF Library](http://www.fpdf.org/)
- **Other Tools**: File Upload, Session Management


## 📁 File Structure

/invoice-generator/<br>
│
├── fpdf/ # FPDF library files<br>
├── uploads/ # Stores uploaded logos and signatures<br>
├── create_pdf.php # PHP file to generate and download PDF<br>
├── genrate_invoice.php # Form interface and data submission<br>
├── style.css # Optional custom styling<br>
└── README.md # Project documentation<br>

⚙️ How to Use<br>
1.Start a local server (e.g., XAMPP or WAMP), place the project in your htdocs directory.<br>
2.Open http://localhost/invoice-generator/genrate_invoice.php in your browser.<br>
3.Fill in the invoice details, upload logo/signature, add items dynamically.<br>
4.Click on Generate PDF — your invoice will be downloaded instantly.<br>
