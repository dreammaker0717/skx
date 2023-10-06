<?php

function _login_attempt() {
	$email = esc($_POST["emailaddress"]);
	$passwd = esc($_POST["password"]);
	$rememberme = esc(@$_POST["rememberme"])==="on";	
	$db = M::db();
	
		$data =$db->select("users","*", [
			"active" => "1",
			"deleted" => "0",
			"access[>=]" => "1",
			"username" => $email
		]);
		
		$user=null;

		if(count($data)==1) {
			$user = $data[0];
		}
		
		if($user==null) {
			view('layout/head');
			view( 'login', [  'email' => $email, 'message' => 'Invalid username or password!!' ]);			
		}
		else if($user["password"]  !== md5($passwd)) {		
			view('layout/head');
			view( 'login', [  'email' => $email, 'message' => 'Invalid username or password!' ]);			
		}
		else {
			$role = $db->get( "user_role", "*", ["ur_id" => $user["user_role"] ] );
			$_SESSION["user_id"] = $user["user_id"];
			$_SESSION["user_name"] = $user["username"];
			$_SESSION["access"] = $user["access"];
			$_SESSION["class"] = $user["class"];
			$_SESSION["session_time"] = time();
			$_SESSION["user_role"] = $user["user_role"];
			$_SESSION["user_role_name"] = $role["ur_name"];	
			if($_POST["rememberme"]=="on") {
				setcookie("email", $user["email"], time()+36000);
			}	
			header("Location: /");
			exit();
		}
		

}