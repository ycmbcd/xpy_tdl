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
		//获取最大工号
    $sql = "SELECT Max(u_id) as max_num FROM tdl_user";
    $res = $db->getOne($sql);
    $new_num = $res['max_num']+1;
		$sql = "INSERT INTO tdl_user (u_id,u_name,u_pwd,u_type) VALUES ('{$new_num}','{$add_user}','Just.1t','-')";
		$res = $db->execute($sql);
		echo 'ok';
}