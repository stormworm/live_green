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
				case "dayData":
					$this->dayData();
					break;
				case "dayDataRange":
					$this->dayDataRange();			
			}
		} else {
			echo "ERROR";
		}
	}	

	public function addUser(){
		$this->load->model("connector");
		if (isset($_GET["name"]) && isset($_GET["email"]) && isset($_GET["password"])){
			try{									
				$retData = $this->connector->addNewUser($_GET["name"], $_GET["email"], $_GET["password"]);
				if ($retData){					
					echo "SUCCESS";
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

	public function addDayEntry($uid, $cost, $start, $duration, $usage){
		if (isset($uid) && isset($cost) && isset($start) && isset($duration)){
			$this->load->model("connector");
			// TODO: convert start to day
			$result = $this->connector->addDailyEntry($uid, $cost, $start, $duration, $usage);			
		} else {
			return;
		}
	}


	public function getDailyUsage(){
		if (isset($_GET["uid"])){
			$this->load->model("connector");
			$result = $this->connector->getDailyUsage($_GET["uid"]);			
			if ($result->result_array() != NULL){
				echo json_encode($result->result_array());
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	
	}



	public function scrapeXMLFile(){
		try{			
			$this->load->helper('url');			
			$this->load->helper('date');
			$xml = simplexml_load_file(base_url() . "xml/1hrLP_32Days.xml");
			$i = 0;
			foreach($xml->entry as $ent){
			if(isset($ent->content->IntervalBlock)){
				foreach($ent->content->IntervalBlock as $block){
					foreach($block->IntervalReading as $record){
						$cost = $record->cost[0];
						$usage = $record->value[0];						
						$start = $record->timePeriod[0]->start[0];
						$duration = $record->timePeriod[0]->duration[0];
						if (isset($_GET["uid"])){
							$uid = $_GET["uid"];
						} else {
							$uid = 1;
						}
						try{
							$start = gmdate('Y-m-d H:i:s', intval($start));
							$this->addDayEntry($uid, $cost, $start, $duration, $usage);
						} catch (Exception $e){
							echo "Failed to add data";
						}
						
						}
					}
				}		
			}
		} catch(Exception $e){
			echo "Failed";
		}
	}

	public function dayData(){
		if (isset($_GET["uid"])){
			$this->load->model("connector");
			if (isset($_GET["date"])){
				$date = $_GET["date"];
			} else {
				$date = date("Y-m-d");
			}
			$startDate = $date . " 00:00:00";
			$endDate = $date . " 23:00:00";
						
			$result = $this->connector->getUsageBetween($_GET["uid"], $startDate , $endDate);

			if ($result->result_array() != NULL){
				$return = array();				
				$return[] = $result->row(0);				
				echo json_encode($return);
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	
	}

	public function dayDataRange(){
		if (isset($_GET["uid"])){
			$this->load->model("connector");
			if (isset($_GET["end_date"])){
				$endDate = $_GET["end_date"];
			} else {
				$endDate = date("Y-m-d");
			}
			if (isset($_GET["start_date"])){
				$startDate = $_GET["start_date"];
			} else {
				$startDate = date("Y-m-d");
			}

			$startDate = $startDate . " 00:00:00";
			$endDate = $endDate . " 23:00:00";
						
			$result = $this->connector->getUsageBetween($_GET["uid"], $startDate , $endDate);

			if ($result->result_array() != NULL){
				$return = array();				
				$return[] = $result->row(0);				
				echo json_encode($return);
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	

	}
}
?>
