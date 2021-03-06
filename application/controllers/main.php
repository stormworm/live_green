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
					break;
				case "weekData":
					$this->weekData();
					break;
				case "monthData":
					$this->monthData();
					break;				
				case "yearData":
					$this->yearData();
					break;				
				case "sendInvite":
					$this->sendInvite();
					break;
				case "getInvites":
					$this->getInvites();
					break;
				case "getRequests":
					$this->getRequests();
					break;
				case "getFriendships":
					$this->getFriendships();
					break;
				case "removeFriendship":
					$this->removeFriendship();
					break;
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

	public function sendInvite(){
		if (isset($_GET["uid_1"]) && isset($_GET["friend_email"])){
			$this->load->model("connector");
			$friend_id = $this->connector->getUserIDFromEmail($_GET["friend_email"]);
			if ($friend_id == NULL){
				echo "USER " . $_GET["friend_email"] . " DOES NOT EXIST";				
			} else {
				$ret_data = $this->connector->setInvite($_GET["uid_1"], $friend_id);
				if ($ret_data != NULL){
					echo "SUCCESS";
				} else {
					echo "ERROR: FRIENDSHIP NOT ADDED";	
				}
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function addFriendship(){		
		if (isset($_GET["uid_1"]) && isset($_GET["uid_2"])){
			$this->load->model("connector");
			$friend_id = $_GET["uid_2"];
			$ret_data = $this->connector->addNewFriendship($_GET["uid_1"], $friend_id);	
			if ($ret_data){
				echo "SUCCESS";
			} else {
				echo "ERROR: FRIENDSHIP NOT ADDED";	
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function friendSequence($uid, $stat){
			$this->load->model("connector");			
			$result = $this->connector->getFriendships($uid);
			$jsonRes = array();
			foreach ($result->result() as $row){
				$status = intval($row->status);
				if ($status == $stat){
					$resArray = array();				
					$uid = intval($row->uid);
					$friendName = $this->getUserInfo($uid)->row(0)->name;
					$resArray = array("uid"=>$uid, "name"=>$friendName, "week"=>$this->curWeekDataByUser($uid), 
						"month"=>$this->curMonthDataByUser($uid),"year"=>$this->curYearDataByUser($uid));
					$jsonRes[] = $resArray;
				}
			}
			echo json_encode($jsonRes);		
	}

	public function getInvites(){
		if (isset($_GET["uid"])){
			$this->friendSequence($_GET["uid"], 2);
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function getRequests(){
		if (isset($_GET["uid"])){
			$this->friendSequence($_GET["uid"], 1);
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}
	}

	public function getFriendships(){
		if (isset($_GET["uid"])){
			$this->friendSequence($_GET["uid"], 0);
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

	public function getUserInfo($uid){
		$this->load->model("connector");
		$result = $this->connector->getUserByID($uid);			
		if ($result->result_array() != NULL){
			return $result;
		} else {
			return NULL;
		}		
	}

	public function removeFriendship(){
		if (isset($_GET["uid_1"]) && isset($_GET["uid_2"])){
			$this->load->model("connector");
			$friend_id = $_GET["uid_2"];
			$ret_data = $this->connector->removeFriendship($_GET["uid_1"], $friend_id);	
			if ($ret_data){
				echo "SUCCESS";
			} else {
				echo "ERROR: FRIENDSHIP NOT ADDED";	
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
			$xml = simplexml_load_file(base_url() . "xml/hourlyForMonthSep.xml");
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
								$start = date('Y-m-d H:i:s', strtotime($start. '+ 1 years'));
								$this->addDayEntry($uid, $cost, $start, $duration, $usage);
							} catch (Exception $e){
								echo "Failed to add data";
							}
							
						}
					}
				}		
			}
			echo "SUCCESS";
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
			$endDate =  date('Y-m-d', strtotime($startDate . ' + 1 days'));

			$result = $this->rangeDataRaw($_GET["uid"], $startDate, $endDate);

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

	public function findSimilarUsers(){
		if (isset($_GET["uid"]) && isset($_GET["house_size"]) && isset($_GET["num_users"])){
			$this->load->model("connector");
			if (isset($_GET["heat_type"])){
				$houseSize = intval($_GET["house_size"]);
				$numUsers = intval($_GET["num_users"]);
				$result = $this->connector->getRelatedUsersWithHeat($uid, $houseSize, $numUsers, $_GET["heat_type"]);
			} else {
				$result = $this->connector->getRelatedUsers($uid, $houseSize, $numUsers);
			}
			if ($result->result_array() != NULL){
				echo json_encode($result->result_array());
			} else {
				echo "ERROR";
			}

		} else {
			echo "ERROR";
		}
	}

	public function rangeDataRaw($uid, $start, $end){
		if (isset($_GET["uid"])){
			$this->load->model("connector");			
			$result = $this->connector->getUsageBetween($uid, $start , $end);
			if ($result->result_array() != NULL){
				$return = array();				
				$return[] = $result->row(0);				
				return $result;
			} else {
				echo "ERROR";
			}
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	
	}

	public function dayDataRange(){
		if (isset($_GET["uid"])){			
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
			//find the difference between the two dates
			$startDateWithSeconds = $startDate . " 00:00:00";
			$endDateWithSeconds = $endDate . " 00:00:00";
			
			$d_start = new DateTime($startDateWithSeconds);
			$d_end = new DateTime($endDateWithSeconds);
			$diff = $d_start->diff($d_end);
			$day_diff = $diff->format("%d");			
			$return = array();

			for ($i = 0; $i <= $day_diff; $i++) {
				$nextDay = date('Y-m-d', strtotime($startDate. ' + 1 days'));
				$result = $this->rangeDataRaw($_GET["uid"], $startDate, $nextDay);
				if ($result->result_array() != NULL){			
					$return[] = $result->row(0);
				} else {
					echo "ERROR";
				}				
				$startDate = $nextDay;
			}
			echo json_encode($return);
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	
	}

	public function weekData(){
		if (isset($_GET["uid"])){			
			if (isset($_GET["date"])){
				$date = $_GET["date"];
			} else {
				$date = date("Y-m-d");
			}

			if (!isset($_GET["prev"])){
				$prevWeeks = 0;
			} else {
				$prevWeeks = intval($_GET["prev"]);
			}
			$startOfWeek = $this->getStartOfWeek($date);
			$endDate = date('Y-m-d', strtotime($date. ' + 1 days'));
			$result = array();
			$res = $this->rangeDataRaw($_GET["uid"], $startOfWeek, $endDate)->row(0);
			if ($res->date != null){
					$result[] = $res;
			}			
			$i = 1;	
			while ($i <= $prevWeeks){
				$addWeek = " + 1 weeks"; 
				$subWeek = " - " . $i . " weeks"; 
				$start = date('Y-m-d', strtotime($startOfWeek . $subWeek));
				$end = date('Y-m-d', strtotime($start . $addWeek));				
				$res = $this->rangeDataRaw($_GET["uid"], $start, $end)->row(0);
				if ($res->date != null){
					$result[] = $res;
				}			
				$i++;
			}
			$result = array_reverse($result);
			echo json_encode($result);
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	
	}
	
	public function curWeekDataByUser($uid){
		$date = date("Y-m-d");
		$start = $this->getStartOfWeek($date);
		$end = date('Y-m-d', strtotime($date. ' + 1 days'));
		$res = $this->rangeDataRaw($uid, $start, $end)->row(0);
		if ($res->date != null){
			return $res->usage;
		} else {
			return 0;
		}		
	}

	public function curMonthDataByUser($uid){
		$date = date("Y-m-d");
		$start = $this->getStartOfMonth($date);
		$end = date('Y-m-d', strtotime($date. ' + 1 days'));
		$res = $this->rangeDataRaw($uid, $start, $end)->row(0);
		if ($res->date != null){
			return $res->usage;
		} else {
			return 0;
		}		
	}

	public function curYearDataByUser($uid){
		$date = date("Y-m-d");
		$start = $this->getStartOfYear($date);
		$end = date('Y-m-d', strtotime($date. ' + 1 days'));
		$res = $this->rangeDataRaw($uid, $start, $end)->row(0);
		if ($res->date != null){
			return $res->usage;
		} else {
			return 0;
		}
	}

	public function monthData(){
		if (isset($_GET["uid"])){			
			if (isset($_GET["date"])){
				$date = $_GET["date"];
			} else {
				$date = date("Y-m-d");
			}

			if (!isset($_GET["prev"])){
				$prevMonths = 0;
			} else {
				$prevMonths = intval($_GET["prev"]);
			}

			$startDate = $this->getStartOfMonth($date);
			//Include current day
			$endDate = date('Y-m-d', strtotime($date. ' + 1 days'));
			$result = array();
			$res = $this->rangeDataRaw($_GET["uid"], $startDate, $endDate)->row(0);
			if ($res->date != null){
					$result[] = $res;
			}
			$i = 1;
			while ($i <= $prevMonths){	
				$addMonth = " + 1 months"; 
				$subMonth = " - " . $i . " months"; 
				$start = date('Y-m-d', strtotime($startDate . $subMonth));
				$end = date('Y-m-d', strtotime($start . $addMonth));
				$res =	$this->rangeDataRaw($_GET["uid"], $start, $end)->row(0);	
				if ($res->date != null){
					$result[] = $res;
				}				
				$i++;
			}
			$result = array_reverse($result);
			echo json_encode($result);

		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	
	}

	public function yearData(){
		if (isset($_GET["uid"])){
			if (isset($_GET["date"])){
				$date = $_GET["date"];
			} else {
				$date = date("Y-m-d");
			}
			if (!isset($_GET["prev"])){
				$prevYears = 0;
			} else {
				$prevYears = intval($_GET["prev"]);
			}
			$startOfYear = $this->getStartOfYear($date);
			$endDate = date('Y-m-d', strtotime($date. ' + 1 days'));
			$result = array();			
			$res = $this->rangeDataRaw($_GET["uid"], $startOfYear, $endDate)->row(0);
			if ($res->date != null){
					$result[] = $res;
			}
			$i = 1;	
			while ($i <= $prevYears){
				$add = " + 1 years"; 
				$sub = " - " . $i . " years"; 
				$start = date('Y-m-d', strtotime($startOfYear . $sub));
				$end = date('Y-m-d', strtotime($start . $add));
				$res = $this->rangeDataRaw($_GET["uid"], $start, $end)->row(0);
				if ($res->date != null){
					$result[] = $res;
				}
				$i++;
			}
			$result = array_reverse($result);
			echo json_encode($result);
		} else {
			echo "ERROR: NOT ALL FIELDS FILLED";
		}	
	}

	function getStartOfWeek($inDate) {
		$date = strtotime( date('Y-m-d', strtotime($inDate))); 		
		if ($num = date('N', strtotime($inDate)) == 7){
			$start = date('Y-m-d', $date);
		} else {
		    $start = strtotime('this week last sunday', $date);	    
		    $start = date('Y-m-d', $start);		    
		}	
		return $start;   
	}

	function getStartOfMonth($inDate) {
		$date = date('Y-m-01', strtotime($inDate)); 				
		return $date;
	}

	function getStartOfYear($inDate) {
		$date = date('Y-01-01', strtotime($inDate)); 				
		return $date;
	}
}
?>
