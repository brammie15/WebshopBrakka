<?php
$pages = array(
    'index.php' => 'Home',
    'login.php' => 'Login',
);

if (isset($_SESSION['user'])) {
    unset($pages['login.php']);
    $pages['winkelmandje.php'] = 'Winkelmandje';
    if($_SESSION['userType'] == UserTypes::Employee){
        $pages['admin.php'] = 'Admin';
    }
    $pages['logout.php'] = 'Logout';
}
?>
<link rel="stylesheet" href="./navbar.css" type="text/css">
<nav>
    <ul>
        <?php foreach ($pages as $filename => $pageTitle) { ?>
            <li><a href="<?= $filename ?>"><?= $pageTitle ?></a></li>
            <?php
        }
        ?>
    </ul>
</nav>

