<?php
session_start();
include 'connection.php';

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$usrname = $_SESSION['usrname'];
$usrname_safe = $conn->real_escape_string($usrname);

// Fetch user details
$sql_user = "SELECT u_id FROM credential WHERE username='$usrname_safe'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();
$u_id = $user['u_id'];

// Store u_id in session
$_SESSION['u_id'] = $u_id;

// Handle cart updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cart_id']) && isset($_POST['action'])) {
        $cart_id = $_POST['cart_id'];

        if ($_POST['action'] == "increase") {
            $sql = "UPDATE cart SET quantity = quantity + 1 WHERE cart_id = '$cart_id' AND u_id = '$u_id'";
        } elseif ($_POST['action'] == "decrease") {
            $sql = "UPDATE cart SET quantity = quantity - 1 WHERE cart_id = '$cart_id' AND u_id = '$u_id' AND quantity > 1";
        } elseif ($_POST['action'] == "remove") {
            $sql = "DELETE FROM cart WHERE cart_id = '$cart_id' AND u_id = '$u_id'";
        }

        $conn->query($sql);
        header("Location: cart.php");
        exit;
    }
}

// Fetch cart items from the database
$sql_cart = "SELECT c.cart_id, m.med_id, m. name, m.price, c.quantity 
             FROM cart c
             JOIN medicine m ON c.med_id = m.med_id
             WHERE c.u_id = '$u_id'";
$result_cart = $conn->query($sql_cart);

$cart_items = [];
$totalAmount = 0;

if ($result_cart->num_rows > 0) {
    while ($row = $result_cart->fetch_assoc()) {
        $row['total_price'] = $row['price'] * $row['quantity'];
        $totalAmount += $row['total_price'];
        $cart_items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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

<body class="bg-gray-100 text-gray-900">
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
                        <a href="orders.php" class="block px-4 py-2 text-white hover:bg-gray-600">My Orders</a>
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

    <main class="container mx-auto p-6 max-w-lg bg-white rounded-lg shadow-md mt-10">
        <h1 class="text-3xl font-bold mb-5 text-center text-yellow-600">🛒 Your Cart</h1>

        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
            <?php if (!empty($cart_items)) { ?>
                <?php foreach ($cart_items as $item) { ?>
                    <div class="flex justify-between items-center p-4 bg-gray-200 rounded-lg mt-3 shadow">
                        <div class="w-40">
                            <h2 class="text-lg font-semibold"><?php echo htmlspecialchars($item['name']); ?></h2>
                            <p class="text-green-600 font-bold">₹<?php echo number_format($item['total_price'], 2); ?>
                                (<?php echo $item['quantity']; ?>x)</p>
                        </div>

                        <form method="POST" class="flex items-center">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <button type="submit" name="action" value="decrease"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded">➖</button>
                            <span class="mx-2 font-bold"><?php echo $item['quantity']; ?></span>
                            <button type="submit" name="action" value="increase"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded">➕</button>
                        </form>

                        <form method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <button type="submit" name="action" value="remove"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">🗑️</button>
                        </form>
                    </div>
                <?php } ?>
                <div class="mt-5 text-right text-lg font-bold text-yellow-700">Total:
                    ₹<?php echo number_format($totalAmount, 2); ?></div>
            <?php } else { ?>
                <p class="text-center text-gray-500 mt-5">Your cart is empty. Start adding products! 🛍️</p>
            <?php } ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <button id="proceedToOrder" class="bg-blue-500 text-white px-4 py-2 rounded justify-center w-full mt-5">
                Proceed to Order
            </button>

            <script>
                document.getElementById("proceedToOrder").addEventListener("click", function () {
                    Swal.fire({
                        title: "Choose Payment Method",
                        icon: "info",
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonText: "Pay Online",
                        denyButtonText: "Cash on Delivery",
                        cancelButtonText: "Cancel",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire("Error", "This feature is not available yet.", "error");
                        } else if (result.isDenied) {
                            Swal.fire({
                                title: "Confirm Order",
                                text: "Are you sure you want to place the order with Cash on Delivery?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Yes, Confirm",
                                cancelButtonText: "Cancel",
                            }).then((confirmResult) => {
                                if (confirmResult.isConfirmed) {
                                    window.location.href = "process_payment.php";
                                }
                            });
                        }
                    });
                });
            </script>


        </div>
    </main>



    <script>
        document.getElementById('orderButton')?.addEventListener('click', function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Choose Payment Method',
                text: 'Select your preferred payment option:',
                icon: 'info',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '💳 Pay Online',
                denyButtonText: '💵 Cash on Delivery',
                cancelButtonText: '❌ Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Service Unavailable', 'Online payment is currently not available.', 'error');
                } else if (result.isDenied) {
                    window.location.href = 'process_payment.php';
                }
            });
        });
    </script>


    <form method="POST" id="codForm" action="cart.php">
        <input type="hidden" name="cod_order" value="1">
    </form>
</body>

</html>