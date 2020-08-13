<?php
session_start();

require_once("./access.php");
require_once("./pdo/PdoMySQL.class.php");
$db = new PdoMySQL();

// 查询部门
if(isset($_POST['getGroup'])){
    $sql = "SELECT * FROM tdl_group ORDER BY id";
    $res = $db->getAll($sql);
    echo json_encode($res);
}

// 添加一个部门
if(isset($_POST['newGroup'])){
    $add_group = addslashes($_POST['newGroup']);
    $sql = "SELECT 1 FROM tdl_group WHERE m_group = '{$add_group}'";
    $res = $db->getOne($sql);
    if(empty($res)){
        $sql = "INSERT INTO tdl_group (m_group) VALUES ('{$add_group}')";
        $res = $db->execute($sql);
        echo 'ok';
    }else{
        echo 'has';
    }
}

// 删除一个部门
if(isset($_POST['delGroup'])){
    $del_id = $_POST['delGroup'];
    $sql = "DELETE FROM tdl_group WHERE id = '{$del_id}'";
    $res = $db->execute($sql);
    echo 'ok';
}