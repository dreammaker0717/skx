<?php

/**
 * Part of The Straight Framework.
 */

/* load the Straight Framework  */
require PATH_LIB . '/Straight/straight.php';

require PATH_LIB . '/Dropdown.php';

/* English dictionary */
require PATH_I18N . '/en/en.php';

require PATH_CONFIG. '/settings.php';
require_once(PATH_CONFIG."/rb_config.php");
require_once(PATH_CONFIG."/constants.php");

$isAjax = strpos( $_SERVER["REQUEST_URI"], 'ajax') > 0;



  function isLoginSessionExpired() {
  
	$login_session_duration = 60 * 60 * 24;
	$current_time=time();
	if(isset($_SESSION["session_time"]) and isset($_SESSION["user_id"])) {
	  if(((time() - $_SESSION["session_time"]) > $login_session_duration )) {
		return TRUE;
	  }
	}
	return FALSE;
  
  }
  
  
function secure($min_level=0) {

	if(empty($_SESSION["user_id"]) || isLoginSessionExpired() ) {
		session_destroy();
		header("Location: /login?session_invalid");
		exit();
	}
	else if($_SESSION['access']<$min_level) {		
		header("Location: /error-500.html?permission_denied");
		exit();
	}
	$isAjax = strpos( $_SERVER["REQUEST_URI"], 'ajax') > 0;

	if(!$isAjax) {
		view('layout/head');
		view('layout/partial/navigation');
	}
	

}


/* Define your routes like this */

/* URL: GET / */
function __do_get() {

	secure();
	view( 'welcome', [ 'greeting' => dict('welcome') ] );
}


function __do_get_login() {
	view('layout/head');
	view( 'login' ,[ 'email'=> isset($_COOKIE['email']) ? $_COOKIE['email'] :  '', 'message'=>''] );
}
function __do_post_login() {	
	require_once(PATH_CNTRL."/login.controller.php");
	_login_attempt();
	
}
function __do_get_logout() {

	session_destroy();
	header("Location: /login");
	exit();
}

function __do_get_archive() {
	secure();		
	view("empty");
}

function __do_get_aserial() {
	secure();		
	view("aserial");
}

function __do_get_admin($part) {
	secure();		
	view("admin", [ "part" => $part] );
}

function __do_post_adminajax($part) {
	secure();				
	$action= $_POST["action"];

	require_once(PATH_CNTRL."/admin.controller.php");
}

function __do_get_accstocks($part) {
	secure();		
	view("accstocks", [ "part" => $part] );
}



function __do_get_stocks($part) {
	secure();		
	view("stocks", [ "part" => $part] );
}

function __do_get_report($part) {
	secure();		
	view("report", [ "part" => $part] );
}


function __do_get_tool2($part) {
	secure();		
	view("tool2", [ "part" => $part] );
}
function __do_post_tool2($part) {
	secure();		
	view("tool2", [ "part" => $part] );
}

function __do_post_tool2ajax($part) {
	secure();
	$action = $_POST["action"];
	require_once(PATH_CNTRL."/tool2.controller.php");
}



function __do_get_tool($part) {
	secure();		
	view("tool", [ "part" => $part] );
}
function __do_post_tool($part) {
	secure();		
	view("tool", [ "part" => $part] );
}

function __do_post_toolajax($part) {
	secure();
	$action = $_POST["action"];
	require_once(PATH_CNTRL."/tool.controller.php");
}

function __do_get_stock($id) {
	secure();		
	view("stock", [ "id" => $id] );
}



function __do_get_accorder($id) {
	secure();		
	view("accorder", [ "id" => $id] );
}

function __do_get_accorders() {
	secure();		
	view("accorders", [  ] );
}

function __do_post_accordersajax() {
	secure();	
	$action= $_POST["action"];				
	require_once(PATH_CNTRL."/accorder.controller.php");
}
function __do_get_accorderin($id) {
	secure();		
	view("accorder", [ "id" => $id, "in"=>true] );
}


function __do_get_orders() {
	secure();		
	view("orders", [  ] );
}

function __do_post_ordersajax() {
	secure();	
	$action= $_POST["action"];				
	require_once(PATH_CNTRL."/order.controller.php");
}

function __do_get_order($id) {
	secure();		
	view("order", [ "id" => $id] );
}

function __do_get_orderin($id) {
	secure();		
	view("order", [ "id" => $id, "in"=>true] );
}

function __do_get_neworder() {
	secure();		
	view("order", [ ] );
}
function __do_get_newaccorder() {
	secure();		
	view("accorder", [ ] );
}
function __do_post_accstocksprintajax($part){
	
	secure();
	view("accstocksprint", ["part" => $part]);
}
function __do_get_accstocksprintajax($part){
	
	secure();
	
	view("accstocksprint", ["part" => $part]);
}
function __do_post_stocksprintajax($part){
	
	secure();
	view("stocksprint", ["part" => $part]);
}
function __do_get_stocksprintajax($part){
	
	secure();
	view("stocksprint", ["part" => $part]);
}
function __do_post_stocksajax($part) {
	secure();				
	$action= $_POST["action"];
	require_once(PATH_CNTRL."/stock.controller.php");
}

function __do_post_accstocksajax($part) {
	secure();				
	$action= $_POST["action"];
	require_once(PATH_CNTRL."/accstock.controller.php");
}
function __do_get_label() {
	secure();		
	view("label", [  ] );
}
function __do_post_labelajax($part) {
	secure();				
	$action= $_POST["action"];
	require_once(PATH_CNTRL."/label.controller.php");
}

function __do_get_dispatch() {
	secure();		
	view("dispatch", [  ] );
}
function __do_post_dispatchajax($part) {
	secure();				
	$action= $_POST["action"];
	require_once(PATH_CNTRL."/dispatch.controller.php");
}

/* Handle requests */
if (!fmap( [ ], '__do' )) {	
	http_response_code(404);
	view('layout/head');
	view('layout/partial/navigation');
	view( '404' );
}

if(!$isAjax)
	view('layout/foot');


function __do_get_newitemorder($id) {
	secure();		
	view("newitemorder", [ "id" => $id] );
}

function __do_get_newitemorders() {
	secure();		
	view("newitemorders", [  ] );
}

function __do_post_newitemordersajax() {
	secure();	
	$action= $_POST["action"];				
	require_once(PATH_CNTRL."/newitemorder.controller.php");
}
function __do_get_newitemorderin($id) {
	secure();		
	view("newitemorder", [ "id" => $id, "in"=>true] );
}
function __do_get_newitemstocks($part) {
	secure();		
	view("newitemstocks", [ "part" => $part] );
}
function __do_post_newitemstocksajax($part) {
	secure();				
	$action= $_POST["action"];
	require_once(PATH_CNTRL."/newitemstock.controller.php");
}
function __do_get_newrmac() {
	secure();		
	view("newrmac", [  ] );
}
function __do_post_newrmacajax($part) {
	secure();				
	$action= $_POST["action"];
	require_once(PATH_CNTRL."/newrmac.controller.php");
}
