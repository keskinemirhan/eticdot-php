<?php
include_once "service/utils.php";
if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["id"]) &&
    isset($_POST["status"]) &&
    !empty($_POST["status"] &&
        !empty($_POST["id"]))
) {
    $find = $stmt_execute(
        'select pi.id from purchase_item pi, product p  
    where p.id = pi.productId and p.vendorId = ? and pi.id = ?',
        "ss",
        $vendorId,
        $_POST["id"]
    );
    if ($find->num_rows > 0) {
        $stmt_execute(
            "update purchase_item set status = ? where id = ? ",
            "ss",
            $_POST["status"],
            $find->fetch_assoc()["id"]
        );
        redirect("vendor-order-detail.php?id=" . $_POST["id"]);
    } else redirect("vendor-order-list.php");
} else

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && !empty($_GET["id"])) {
    $purchaseItemId = $_GET["id"];

    $user = $stmt_execute(
        "SELECT CONCAT(u.name, ' ',u.surname) as namesurname,
        u.email as email, u.phoneNumber as phoneNumber, u.address as address
        from user u, purchase p, purchase_item pi where p.userId = u.id 
        and p.id = pi.purchaseId and pi.id = ?",
        "s",
        $purchaseItemId
    )->fetch_assoc();

    $purchaseItem = $stmt_execute(
        "select pi.id, p.name as prodName, p.image,  
        pi.priceSnapshot, pi.amount, pi.status
        from product p, purchase_item pi 
        where pi.productId = p.id and p.vendorId = ?
        and pi.id = ?",
        "ss",
        $vendorId,
        $purchaseItemId
    )->fetch_assoc();
} else {
    include_once "service/utils.php";
    redirect("vendor-panel.php");
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
                    <?php echo $user["phoneNumber"] ?></td>
                <td>
                    <?php echo $user["address"] ?></td>
            </tr>
        </tbody>
    </table>
    <h3>Ordered Product</h3>
    <hr>
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Product</th>
                <th scope="col">Price</th>
                <th scope="col">Amount</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><img class="item-image" src="<?php echo $purchaseItem["image"] ?>" /> </td>
                <td>
                    <?php echo $purchaseItem["prodName"] ?>
                </td>

                <td>
                    <?php echo $purchaseItem["priceSnapshot"] ?>$</td>
                <td>
                    <?php echo $purchaseItem["amount"] ?></td>
                <td>
                    <?php echo $purchaseItem["status"] ?></td>
            </tr>
        </tbody>
    </table>
    <form action="vendor-order-detail.php" method="post">
        <label class="form-label" for="status">Status: </label>
        <input class="form-control" style="width: 200px;" value="<?php echo $purchaseItem['status'] ?>" type="text" name="status" id="status">
        <input class="form-control" hidden style="width: 200px;" value="<?php echo $purchaseItem['id'] ?>" type="text" name="id" id="status">
        <input class="btn btn-primary mt-4" type="submit" value="Change Status">
    </form>
</div>
<div>

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