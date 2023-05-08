<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include PHP Excel library files
require_once APPPATH.'/third_party/PHPExcel/PHPExcel.php';

class Php_excel_lib extends PHPExcel
{
    function __construct()
    {
        parent::__construct();
        log_message('Debug', 'PHP Excel class is loaded.');

        $this->today = date("Y-m-d");
        $this->worksheetArr = array();
        $this->defaultBorder = array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb'=>'A6A6A6')
        );
        $this->headBorder = array(
            'borders' => array(
                'bottom' => $this->defaultBorder,
                'left'   => $this->defaultBorder,
                'top'    => $this->defaultBorder,
                'right'  => $this->defaultBorder
            )
        );
    }

    function load($subject, $description)
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("")
            ->setLastModifiedBy("")
            ->setTitle("")
            ->setSubject($subject)
            ->setDescription($description)
            ->setKeywords("")
            ->setCategory("");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Noto Sans');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }

    public function download($objPHPExcel, $filename){
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $filename = "{$filename}.xlsx";    // 엑셀 파일명
        $filename = str_replace(' ', '_', $filename);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"');     // 브라우저에서 받을 파일명
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');        // no cache
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        // Excel 2007 포맷으로 저장
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        // 서버에 파일을 쓰지 않고 바로 다운로드
        $objWriter->save('php://output');
    }
}
