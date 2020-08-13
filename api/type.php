<?php
session_start();

require_once("./access.php");
require_once("./pdo/PdoMySQL.class.php");
$db = new PdoMySQL();

// 查询月报类型
if(isset($_POST['getType'])){
    $u_id = $_SESSION['u_id'];
    $sql = "SELECT * FROM tdl_type WHERE u_id in ('{$u_id}', 0) ORDER BY u_id,id DESC";
    $res = $db->getAll($sql);
    echo json_encode($res);
}

// 添加一个月报类型
if(isset($_POST['setNewType'])){
    $add_type = addslashes($_POST['setNewType']);
    $u_id = $_SESSION['u_id'];
    $sql = "SELECT 1 FROM tdl_type WHERE m_type = '{$add_type}' AND u_id = '{$u_id}'";
    $res = $db->getOne($sql);
    if(empty($res)){
        $sql = "INSERT INTO tdl_type (u_id, m_type) VALUES ('{$u_id}', '{$add_type}')";
        $res = $db->execute($sql);
        echo 'ok';
    }else{
        echo 'has';
    }
}

// 删除一个月报类型
if(isset($_POST['delType'])){
    $del_type_id = $_POST['delType'];
    $sql = "DELETE FROM tdl_type WHERE id = '{$del_type_id}'";
    $res = $db->execute($sql);
    echo 'ok';
}

// 公共月报类型 #################################

// 添加一个公共月报类型
if(isset($_POST['newCommonType'])){
    $add_type = addslashes($_POST['newCommonType']);
    $u_id = $_SESSION['u_id'];
    $sql = "SELECT 1 FROM tdl_type WHERE m_type = '{$add_type}' AND u_id = 0";
    $res = $db->getOne($sql);
    if(empty($res)){
        $sql = "INSERT INTO tdl_type (u_id, m_type) VALUES (0, '{$add_type}')";
        $res = $db->execute($sql);
        echo 'ok';
    }else{
        echo 'has';
    }
}

// 查询公共月报类型
if(isset($_POST['getCommonType'])){
    $u_id = 0;
    $sql = "SELECT * FROM tdl_type WHERE u_id = '{$u_id}' ORDER BY id DESC";
    $res = $db->getAll($sql);
    echo json_encode($res);
}