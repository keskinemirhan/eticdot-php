<?php
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$page = intval($page);
if ($page < 1) $page = 1;
$limit = 25;
$offset = ($page - 1) * $limit;

if (isset($_REQUEST["search"])) {
    $queryStr = $_REQUEST["search"];
    $vendors_result = $mysqli->query("SELECT id, name, email from vendor 
    where name like '%$queryStr%' or email like '%$queryStr%' limit $limit offset $offset  ");

    $vendors_count = $mysqli->query("SELECT count(*) from vendor 
    where name like '%$queryStr%' or email like '%$queryStr%'")->fetch_row()[0];

    $total_page = ceil($vendors_count / $limit);
} else {
    $vendors_result = $mysqli->query("SELECT id, name, email from vendor order by name asc limit $limit offset $offset");
    $vendors_count = $mysqli->query("SELECT count(*) from vendor ")->fetch_row()[0];
    $total_page = ceil($vendors_count / $limit);
}

?>
<div class="vendor-list-container">
    <h1>Vendor List</h1>
    <hr>
    <div class="m-2">
        <form class="d-flex justify-content-between" method="get" action="admin-vendor-list.php">
            <div class="d-flex align-items-center">

                <input placeholder="Search..." value="<?php if (isset($queryStr)) echo $queryStr  ?>" class="search form-control" type="text" name="search" id="search">
                <input class="btn btn-primary mx-2" type="submit" value="Search">
                <a href="admin-vendor-add.php" class="btn btn-success mx-2">Add Vendor</a>
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
    <span class="badge bg-primary my-2">Total: <?php echo $vendors_count ?></span>
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($vendor = $vendors_result->fetch_row()) { ?>
                <tr>
                    <td><?php echo $vendor[1] ?> </td>
                    <td>
                        <?php echo $vendor[2] ?></td>
                    <td>
                        <form action="admin-vendor-list.php">
                            <a class="btn btn-primary" href="<?php echo "admin-vendor-update.php?id=$vendor[0]" ?>">Update</a>
                            <a class="btn btn-danger" href="<?php echo "admin-vendor-update.php?id=$vendor[0]" ?>">Delete</a>
                            <input type="text" value="" name="deleteId" hidden id="">
                            <input type="text" value="" name="deleteId" hidden id="">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php if ($vendors_result->num_rows < 1) echo "No items found..." ?>
</div>
<style>
    .search {
        width: 200px;

    }


    .vendor-list-container {
        max-width: 1300px;
        margin-left: auto;
        margin-right: auto;


    }
</style>