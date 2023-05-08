<?php
$pages = array(
   '../index.php' => 'Home',
    'addProduct.php' => 'Add Product',
);

?>
<link rel="stylesheet" href="navbar.css" type="text/css">
<?php echo __DIR__ ?>
<nav>
    <ul>
        <?php foreach ($pages as $filename => $pageTitle) { ?>
            <li><a href="<?= $filename ?>"><?= $pageTitle ?></a></li>
            <?php
        }
        ?>
    </ul>
</nav>

