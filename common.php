<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
enum UserTypes{
    case Customer;
    case Employee;
}


function checkAuth($AuthLevel): void {
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isSession(["user", "userType"])){
        header("Location: login.php");
    }
    if($_SESSION["userType"] != $AuthLevel){
        header("Location: unauthorized.php");
    }
}

function isGet($nameArray): bool {
    $result = true;
    foreach ($nameArray as $name) {
        if (!isset($_GET[$name])) {
            $result = false;
        }
    }
    return $result;
}

function isPost($nameArray): bool {
    $result = true;
    foreach ($nameArray as $name) {
        if (!isset($_POST[$name])) {
            $result = false;
        }
    }
    return $result;
}

function isSession($nameArray): bool {
    $result = true;
    foreach ($nameArray as $name) {
        if (!isset($_SESSION[$name])) {
            $result = false;
        }
    }
    return $result;
}

