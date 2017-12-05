<?php
class mdl_excel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('excel');
    }
	
	public function download_reports( $data = array() )
	{
		
		if(empty( $data )) return false;
		$letters = range('A', 'Z');
		$x = 0;
		foreach($data[0] as $key => $columns)
		{
			$contents[$letters[$x].'1'] = strtoupper(str_replace('_',' ',$key));
			$this->excel->getActiveSheet()->getColumnDimension($letters[$x])->setAutoSize(true);
			$this->excel->getActiveSheet()->getStyle($letters[$x].'1')->applyFromArray(array('fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF30'))));
			$x++;
		}

		$i = 2;
		foreach($data as $item)
		{
			$x = 0;
			foreach($item as $DataCell)
			{
				$contents[$letters[$x].$i] = $DataCell;
				$x++;
			}
			$i++;
		}
		$this->excel->getProperties()->setCreator("Access Unli Strategies");
		$this->excel->getProperties()->setLastModifiedBy("Access Unli Strategies");
		$this->excel->getProperties()->setTitle("Access Unli Reports");
		$this->excel->getProperties()->setSubject("Access Unli Reports");
		$this->excel->getProperties()->setDescription("System Generated Reports");
		$this->excel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold(true);

		foreach( $contents as $key => $item )
		{
			$this->excel->getActiveSheet()->setCellValue($key, $item);
			
		}
		$filename = md5( time().rand( 0, 999999999 ) );
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		
		// Do your stuff here
		$writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		// This line will force the file to download
		$writer->save('php://output');
	}
}






