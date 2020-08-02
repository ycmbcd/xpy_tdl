<?php
session_start();

require_once("./access.php");
require_once("./pdo/PdoMySQL.class.php");
$db = new PdoMySQL();

// 查询月报类型
if(isset($_POST['getType'])){
    $u_id = $_SESSION['u_id'];
    $sql = "SELECT * FROM tdl_type WHERE u_id = '{$u_id}' GROUP BY m_type ORDER BY id DESC";
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