<?php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && !empty($_GET["id"])) {
    $purchaseId = $_GET["id"];
    $user = $stmt_execute(
        "SELECT CONCAT(u.name, ' ',u.surname) as namesurname,
        u.email as email, u.phoneNumber as phoneNumber, u.address as address
        from user u, purchase p where p.userId = u.id and p.id = ?",
        "s",
        $purchaseId
    )->fetch_assoc();
    $purchase = $stmt_execute(
        "SELECT address, total, 
        date_format(createdAt, '%Y-%m-%d at %H:%i') as createdAt
        from purchase
        where id = ?
        ",
        "s",
        $purchaseId
    );
    if ($purchase->num_rows < 1) {
        echo "<h1>Not found...</h1>";
        exit;
    }
    $purchase = $purchase->fetch_assoc();

    $purchaseItems = $stmt_execute(
        "select p.name as prodName, p.image, v.name as vendorName, 
        pi.priceSnapshot, pi.amount, pi.status
        from product p, purchase_item pi, vendor v
        where v.id = p.vendorId and pi.productId = p.id
        and pi.purchaseId = ?",
        "s",
        $purchaseId
    )->fetch_all(MYSQLI_ASSOC);
} else {
    include_once "service/utils.php";
    redirect("admin-panel.php");
}

?>
<div class="list-container">
    <h1>Order Details</h1>
    <hr>
    <h3>Ordered By</h3>
    <hr>
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Name and Surname</th>
                <th scope="col">Email</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Address</th>
            </tr>
        </thead>
        <tbody>

            <tr>

                <td>
                    <?php echo $user["namesurname"] ?>
                </td>

                <td>
                    <?php echo $user["email"] ?></td>
                <td>
                    <?php echo $user["phoneNumber"] ?>$</td>
                <td>
                    <?php echo $user["address"] ?></td>
            </tr>
        </tbody>
    </table>
    <hr>
    <h3>Order Details</h3>
    <hr>
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Address</th>
                <th scope="col">Total</th>
                <th scope="col">Ordered At</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <?php echo $purchase["address"] ?>
                </td>

                <td>
                    <?php echo $purchase["total"] ?>$</td>
                <td>
                    <?php echo $purchase["createdAt"] ?></td>
            </tr>
        </tbody>
    </table>
    <hr>
    <h3>Ordered Products</h3>
    <hr>
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Product</th>
                <th scope="col">Vendor</th>
                <th scope="col">Price</th>
                <th scope="col">Amount</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchaseItems as $item) { ?>
                <tr>
                    <td><img class="item-image" src="<?php echo $item["image"] ?>" /> </td>
                    <td>
                        <?php echo $item["prodName"] ?>
                    </td>

                    <td>
                        <?php echo $item["vendorName"] ?></td>
                    <td>
                        <?php echo $item["priceSnapshot"] ?>$</td>
                    <td>
                        <?php echo $item["amount"] ?></td>
                    <td>
                        <?php echo $item["status"] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<style>
    .search {
        width: 200px;

    }

    .item-image {
        width: 100px;
        height: 100px;
        object-fit: contain;
    }


    .list-container {
        max-width: 1300px;
        margin-left: auto;
        margin-right: auto;


    }
</style>