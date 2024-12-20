<?php
if(!class_exists('DBConnection')){
    require_once('../config.php');
    require_once('DBConnection.php');
}

class SystemSettings extends DBConnection {
    public function __construct() {
        parent::__construct();
    }

    function check_connection() {
        return($this->conn);
    }

    function load_system_info() {
        $sql = "SELECT * FROM system_info";
        $qry = $this->conn->query($sql);
        while($row = $qry->fetch_assoc()) {
            $_SESSION['system_info'][$row['meta_field']] = $row['meta_value'];
        }
    }

    function update_system_info() {
        $sql = "SELECT * FROM system_info";
        $qry = $this->conn->query($sql);
        while($row = $qry->fetch_assoc()) {
            if(isset($_SESSION['system_info'][$row['meta_field']])) unset($_SESSION['system_info'][$row['meta_field']]);
            $_SESSION['system_info'][$row['meta_field']] = $row['meta_value'];
        }
        return true;
    }

    function update_settings_info() {
        $resp = ['msg' => '']; // Initialize the $resp array

        foreach ($_POST as $key => $value) {
            if(!in_array($key, array("content"))) {
                $value = str_replace("'", "&apos;", $value);
                if(isset($_SESSION['system_info'][$key])) {
                    $qry = $this->conn->query("UPDATE system_info SET meta_value = '{$value}' WHERE meta_field = '{$key}' ");
                } else {
                    $qry = $this->conn->query("INSERT INTO system_info SET meta_value = '{$value}', meta_field = '{$key}' ");
                }
            }
        }

        if(isset($_POST['content'])) {
            foreach($_POST['content'] as $k => $v) {
                file_put_contents("../{$k}.html", $v);
            }
        }

        if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $resp['msg'] .= $this->upload_image($_FILES['img'], 'logo', 200, 200);
        }

        if(isset($_FILES['cover']) && $_FILES['cover']['tmp_name'] != '') {
            $resp['msg'] .= $this->upload_image($_FILES['cover'], 'cover', 1280, 720);
        }

        $update = $this->update_system_info();
        $flash = $this->set_flashdata('success', 'System Info Successfully Updated.');
        if($update && $flash) {
            return json_encode($resp);
        }
    }

    function upload_image($file, $field_name, $new_width, $new_height) {
        $resp_msg = '';
        $fname = 'uploads/'.$field_name.'-'.time().'.png';
        $dir_path = base_app . $fname;
        $upload = $file['tmp_name'];
        $type = mime_content_type($upload);
        $allowed = array('image/png', 'image/jpeg');

        if(!in_array($type, $allowed)) {
            $resp_msg .= "But image failed to upload due to invalid file type.";
        } else {
            list($width, $height) = getimagesize($upload);
            $t_image = imagecreatetruecolor($new_width, $new_height);
            $gdImg = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
            imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            if($gdImg) {
                if(is_file($dir_path)) unlink($dir_path);
                $uploaded_img = imagepng($t_image, $dir_path);
                imagedestroy($gdImg);
                imagedestroy($t_image);
            } else {
                $resp_msg .= " But image failed to upload due to unknown reason.";
            }

            if(isset($uploaded_img) && $uploaded_img == true) {
                if(isset($_SESSION['system_info'][$field_name])) {
                    $qry = $this->conn->query("UPDATE system_info SET meta_value = '{$fname}' WHERE meta_field = '{$field_name}' ");
                    if(is_file(base_app . $_SESSION['system_info'][$field_name])) unlink(base_app . $_SESSION['system_info'][$field_name]);
                } else {
                    $qry = $this->conn->query("INSERT INTO system_info SET meta_value = '{$fname}', meta_field = '{$field_name}' ");
                }
                unset($uploaded_img);
            }
        }

        return $resp_msg;
    }

    function set_userdata($field='', $value='') {
        if(!empty($field) && !empty($value)) {
            $_SESSION['userdata'][$field] = $value;
        }
    }

    function userdata($field = '') {
        if(!empty($field)) {
            if(isset($_SESSION['userdata'][$field]))
                return $_SESSION['userdata'][$field];
            else
                return null;
        } else {
            return false;
        }
    }

    function set_flashdata($flash='', $value='') {
        if(!empty($flash) && !empty($value)) {
            $_SESSION['flashdata'][$flash] = $value;
            return true;
        }
    }

    function chk_flashdata($flash = '') {
        if(isset($_SESSION['flashdata'][$flash])) {
            return true;
        } else {
            return false;
        }
    }

    function flashdata($flash = '') {
        if(!empty($flash)) {
            $_tmp = $_SESSION['flashdata'][$flash];
            unset($_SESSION['flashdata']);
            return $_tmp;
        } else {
            return false;
        }
    }

    function sess_des() {
        // Start the session if it's not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Check if `$_SESSION['userdata']` exists and unset it
        if (isset($_SESSION['userdata'])) {
            unset($_SESSION['userdata']);
        }
    
        // Unset all other session variables
        $_SESSION = [];
    
        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
    
        // Destroy the session
        session_destroy();
    
        return true;
    }
    

    function info($field='') {
        if(!empty($field)) {
            if(isset($_SESSION['system_info'][$field]))
                return $_SESSION['system_info'][$field];
            else
                return false;
        } else {
            return false;
        }
    }

    function set_info($field='', $value='') {
        if(!empty($field) && !empty($value)) {
            $_SESSION['system_info'][$field] = $value;
        }
    }
}

$_settings = new SystemSettings();
$_settings->load_system_info();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
    case 'update_settings':
        echo $sysset->update_settings_info();
        break;
    default:
        // echo $sysset->index();
        break;
}
?>