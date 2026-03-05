<?php
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function log_activity($user_type, $user_id, $username, $action, $description)
	{
		$user_type = $this->db->real_escape_string($user_type);
		$user_id = (int) $user_id;
		$username = $this->db->real_escape_string($username);
		$action = $this->db->real_escape_string($action);
		$description = $this->db->real_escape_string($description);

		$sql = "INSERT INTO activity_logs (user_type, user_id, username, action, description) 
				VALUES ('$user_type', $user_id, '$username', '$action', '$description')";
		return $this->db->query($sql);
	}

	function login()
	{
		extract($_POST);
		$type = array("", "users", "faculty_list", "student_list");
		$type2 = array("", "admin", "faculty", "student");
		$qry = $this->db->query("SELECT *,concat(firstname,' ',COALESCE(NULLIF(middlename,''),''),' ',lastname) as name FROM {$type[$login]} where username = '" . $username . "' and password = '" . md5($password) . "'  ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$_SESSION['login_type'] = $login;
			$_SESSION['login_view_folder'] = $type2[$login] . '/';
			$academic = $this->db->query("SELECT * FROM academic_list where is_default = 1 ");
			if ($academic->num_rows > 0) {
				foreach ($academic->fetch_array() as $k => $v) {
					if (!is_numeric($k))
						$_SESSION['academic'][$k] = $v;
				}
			}

			// Log login activity
			$this->log_activity($type2[$login], $_SESSION['login_id'], $username, 'Login', ucfirst($type2[$login]) . ' ' . $_SESSION['login_name'] . ' logged in');

			return 1;
		} else {
			return 2;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '" . $student_code . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['rs_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function save_user()
	{
		extract($_POST);
		$data = "";
		$password_changed = false;
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";
			$password_changed = true;
		}
		$check_username = $this->db->query("SELECT * FROM users where username ='$username' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check_username > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");
			if ($save) {
				$new_id = $this->db->insert_id;
				$this->log_activity('admin', $new_id, $username, 'Account Created', "Admin account '$username' was created");
			}
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
			if ($save && $password_changed) {
				$this->log_activity('admin', $id, $username, 'Password Changed', "Admin '$username' changed their password");
			} elseif ($save) {
				$this->log_activity('admin', $id, $username, 'Account Updated', "Admin account '$username' was updated");
			}
		}

		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
				if ($k == 'password') {
					if (empty($v))
						continue;
					$v = md5($v);

				}
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where username ='$username' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");

		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			if (empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$_SESSION['login_id'] = $id;
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user()
	{
		extract($_POST);
		$data = "";
		$type = array("", "users", "faculty_list", "student_list");
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {

				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM {$type[$_SESSION['login_type']]} where username ='$username' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (!empty($password))
			$data .= " ,password=md5('$password') ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO {$type[$_SESSION['login_type']]} set $data");
		} else {
			echo "UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id";
			$save = $this->db->query("UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id");
		}

		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$user_info = $this->db->query("SELECT CONCAT(firstname,' ',lastname) as name, username FROM users WHERE id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete) {
			$user_name = $user_info['name'] ?? '';
			$username = $user_info['username'] ?? '';
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Admin User Deleted', "Admin account '$user_name' ($username) was deleted");
			return 1;
		}
	}
	function save_system_settings()
	{
		extract($_POST);
		$data = '';
		foreach ($_POST as $k => $v) {
			if (!is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if ($_FILES['cover']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set $data where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if ($save) {
			foreach ($_POST as $k => $v) {
				if (!is_numeric($k)) {
					$_SESSION['system'][$k] = $v;
				}
			}
			if ($_FILES['cover']['tmp_name'] != '') {
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image()
	{
		extract($_FILES['file']);
		if (!empty($tmp_name)) {
			$fname = strtotime(date("Y-m-d H:i")) . "_" . (str_replace(" ", "-", $name));
			$move = move_uploaded_file($tmp_name, 'assets/uploads/' . $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path = explode('/', $_SERVER['PHP_SELF']);
			$currentPath = '/' . $path[1];
			if ($move) {
				return $protocol . '://' . $hostName . $currentPath . '/assets/uploads/' . $fname;
			}
		}
	}
	function save_subject()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM subject_list where code = '$code' and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO subject_list set $data");
			if ($save) {
				$subject_code = $code ?? '';
				$subject_name = $subject ?? '';
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Subject Created', "Subject '$subject_code - $subject_name' was created");
			}
		} else {
			$save = $this->db->query("UPDATE subject_list set $data where id = $id");
			if ($save) {
				$subject_code = $code ?? '';
				$subject_name = $subject ?? '';
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Subject Updated', "Subject '$subject_code - $subject_name' was updated");
			}
		}
		if ($save) {
			return 1;
		}
	}
	function delete_subject()
	{
		extract($_POST);
		$subject_info = $this->db->query("SELECT code, subject FROM subject_list WHERE id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM subject_list where id = $id");
		if ($delete) {
			$subject_code = $subject_info['code'] ?? '';
			$subject_name = $subject_info['subject'] ?? '';
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Subject Deleted', "Subject '$subject_code - $subject_name' was deleted");
			return 1;
		}
	}
	function save_class()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM class_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO class_list set $data");
			if ($save) {
				$class_name = ($curriculum ?? '') . ' ' . ($level ?? '') . ' - ' . ($section ?? '');
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Class Created', "Class '$class_name' was created");
			}
		} else {
			$save = $this->db->query("UPDATE class_list set $data where id = $id");
			if ($save) {
				$class_name = ($curriculum ?? '') . ' ' . ($level ?? '') . ' - ' . ($section ?? '');
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Class Updated', "Class '$class_name' was updated");
			}
		}
		if ($save) {
			return 1;
		}
	}
	function delete_class()
	{
		extract($_POST);
		$class_info = $this->db->query("SELECT curriculum, level, section FROM class_list WHERE id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM class_list where id = $id");
		if ($delete) {
			$class_name = ($class_info['curriculum'] ?? '') . ' ' . ($class_info['level'] ?? '') . ' - ' . ($class_info['section'] ?? '');
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Class Deleted', "Class '$class_name' was deleted");
			return 1;
		}
	}
	function save_academic()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM academic_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		$hasDefault = $this->db->query("SELECT * FROM academic_list where is_default = 1")->num_rows;
		if ($hasDefault == 0) {
			$data .= " , is_default = 1 ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO academic_list set $data");
			if ($save) {
				$academic_year = ($year ?? '') . ' - ' . ($semester ?? '');
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Academic Year Created', "Academic year '$academic_year' was created");
			}
		} else {
			$save = $this->db->query("UPDATE academic_list set $data where id = $id");
			if ($save) {
				$academic_year = ($year ?? '') . ' - ' . ($semester ?? '');
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Academic Year Updated', "Academic year '$academic_year' was updated");
			}
		}
		if ($save) {
			return 1;
		}
	}
	function delete_academic()
	{
		extract($_POST);
		$academic_info = $this->db->query("SELECT year, semester FROM academic_list WHERE id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM academic_list where id = $id");
		if ($delete) {
			$academic_year = ($academic_info['year'] ?? '') . ' - ' . ($academic_info['semester'] ?? '');
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Academic Year Deleted', "Academic year '$academic_year' was deleted");
			return 1;
		}
	}
	function make_default()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE academic_list set is_default = 0");
		$update1 = $this->db->query("UPDATE academic_list set is_default = 1 where id = $id");
		$qry = $this->db->query("SELECT * FROM academic_list where id = $id")->fetch_array();
		if ($update && $update1) {
			foreach ($qry as $k => $v) {
				if (!is_numeric($k))
					$_SESSION['academic'][$k] = $v;
			}

			return 1;
		}
	}
	function save_criteria()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM criteria_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}

		if (empty($id)) {
			$lastOrder = $this->db->query("SELECT * FROM criteria_list order by abs(order_by) desc limit 1");
			$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
			$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO criteria_list set $data");
			if ($save) {
				$criteria_name = $criteria ?? '';
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Criteria Created', "Criteria '$criteria_name' was created");
			}
		} else {
			$save = $this->db->query("UPDATE criteria_list set $data where id = $id");
			if ($save) {
				$criteria_name = $criteria ?? '';
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Criteria Updated', "Criteria '$criteria_name' was updated");
			}
		}
		if ($save) {
			return 1;
		}
	}
	function delete_criteria()
	{
		extract($_POST);
		$criteria_info = $this->db->query("SELECT criteria FROM criteria_list WHERE id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM criteria_list where id = $id");
		if ($delete) {
			$criteria_name = $criteria_info['criteria'] ?? '';
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Criteria Deleted', "Criteria '$criteria_name' was deleted");
			return 1;
		}
	}
	function save_criteria_order()
	{
		extract($_POST);
		$data = "";
		foreach ($criteria_id as $k => $v) {
			$update[] = $this->db->query("UPDATE criteria_list set order_by = $k where id = $v");
		}
		if (isset($update) && count($update)) {
			return 1;
		}
	}

	function save_question()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		if (empty($id)) {
			$lastOrder = $this->db->query("SELECT * FROM question_list where academic_id = $academic_id order by abs(order_by) desc limit 1");
			$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
			$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO question_list set $data");
			if ($save) {
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Question Created', "Question was created");
			}
		} else {
			$save = $this->db->query("UPDATE question_list set $data where id = $id");
			if ($save) {
				$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Question Updated', "Question was updated");
			}
		}
		if ($save) {
			return 1;
		}
	}
	function delete_question()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM question_list where id = $id");
		if ($delete) {
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Question Deleted', "Question was deleted");
			return 1;
		}
	}
	function save_question_order()
	{
		extract($_POST);
		$data = "";
		foreach ($qid as $k => $v) {
			$update[] = $this->db->query("UPDATE question_list set order_by = $k where id = $v");
		}
		if (isset($update) && count($update)) {
			return 1;
		}
	}
	function save_faculty()
	{
		extract($_POST);
		$data = "";
		$password_changed = false;
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";
			$password_changed = true;
		}
		$check = $this->db->query("SELECT * FROM faculty_list where school_id ='$school_id' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 3;
			exit;
		}
		$check_username = $this->db->query("SELECT * FROM faculty_list where username ='$username' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check_username > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO faculty_list set $data");
			if ($save) {
				$new_id = $this->db->insert_id;
				$name = $firstname . ' ' . $lastname;
				$this->log_activity('faculty', $new_id, $username, 'Account Created', "Faculty account '$name' ($username) was created");
			}
		} else {
			$save = $this->db->query("UPDATE faculty_list set $data where id = $id");
			if ($save && $password_changed) {
				$name = $firstname . ' ' . $lastname;
				$this->log_activity('faculty', $id, $username, 'Password Changed', "Faculty '$name' changed their password");
			} elseif ($save) {
				$name = $firstname . ' ' . $lastname;
				$this->log_activity('faculty', $id, $username, 'Account Updated', "Faculty account '$name' was updated");
			}
		}

		if ($save) {
			return 1;
		}
	}
	function delete_faculty()
	{
		extract($_POST);
		$faculty_info = $this->db->query("SELECT CONCAT(firstname,' ',lastname) as name, username FROM faculty_list WHERE id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM faculty_list where id = " . $id);
		if ($delete) {
			$faculty_name = $faculty_info['name'] ?? '';
			$username = $faculty_info['username'] ?? '';
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Faculty Deleted', "Faculty account '$faculty_name' ($username) was deleted");
			return 1;
		}
	}
	function save_student()
	{
		extract($_POST);
		$data = "";
		$password_changed = false;
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";
			$password_changed = true;
		}
		$check_username = $this->db->query("SELECT * FROM student_list where username ='$username' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check_username > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO student_list set $data");
			if ($save) {
				$new_id = $this->db->insert_id;
				$name = $firstname . ' ' . $lastname;
				$this->log_activity('student', $new_id, $username, 'Account Created', "Student account '$name' ($username) was created");
			}
		} else {
			$save = $this->db->query("UPDATE student_list set $data where id = $id");
			if ($save && $password_changed) {
				$name = $firstname . ' ' . $lastname;
				$this->log_activity('student', $id, $username, 'Password Changed', "Student '$name' changed their password");
			} elseif ($save) {
				$name = $firstname . ' ' . $lastname;
				$this->log_activity('student', $id, $username, 'Account Updated', "Student account '$name' was updated");
			}
		}

		if ($save) {
			return 1;
		}
	}
	function delete_student()
	{
		extract($_POST);
		$student_info = $this->db->query("SELECT CONCAT(firstname,' ',lastname) as name, username FROM student_list WHERE id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM student_list where id = " . $id);
		if ($delete) {
			$student_name = $student_info['name'] ?? '';
			$username = $student_info['username'] ?? '';
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Student Deleted', "Student account '$student_name' ($username) was deleted");
			return 1;
		}
	}
	function bulk_update_students()
	{
		extract($_POST);

		if (!isset($student_ids)) {
			return json_encode(['success' => false, 'message' => 'student_ids parameter not received']);
		}

		if (is_string($student_ids)) {
			$decoded = json_decode($student_ids, true);

			if ($decoded === null) {
				return json_encode(['success' => false, 'message' => 'Failed to decode student_ids JSON']);
			}

			$student_ids = $decoded;
		}

		// Convert single value to array
		if (!is_array($student_ids)) {
			$student_ids = [$student_ids];
		}

		// Handle student_ids array
		if (empty($student_ids)) {
			return json_encode(['success' => false, 'message' => 'No students selected']);
		}

		if (empty($class_id)) {
			return json_encode(['success' => false, 'message' => 'No class selected']);
		}

		$success_count = 0;
		$error_message = '';

		foreach ($student_ids as $student_id) {
			$student_id = intval($student_id);
			$class_id_escaped = $this->db->real_escape_string($class_id);

			$save = $this->db->query("UPDATE student_list SET class_id = '$class_id_escaped' WHERE id = '$student_id'");

			if ($save) {
				$success_count++;
			} else {
				$error_message = $this->db->error;
				break;
			}
		}

		if ($success_count > 0) {
			// Get class name for logging
			$class_info = $this->db->query("SELECT concat(curriculum,' ',level,' - ',section) as class_name FROM class_list WHERE id = '$class_id_escaped'")->fetch_array();
			$class_name = $class_info['class_name'] ?? 'Class ID: ' . $class_id;

			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Bulk Student Update', "$success_count student(s) updated to $class_name");

			return 1;
		} else {
			return json_encode(['success' => false, 'message' => 'Database error: ' . $error_message]);
		}
	}

	function save_subject_mapping()
	{
		extract($_POST);

		// Check if mapping already exists
		$check = $this->db->query("SELECT * FROM subject_course_mapping WHERE subject_id = '$subject_id' AND curriculum = '$curriculum' AND level = '$level'");
		if ($check->num_rows > 0) {
			return 2; // Already exists
		}

		$insert = $this->db->query("INSERT INTO subject_course_mapping (subject_id, curriculum, level) VALUES ('$subject_id', '$curriculum', '$level')");

		if ($insert) {
			$subject_info = $this->db->query("SELECT code, subject FROM subject_list WHERE id = '$subject_id'")->fetch_array();
			$subject_name = ($subject_info['code'] ?? '') . ' - ' . ($subject_info['subject'] ?? '');
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Subject Mapping Created', "Subject '$subject_name' mapped to $curriculum - Level $level");
			return 1;
		} else {
			return 0;
		}
	}

	function delete_subject_mapping()
	{
		extract($_POST);
		$mapping_info = $this->db->query("SELECT scm.*, s.code, s.subject 
											FROM subject_course_mapping scm 
											LEFT JOIN subject_list s ON scm.subject_id = s.id 
											WHERE scm.id = '$id'")->fetch_array();
		$delete = $this->db->query("DELETE FROM subject_course_mapping WHERE id = '$id'");
		if ($delete) {
			$subject_name = ($mapping_info['code'] ?? '') . ' - ' . ($mapping_info['subject'] ?? '');
			$curriculum = $mapping_info['curriculum'] ?? '';
			$level = $mapping_info['level'] ?? '';
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Subject Mapping Deleted', "Subject '$subject_name' removed from $curriculum - Level $level");
			return 1;
		} else {
			return 0;
		}
	}

	function auto_assign_subjects_for_student($student_id, $class_id)
	{
		// Get the class details
		$class = $this->db->query("SELECT curriculum, level FROM class_list WHERE id = '$class_id'")->fetch_array();
		if (!$class) {
			return false;
		}

		$curriculum = $class['curriculum'];
		$level = $class['level'];

		// Check if student is irregular
		$student = $this->db->query("SELECT is_irregular FROM student_list WHERE id = '$student_id'")->fetch_array();
		$is_irregular = $student['is_irregular'] ?? 0;

		// Get current academic year
		$academic = $this->db->query("SELECT id FROM academic_list WHERE is_default = 1")->fetch_array();
		if (!$academic) {
			return false;
		}
		$academic_id = $academic['id'];

		// Get all subjects for this course/year
		$subjects = $this->db->query("SELECT subject_id FROM subject_course_mapping WHERE curriculum = '$curriculum' AND level = '$level'");

		if ($is_irregular == 0) {
			// For regular students: Remove old restrictions and add new ones
			// First, get all restrictions for this student in current academic year
			$this->db->query("DELETE FROM restriction_list WHERE id IN (
				SELECT r.id FROM restriction_list r 
				INNER JOIN class_list c ON r.class_id = c.id 
				WHERE r.academic_id = '$academic_id' 
				AND r.class_id IN (SELECT class_id FROM student_list WHERE id = '$student_id')
			)");
		}
		// Note: For irregular students, we don't delete existing restrictions

		// Add new subject restrictions
		while ($row = $subjects->fetch_assoc()) {
			$subject_id = $row['subject_id'];

			// Get faculty assigned to this subject for this class (if any)
			// For now, we'll need admin to manually assign faculty
			// We just create the restriction with a placeholder faculty_id = 0
			// Admin will need to edit and assign the faculty

			// Check if restriction already exists
			$check = $this->db->query("SELECT id FROM restriction_list WHERE academic_id = '$academic_id' AND class_id = '$class_id' AND subject_id = '$subject_id'");

			if ($check->num_rows == 0) {
				// Insert restriction without faculty (faculty_id will need to be updated by admin)
				// We'll use faculty_id = 1 as placeholder - admin needs to update this
				$faculty_id = 1; // Placeholder - admin must assign proper faculty
				$this->db->query("INSERT INTO restriction_list (academic_id, faculty_id, class_id, subject_id) VALUES ('$academic_id', '$faculty_id', '$class_id', '$subject_id')");
			}
		}

		return true;
	}

	function auto_assign_subjects()
	{
		extract($_POST);
		return $this->auto_assign_subjects_for_student($student_id, $class_id);
	}

	function add_irregular_subject()
	{
		extract($_POST);

		// Check if subject is already assigned
		$check = $this->db->query("SELECT * FROM irregular_student_subjects WHERE student_id = $student_id AND subject_id = $subject_id");
		if ($check->num_rows > 0) {
			return 2; // Already exists
		}

		$data = "INSERT INTO irregular_student_subjects (student_id, subject_id) VALUES ($student_id, $subject_id)";
		$save = $this->db->query($data);
		if ($save) {
			$student_info = $this->db->query("SELECT CONCAT(firstname,' ',lastname) as name FROM student_list WHERE id = $student_id")->fetch_array();
			$subject_info = $this->db->query("SELECT code FROM subject_list WHERE id = $subject_id")->fetch_array();
			$student_name = $student_info['name'] ?? '';
			$subject_code = $subject_info['code'] ?? '';
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Irregular Subject Added', "Subject '$subject_code' added to irregular student '$student_name'");
			return 1;
		}
	}

	function get_irregular_subjects()
	{
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT iss.*, s.code, s.subject, s.description 
								FROM irregular_student_subjects iss
								INNER JOIN subject_list s ON iss.subject_id = s.id
								WHERE iss.student_id = $student_id
								ORDER BY s.code");
		$html = '';
		while ($row = $get->fetch_assoc()) {
			$html .= '<tr>';
			$html .= '<td>' . $row['code'] . '</td>';
			$html .= '<td>' . $row['subject'] . '</td>';
			$html .= '<td>' . $row['description'] . '</td>';
			$html .= '<td class="text-center"><button class="btn btn-sm btn-danger remove-irregular-subject" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button></td>';
			$html .= '</tr>';
		}
		if ($html == '') {
			$html = '<tr><td colspan="4" class="text-center">No additional subjects found</td></tr>';
		}
		return $html;
	}

	function remove_irregular_subject()
	{
		extract($_POST);
		$mapping_info = $this->db->query("SELECT iss.*, st.firstname, st.lastname, s.code 
											FROM irregular_student_subjects iss 
											LEFT JOIN student_list st ON iss.student_id = st.id 
											LEFT JOIN subject_list s ON iss.subject_id = s.id 
											WHERE iss.id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM irregular_student_subjects WHERE id = $id");
		if ($delete) {
			$student_name = ($mapping_info['firstname'] ?? '') . ' ' . ($mapping_info['lastname'] ?? '');
			$subject_code = $mapping_info['code'] ?? '';
			$this->log_activity('admin', $_SESSION['login_id'], $_SESSION['login_username'], 'Irregular Subject Removed', "Subject '$subject_code' removed from irregular student '$student_name'");
			return 1;
		}
	}

	function save_task()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if ($k == 'description')
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO task_list set $data");
		} else {
			$save = $this->db->query("UPDATE task_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_task()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_progress()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if ($k == 'progress')
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!isset($is_complete))
			$data .= ", is_complete=0 ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO task_progress set $data");
		} else {
			$save = $this->db->query("UPDATE task_progress set $data where id = $id");
		}
		if ($save) {
			if (!isset($is_complete))
				$this->db->query("UPDATE task_list set status = 1 where id = $task_id ");
			else
				$this->db->query("UPDATE task_list set status = 2 where id = $task_id ");
			return 1;
		}
	}
	function delete_progress()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_progress where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_restriction()
	{
		extract($_POST);
		$filtered = implode(",", array_filter($rid));
		if (!empty($filtered))
			$this->db->query("DELETE FROM restriction_list where id not in ($filtered) and academic_id = $academic_id");
		else
			$this->db->query("DELETE FROM restriction_list where  academic_id = $academic_id");
		foreach ($rid as $k => $v) {
			$data = " academic_id = $academic_id ";
			$data .= ", faculty_id = {$faculty_id[$k]} ";
			$data .= ", class_id = {$class_id[$k]} ";
			$data .= ", subject_id = {$subject_id[$k]} ";
			if (empty($v)) {
				$save[] = $this->db->query("INSERT INTO restriction_list set $data ");
			} else {
				$save[] = $this->db->query("UPDATE restriction_list set $data where id = $v ");
			}
		}
		return 1;
	}
	function save_evaluation()
	{

		extract($_POST);

		$feedback_id = null;
		if (isset($rating) || isset($comment)) {
			$rating = isset($rating) ? $this->db->real_escape_string($rating) : '';
			$feedback_comment = isset($comment) ? $this->db->real_escape_string($comment) : '';

			$feedback_sql = "INSERT INTO additional_feedback (rating, comment) 
						 VALUES ('{$rating}', '{$feedback_comment}')";
			$feedback_result = $this->db->query($feedback_sql);

			if ($feedback_result) {
				$feedback_id = $this->db->insert_id;
			}
		}

		$data = " student_id = {$_SESSION['login_id']} ";
		$data .= ", academic_id = $academic_id ";
		$data .= ", subject_id = $subject_id ";
		$data .= ", class_id = $class_id ";
		$data .= ", restriction_id = $restriction_id ";
		$data .= ", faculty_id = $faculty_id ";

		if ($feedback_id !== null) {
			$data .= ", feedback_id = $feedback_id ";
		}

		$save = $this->db->query("INSERT INTO evaluation_list set $data");

		if ($save) {
			$eid = $this->db->insert_id;
			foreach ($qid as $k => $v) {
				$data = " evaluation_id = $eid ";
				$data .= ", question_id = $v ";
				$data .= ", rate = {$rate[$v]} ";
				$ins[] = $this->db->query("INSERT INTO evaluation_answers set $data ");
			}
			if (isset($ins))
				return 1;
		}
	}
	function get_class()
	{
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT c.id,concat(c.curriculum,' ',c.level,' - ',c.section) as class,s.id as sid,concat(s.code,' - ',s.subject) as subj FROM restriction_list r inner join class_list c on c.id = r.class_id inner join subject_list s on s.id = r.subject_id where r.faculty_id = {$fid} and academic_id = {$_SESSION['academic']['id']} ");
		while ($row = $get->fetch_assoc()) {
			$data[] = $row;
		}
		return json_encode($data);

	}
	function get_report()
	{
		extract($_POST);
		$data = array();

		// Existing evaluation data query
		$get = $this->db->query("SELECT * FROM evaluation_answers where evaluation_id in (SELECT evaluation_id FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id and subject_id = $subject_id and class_id = $class_id ) ");
		$answered = $this->db->query("SELECT * FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id and subject_id = $subject_id and class_id = $class_id");

		// Get feedback data
		$feedback_query = $this->db->query("SELECT af.rating, af.comment, CONCAT(sl.firstname, ' ', COALESCE(NULLIF(sl.middlename,''),''), ' ', sl.lastname) as student_name 
                                       FROM additional_feedback af 
                                       JOIN evaluation_list el ON af.id = el.feedback_id 
                                       JOIN student_list sl ON el.student_id = sl.id 
                                       WHERE el.academic_id = {$_SESSION['academic']['id']} 
                                       AND el.faculty_id = $faculty_id 
                                       AND el.subject_id = $subject_id 
                                       AND el.class_id = $class_id 
                                       AND el.feedback_id IS NOT NULL AND el.feedback_id > 0");

		$rate = array();
		$total_rate = 0;
		while ($row = $get->fetch_assoc()) {
			if (isset($row['rate']))
				$total_rate += (float) $row['rate'];
			if (!isset($rate[$row['question_id']][$row['rate']]))
				$rate[$row['question_id']][$row['rate']] = 0;
			$rate[$row['question_id']][$row['rate']] += 1;
		}

		$ta = $answered->num_rows;

		$num_questions_qry = $this->db->query("SELECT COUNT(*) as cnt FROM question_list where academic_id = {$_SESSION['academic']['id']}");
		$num_questions_row = $num_questions_qry->fetch_array();
		$num_questions = isset($num_questions_row['cnt']) ? (int) $num_questions_row['cnt'] : 0;
		$avg_per_item = 0;
		$verbal_rating = '';
		if ($ta > 0 && $num_questions > 0) {
			$average_total_per_student = $total_rate / $ta;
			$avg_per_item = $average_total_per_student / $num_questions;
			$avg_per_item = round($avg_per_item, 2);
			if ($avg_per_item >= 4.5 && $avg_per_item <= 5.0) {
				$verbal_rating = 'Outstanding';
			} elseif ($avg_per_item >= 3.75 && $avg_per_item < 4.5) {
				$verbal_rating = 'Very Satisfactory';
			} elseif ($avg_per_item >= 3.00 && $avg_per_item < 3.75) {
				$verbal_rating = 'Satisfactory';
			} elseif ($avg_per_item >= 2.00 && $avg_per_item < 3.00) {
				$verbal_rating = 'Fair';
			} else {
				$verbal_rating = 'Needs Improvement';
			}
		}
		$r = array();
		foreach ($rate as $qk => $qv) {
			foreach ($qv as $rk => $rv) {
				$r[$qk][$rk] = ($rate[$qk][$rk] / $ta) * 100;
			}
		}

		// Process feedback data
		$feedback_data = array();
		$rating_summary = array('Good' => 0, 'Neutral' => 0, 'Bad' => 0);

		while ($feedback_row = $feedback_query->fetch_assoc()) {
			$feedback_data[] = $feedback_row;
			if (isset($rating_summary[$feedback_row['rating']])) {
				$rating_summary[$feedback_row['rating']]++;
			}
		}

		$data['tse'] = $ta;
		$data['data'] = $r;
		$data['feedback'] = $feedback_data;
		$data['rating_summary'] = $rating_summary;
		$data['avg_per_item'] = $avg_per_item;
		$data['verbal_rating'] = $verbal_rating;
		$final_report = array('avg_per_item' => 0, 'verbal_rating' => '', 'total_evaluations' => 0);
		$sum_row = $this->db->query("SELECT SUM(rate) as total_rate FROM evaluation_answers where evaluation_id in (SELECT evaluation_id FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id)")->fetch_array();
		$total_rate_all = isset($sum_row['total_rate']) ? (float) $sum_row['total_rate'] : 0;
		$cnt_row = $this->db->query("SELECT COUNT(DISTINCT evaluation_id) as cnt FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id")->fetch_array();
		$count_evals = isset($cnt_row['cnt']) ? (int) $cnt_row['cnt'] : 0;
		if ($count_evals > 0 && $num_questions > 0) {
			$average_total_per_submission = $total_rate_all / $count_evals;
			$final_avg_per_item = round($average_total_per_submission / $num_questions, 2);
			$final_report['avg_per_item'] = $final_avg_per_item;
			$final_report['total_evaluations'] = $count_evals;
			if ($final_avg_per_item >= 4.5 && $final_avg_per_item <= 5.0) {
				$final_report['verbal_rating'] = 'Outstanding';
			} elseif ($final_avg_per_item >= 3.75 && $final_avg_per_item < 4.5) {
				$final_report['verbal_rating'] = 'Very Satisfactory';
			} elseif ($final_avg_per_item >= 3.00 && $final_avg_per_item < 3.75) {
				$final_report['verbal_rating'] = 'Satisfactory';
			} elseif ($final_avg_per_item >= 2.00 && $final_avg_per_item < 3.00) {
				$final_report['verbal_rating'] = 'Fair';
			} else {
				$final_report['verbal_rating'] = 'Needs Improvement';
			}
		}
		$data['final_report'] = $final_report;

		return json_encode($data);
	}
	function get_overall_report()
	{
		extract($_POST);
		$data = array();

		// Get all evaluations for this faculty across all classes and subjects
		$get = $this->db->query("SELECT * FROM evaluation_answers where evaluation_id in (SELECT evaluation_id FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id) ");
		$answered = $this->db->query("SELECT * FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id");

		$rate = array();
		$total_rate = 0;
		while ($row = $get->fetch_assoc()) {
			if (isset($row['rate']))
				$total_rate += (float) $row['rate'];
			if (!isset($rate[$row['question_id']][$row['rate']]))
				$rate[$row['question_id']][$row['rate']] = 0;
			$rate[$row['question_id']][$row['rate']] += 1;
		}

		$ta = $answered->num_rows;

		$num_questions_qry = $this->db->query("SELECT COUNT(*) as cnt FROM question_list where academic_id = {$_SESSION['academic']['id']}");
		$num_questions_row = $num_questions_qry->fetch_array();
		$num_questions = isset($num_questions_row['cnt']) ? (int) $num_questions_row['cnt'] : 0;
		$avg_per_item = 0;
		$verbal_rating = '';
		if ($ta > 0 && $num_questions > 0) {
			$average_total_per_student = $total_rate / $ta;
			$avg_per_item = $average_total_per_student / $num_questions;
			$avg_per_item = round($avg_per_item, 2);
			if ($avg_per_item >= 4.5 && $avg_per_item <= 5.0) {
				$verbal_rating = 'Outstanding';
			} elseif ($avg_per_item >= 3.75 && $avg_per_item < 4.5) {
				$verbal_rating = 'Very Satisfactory';
			} elseif ($avg_per_item >= 3.00 && $avg_per_item < 3.75) {
				$verbal_rating = 'Satisfactory';
			} elseif ($avg_per_item >= 2.00 && $avg_per_item < 3.00) {
				$verbal_rating = 'Fair';
			} else {
				$verbal_rating = 'Needs Improvement';
			}
		}

		$r = array();
		foreach ($rate as $qk => $qv) {
			foreach ($qv as $rk => $rv) {
				$r[$qk][$rk] = ($rate[$qk][$rk] / $ta) * 100;
			}
		}

		$data['tse'] = $ta;
		$data['data'] = $r;
		$data['avg_per_item'] = $avg_per_item;
		$data['verbal_rating'] = $verbal_rating;

		return json_encode($data);
	}
	function get_additional_feedback()
	{
		extract($_POST);
		$allowed = array('good', 'neutral', 'bad', 'all');
		$rating_filter = isset($rating) ? strtolower(trim($rating)) : 'all';
		if (!in_array($rating_filter, $allowed))
			$rating_filter = 'all';

		$base_where = "el.academic_id = {$_SESSION['academic']['id']} AND el.faculty_id = $faculty_id AND el.subject_id = $subject_id AND el.class_id = $class_id AND el.feedback_id IS NOT NULL AND el.feedback_id > 0";

		$where = $base_where;
		if ($rating_filter !== 'all') {
			$esc = $this->db->real_escape_string($rating_filter);
			$where .= " AND LOWER(af.rating) = '{$esc}'";
		}

		$sql = "SELECT af.rating, af.comment FROM additional_feedback af JOIN evaluation_list el ON af.id = el.feedback_id WHERE $where ORDER BY af.id DESC";
		$qry = $this->db->query($sql);

		$feedback = array();
		while ($row = $qry->fetch_assoc()) {
			$feedback[] = array(
				'rating' => $row['rating'],
				'comment' => $row['comment']
			);
		}

		$summary_sql = "SELECT LOWER(af.rating) as rating, COUNT(*) as cnt FROM additional_feedback af JOIN evaluation_list el ON af.id = el.feedback_id WHERE $base_where GROUP BY LOWER(af.rating)";
		$sum_q = $this->db->query($summary_sql);
		$summary = array('good' => 0, 'neutral' => 0, 'bad' => 0);
		while ($srow = $sum_q->fetch_assoc()) {
			$rk = strtolower($srow['rating']);
			if (isset($summary[$rk]))
				$summary[$rk] = (int) $srow['cnt'];
		}

		return json_encode(array(
			'feedback' => $feedback,
			'summary' => $summary,
			'filter' => $rating_filter,
			'total' => count($feedback)
		));
	}
}