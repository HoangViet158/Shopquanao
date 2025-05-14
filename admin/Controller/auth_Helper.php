<?php
// session_start();
// session_unset();
// session_destroy();
require_once('../Controller/permission_Controller.php');
session_start();
$permissionController = new permission_Controller();

function requireLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $currentPage = basename($_SERVER['PHP_SELF']);


    // Nếu trang hiện tại là trang được phép truy cập công khai
    // if (in_array($currentPage, $allowedPages)) {
    //     return;
    // }
    $path = $_SERVER['PHP_SELF']; // VD: /web2/admin/view/s.php
            // Tách chuỗi thành mảng
    $parts = explode('/', str_replace('\\', '/', $path));
    $index = array_search("admin", $parts);        
    $projectFolder = $parts[$index - 1];
    $fullPath = "/user/View/product.php";
    $urlPath = "/" . $projectFolder . $fullPath;
    $succesPath = "/" . $projectFolder . "/user/View/index.php";
    // Nếu chưa login, redirect về login.php
    if (!isset($_SESSION['user'])) {
        // $path = $_SERVER['PHP_SELF']; // VD: /web2/admin/view/s.php
        //         // Tách chuỗi thành mảng
        // $parts = explode('/', str_replace('\\', '/', $path));

        // Tìm vị trí "htdocs", và lấy phần tiếp theo chính là "Web2"
        // $index = array_search("admin", $parts);        
        // $projectFolder = $parts[$index - 1];
        // $fullPath = "/user/View/login.php";
        // $urlPath = "/" . $projectFolder . $fullPath;
        header("Location: " . $urlPath);
        exit();
    }
    if ($_SESSION['user']['permission'] == 3){
        header("Location: " . $succesPath);
        exit();
    }
}

function canViewProduct(){
    requireLogin();
    global $permissionController;
    $action = $permissionController->getAction($_SESSION['user']['permission'],1);
    while ($row = $action->fetch_assoc()) {
        $actions = explode(',', $row['HanhDong']); // Tách chuỗi thành mảng
        if (in_array('view', $actions)) {
            return true;
        }
    }
    return false;
}

function canViewBill(){
    requireLogin();
    global $permissionController;
    $action = $permissionController->getAction($_SESSION['user']['permission'],2);
    while ($row = $action->fetch_assoc()) {
        $actions = explode(',', $row['HanhDong']); // Tách chuỗi thành mảng
        if (in_array('view', $actions)) {
            return true;
        }
    }
    return false;
}

function canViewGoodReceipt(){
    requireLogin();
    global $permissionController;
    $action = $permissionController->getAction($_SESSION['user']['permission'],3);
    while ($row = $action->fetch_assoc()) {
        $actions = explode(',', $row['HanhDong']); // Tách chuỗi thành mảng
        if (in_array('view', $actions)) {
            return true;
        }
    }
    return false;
}

function canViewPromotion(){
    requireLogin();
    global $permissionController;
    $action = $permissionController->getAction($_SESSION['user']['permission'],4);
    while ($row = $action->fetch_assoc()) {
        $actions = explode(',', $row['HanhDong']); // Tách chuỗi thành mảng
        if (in_array('view', $actions)) {
            return true;
        }
    }
    return false;
}

function canViewUser(){
    requireLogin();
    global $permissionController;
    $action = $permissionController->getAction($_SESSION['user']['permission'],5);
    while ($row = $action->fetch_assoc()) {
        $actions = explode(',', $row['HanhDong']); // Tách chuỗi thành mảng
        if (in_array('view', $actions)) {
            return true;
        }
    }
    return false;
}

function canViewPermission(){
    requireLogin();
    if($_SESSION['user']['permission'] == 1){
        return true;
    }
    return false;
}

// kiểm tra từng hành động
function hasAction($resourceId, $actionName) {
    global $permissionController;
    $actions = $permissionController->getAction($_SESSION['user']['permission'], $resourceId);
    while ($row = $actions->fetch_assoc()) {
        $actionList = explode(',', $row['HanhDong']);
        if (in_array($actionName, $actionList)) {
            return true;
        }
    }
    return false;
}
?>