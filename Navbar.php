<?php

function getNav(){

    $loginLogout = <<<HTML
        
HTML;


    return <<<HTML
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="winkelmandje.php">WinkelMandje</a></li>
    </ul>
</nav>
HTML;

}
