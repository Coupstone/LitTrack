<?php
require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		if(!isset($_POST['status']) && $this->settings->userdata('login_type') == 1){
			$_POST['status'] = 1;
			$_POST['type'] = 2;
		}
		extract($_POST);
		$oid = $id;
		$data = '';
		if(isset($oldpassword)){
			if(md5($oldpassword) != $this->settings->userdata('password')){
				return 4;
			}
		}
		$chk = $this->conn->query("SELECT * FROM `users` where username ='{$username}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		foreach($_POST as $k => $v){
			if(in_array($k,array('firstname','middlename','lastname','username','type'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}

		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$id = $this->conn->insert_id;
				$this->settings->set_flashdata('success','User Details successfully saved.');
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				if($id == $this->settings->userdata('id')){
					foreach($_POST as $k => $v){
						if($k != 'id'){
							if(!empty($data)) $data .=" , ";
							$this->settings->set_userdata($k,$v);
						}
					}
					
				}
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}
			
		}
		
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = 'uploads/avatar-'.$id.'.png';
			$dir_path = base_app . $fname;
			$upload = $_FILES['img']['tmp_name'];
			$type = mime_content_type($upload);
			$allowed = array('image/png', 'image/jpeg');
		
			if (in_array($type, $allowed)) {
				$new_height = 200; 
				$new_width = 200; 
		
				list($width, $height) = getimagesize($upload);
				$t_image = imagecreatetruecolor($new_width, $new_height);
				imagealphablending($t_image, false);
				imagesavealpha($t_image, true);
		
				$gdImg = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
				imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				
				if ($gdImg) {
					if (is_file($dir_path)) unlink($dir_path);  // Remove old image if exists
					$uploaded_img = imagepng($t_image, $dir_path);
					imagedestroy($gdImg);
					imagedestroy($t_image);
				} else {
					$resp['msg'] .= " But image failed to upload due to an unknown reason.";
				}
			} else {
				$resp['msg'] .= " But image failed to upload due to an invalid file type.";
			}
		
			if (isset($uploaded_img)) {
				// Update the avatar field in the database
				$this->conn->query("UPDATE student_list SET `avatar` = CONCAT('{$fname}', '?v=', unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '{$id}'");
				if ($id == $this->settings->userdata('id')) {
					$this->settings->set_userdata('avatar', $fname);
				}
			}
		}
		
		if(isset($resp['msg']))
		$this->settings->set_flashdata('success',$resp['msg']);
		return  $resp['status'];
	}
	public function delete_users(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM users where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){
			$avatar = explode("?",$avatar)[0];
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	public function save_student() {
		extract($_POST);
		$data = '';
		$resp = ['msg' => ''];

		// Check for required fields
		if (empty($firstname) || empty($lastname) || empty($email)) {
			return json_encode(array("status" => "failed", "msg" => "Please fill in all required fields."));
		}

		// Check if email is already in use
		$chk = $this->conn->query("SELECT * FROM `student_list` WHERE email ='{$email}' ".($id > 0 ? " AND id != '{$id}' " : ""))->num_rows;
		if ($chk > 0) {
			return json_encode(array("status" => "failed", "msg" => "Email is already in use."));
		}

		// Verify current password if provided
		if (!empty($oldpassword)) {
			$user = $this->conn->query("SELECT password FROM student_list WHERE id = '{$id}'");
			if ($user->num_rows > 0) {
				$storedPassword = $user->fetch_assoc()['password'];
				if (md5($oldpassword) !== $storedPassword) {
					return json_encode(array("status" => "failed", "msg" => "Current password is incorrect."));
				}
			} else {
				return json_encode(array("status" => "failed", "msg" => "User not found."));
			}
		}

		// Prepare data for insertion or update
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'oldpassword', 'cpassword', 'password'))) {
				if (!empty($data)) $data .= " , ";
				$data .= " {$k} = '{$v}' ";
			}
		}

		// Only update the password if both password fields are provided and match
		if (!empty($password) && !empty($cpassword) && $password === $cpassword) {
			$password = md5($password);
			if (!empty($data)) $data .= " , ";
			$data .= " `password` = '{$password}' ";
		} elseif (!empty($password) || !empty($cpassword)) {
			return json_encode(array("status" => "failed", "msg" => "New password and confirmation do not match."));
		}

		// Insert or update student record
		if (empty($id)) {
			$qry = $this->conn->query("INSERT INTO student_list SET {$data}");
			if ($qry) {
				$id = $this->conn->insert_id;
				$this->settings->set_flashdata('success', 'Student User Details successfully saved.');
				$resp['status'] = "success";
			} else {
				return json_encode(array("status" => "failed", "msg" => "An error occurred while saving the data. Error: ". $this->conn->error));
			}
		} else {
			$qry = $this->conn->query("UPDATE student_list SET $data WHERE id = {$id}");
			if ($qry) {
				$this->settings->set_flashdata('success', 'Student User Details successfully updated.');
				$resp['status'] = "success";
			} else {
				return json_encode(array("status" => "failed", "msg" => "An error occurred while saving the data. Error: ". $this->conn->error));
			}
		}

		// Handle image upload if provided
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$target_dir = "uploads/"; // Directory to save uploaded avatars
			if (!is_dir($target_dir)) {
				mkdir($target_dir, 0777, true);
			}

			// Generate a unique name for the uploaded file to prevent overwrites
			$file_name = 'avatar-'.$id.'.png';
			$target_file = $target_dir . $file_name;

			// Validate image file type
			$upload = $_FILES['img']['tmp_name'];
			$type = mime_content_type($upload);
			$allowed = array('image/png', 'image/jpeg');

			if (in_array($type, $allowed)) {
				// Resize and save the image
				$new_height = 200;
				$new_width = 200;
				list($width, $height) = getimagesize($upload);
				$t_image = imagecreatetruecolor($new_width, $new_height);
				imagealphablending($t_image, false);
				imagesavealpha($t_image, true);

				$gdImg = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
				imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

				if ($gdImg) {
					if (is_file(base_app . $target_file)) unlink(base_app . $target_file);  // Remove old image if exists
					$uploaded_img = imagepng($t_image, base_app . $target_file);
					imagedestroy($gdImg);
					imagedestroy($t_image);
				} else {
					$resp['msg'] .= " But image failed to upload due to an unknown reason.";
				}
			} else {
				$resp['msg'] .= " But image failed to upload due to an invalid file type.";
			}

			if (isset($uploaded_img)) {
				// Update the avatar field in the database
				$this->conn->query("UPDATE student_list SET `avatar` = CONCAT('{$target_file}', '?v=', unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '{$id}'");
				if ($id == $this->settings->userdata('id')) {
					$this->settings->set_userdata('avatar', $target_file);
				}
			}
		}

		if (isset($resp['msg']))
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}

	
	public function delete_student(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM student_list where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM student_list where id = $id");
		if($qry){
			$avatar = explode("?",$avatar)[0];
			$this->settings->set_flashdata('success','Student User Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	public function verify_student(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `student_list` set `status` = 1 where id = $id");
		if($update){
			$this->settings->set_flashdata('success','Student Account has verified successfully.');
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	case 'save_student':
		echo $users->save_student();
	break;
	case 'delete_student':
		echo $users->delete_student();
	break;
	case 'verify_student':
		echo $users->verify_student();
	break;
	default:
		// echo $sysset->index();
		break;
}