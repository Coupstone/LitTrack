<?php
ob_start();
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');
session_start();

// Prevent caching of pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once('initialize.php');
require_once('classes/DBConnection.php');
require_once('classes/SystemSettings.php');
$db = new DBConnection;
$conn = $db->conn;
function redirect($url=''){
	if(!empty($url))
	echo '<script>location.href="'.base_url .$url.'"</script>';
}

// Check if the current user is logged in and redirect accordingly
function check_active_session() {
    if (isset($_SESSION['user_id'])) {
        redirect('admin/'); // Redirect admin to the admin dashboard
    } elseif (isset($_SESSION['student_id'])) {
        redirect('index.php'); // Redirect student to the student dashboard
    }
}

// Call `check_active_session()` only on `choices.php`
if (basename($_SERVER['PHP_SELF']) === 'choices.php') {
    check_active_session(); // Redirect logged-in users
}

// Function to check if a user is logged in
function check_login(){
    if(!isset($_SESSION['user_id']) && !isset($_SESSION['student_id'])){
        redirect('login.php'); // Redirect to login page if not logged in
    }
}

function validate_image($file){
	if(!empty($file)){
			// exit;
        $ex = explode('?',$file);
        $file = $ex[0];
        $param =  isset($ex[1]) ? '?'.$ex[1]  : '';
		if(is_file(base_app.$file)){
			return base_url.$file.$param;
		}else{
			return base_url.'dist/img/no-image-available.png';
		}
	}else{
		return base_url.'dist/img/no-image-available.png';
	}
}
function isMobileDevice(){
    $aMobileUA = array(
        '/iphone/i' => 'iPhone', 
        '/ipod/i' => 'iPod', 
        '/ipad/i' => 'iPad', 
        '/android/i' => 'Android', 
        '/blackberry/i' => 'BlackBerry', 
        '/webos/i' => 'Mobile'
    );

    //Return true if Mobile User Agent is detected
    foreach($aMobileUA as $sMobileKey => $sMobileOS){
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    //Otherwise return false..  
    return false;
}

// Check if the current URL is the root URL and redirect
if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/LitTrack/') {
    redirect('homepage.php');
}

// Redirect logged-in users away from the login page
if (basename($_SERVER['PHP_SELF']) === 'login.php') {
    if (isset($_SESSION['user_id']) || isset($_SESSION['student_id'])) {
        redirect('index.php'); // Redirect to homepage or dashboard
    }
}
ob_end_flush();
?>