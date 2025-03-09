<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Medical Store</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .custom-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            text-align: center;
        }

        .content {
            flex: 1;
        }

        .footer {
            background: #1E3A8A;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
            font-size: 18px;
        }

        .footer a {
            color: #FFD700;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .footer a:hover {
            color: #FFA500;
        }
    </style>
</head>
<body>

    <!-- About Us Section -->
    <div class="custom-card">
        <h1 class="text-2xl font-bold">About Us</h1>
        <p>Welcome to <strong>Medical Store</strong>, your trusted source for quality medicines and healthcare products. We are committed to providing the best healthcare solutions at affordable prices.</p>
        <p>Our mission is to ensure easy access to essential medicines while maintaining top-notch service and customer satisfaction.</p>
    </div>

    <!-- Contact Us Section -->
    <div class="custom-card">
        <h2 class="text-2xl font-bold">Get in Touch with Us!</h2>
        <p>We’re here to help! Whether you have questions about our products, need assistance with an order, or just want to share your feedback, feel free to reach out to us.</p>
        <div class="mt-4">
            <p>📍 <strong>Address:</strong> Mulund</p>
            <p>📞 <strong>Phone:</strong> 9000011111</p>
            <p>📧 <strong>Email:</strong> medicalstore@gmail.com</p>
        </div>
        <p class="mt-4">Fill out the form below, and our team will get back to you as soon as possible. Your satisfaction is our priority!</p>
        <p><strong>Thank you for choosing Medical Store!</strong></p>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <a href="homenew.php">🏠 Back to Homepage</a>
    </footer>

</body>
</html>