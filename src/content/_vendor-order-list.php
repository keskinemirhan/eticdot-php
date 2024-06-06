<?php
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$page = intval($page);
if ($page < 1) $page = 1;
$limit = 25;
$offset = ($page - 1) * $limit;

if (isset($_REQUEST["search"])) {
    $queryStr = $_REQUEST["search"];
    $result = $mysqli->query(
        "SELECT pi.id, pu.address,
        pi.priceSnapshot, pi.amount, p.name,
        date_format(pi.createdAt, '%Y-%m-%d at %H:%i') as createdAt,
        concat(u.name,' ', u.surname) as namesurname,
        u.email, pi.status
        from purchase pu, purchase_item pi, user u, product p
        where pu.id = pi.purchaseId and u.id = pu.userId 
        and pi.productId = p.id and p.vendorId = '$vendorId'
        and (u.name like '%$queryStr%' 
        or u.surname like '%$queryStr%' 
        or p.name like '%$queryStr%')
        order by pi.createdAt desc
        limit $limit offset $offset"
    );

    $count = $mysqli->query(
        " SELECT count(*) 
        from purchase pu, purchase_item pi, user u, product p
        where pu.id = pi.purchaseId and u.id = pu.userId 
        and pi.productId = p.id and p.vendorId = '$vendorId'
        and (u.name like '%$queryStr%' 
        or u.surname like '%$queryStr%' 
        or p.name like '%$queryStr%')
    "
    )->fetch_row()[0];

    $total_page = ceil($count / $limit);
} else {
    $result = $mysqli
        ->query(" SELECT pi.id, pu.address,
        date_format(pi.createdAt, '%Y-%m-%d at %H:%i') as createdAt,
        pi.priceSnapshot, pi.amount, p.name,
        concat(u.name,' ', u.surname) as namesurname,
        u.email, pi.status 
        from purchase pu, purchase_item pi, user u, product p
        where pu.id = pi.purchaseId and u.id = pu.userId 
        and pi.productId = p.id and p.vendorId = '$vendorId'
        order by pi.createdAt desc
        limit $limit offset $offset");
    $count = $mysqli
        ->query("SELECT count(*) from purchase pu, purchase_item pi, user u, product p
        where pu.id = pi.purchaseId and u.id = pu.userId 
        and pi.productId = p.id and p.vendorId = '$vendorId'
")->fetch_row()[0];
    $total_page = ceil($count / $limit);
}

?>
<div class="list-container">
    <h1>Order List</h1>
    <hr>
    <div class="m-2">
        <form class="d-flex justify-content-between" method="get" action="vendor-order-list.php">
            <div class="d-flex align-items-center">

                <input placeholder="Search..." value="<?php if (isset($queryStr)) echo $queryStr  ?>" class="search form-control" type="text" name="search" id="search">
                <input class="btn btn-primary mx-2" type="submit" value="Search">
            </div>
            <div class="d-flex align-items-center">
                <label for="page">Select Page: </label>
                <select value="<?php echo $page ?>" style="width: initial;" name="page" class="form-select mx-2" id="">
                    <?php for ($i = 1; $i <= $total_page; $i++) { ?>
                        <option <?php if ($i == $page) echo "selected" ?> value="<?php echo $i ?>">
                            <?php echo $i ?>
                        </option>
                    <?php } ?>
                </select>

                <input class="btn btn-primary mx-2" type="submit" value="Go To Page">
            </div>
        </form>

    </div>
    <span class="badge bg-primary my-2">Total: <?php echo $count ?></span>
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Name and Surname</th>
                <th scope="col">Email</th>
                <th scope="col">Product</th>
                <th scope="col">Amount</th>
                <th scope="col">Price</th>
                <th scope="col">Ordered At</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $result->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <?php echo $item["namesurname"] ?>
                    </td>

                    <td>
                        <?php echo $item["email"] ?></td>
                    <td>
                        <?php echo $item["name"] ?></td>
                    <td>
                        <?php echo $item["amount"] ?></td>
                    <td>
                        <?php echo $item["priceSnapshot"] ?>$</td>
                    <td>
                        <?php echo $item["createdAt"] ?></td>
                    <td>
                        <?php echo $item["status"] ?></td>
                    <td>
                        <a class="btn btn-primary" href="<?php echo "vendor-order-detail.php?id=" . $item['id'] ?>">See Details</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php if ($result->num_rows < 1) echo "No items found..." ?>
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