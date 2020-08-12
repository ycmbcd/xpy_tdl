<?php
session_start();

require_once("./access.php");
require_once("./pdo/PdoMySQL.class.php");
$db = new PdoMySQL();

// 删除一个任务
if(isset($_POST['del_tdl'])){
  $id = $_POST['del_tdl'];
  $sql = "DELETE FROM tdl_list WHERE id = '{$id}'";
  $res = $db->execute($sql);
  echo 'ok';
}

// 添加一个任务
if(isset($_POST['add_tdl'])){
  $u_id = $_SESSION['u_id'];
  $u_name = $_SESSION['u_name'];
  $t_week = addslashes($_POST['addWeek']);
  $t_date = addslashes($_POST['addDate']);
  $t_work = addslashes($_POST['addWork']);
  $t_type = addslashes($_POST['addType']);
  $t_value = addslashes($_POST['addValue']);
  $t_time = addslashes($_POST['addTime']);
  $t_time_other = addslashes($_POST['addTimeOther']);
  $t_ask = addslashes($_POST['addAsk']);
  $t_do = addslashes($_POST['addDo']);
    
  $sql = "INSERT INTO tdl_list (
    u_id,
    u_name,
    t_week,
    t_date,
    t_work,
    t_type,
    t_value,
    t_time,
    t_time_other,
    t_ask,
    t_do
  ) VALUES (
    '{$u_id}',
    '{$u_name}',
    '{$t_week}',
    '{$t_date}',
    '{$t_work}',
    '{$t_type}',
    '{$t_value}',
    '{$t_time}',
    '{$t_time_other}',
    '{$t_ask}',
    '{$t_do}')";
  $res = $db->execute($sql);
  echo 'ok';
}

// 查询任务
if(isset($_POST['get_tdl'])){
  // 日期限制
  $startDate = addslashes($_POST['startDate']);
  $endDate = addslashes($_POST['endDate']);
  // 分页限制
  $nowPage = addslashes($_POST['nowPage']);
  $nowSize = addslashes($_POST['nowSize']);
  $start = ($nowPage-1) * $nowSize;
  $end = $nowSize;
  // 如果日期不完全，返回空
  if($startDate == '' or $endDate == ''){
    echo '';
  }else{
    $u_id = $_SESSION['u_id'];
    // 查询数据
  $sql = "SELECT * FROM tdl_list WHERE u_id = '{$u_id}' AND t_date BETWEEN '{$startDate}' AND '{$endDate}' ORDER BY t_date LIMIT {$start}, {$end}";
    $res = $db->getAll($sql);
    foreach ($res as $key => $value) {
      $res[$key]['mark'] = false;
    }
    $final_res['list'] = $res;
    // 查询个数
    $sql2 = "SELECT count(1) as cc FROM tdl_list WHERE u_id = '{$u_id}' AND t_date BETWEEN '{$startDate}' AND '{$endDate}'";
    $res2 = $db->getOne($sql2);
    $final_res['count'] = $res2['cc'];
    $final_res['start'] = $start;
    $final_res['end'] = $end;
    echo json_encode($final_res);
  }
}

// 查询所有任务
if(isset($_POST['get_all_tdl'])){
  // 日期限制
  $startDate = addslashes($_POST['startDate']);
  $endDate = addslashes($_POST['endDate']);
  // 分页限制
  $nowPage = addslashes($_POST['nowPage']);
  $nowSize = addslashes($_POST['nowSize']);
  $start = ($nowPage-1) * $nowSize;
  $end = $nowSize;
  // 如果日期不完全，返回空
  if($startDate == '' or $endDate == ''){
    echo '';
  }else{
    $u_id = $_SESSION['u_id'];
    // 查询数据
  $sql = "SELECT * FROM tdl_list WHERE t_date BETWEEN '{$startDate}' AND '{$endDate}' ORDER BY u_name,t_date LIMIT {$start}, {$end}";
    $res = $db->getAll($sql);
    foreach ($res as $key => $value) {
      $res[$key]['mark'] = false;
    }
    $final_res['list'] = $res;
    // 查询个数
    $sql2 = "SELECT count(1) as cc FROM tdl_list WHERE t_date BETWEEN '{$startDate}' AND '{$endDate}'";
    $res2 = $db->getOne($sql2);
    $final_res['count'] = $res2['cc'];
    $final_res['start'] = $start;
    $final_res['end'] = $end;
    echo json_encode($final_res);
  }
}

// 获取任务详情
if(isset($_POST['get_edit'])){
  $id = $_POST['get_edit'];
  $sql = "SELECT * FROM tdl_list WHERE id = '{$id}'";
  $res = $db->getOne($sql);
  echo json_encode($res);
}


// 修改任务
if(isset($_POST['update_edit'])){
  $tdl_id = $_POST['update_edit'];
  $t_week = addslashes($_POST['editWeek']);
  $t_date = addslashes($_POST['editDate']);
  $t_work = addslashes($_POST['editWork']);
  $t_type = addslashes($_POST['editType']);
  $t_value = addslashes($_POST['editValue']);
  $t_time = addslashes($_POST['editTime']);
  $t_time_other = addslashes($_POST['editTimeOther']);
  $t_ask = addslashes($_POST['editAsk']);
  $t_do = addslashes($_POST['editDo']);
  $sql = "UPDATE tdl_list SET
    t_week = '{$t_week}',
    t_date = '{$t_date}',
    t_work = '{$t_work}',
    t_type = '{$t_type}',
    t_value = '{$t_value}',
    t_time = '{$t_time}',
    t_time_other = '{$t_time_other}',
    t_ask = '{$t_ask}',
    t_do = '{$t_do}' WHERE 
    id = '{$tdl_id}'";
  $res = $db->execute($sql);
  echo 'ok';
}
