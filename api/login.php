<?php
session_start();

require_once("./access.php");
require_once("./pdo/PdoMySQL.class.php");
$db = new PdoMySQL();

// 登录
if(isset($_POST['login'])){
    $u_id = addslashes($_POST['u_id']);
    $u_pwd = md5(addslashes($_POST['u_pwd']).'tse4$dA*65');
    $sql = "SELECT u_pwd,u_name,u_type FROM tdl_user WHERE u_id = '{$u_id}'";
    $res = $db->getOne($sql);
    if(empty($res)){
        echo 'ban';
    }else{
        if($u_pwd == $res['u_pwd']){
            $_SESSION['xpy_tdl'] = 'ing';
            $_SESSION['u_name'] = $res['u_name'];
            $_SESSION['u_id'] = $u_id;
            $_SESSION['u_type'] = $res['u_type'];
            echo 'go';
        }else{
            echo 'ban';
        }
    }
}

// 检测登录状态
if(isset($_GET['ping'])){
    // 部署
    if($_SESSION['xpy_tdl'] == 'ing'){
        $final_res['status'] = 'ing';
        $final_res['u_name'] = $_SESSION['u_name'];
        $final_res['u_id'] = $_SESSION['u_id'];
        $final_res['u_type'] = $_SESSION['u_type'];
        echo json_encode($final_res);
    }else{
      echo 'Ready';
    }
}

// 退出
if(isset($_GET['logout'])){
	session_destroy();
	
	echo "bye";
}

// 修改密码
if(isset($_GET['change_pwd'])){
    $o_pwd = md5(addslashes($_GET['o_pwd']).'tse4$dA*65');
    $new_pwd = addslashes($_GET['new_pwd']);
    $u_id = $_SESSION['u_id'];
    // 查询原始密码是否正确
    $sql = "SELECT u_pwd FROM tdl_user WHERE u_id = '{$u_id}'";
    $res = $db->getOne($sql);
    $pwd = $res['u_pwd'];
    if($o_pwd == $pwd){
        $new_pwd = md5($new_pwd.'tse4$dA*65');
        $sql = "UPDATE tdl_user SET u_pwd = '{$new_pwd}' WHERE u_id = '{$u_id}'";
        $res = $db->execute($sql);
        session_destroy();
        echo 'ok';
    }else{
        echo 'ban';
    }
}