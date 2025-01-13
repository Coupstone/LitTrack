<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_department(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `department_list` set {$data} ";
		}else{
			$sql = "UPDATE `department_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `department_list` where `name`='{$name}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Department Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Department details successfully added.";
				else
					$resp['msg'] = "Department details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_department(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `department_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Department has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_curriculum(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `curriculum_list` set {$data} ";
		}else{
			$sql = "UPDATE `curriculum_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `curriculum_list` where `name`='{$name}' and `department_id` = '{department_id}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Curriculum Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Curriculum details successfully added.";
				else
					$resp['msg'] = "Curriculum details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_curriculum(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `curriculum_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Curriculum has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_archive() {
		if (empty($_POST['id'])) {
			$pref = date("Ym");
			$code = sprintf("%'.04d", 1);
			while (true) {
				$check = $this->conn->query("SELECT * FROM `archive_list` WHERE archive_code = '{$pref}{$code}'")->num_rows;
				if ($check > 0) {
					$code = sprintf("%'.04d", abs($code) + 1);
				} else {
					break;
				}
			}
			$_POST['archive_code'] = $pref . $code;
			$_POST['student_id'] = $this->settings->userdata('id');
			$_POST['curriculum_id'] = $this->settings->userdata('curriculum_id');
		}
		
		if (isset($_POST['abstract'])) {
			$_POST['abstract'] = htmlentities($_POST['abstract']);
		}
		
		extract($_POST);
		$data = "";
		
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'author_firstname', 'author_lastname')) && !is_array($_POST[$k])) {
				if (!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
	
		if (empty($id)) {
			$sql = "INSERT INTO `archive_list` SET {$data}";
		} else {
			$sql = "UPDATE `archive_list` SET {$data} WHERE id = '{$id}'";
		}
	
		$save = $this->conn->query($sql);
		if ($save) {
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['id'] = $aid;
	
			// Insert authors
			if (!empty($_POST['author_firstname']) && is_array($_POST['author_firstname'])) {
				$this->conn->query("DELETE FROM `archive_authors` WHERE archive_id = '{$aid}'"); // Clear existing authors if updating
				foreach ($_POST['author_firstname'] as $index => $first_name) {
					$last_name = $_POST['author_lastname'][$index];
					$first_name = $this->conn->real_escape_string($first_name);
					$last_name = $this->conn->real_escape_string($last_name);
	
					$this->conn->query("INSERT INTO `archive_authors` (archive_id, first_name, last_name) VALUES ('{$aid}', '{$first_name}', '{$last_name}')");
				}
			}
	
			if (empty($id)) {
				$resp['msg'] = "Archive was successfully submitted";
			} else {
				$resp['msg'] = "Archive details were updated successfully.";
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred.";
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
	
		return json_encode($resp);
	}
	
	
	public function delete_archive() {
		extract($_POST);
		error_log("Deleting archive with ID: {$id}");

		// Delete related records in archive_reads table
		$del_reads = $this->conn->query("DELETE FROM `archive_reads` WHERE archive_id = '{$id}'");
		if (!$del_reads) {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			error_log("Delete response: " . json_encode($resp));
			header('Content-Type: application/json');
			echo json_encode($resp);
			exit;
		}

		// Delete related records in lda_topics table
		$del_topics = $this->conn->query("DELETE FROM `lda_topics` WHERE paper_id = '{$id}'");
		if (!$del_topics) {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			error_log("Delete response: " . json_encode($resp));
			header('Content-Type: application/json');
			echo json_encode($resp);
			exit;
		}

		// Delete the archive
		$del = $this->conn->query("DELETE FROM `archive_list` WHERE id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Archive has been deleted successfully.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		error_log("Delete response: " . json_encode($resp));
		header('Content-Type: application/json');
		echo json_encode($resp);
		exit;
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_department':
		echo $Master->save_department();
	break;
	case 'delete_department':
		echo $Master->delete_department();
	break;
	case 'save_curriculum':
		echo $Master->save_curriculum();
	break;
	case 'delete_curriculum':
		echo $Master->delete_curriculum();
	break;
	case 'save_archive':
		echo $Master->save_archive();
	break;
	case 'delete_archive':
		echo $Master->delete_archive();
	break;
	case 'update_status':
		echo $Master->update_status();
	break;
	case 'save_payment':
		echo $Master->save_payment();
	break;
	case 'delete_payment':
		echo $Master->delete_payment();
	break;
	default:
		// echo $sysset->index();
		break;
}