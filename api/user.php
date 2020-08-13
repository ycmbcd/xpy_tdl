<?php
session_start();

require_once("./access.php");
require_once("./pdo/PdoMySQL.class.php");
$db = new PdoMySQL();

// 查询员工
if(isset($_POST['getUsers'])){
		//获取最大工号
    $sql = "SELECT * FROM tdl_user ORDER BY id DESC";
    $res = $db->getAll($sql);
    echo json_encode($res);
}

// 添加员工
if(isset($_POST['addUser'])){
    $add_user = $_POST['addUser'];
    $new_group = $_POST['newGroup'];
		//获取最大工号
    $sql = "SELECT Max(u_id) as max_num FROM tdl_user";
    $res = $db->getOne($sql);
    $new_num = $res['max_num']+1;
		$sql = "INSERT INTO tdl_user (u_id,u_name,u_pwd,u_type,u_group) VALUES ('{$new_num}','{$add_user}','Just.1t','-','{$new_group}')";
		$res = $db->execute($sql);
		echo 'ok';
}

// 删除员工
if(isset($_POST['delUser'])){
   $del_user = $_POST['delUser'];
  // 删除员工
  $sql = "DELETE FROM tdl_user WHERE u_id = '{$del_user}'";
  $res = $db->execute($sql);
  // 删除员工对应的月报类型
  $sql = "DELETE FROM tdl_type WHERE u_id = '{$del_user}'";
  $res = $db->execute($sql);
  
  echo 'ok';
}

// 重置密码
if(isset($_POST['resetPwd'])){
  $u_id = $_POST['resetPwd'];
  $salt = 'Just.1ttse4$dA*65';
  $u_pwd = md5($salt);
  $sql = "UPDATE tdl_user SET u_pwd = '{$u_pwd}' WHERE u_id = '{$u_id}'";
  $res = $db->execute($sql);
  
  echo 'ok';
}

// 修改用户
if(isset($_POST['updateUser'])){
  $u_id = $_POST['updateUser'];
  $u_name = $_POST['newName'];
  $u_group = $_POST['newGroup'];
  $sql = "UPDATE tdl_user SET u_name = '{$u_name}',u_group = '{$u_group}' WHERE u_id = '{$u_id}'";
  $res = $db->execute($sql);
  
  echo 'ok';
}