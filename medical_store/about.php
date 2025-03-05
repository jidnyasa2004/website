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

    <!-- Main Content -->
    <div class="content p-6">
        <h2 class="text-3xl font-bold">About Us</h2>

        <div class="mt-6 custom-card p-6">
            <h3 class="text-2xl font-bold text-gray-700">Welcome to Our Medical Store</h3>
            <p class="mt-2 text-gray-600">
                We are committed to providing high-quality medicines and healthcare products to our customers. 
                Our mission is to ensure accessibility to essential medicines at affordable prices.
            </p>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Our Mission -->
            <div class="custom-card p-6">
                <h3 class="text-xl font-bold text-gray-700">Our Mission</h3>
                <p class="mt-2 text-gray-600">
                    To enhance healthcare accessibility by delivering safe, effective, and high-quality medications 
                    at competitive prices.
                </p>
            </div>

            <!-- Our Vision -->
            <div class="custom-card p-6">
                <h3 class="text-xl font-bold text-gray-700">Our Vision</h3>
                <p class="mt-2 text-gray-600">
                    To be the most trusted and customer-centric medical store, ensuring health and wellness for all.
                </p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Services -->
            <div class="custom-card p-6">
                <h3 class="text-xl font-bold text-gray-700">Our Services</h3>
                <ul class="mt-2 text-gray-600 list-disc list-inside">
                    <li>Prescription Medicines</li>
                    <li>Over-the-Counter Drugs</li>
                    <li>Health & Wellness Products</li>
                    <li>Online Medicine Ordering</li>
                    <li>Home Delivery Services</li>
                </ul>
            </div>

            <!-- Contact Us -->
            <div class="custom-card p-6">
                <h3 class="text-xl font-bold text-gray-700">Contact Us</h3>
                <p class="mt-2 text-gray-600"><strong>Email:</strong> support@medicalstore.com</p>
                <p class="mt-2 text-gray-600"><strong>Phone:</strong> +91 90705 00000</p>
                <p class="mt-2 text-gray-600"><strong>Address:</strong> 123, Main Street, New Delhi, India</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <a href="homenew.php">🏠 Back to Homepage</a>
    </footer>

</body>
</html>
