<?php
include "service/dbconnect.php";
$stmt = $mysqli->prepare("SELECT name from admin where id = ?");
$stmt->bind_param("s", $adminId);
$stmt->execute();
$admin = $stmt->get_result()->fetch_row();

?>
<div class="panel-container">
    <div class="panel-menu">
        <button class="panel-item"><i class="bi bi-person-circle"></i> <b><?php echo $admin[0] ?></b></button>
        <a class="panel-item" href="admin-panel.php">Main Page</a>
        <button data-target="vendor" class="panel-item panel-drop-btn"><i class="bi bi-shop"></i> Vendors</button>
        <div id="vendor" class="panel-dropdown">
            <a href="admin-vendor-list.php" class="panel-item">Vendor List</a>
            <a href="admin-vendor-add.php" class="panel-item">Add Vendor</a>
        </div>
        <button data-target="product" class="panel-item panel-drop-btn"><i class="bi bi-box2-fill"></i> Products</button>
        <div id="product" class="panel-dropdown">
            <a href="admin-product-list.php" class="panel-item">Product List</a>
            <a href="admin-product-add.php" class="panel-item">Add Product</a>
        </div>

        <button data-target="order" class="panel-item panel-drop-btn"><i class="bi bi-truck"></i> Orders</button>
        <div id="order" class="panel-dropdown">
            <a href="admin-order-list.php" class="panel-item">Order List</a>
        </div>
        <button data-target="category" class="panel-item panel-drop-btn"><i class="bi bi-tags-fill"></i> Categories</button>
        <div id="category" class="panel-dropdown">
            <a href="admin-category-list.php" class="panel-item">Category List</a>
            <a href="admin-category-add.php" class="panel-item">Add Category</a>
        </div>
        <a href="admin-logout.php" class="panel-item">Logout</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        $(".panel-drop-btn").click(function() {
            const drop = $(this).attr("data-target");
            $("#" + drop).toggle();
        });
    </script>
    <style>
        .admin-table {
            border: 1px solid rgb(194, 194, 194);
        }

        .prod-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            background-color: white;
            border-radius: 5px;
            border: 1px solid rgb(196, 196, 196);
        }



        .panel-menu {
            background-color: rgb(248, 248, 248);
            width: 300px;
            overflow: scroll;
        }

        .panel-item:hover {
            background-color: rgb(231, 231, 231);
            color: rgb(104, 104, 104);
        }

        .panel-dropdown>.panel-item {
            background-color: rgb(236, 236, 236);
        }

        .panel-dropdown>.panel-item:hover {
            background-color: rgb(221, 221, 221);
        }

        .panel-item {
            font-size: 18px;
            text-align: center;
            padding: 10px;
            color: rgb(66, 66, 66);
            width: 100%;
            display: block;
            border-bottom: 1px solid rgb(218, 218, 218);
        }

        .panel-item i {
            font-size: 22px;
        }

        .panel-drop-btn {
            font-size: 20px;
            font-weight: 500;
            text-align: left;
        }

        .panel-dropdown {
            display: none;
        }

        .panel-screen {
            flex: 1;
        }

        .panel-container {
            min-width: 1080px;
            min-height: 100vh;
            display: flex;
        }
    </style>