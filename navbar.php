<?php
$pages = array(
    'index.php' => 'Home',
    'login.php' => 'Login',
);

if (isset($_SESSION['user'])) {
    unset($pages['login.php']);
    $pages['winkelmandje.php'] = 'Winkelmandje';
    $pages['account.php'] = 'Account';
    $pages['logout.php'] = 'Logout';
}


?>
<link rel="stylesheet" href="/navbar.css" type="text/css">
<nav>
    <ul>
        <?php foreach ($pages as $filename => $pageTitle) { ?>
        <li><a href="<?= $filename ?>"><?=$pageTitle?></a></li>
        <?php
        }
        ?>
    </ul>
</nav>


<!--<nav>-->
<!--    <ul>-->
<!--        <li><a href="index.php">Home</a></li>-->
<!--        <li><a href="login.php">Login</a></li>-->
<!--        --><?php //if (isset($_SESSION['user'])): ?>
<!--        <li><a href="logout.php">Logout</a></li>-->
<!--                    <li><p id="welkomText">Welkom --><?php ////=$_SESSION['user']?><!--</p></li>-->
<!--        <li><a href="winkelmandje.php">WinkelMandje</a></li>-->
<!--        --><?php //endif; ?>
<!--        --><?php //if ($isEmployee): ?>
<!--            <li><a href="admin.php">Admin</a></li>-->
<!--        --><?php //endif; ?>
<!--    </ul>-->
<!--</nav>-->

