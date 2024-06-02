<?php
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$page = intval($page);
if ($page < 1) $page = 1;
$limit = 25;
$offset = ($page - 1) * $limit;

if (isset($_REQUEST["search"])) {
    $queryStr = $_REQUEST["search"];
    $products_result = $mysqli->query("SELECT 
    p.id, p.image, p.name, v.name, c.name ,p.price, p.prevPrice 
    from category as c, product as p, vendor as v
    where c.id = p.categoryId and v.id = p.vendorId and (c.name like '%$queryStr%' 
    or v.name like '%$queryStr%' or
    p.name like '%$queryStr%') limit $limit offset $offset  ");

    $product_count = $mysqli->query("SELECT 
    count(*)
    from category as c, product as p, vendor as v
    where c.id = p.categoryId and v.id = p.vendorId and (c.name like '%$queryStr%' 
    or v.name like '%$queryStr%' or
    p.name like '%$queryStr%') limit $limit offset $offset  ")->fetch_row()[0];

    $total_page = ceil($product_count / $limit);
} else {
    $products_result = $mysqli->query("SELECT 
    p.id,p.image, p.name, v.name, c.name ,p.price, p.prevPrice   
    from category as c, product as p, vendor as v
    where c.id = p.categoryId and v.id = p.vendorId");
    $product_count = $mysqli->query("SELECT count(*) from product")->fetch_row()[0];
    $total_page = ceil($product_count / $limit);
}

?>
<div class="product-list-container">
    <h1>Product List</h1>
    <hr>
    <div class="m-2">
        <form class="d-flex justify-content-between" method="get" action="admin-product-list.php">
            <div class="d-flex align-items-center">

                <input placeholder="Search..." value="<?php if (isset($queryStr)) echo $queryStr  ?>" class="search form-control" type="text" name="search" id="search">
                <input class="btn btn-primary mx-2" type="submit" value="Search">
                <a href="admin-product-add.php" class="btn btn-success mx-2">Add Product</a>
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
    <span class="badge bg-primary my-2">Total: <?php echo $product_count ?></span>
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Vendor</th>
                <th scope="col">Category</th>
                <th scope="col">Price</th>
                <th scope="col">Previous Price</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = $products_result->fetch_row()) { ?>
                <tr>
                    <td><img class="item-image" src="<?php echo $product[1] ?>" /> </td>
                    <td>
                        <?php echo $product[2] ?></td>
                    <td>
                        <?php echo $product[3] ?></td>
                    <td>
                        <?php echo $product[4] ?></td>
                    <td>
                        <?php echo $product[5] ?></td>
                    <td>
                        <?php echo $product[6] ?></td>
                    <td>
                        <form action="admin-product-list.php">
                            <a class="btn btn-primary" href="<?php echo "admin-product-update.php?id=$product[0]" ?>">Update</a>
                            <a class="btn btn-danger" href="<?php echo "admin-product-update.php?id=$product[0]" ?>">Delete</a>
                            <input type="text" value="" name="deleteId" hidden id="">
                            <input type="text" value="" name="deleteId" hidden id="">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php if ($products_result->num_rows < 1) echo "No items found..." ?>
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


    .product-list-container {
        max-width: 1300px;
        margin-left: auto;
        margin-right: auto;


    }
</style>