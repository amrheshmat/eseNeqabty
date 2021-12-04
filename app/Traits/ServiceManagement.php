<?php
/**
 * Created by PhpStorm.
 * User: admin
 */
namespace App\Traits;
use PhpOffice\PhpSpreadsheet\Reader;

trait ServiceManagement
{
    /**
     * @return array
     */
   public function getAllowedServices(){
       $reader = new Reader\Xlsx();
       //$reader = IOFactory::createReader('Xlsx');
       $reader->setReadDataOnly(true);
       $reader->setReadEmptyCells(false);
       $spreadsheet = $reader->load(storage_path('services.xlsx'));
       $highestDataRow=$spreadsheet->getActiveSheet()->getHighestDataRow();
       $result=$spreadsheet->getActiveSheet()->rangeToArray('A2:A'.$highestDataRow,null, true, true, true);
       $result=array_column($result, 'A');
       return $result;
   }
}

