<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Store Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
       <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; color: #333; }
        header { background: linear-gradient(to right, #1E3A8A, #2563EB); padding: 1rem; color: white; }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-links a:hover { color: #FACC15; }
        
        
       
       
        }
        .search-container button:hover { background: #FFA500; }

        footer {
            background: #1E3A8A;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 18px;
            margin-top: 20px;
        }
        .footer-links a {
            color: #FFD700;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
        }
        .footer-links a:hover { text-decoration: underline; }
    </style>
    
    <header>
    <nav class="flex justify-between items-center px-6 py-3 text-white">
        <div class="text-2xl font-bold">
            <i class="fa-solid fa-prescription-bottle-medical"></i> MEDICAL STORE
        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="category.php">Category</a>
            <a href="cart.php">Cart</a>
            <a href="contact_us.php">Contact Us</a>
            <a href="feedback.php">Feedback</a>
            <!--<?php if ($isLoggedIn): ?>-->
               <!-- <a href="logout.php" style="color: #FFD700;">Logout</a>-->
           <!--  <?php else: ?>-->
                <a href="login.php" style="color: #FFD700;">Login</a>
                <a href="register.php" style="color:#FFD700;">Register</a>
           <!--  <?php endif; ?>-->
        </div>
    </nav>
</header>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("active");
                    }
                });
            }, { threshold: 0.5 });

            document.querySelectorAll(".fade-in-left").forEach(element => {
                observer.observe(element);
            });
        });
    </script>
</head>


    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold text-center mb-8">How Medical Store Management System Works</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="bg-white shadow-lg p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Step 1: Register</h2>
                <p class="text-gray-600">Sign up as a user or admin to access the access medical management system.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white shadow-lg p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Step 2: Overview</h2>
                <p class="text-gray-600">After successfully registering and signing in, take the overview of the system
                    by navigating to different pages.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white shadow-lg p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Step 3: Select Medicine</h2>
                <p class="text-gray-600">Choose from various medicine that you need.</p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white shadow-lg p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Step 4: Entering Details</h2>
                <p class="text-gray-600">After selecting medicine, enter your data to proceed to purchase medicine.</p>
            </div>

            <!-- Step 5 -->
            <div class="bg-white shadow-lg p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Step 5: Make Payment (offline)</h2>
                <p class="text-gray-600">Complete payment via offline methods by visiting medical store.</p>
            </div>

            <!-- Step 6 -->
            <div class="bg-white shadow-lg p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Step 6: Account Activation</h2>
                <p class="text-gray-600">After successfully completing offline payment, the admin will update your
                    payment status .</p>
            </div>


            <!-- Step 7 -->
            <div class="bg-white shadow-lg p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Step 7: Get Support</h2>
                <p class="text-gray-600">Contact medical store admins for guidance and assistance.</p>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-12">
            <h2 class="text-3xl font-bold text-center mb-6">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <div class="bg-white shadow p-4 rounded-lg">
                    <h3 class="font-semibold">1. How do I reset my password?</h3>
                    <p class="text-gray-600">Click on the 'Forgot Password' button and fill in the same email that you
                        entered while registering your account.</p>
                </div>
               
                <div class="bg-white shadow p-4 rounded-lg">
                    <h3 class="font-semibold">2. How do I contact ?</h3>
                    <p class="text-gray-600"> Go to the Contact page and fill in the form. The
                        admin will contact you back with further details</p>
                </div>
                <div class="bg-white shadow p-4 rounded-lg">
                    <h3 class="font-semibold">3. What payment methods are available?</h3>
                    <p class="text-gray-600">Currently, only offline payment method is available. After successfully
                        purchasing a medicine select cod and make offline payment on cash on delivery.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
    <p>&copy; <?php echo date("Y"); ?> Medical Store. All rights reserved.</p>
    <p>Contact us at <a href="mailto:support@medicalstore.com">support@medicalstore.com</a></p>
    <div class="footer-links">
        <a href="about.php">About</a>
        <a href="contact_us.php">Contact Us</a>
    </div>
</footer>
</body>

</html>