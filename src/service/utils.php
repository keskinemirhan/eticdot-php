<?php
function redirect($path)
{
    echo "<script>window.location.href = '$path';</script>";
}

function isblank_post(string ...$fields)
{
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field]))
            return true;
    }
    return false;
};
