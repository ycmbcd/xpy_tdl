<?php
session_start();

require_once("./access.php");
require_once("./pdo/PdoMySQL.class.php");
$db = new PdoMySQL();

// 下载统计月报表
if(isset($_POST['down_month'])){
    // 获取月份
    $down_month = addslashes($_POST['down_month']);

    require_once("./PHPExcel/PHPExcel.php");  //引入PHPExcel
    //PHPExcel
    $objPHPExcel = new PHPExcel();
    $objSheet = $objPHPExcel->getActiveSheet();
    $objSheet->setTitle('统计月报表→'.$down_month);//表名

    $objSheet->getDefaultStyle()->getFont()->setName("微软雅黑")->setSize(11);  //默认字体
    // 标题设置
    $objPHPExcel->getActiveSheet()->getStyle('A:D')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
    // $objSheet->freezePane('A2');//冻结表头
    $objPHPExcel->getActiveSheet()->mergeCells('B2:E2');// 合并单元格
    $objSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    // $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);//前景色
    $objSheet->getStyle('B2:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objSheet->getStyle('B2:D2')->getFill()->getStartColor()->setRGB('dce6f1'); //背景色
    // 特定单元格宽高
    $objSheet->getColumnDimension('C')->setWidth(70);//单元格宽
    $objSheet->getColumnDimension('D')->setWidth(30);//单元格宽
    $objSheet->getColumnDimension('E')->setWidth(20);//单元格宽
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);   //行高
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
    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中对齐
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
        ->setCellValue("A2", "月 报")
        ->setCellValue("B2", $down_month)
        ->setCellValue("A3", "职 员")
        ->setCellValue("B3", "序 号")
        ->setCellValue("C3", "项目或工作内容")
        ->setCellValue("D3", "工作小时数")
        ->setCellValue("E3", "加班小时数");    //表头值

    $u_id = $_SESSION['u_id'];

    $startDate = $down_month.'-01';
    $endDate = $down_month.'-31';

    // 查询数据
    $sql = "SELECT u_name,t_type,sum(t_time) AS sum_time,sum(t_time_other) AS sum_time_other FROM tdl_list WHERE t_date BETWEEN '{$startDate}' AND '{$endDate}' GROUP BY u_name,t_type";
    $res = $db->getAll($sql);

    $j=4;
    $index = 1;
    $now_name = '';
    foreach ($res as $key => $value) {
        if($now_name !== $value['u_name']){
            $now_name = $value['u_name'];
            $index = 1;
        }
        $objSheet
            ->setCellValue("A".$j,$value['u_name'])
            ->setCellValue("B".$j,$index)
            ->setCellValueExplicit("C".$j,$value['t_type'],PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue("D".$j,$value['sum_time'])
            ->setCellValue("E".$j,$value['sum_time_other']);
        $j++;
        $index ++;
    }

    $end_line = $j - 1;
    // 应用边框
    $objPHPExcel->getActiveSheet()->getStyle('A2:E'.$end_line)->applyFromArray($styleThinBlackBorderOutline);

    $table_name = 'month_all_table_'.$u_id.'_'.time().'.xlsx';
    // $objPHPExcel->getActiveSheet()->getColumnDimension()->setAutoSize(true);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save("../down/".$table_name);   //保存在服务器
    echo $table_name;

}