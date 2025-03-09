<?php
// Include connection file
include 'connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch user information
$email = $_SESSION['email'];
$sql_user = "SELECT u_id, email, username FROM credential WHERE email = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("s", $email);
$stmt->execute();
$result_user = $stmt->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $_SESSION['u_id'] = $user['u_id']; // Store u_id in session
    $_SESSION['usrname'] = $user['username']; // Store username if needed
}
$stmt->close();

// Fetch medicines from database
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql_medicines = $search_query ?
    "SELECT name, description, med_image, price FROM medicine WHERE name LIKE '%$search_query%'" :
    "SELECT name, description, med_image, price FROM medicine";
$result_medicines = $conn->query($sql_medicines);
$medicines = $result_medicines->fetch_all(MYSQLI_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Store</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
            color: #333;
        }

        header {
            background: linear-gradient(to right, #1E3A8A, #2563EB);
            padding: 1rem;
            color: white;
        }

        .profile-menu {
            display: none;
            position: absolute;
            right: 20px;
            top: 60px;
            background: #1E3A8A;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            z-index: 1000;
            color: white;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-menu a {
            display: block;
            color: #FACC15;
            text-decoration: none;
            font-weight: 500;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            padding: 1rem;
        }

        .product-card {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: scale(1.03);
        }

        .btn {
            padding: 0.5rem 1rem;
            background: linear-gradient(to right, #2563EB, #1E3A8A);
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        footer {
            background: #1E3A8A;
            color: white;
            text-align: center;
            padding: 1.5rem;
        }
    </style>
</head>

<body>

    <header>
        <nav class="flex justify-between items-center px-6 py-3 text-white">
            <div class="text-2xl font-bold">
                <i class="fa-solid fa-prescription-bottle-medical"></i> MEDICAL STORE
            </div>

            <div class="flex items-center space-x-6">
                <a href="homenew.php"><i class="fa-solid fa-house mr-2"></i> Home</a>
                <a href="category.php"><i class="fa-solid fa-th-large mr-2"></i> Category</a>
                <a href="cart.php"><i class="fa-solid fa-cart-shopping mr-2"></i> Cart</a>
                <a href="invoice1.php"><i class="fa-solid fa-file-invoice-dollar mr-2"></i> Invoice</a>
                <a href="contact_us.php"><i class="fa-solid fa-envelope mr-2"></i> Contact Us</a>
                <a href="feedback.php"><i class="fa-solid fa-comment-dots mr-2"></i> Feedback</a>
                <!-- Profile Icon -->
                <div class="relative">
                    <button id="profileToggle" class="flex items-center space-x-2 text-white focus:outline-none">
                        <i class="fas fa-user-circle text-3xl"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileDropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-gray-700 rounded-lg shadow-lg">
                        <a href="profile.php" class="block px-4 py-2 text-white hover:bg-gray-600">View Profile</a>
                       
                        <a href="logout.php" class="block px-4 py-2 text-white hover:bg-red-600">Logout</a>
                    </div>
                </div>
            </div>

        </nav>
    </header>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const profileToggle = document.getElementById("profileToggle");
            const profileDropdown = document.getElementById("profileDropdown");

            profileToggle.addEventListener("click", function (event) {
                event.stopPropagation();
                profileDropdown.classList.toggle("hidden");
            });

            document.addEventListener("click", function (event) {
                if (!profileToggle.contains(event.target)) {
                    profileDropdown.classList.add("hidden");
                }
            });
        });
    </script>

    <main>
        <div class="flex justify-center my-4">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search for medicines..."
                    class="border px-3 py-2 rounded-lg" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Search</button>
            </form>
        </div>

        <div class="product-grid">
            <?php foreach ($medicines as $medicine): ?>
                <div class="product-card">
                    <div class="w-full h-40 flex justify-center items-center">
                        <img src="<?php echo htmlspecialchars($medicine['med_image']); ?>"
                            class="max-h-full max-w-full rounded-lg">
                    </div>
                    <h2 class="font-bold text-lg"><?php echo htmlspecialchars($medicine['name']); ?></h2>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($medicine['description']); ?></p>
                    <p class="text-blue-600 font-bold">₹ <?php echo htmlspecialchars($medicine['price']); ?></p>
                    <button class="btn add-to-cart" data-name="<?php echo htmlspecialchars($medicine['name']); ?>"
                        data-price="<?php echo htmlspecialchars($medicine['price']); ?>">
                        Add to Cart
                    </button>

                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Medical Store. All rights reserved.</p>
        <p>Contact us at <a href="mailto:support@medicalstore.com" class="text-yellow-300">medicalstore@gmail.com</a>
        </p>
        <p>We provide the best quality medicines at affordable prices.</p>
        <div>
            <?php if (!isset($_SESSION['usrname'])): ?>
                <a href="login.php" class="text-yellow-300">Login</a>
            <?php endif; ?>
            <a href="about.php" class="text-yellow-300">About</a>
            <a href="contact_us.php" class="text-yellow-300">Contact Us</a>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function () {
                const med_name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');

                // Send the medicine details to add_to_cart.php
                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: med_name, price: price })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'no_customer') {
                            // Show SweetAlert2 error and redirect after 2 seconds
                            Swal.fire({
                                icon: 'error',
                                title: 'No Customer Data Found!',
                                text: 'Please add your details to continue.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            setTimeout(() => {
                                window.location.href = 'add_details.php';
                            }, 2000);
                        } else if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Added to Cart!',
                                text: 'Item added successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Something went wrong.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        console.error('Error:', error);
                    });
            });
        });
    </script>


</body>

</html>