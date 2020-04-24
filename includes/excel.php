<?php
include INCLUDES_PATH . 'Spout/Autoloader/autoload.php';
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

require INCLUDES_PATH . 'phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
class Excel extends Products {
	private $file;
	
	public function export(){
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(INCLUDES_PATH . 'products_template.xltx');
		$spreadsheet->setActiveSheetIndex(0);
		$sheet = $spreadsheet->getActiveSheet();

		$spreadsheet->getProperties()
			->setTitle('Products')
			->setSubject('Products');


		$list = $this->list();

		$nextrow = 2;
		foreach($list as $key => $value){
			$sheet->setCellValue('A' . $nextrow, $value['id'])->setCellValue('B' . $nextrow, $value['sku'])->setCellValue('C' . $nextrow, $value['name'])->setCellValue('D' . $nextrow, $value['price']);
			
			$nextrow++;
		}

		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Products'.time().'.xlsx"');

		$writer->save("php://output");
	}
	
	public function setFile($file){
		$this->file = $file;
	}
	
	public function import(){
		global $db;
		
		if (!empty($this->file['name'])) {
			$pathinfo = pathinfo($this->file["name"]); // get file extension (eg. xlsx)
			
			if(($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['excelfile']['size'] > 0){ // check file extension or file is empty
				$inputFileName = $this->file['tmp_name']; 
				
				
				
				$reader = ReaderEntityFactory::createXLSXReader($inputFileName);
				$reader->open($inputFileName);
				
				foreach($reader->getSheetIterator() as $sheet){ // get sheet
					
					$query = '';
					$i = 0;
					foreach ($sheet->getRowIterator() as $row) { // get row
						if($i == 0){
							$i++;
							continue;
						}
						
						$row = $row->toArray();
						$row[3] = $row[3] / 0.01;
						
						$query.= "INSERT INTO `products` (`sku`, `name`, `price`, `created`) VALUES ('".$row[1]."', '".$row[2]."', '".$row[3]."', ".time().")
						ON DUPLICATE KEY UPDATE `sku` = '".$row[1]."', `name` = '".$row[2]."', `price` = '".$row[3]."'; ";
					}
					
					$db->multi_query($query);
				}
			}
		}
	}
}