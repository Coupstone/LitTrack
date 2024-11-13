<?php
require_once '../config.php';

class Login extends DBConnection {
    private $settings;
    
    public function __construct() {
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
        ini_set('display_errors', 1); // Fix typo: should be `display_errors`
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function index() {
        echo "<h1>Access Denied</h1> <a href='" . base_url . "'>Go Back.</a>";
    }
    
    // Admin login
    public function login() {
        extract($_POST);
        
        $qry = $this->conn->query("SELECT * FROM users WHERE username = '$username' AND password = md5('$password')");
        
        if ($qry->num_rows > 0) {
            $res = $qry->fetch_array();
            if ($res['status'] != 1) {
                return json_encode(['status' => 'notverified']);
            }
            foreach ($res as $k => $v) {
                if (!is_numeric($k) && $k != 'password') {
                    $this->settings->set_userdata($k, $v);
                }
            }
            // Set `user_id` in session for admins
            $_SESSION['user_id'] = $res['id']; // Assuming 'id' is the column name for admin user ID
            $this->settings->set_userdata('login_type', 1); // 1 for admin login type
            return json_encode(['status' => 'success']);
        } else {
            return json_encode(['status' => 'incorrect', 'last_qry' => "SELECT * FROM users WHERE username = '$username' AND password = md5('$password')"]);
        }
    }
    
    public function logout() {
        if ($this->settings->sess_des()) {
            redirect('./choices.php');
        }
    }
    
    // Student login
    public function student_login() {
        extract($_POST);
        
        $qry = $this->conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS fullname FROM student_list WHERE email = '$email' AND password = md5('$password')");
        
        if ($this->conn->error) {
            $resp['status'] = 'failed';
            $resp['msg'] = "An error occurred while fetching data. Error: " . $this->conn->error;
        } else {
            if ($qry->num_rows > 0) {
                $res = $qry->fetch_array();
                if ($res['status'] == 1) {
                    foreach ($res as $k => $v) {
                        $this->settings->set_userdata($k, $v);
                    }
                    // Set `student_id` in session for students
                    $_SESSION['student_id'] = $res['id']; // Assuming 'id' is the column name for student ID
                    $this->settings->set_userdata('login_type', 2); // 2 for student login type
                    $resp['status'] = 'success';
                } else {
                    $resp['status'] = 'failed';
                    $resp['msg'] = "Your Account is not verified yet.";
                }
            } else {
                $resp['status'] = 'failed';
                $resp['msg'] = "Invalid email or password.";
            }
        }
        
        return json_encode($resp);
    }
    
    public function student_logout() {
        if ($this->settings->sess_des()) {
            redirect('./choices.php');
        }
    }
}

$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();

switch ($action) {
    case 'login':
        echo $auth->login();
        break;
    case 'logout':
        echo $auth->logout();
        break;
    case 'student_login':
        echo $auth->student_login();
        break;
    case 'student_logout':
        echo $auth->student_logout();
        break;
    default:
        echo $auth->index();
        break;
}
