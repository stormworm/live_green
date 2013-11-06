<?php 

class main extends CI_Controller {

	public function handler(){
		if (isset $_GET["module"]){
			$module = $_GET["module"];
			switch($module){
				case "addUser":
					addUser();
					break;
				case "login":
					login();
					break;
				case "addFriendship":
					addFriendship();
					break;
				case "addAchievement":
					addAchievement();
					break;
			}
		} else {
			echo "ERROR";
		}
	}	

	public function addUser(){		
		$this->load->model("users");
		if (isset($_GET["name"]) && isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["house_size"]) && isset($_GET["num_family_members"]) && isset($_GET["heat_type"])){
			try{									
				if (isset($_GET['picture'])){
					$picture = $_GET['picture'];
				} else {
					$picture = "";
				}
				$retData = $this->users->addNewUser($_GET["name"], $_GET["email"], $_GET["password"], $picture,$_GET["house_size"], $_GET["num_family_members"], $_GET["heat_type"]);
				if ($retData){
					$data =  array("name"=>$_GET["name"],
							   "email"=>$_GET["email"],
							   "password"=>$_GET["password"],
							   "picture"=>$picture,
							   "house_size"=>$_GET["house_size"],
							   "num_family_members"=>$_GET["num_family_members"],
							   "heat_type"=>$_GET["heat_type"]
							);
					echo "[" . json_encode($data) . "]";
				} else {
					echo "ERROR: USER NOT ADDED";
				}				
			} catch (Exception $e) {
				echo "ERROR: DATABASE ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function login(){
		if (isset($_GET["email"]) && isset($_GET["password"])){
			$this->load->model("users");
			$res = $this->users->login($_GET["email"], $_GET["password"]);
			if ($res->result_array() != null){
				echo json_encode($res->result_array());
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR";
		} 
	}
	
	public function addFriendship(){		
		return;
	}

	public function addAchievement(){
		return;
	}
	
}
?>