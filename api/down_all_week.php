<?php
session_start();

require_once("./access.php");
require_once("./pdo/PdoMySQL.class.php");
$db = new PdoMySQL();

// 下载统计周报表
if(isset($_POST['down_week'])){
  // 日期限制
  $startDate = addslashes($_POST['startDate']);
  $endDate = addslashes($_POST['endDate']);

  // 如果日期不完全，返回空
  if($startDate == '' or $endDate == ''){
    echo 'error_date';
  }else{
    require_once("./PHPExcel/PHPExcel.php");  //引入PHPExcel
    //PHPExcel
    $objPHPExcel = new PHPExcel();
    $objSheet = $objPHPExcel->getActiveSheet();
    $objSheet->setTitle('统计周报表→'.$startDate.'~'.$endDate);//表名
    
    $objSheet->getDefaultStyle()->getFont()->setName("微软雅黑")->setSize(11);  //默认字体
    // 标题设置
    $objPHPExcel->getActiveSheet()->getStyle('A:H')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
    // $objSheet->freezePane('A2');//冻结表头
    $objPHPExcel->getActiveSheet()->mergeCells('B2:H2');// 合并单元格
    $objSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    // $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);//前景色
    $objSheet->getStyle('A2:H2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objSheet->getStyle('A2:H2')->getFill()->getStartColor()->setRGB('dce6f1'); //背景色
    // 特定单元格宽高
    $objSheet->getColumnDimension('C')->setWidth(12);//单元格宽
    $objSheet->getColumnDimension('D')->setWidth(40);//单元格宽
    $objSheet->getColumnDimension('E')->setWidth(10);//单元格宽
    $objSheet->getColumnDimension('F')->setWidth(12);//单元格宽
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);   //行高
    $objSheet->getColumnDimension('G')->setWidth(30);//单元格宽
    $objSheet->getColumnDimension('H')->setWidth(30);//单元格宽
    $objSheet->getRowDimension('2')->setRowHeight(20);//单元格高
    $objSheet->getRowDimension('3')->setRowHeight(20);//单元格高
    $objSheet->getRowDimension('4')->setRowHeight(20);//单元格高
    $objSheet->getRowDimension('5')->setRowHeight(20);//单元格高
    $objSheet->getRowDimension('6')->setRowHeight(20);//单元格高
    $objSheet->getRowDimension('7')->setRowHeight(20);//单元格高
    $objSheet->getRowDimension('8')->setRowHeight(20);//单元格高
    $objSheet->getRowDimension('9')->setRowHeight(20);//单元格高
    $objSheet->getRowDimension('10')->setRowHeight(20);//单元格高
    // 值设置
    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    // 设置边框
    $styleThinBlackBorderOutline = array(
        'borders' => array(
            'allborders' => array( //设置全部边框
                'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
            ),
        ),
    );
    // $objPHPExcel->getActiveSheet()->getStyle('B2:H10')->applyFromArray($styleThinBlackBorderOutline);
    
    $objSheet
        ->setCellValue("B2", "工作日志")
        ->setCellValue("A3", "职 员")
        ->setCellValue("B3", "星 期")
        ->setCellValue("C3", "日 期")
        ->setCellValue("D3", "工作内容")
        ->setCellValue("E3", "完成成果")
        ->setCellValue("F3", "工时（小时）")
        ->setCellValue("G3", "存在问题")
        ->setCellValue("H3", "拟采取措施");    //表头值

    $u_id = $_SESSION['u_id'];
    // 查询数据
    $sql = "SELECT * FROM tdl_list WHERE t_date BETWEEN '{$startDate}' AND '{$endDate}' ORDER BY u_name,t_date";
    $res = $db->getAll($sql);

    $j=4;
    $now_week = '';
    $now_date = '';
    foreach ($res as $key => $value) {
      if($now_week == $value['t_week'] && $now_date == $value['t_date']){
        $value['t_week'] = '';
        $value['t_date'] = '';
      }else{
        $now_week = $value['t_week'];
        $now_date = $value['t_date'];
      }

        $objSheet
            ->setCellValue("A".$j,$value['u_name'])        
            ->setCellValue("B".$j,str_replace('星期','', $value['t_week']))
            ->setCellValue("C".$j,str_replace('-', '/', $value['t_date']))
            ->setCellValueExplicit("D".$j,$value['t_work'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue("E".$j,$value['t_value'].'%')
            ->setCellValue("F".$j,$value['t_time'] + $value['t_time_other'])
            ->setCellValueExplicit("G".$j,$value['t_ask'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("H".$j,$value['t_do'],PHPExcel_Cell_DataType::TYPE_STRING);
        $j++;
    }

    $end_line = $j - 1;
    // 应用边框
    $objPHPExcel->getActiveSheet()->getStyle('A2:H'.$end_line)->applyFromArray($styleThinBlackBorderOutline);

    $table_name = 'week_all_table_'.$u_id.'_'.time().'.xlsx';
    // $objPHPExcel->getActiveSheet()->getColumnDimension()->setAutoSize(true);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save("../down/".$table_name);   //保存在服务器
    echo $table_name;
  }
}