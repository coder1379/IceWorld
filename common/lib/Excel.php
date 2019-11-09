<?php

namespace common\lib;

use Yii;
use PHPExcel;
/*
 excel
 tielin
 */
Class Excel{
	/////////待完成，思路OK，代码待完成


	public function getExcel($fileName,$sheetname,$data){
		
		require_once '../../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Excel")
		->setLastModifiedBy("Excel")
		->setTitle("Excel")
		->setSubject("Excel");
		//遍历数据
		$excelheader=array();
		$excelheader[]="A";
		$excelheader[]="B";
		$excelheader[]="C";
		$excelheader[]="D";
		$excelheader[]="E";
		$excelheader[]="F";
		$excelheader[]="G";
		$excelheader[]="H";
		$excelheader[]="I";
		$excelheader[]="J";
		$excelheader[]="K";
		$excelheader[]="L";
		$excelheader[]="M";
		$excelheader[]="N";
		$excelheader[]="O";
		$excelheader[]="P";
		$excelheader[]="Q";
		$excelheader[]="R";
		$excelheader[]="S";
		$snum=1;
		//print_r($data);
		foreach ($data as $k=>$v){
			$varray=explode(",",$v);
			$hname=0;
			foreach($varray as $vname){

				 $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($excelheader[$hname].$snum,$vname,\PHPExcel_Cell_DataType::TYPE_STRING);
				 /* $objPHPExcel->getActiveSheet()->getStyle($excelheader[$hname].$snum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				   $objPHPExcel->getActiveSheet()->getStyle($excelheader[$hname].$snum)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);*/
				 $hname++;
			}
			$snum++;

		/*  //数据
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',$v['owner_name'],'String');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1',chunk_split($v['order_number']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1',chunk_split($v['order_idcard']));
		//标题
		$objPHPExcel->getActiveSheet()->setTitle('qingpingguo_text');
		//宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);

		//垂直居中
	   $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	   
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);*/

		}
		

	   /* $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($fileName.".xlsx");*/
		
			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		 //$objWriter->save($fileName.".xls");
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment; filename=\"".$fileName.".xls"."\"");
			header('Cache-Control: max-age=0');
			$objWriter->save("php://output");
			exit();
	  
		/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"".$fileName.".xls"."\"");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output'); //文件通过浏览器下载
		 echo "3";
		exit();*/
	}
}

?>