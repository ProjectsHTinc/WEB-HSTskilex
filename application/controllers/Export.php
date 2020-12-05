<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Export extends CI_Controller {
    // construct
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('Export_model', 'export');
    }    

    // create xlsx
    public function generateXls() {

		 $from_date=$this->uri->segment(3);
		 $to_date=$this->uri->segment(4);

        // load excel library
        $this->load->library('excel');
        $listInfo = $this->export->exportList($from_date,$to_date);

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'S.No');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Invoice');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Customer Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Vendor name');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Service Category');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Advance Amount');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Service Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Vendor Amount');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Skilex Amount');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'GST 18%');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'SGST 9%');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'CGST 9%');
        // set Row
        $rowCount = 2;
		$i = 1;
        foreach ($listInfo as $list) {
			$sdate = date('d-m-Y', strtotime($list->order_date));
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $i);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $sdate);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $list->so_id);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $list->contact_person_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $list->spv_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $list->service_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $list->paid_advance_amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $list->net_service_amount);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $list->serv_pro_net_amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $list->skilex_net_amount);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $list->skilex_tax_amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $list->sgst_amount);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $list->cgst_amount);
            $rowCount++;
			$i++;
        }
        $filename = "report_". date("Y-m-d-H-i-s").".xlsx";
        header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');  
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output'); 
 
    }
     
}
?>