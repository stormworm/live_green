<?php 
class Connector extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function addNewUser($name, $email, $password, $picture, $house_size, $num_family_members, $heat_type){
		$data = array(
			'name'=>$name,
			'email'=>$email,
			'password'=>$password,
			'picture'=>$picture,
			'house_size'=>$house_size,
			'num_family_members'=>$num_family_members,
			'heat_type'=>$heat_type	
		);	

		try{
			return $this->db->insert('users', $data);
		} catch (Exception $e){
			return NULL;
		}		
	}	
	
	function login($email, $password){
		$query_string = "SELECT *
						 FROM users
						 WHERE email = '" . $email .
						 "' AND password = '" . $password . "'"; 		
		return $this->db->query($query_string);
	}
}

?>