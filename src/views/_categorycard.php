<?php function categoryCard($name, $image)
{ ?>
    <a href="search.php?q=<?php echo $name ?>" style="background-image: url('<?php echo $image ?>')" class="card-wrapper">
        <div class="btn-wrapper">
            <div class="btn-cat bg-blue"><?php echo $name ?></div>
        </div>
    </a>
<?php } ?>