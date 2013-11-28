<?php 
class main extends CI_Controller {

	public function handler(){
		if (isset($_GET["module"])){
			$module = $_GET["module"];
			switch($module){
				case "addUser":
					$this->addUser();
					break;
				case "login":
					$this->login();
					break;
				case "addFriendship":
					$this->addFriendship();
					break;
				case "getFriendships":
					$this->getFriendships();
					break;
				case "addAchievement":
					$this->addAchievement();
					break;
				case "getFriendshipsWithEmail":
					$this->getFriendshipsWithEmail();
					break;
				case "getUserByID":
					$this->getUserByID();
					break;	
				case "getUserByEmail":
					$this->getUserByEmail();
					break;				
			}
		} else {
			echo "ERROR";
		}
	}	

	public function addUser(){
		$this->load->model("connector");
		if (isset($_GET["name"]) && isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["house_size"]) && isset($_GET["num_family_members"]) && isset($_GET["heat_type"])){
			try{									
				if (isset($_GET['picture'])){
					$picture = $_GET['picture'];
				} else {
					$picture = "";
				}
				$retData = $this->connector->addNewUser($_GET["name"], $_GET["email"], $_GET["password"], $picture,$_GET["house_size"], $_GET["num_family_members"], $_GET["heat_type"]);
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
			$this->load->model("connector");
			$res = $this->connector->login($_GET["email"], $_GET["password"]);
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
		if (isset($_GET["uid_1"]) && isset($_GET["friend_email"])){
			$this->load->model("connector");
			$friend_id = $this->connector->getUserIDFromEmail($_GET["friend_email"]);
			$ret_data = $this->connector->addNewFriendship($_GET["uid_1"], $friend_id);	
			if ($ret_data != NULL){
				$data = array("uid_1"=> $_GET["uid_1"], "uid_2"=>$friend_id);
				echo "[" . json_encode($data) . "]";
			} else {
				echo "ERROR: FRIENDSHIP NOT ADDED";	
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function getFriendships(){
		if (isset($_GET["uid"])){
			$this->load->model("connector");
			$result = $this->connector->getFriendships($_GET["uid"]);
			if ($result->result_array() != NULL){
				echo json_encode($result->result_array());
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function getFriendshipsWithEmail(){
		if (isset($_GET["email"])){
			$this->load->model("connector");
			$uid = $this->connector->getUserIDFromEmail($_GET["email"]);
			$result = $this->connector->getFriendships($uid);
			if ($result->result_array() != NULL){
				echo json_encode($result->result_array());
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function getUserByID(){
		if (isset($_GET["uid"])){
			$this->load->model("connector");
			$result = $this->connector->getUserByID($_GET["uid"]);			
			if ($result->result_array() != NULL){
				echo json_encode($result->result_array());
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function getUserByEmail(){
		if (isset($_GET["email"])){
			$this->load->model("connector");
			$result = $this->connector->getUserByEmail($_GET["email"]);			
			if ($result->result_array() != NULL){
				echo json_encode($result->result_array());
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	
	}

	public function addAchievement(){
		$this->load->model("connector");
		$s = (string)$this->connector->getUserIDFromEmail($_GET["friend_email"]);
		echo $s;
	}

	public function addDayEntry($uid, $cost, $start, $duration){
		if (isset($uid) && isset($cost) && isset($start) && isset($duration)){
			$this->load->model("connector");
			// TODO: convert start to day
			$result = $this->connector->addDayData($start, $duration, $cost);			
		} else {
			return;
		}
	}


}
?>
