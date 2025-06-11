
<?php
session_start();

if (isset($_POST['submit_review'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $review = trim($_POST['review']);

    if (!empty($name) && !empty($email) && !empty($review) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $entry = htmlspecialchars($name) . "|" . htmlspecialchars($email) . "|" . htmlspecialchars($review) . "\n";
        file_put_contents("reviews.txt", $entry, FILE_APPEND | LOCK_EX);
        $_SESSION['message'] = "Thank you for your review!";
        header("Location: " . $_SERVER['PHP_SELF'] . "#testimonials");
        exit();
    } else {
        $_SESSION['message'] = "Please enter valid details in all fields.";
        header("Location: " . $_SERVER['PHP_SELF'] . "#review-form");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Invoice Generator - Home</title>
  <style>
    /* Basic Reset & Typography */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      background: #f9f9f9;
      color: #333;
    }
    a {
      color: #0066cc;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    img {
      max-width: 100%;
      height: auto;
      display: block;
    }
    .container {
      width: 90%;
      max-width: 1200px;
      margin: auto;
    }
    /* Header & Nav */
    header {
      background: #333;
      color: white;
      padding: 15px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    nav {
      display: flex;
      justify-content: center;
      gap: 25px;
      font-weight: bold;
      font-size: 16px;
    }
    nav a {
      color: white;
      padding: 5px 10px;
      border-radius: 4px;
      transition: background 0.3s ease;
    }
    nav a:hover {
      background: #0066cc;
    }
    /* Hero Section */
    .hero {
      background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
      color: white;
      padding: 100px 20px 80px;
      text-align: center;
      position: relative;
    }
    .hero::after {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 0;
    }
    .hero .container {
      position: relative;
      z-index: 1;
      max-width: 700px;
    }
    .hero h1 {
      font-size: 3rem;
      margin-bottom: 20px;
    }
    .hero p {
      font-size: 1.25rem;
      margin-bottom: 40px;
    }
    .btn {
      background: #0066cc;
      color: white;
      padding: 15px 35px;
      border: none;
      border-radius: 30px;
      font-size: 1.2rem;
      cursor: pointer;
      transition: background 0.3s ease;
      display: inline-block;
      text-align: center;
      text-decoration: none;
    }
    .btn:hover {
      background: #004999;
    }
    /* Sections */
    section {
      padding: 60px 20px;
      background: white;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 2rem;
      color: #222;
    }
    /* Features Grid */
    .features {
    padding: 60px 20px;
    background: linear-gradient(to right, #f8fbff, #eef5ff);
  }

  .features h2 {
    text-align: center;
    font-size: 2.8rem;
    margin-bottom: 40px;
    color: #00264d;
    font-weight: bold;
  }

 .features-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* 2 items per row */
  gap: 30px;
  max-width: 1000px;
  margin: 0 auto;
  padding: 0 15px;
}

  .feature {
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation: fadeInUp 0.6s ease forwards;
  }

  .feature:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
  }

  .feature img {
    width: 60px;
    margin-bottom: 20px;
  }

  .feature h3 {
    margin-bottom: 10px;
    font-size: 1.3rem;
    color: #003366;
  }

  .feature p {
    font-size: 1rem;
    color: #444;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

   

    /* Testimonials */
.testimonials {
  padding: 60px 0;
  background: #f5faff; /* optional background */
  text-align: center;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  box-sizing: border-box;
}

.testimonial-cards {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1.2fr));
  gap: 30px;
  width: 100%;
  margin-top: 30px;
}


.testimonial {
  background: #e9f0ff;
  padding: 30px 30px 30px 60px;
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  font-style: italic;
  color: #333;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-sizing: border-box;
}

.testimonial:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.testimonial::before {
  content: "“";
  font-size: 4rem;
  color: #0066cc;
  position: absolute;
  top: 15px;
  left: 20px;
  font-family: serif;
  opacity: 0.15;
  user-select: none;
}

.testimonial h4 {
  margin-top: 20px;
  font-weight: bold;
  color: #004080;
  text-align: right;
  font-style: normal;
}
 /* FAQ */
    .faq {
  padding: 60px 20px;
  background: linear-gradient(to right, #f8fbff, #eaf2ff);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.faq h2 {
  text-align: center;
  font-size: 2.5rem;
  margin-bottom: 40px;
  color: #003366;
}

.container {
  max-width: 800px;
  margin: 0 auto;
}

.faq-item {
  background: #ffffff;
  border-radius: 8px;
  margin-bottom: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: box-shadow 0.3s ease;
  border-left: 5px solid transparent;
  cursor: pointer;
}

.faq-item:hover {
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  border-left: 5px solid #0066cc;
}

.faq-item h3 {
  margin: 0;
  padding: 18px 20px;
  font-size: 1.2rem;
  color: #004080;
  position: relative;
}

.faq-item h3::after {
  content: "+";
  position: absolute;
  right: 20px;
  font-size: 1.5rem;
  transition: transform 0.3s ease;
}

.faq-item.active h3::after {
  content: "–";
  transform: rotate(180deg);
}

.faq-answer {
  max-height: 0;
  overflow: hidden;
  padding: 0 20px;
  font-size: 1rem;
  color: #555;
  transition: max-height 0.4s ease, padding 0.4s ease;
}

.faq-item.active .faq-answer {
  max-height: 200px;
  padding: 15px 20px 20px;
}

    /* Team */
    .team-members {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 40px;
    }
    .member {
      max-width: 200px;
      text-align: center;
    }
    .member img {
      border-radius: 50%;
      margin-bottom: 12px;
      width: 150px;
      height: 150px;
      object-fit: cover;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .member h4 {
      margin-bottom: 5px;
      color: #004080;
    }
    /* Contact */
    form {
      max-width: 600px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    label {
      font-weight: bold;
      color: #004080;
    }
    input[type="text"],
    input[type="email"],
    textarea {
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      resize: vertical;
      width: 100%;
    }
    textarea {
      min-height: 100px;
    }
    .submit-btn {
      background: #0066cc;
      color: white;
      padding: 15px;
      font-size: 1.1rem;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .submit-btn:hover {
      background: #004999;
    }
    /* Footer */
    footer {
      background: #222;
      color: #ddd;
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
    }
    .socials {
      margin-top: 10px;
    }
    .socials a {
      color: #ddd;
      margin: 0 8px;
      font-size: 1.2rem;
      transition: color 0.3s ease;
      text-decoration: none;
    }
    .socials a:hover {
      color: #0066cc;
    }
    /* Responsive adjustments */
    @media (max-width: 768px) {
      nav {
        flex-wrap: wrap;
        gap: 10px;
      }
      .pricing-cards, .testimonial-cards, .team-members {
        flex-direction: column;
        align-items: center;
      }
    }
 .review-form {
  padding: 20px 10px; /* less padding around the section */
  background: #f0f8ff;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  text-align: center;
}

.review-form h2 {
  font-size: 1.6rem;
  margin-bottom: 10px;
  color: #003366;
}

.review-message {
  text-align: center;
  color: green;
  font-weight: bold;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.review-form-inner {
  background: #ffffff;
  max-width: 650px;
  margin: 0 auto;
  padding: 15px 20px; /* reduced padding */
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.04);
  text-align: left;
}

.review-form-inner label {
  font-weight: 600;
  font-size: 0.9rem;
  margin-bottom: 4px;
  display: block;
  color: #004080;
}

.review-form-inner input[type="text"],
.review-form-inner input[type="email"],
.review-form-inner textarea {
  width: 100%;
  padding: 6px 10px; /* tighter padding */
  font-size: 0.9rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 10px; /* less spacing */
  box-sizing: border-box;
}

.review-form-inner textarea {
  resize: vertical;
  min-height: 60px; /* shorter height */
}

.review-form-inner button[type="submit"] {
  background: #0066cc;
  color: white;
  padding: 8px;
  font-size: 0.95rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  transition: background 0.3s ease;
}

.review-form-inner button[type="submit"]:hover {
  background: #004999;
}


  </style>
  <!-- FontAwesome for social icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<header>
  <nav>
    <div class="nav-left">
      <a href="#features">Features</a>
      <a href="#testimonials">Testimonials</a>
      <a href="#team">Team</a>
      <a href="#faq">FAQ</a>
      <a href="#contact">Contact</a>
      <a href="generate_invoice.php">Create Purchase Order</a>
    </div>
    <div class="nav-right">
      <?php if (!empty($_SESSION['user'])): ?>
        <a href="account.php">Account</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </nav>
</header>


  <section class="hero">
    <div class="container">
      <h1>Welcome to Invoice Generator</h1>
      <p>Create professional invoices easily and quickly.</p>
      <a href="http://localhost/invoice-generator/generate_invoice.php" class="btn">Create Purchase Order</a>
    </div>
  </section>
<section id="features" class="features">
  <h2>Features</h2>
  <div class="features-grid container">
    <div class="feature">
      <img src="https://img.icons8.com/ios-filled/100/0066cc/document--v1.png" alt="Easy to Use Icon" />
      <h3>Easy to Use</h3>
      <p>Generate invoices and purchase orders quickly with minimal effort.</p>
    </div>
    <div class="feature">
      <img src="https://img.icons8.com/ios-filled/100/0066cc/automatic.png" alt="Automation Icon" />
      <h3>Smart Automation</h3>
      <p>Auto-calculations for taxes, totals, and discounts—no manual work.</p>
    </div>
    <div class="feature">
      <img src="https://img.icons8.com/ios-filled/100/0066cc/save-as.png" alt="Save and Export Icon" />
      <h3>Save & Export</h3>
      <p>Download your invoices as professionally formatted PDF files.</p>
    </div>
    <div class="feature">
      <img src="https://img.icons8.com/ios-filled/100/0066cc/signature.png" alt="Digital Signature Icon" />
      <h3>Digital Signature</h3>
      <p>Securely sign your documents with uploaded or drawn signatures.</p>
    </div>
  </div>
</section>

 <section id="testimonials" class="testimonials">
  <h2>What Our Users Say</h2>
  <div class="container">
    <div class="testimonial-cards">
    <?php
      if (file_exists("reviews.txt")) {
          $reviews = file("reviews.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
          $latest = array_slice(array_reverse($reviews), 0, 3); // Show latest 3
          foreach ($latest as $line) {
              list($r_name, $r_email, $r_review) = explode('|', $line);
              echo '<div class="testimonial">';
              echo '<p>"' . htmlspecialchars($r_review) . '"</p>';
              echo '<h4>- ' . htmlspecialchars($r_name) . '</h4>';
              echo '</div>';
          }
      }
      ?>
    </div>
    <?php
$current_url = strtok($_SERVER["REQUEST_URI"], '?'); // Strip query params
$show_form = isset($_GET['show_review_form']) && $_GET['show_review_form'] == '1';
$toggle_link = $show_form
    ? $current_url . '#review-form'
    : $current_url . '?show_review_form=1#review-form';
?>

<div style="text-align: center; margin-top: 30px;">
  <a href="<?= htmlspecialchars($toggle_link) ?>" class="btn">
    <?= $show_form ? 'Hide Review Form' : 'Leave a Review' ?>
  </a>
</div>


  </div>
</section>
<?php
$show_form = isset($_GET['show_review_form']) && $_GET['show_review_form'] == '1';
?>
<?php
$show_form = isset($_GET['show_review_form']) && $_GET['show_review_form'] == '1';
?>
<section id="review-form" class="review-form" style="<?= $show_form ? '' : 'display: none;' ?>">
  <h2>Write a Review</h2>

  <?php
  if (!empty($_SESSION['message'])) {
      echo "<p class='review-message'>" . $_SESSION['message'] . "</p>";
      unset($_SESSION['message']);
  }
  ?>

  <form action="" method="post" class="review-form-inner">
    <label for="name">Name:</label>
    <input type="text" name="name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="review">Review:</label>
    <textarea name="review" rows="3" required></textarea>

    <button type="submit" name="submit_review">Submit Review</button>
  </form>
</section>






<section id="team" class="team">
  <h2>Meet The Team</h2>
  <div class="team-members container">
    <div class="member">
      <img src="https://img.icons8.com/clouds/100/000000/user-male-circle.png" alt="ABC" />
      <h4>ABC</h4>
      <p>Founder & CEO</p>
    </div>
    <div class="member">
      <img src="https://img.icons8.com/clouds/100/000000/user-male-circle.png" alt="ABC" />
      <h4>ABC</h4>
      <p>Lead Developer</p>
    </div>
    <div class="member">
      <img src="https://img.icons8.com/clouds/100/000000/user-male-circle.png" alt="ABC" />
      <h4>ABC</h4>
      <p>UI/UX Designer</p>
    </div>
  </div>
</section>

  <section id="faq" class="faq">
    <h2>Frequently Asked Questions</h2>
    <div class="container">
      <div class="faq-item">
        <h3>Is it free to use?</h3>
        <div class="faq-answer">
          <p>Yes, the basic plan is completely free to use with limited monthly invoices.</p>
        </div>
      </div>
      <div class="faq-item">
        <h3>Can I export invoices as PDF?</h3>
        <div class="faq-answer">
          <p>Yes, all invoices and purchase orders can be downloaded as PDFs.</p>
        </div>
      </div>
      <div class="faq-item">
        <h3>Does it support digital signatures?</h3>
        <div class="faq-answer">
          <p>Yes, you can upload or draw your signature for each invoice.</p>
        </div>
      </div>
      <div class="faq-item">
  <h3>Can I customize my invoice template?</h3>
  <div class="faq-answer">
    <p>Yes, you can add your logo and company details to match your brand.</p>
  </div>
</div>

<div class="faq-item">
  <h3>Can I include taxes and discounts?</h3>
  <div class="faq-answer">
    <p>Yes, our system allows you to add GST as well as percentage or flat-rate discounts.</p>
  </div>
</div>


<div class="faq-item">
  <h3>Can I send invoices directly to clients?</h3>
  <div class="faq-answer">
    <p>Yes, after generating an invoice, you can directly send the downloaded PDF.</p>
  </div>
</div>

    </div>
  </section>

  <section id="contact" class="contact">
    <h2>Contact Us</h2>
    <form action="#" method="POST" class="container" onsubmit="return false;">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" placeholder="Your full name" required />

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Your email address" required />

      <label for="message">Message</label>
      <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>

      <button type="submit" class="submit-btn">Send Message</button>
    </form>
  </section>

<style>
  footer {
    background: #222;
    color: #ccc;
    padding: 60px 20px 40px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  footer .container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 40px;
  }

  footer .footer-section {
    flex: 1;
    min-width: 180px;
  }

  footer h3 {
    color: #fff;
    margin-bottom: 20px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }

  footer ul {
    list-style: none;
    padding: 0;
    font-size: 1rem;
    line-height: 1.8;
  }

  footer ul li {
    margin-bottom: 10px;
  }

  footer ul li a {
    color: #bbb;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  footer ul li a:hover {
    color: #4CAF50;
  }

  /* Special style for Create Purchase Order button */
  footer .btn-purchase-order {
    display: inline-block;
    background-color: #4CAF50;
    color: white !important;
    padding: 10px 18px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 3px 6px rgba(76, 175, 80, 0.4);
  }

  footer .btn-purchase-order:hover {
    background-color: #45a049;
    box-shadow: 0 5px 12px rgba(69, 160, 73, 0.6);
  }

  /* Responsive tweak for smaller screens */
  @media (max-width: 768px) {
    footer .container {
      flex-direction: column;
      gap: 30px;
    }
    footer .footer-section {
      min-width: 100%;
    }
  }
</style>
<footer>
  <div class="container" style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 40px; padding: 40px 20px; color: #ccc;">
    
    <!-- Company Info -->
    <div style="flex: 1 1 250px;">
      <h3 style="color: #fff; margin-bottom: 15px;">Invoice Generator</h3>
      <p>Empowering small businesses and freelancers to create professional purchase orders and invoices with ease.</p>
    </div>

    <!-- Quick Links -->
  <div style="flex: 1 1 150px;">
  <h4 style="color: #fff; margin-bottom: 10px;">Quick Links</h4>
  <ul style="list-style: none; padding: 0; margin: 0;">
    <li style="margin-bottom: 8px;"><a href="#features" style="color: #ccc; text-decoration: none;">Features</a></li>
    <li style="margin-bottom: 8px;"><a href="#testimonials" style="color: #ccc; text-decoration: none;">Testimonials</a></li>
    <li><a href="#faq" style="color: #ccc; text-decoration: none;">FAQ</a></li>
  </ul>
</div>


    <!-- Contact Info -->
    <div style="flex: 1 1 200px;">
      <h4 style="color: #fff; margin-bottom: 10px;">Contact Us</h4>
      <p><i class="fas fa-map-marker-alt"></i> Amravati, Maharashtra, India</p>
      <p><i class="fas fa-envelope"></i> support@invoicegen.com</p>
      <p><i class="fas fa-phone"></i> +91 98765 43210</p>
    </div>
  </div>

  <!-- Action Button -->
  <div style="text-align: center; margin-top: 20px;">
    <a href="generate_invoice.php" style="display: inline-block; background-color: #00b894; color: #fff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold;">
      Create Purchase Order
    </a>
  </div>

  <!-- Bottom Bar -->
  <div style="text-align: center; border-top: 1px solid #444; padding: 15px 20px; margin-top: 30px;">
    <p style="margin: 0;"><Strong> Smart invoicing for growing businesses</Strong> <br> Automate your billing, reduce errors, and get paid faster with intelligent invoicing tailored for your growth.</p>
  </div>
</footer>

  <script>
    // FAQ accordion toggle
    document.querySelectorAll('.faq-item').forEach(item => {
      item.addEventListener('click', () => {
        item.classList.toggle('active');
      });
    });
  </script>
  
</body>
</html>