<?php
include 'connection.php'; // Include your database connection file

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $mfg_date = $_POST['mfg_date'];
    $exp_date = $_POST['exp_date'];
    $price = $_POST['price'];

    // Fetch the last med_id from the database
    $query = "SELECT med_id FROM medicine ORDER BY med_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Extract numeric part and increment
        $last_id = intval(substr($row['med_id'], 1)) + 1;
        $med_id = "M" . str_pad($last_id, 5, "0", STR_PAD_LEFT);
    } else {
        // First entry
        $med_id = "M00001";
    }

    // Image upload handling
    $target_dir = "uploads/"; // Folder to store uploaded images
    $image_name = basename($_FILES["image"]["name"]);
    $image_tmp = $_FILES["image"]["tmp_name"];
    $image_path = $target_dir . $image_name;

    // Allow all image types
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff'];
    $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    if (in_array($image_extension, $allowed_types)) {
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Insert into database
            $sql = "INSERT INTO medicine (med_id, name, description, type, mfg_date, exp_date, price, med_image) 
                    VALUES ('$med_id', '$name', '$description', '$type', '$mfg_date', '$exp_date', '$price', '$image_path')";

            if (mysqli_query($conn, $sql)) {
                // Redirect with success message using JavaScript
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Medicine added successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href='add_medicine.php';
                            });
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error adding medicine!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                      </script>";
            }
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Image upload failed!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Invalid File!',
                    text: 'Only image files are allowed!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #2563EB, #1E3A8A);
            font-family: 'Arial', sans-serif;
            color: white;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            color: black;
            /* Set text color to black */
            background-color: white;
            /* Ensure background remains white */
        }


        .custom-sidebar {
            background: #1E3A8A;
            min-height: 100vh;
            padding: 20px;
        }

        .custom-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .custom-sidebar li {
            margin: 15px 0;
            padding: 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .custom-sidebar li:hover,
        .custom-sidebar li a:hover {
            background: #2563EB;
        }

        .custom-sidebar li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .form-container {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            max-width: 600px;
            margin: auto;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .input-field:focus {
            outline: none;
            border-color: #2563EB;
        }

        button {
            background: #2563EB;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #1E3A8A;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }

        .alert-success {
            background: #4CAF50;
            color: white;
        }

        .alert-error {
            background: #F44336;
            color: white;
        }
    </style>
</head>

<body>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-1/6 custom-sidebar text-white">
            <h1 class="text-2xl font-bold mb-6">MEDICAL STORE</h1>
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="medicine.php">Medicine</a></li>
                <li><a href="add_medicine.php">Add Medicine</a></li>
                <li><a href="stock.php">Stock</a></li>
                <li><a href="view_order.php">Order</a></li>
                <li><a href="view_payment.php">Payment</a></li>
                <li><a href="view_feedback.php">Feedback</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="w-5/6 p-6 bg-white">
            <div class="max-w-lg mx-auto bg-blue-800 p-10 rounded-lg shadow-md">
                <h2 class="text-3xl font-bold text-center mb-6">Add Medicine</h2>

                <form action="add_medicine.php" method="POST" enctype="multipart/form-data">
                    <label class="block font-semibold">Medicine Name:</label>
                    <input type="text" name="name" class="w-full border p-2 rounded mb-4 text-black" required>

                    <label class="block font-semibold ">Description:</label>
                    <textarea name="description" class="w-full border p-2 rounded mb-4 text-black" required></textarea>

                    <label class="block font-semibold">Type:</label>
                    <select name="type" class="w-full border p-2 rounded mb-4 text-black" required>
                        <option class="text-black" value="Tablet">Tablet</option>
                        <option class="text-black" value="Cream">Cream</option>
                        <option class="text-black" value="Syrup">Syrup</option>
                    </select>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold">Manufacturing Date:</label>
                            <input type="date" name="mfg_date" class="w-full border p-2 rounded text-black" required>
                        </div>
                        <div>
                            <label class="block font-semibold">Expiry Date:</label>
                            <input type="date" name="exp_date" class="w-full border p-2 rounded text-black" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block font-semibold">Price:</label>
                            <input type="number" name="price" class="w-full border p-2 rounded text-black" step="0.01"
                                required>
                        </div>
                        <div>
                            <label class="block font-semibold">Upload Image:</label>
                            <input type="file" name="image" class="w-full border p-2 rounded " required>
                        </div>
                    </div>

                    <button type="submit" name="submit"
                        class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg mt-6 hover:bg-blue-600">
                        Add Medicine
                    </button>
                </form>
            </div>
        </div>
    </div>
    </div>

</body>

</html>