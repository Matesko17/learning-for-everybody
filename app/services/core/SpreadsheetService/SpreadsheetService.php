<?php

namespace App\Services;

use Nette\FileNotFoundException;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpreadsheetService
{
    /** @var string */
    private $filename;
    
    /** @var Spreadsheet */
    private $spreadsheet;
    
    /**
     * Parser constructor.
     * @param string $filename
     * @throws Exception
     */
    public function __construct(string $filename)
    {
        if(!file_exists($filename)) {
            throw new FileNotFoundException($filename);
        }
        
        $this->filename = $filename;
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        switch($extension) {
            case "csv":
                $reader = new Csv();
                break;
            case "xlsx":
                $reader = new Xlsx();
                break;
            default:
                throw new ReaderException("File extension '".$extension."' not supported!");
                break;
        }
        
        $reader->setReadDataOnly(true);
        $this->spreadsheet = $reader->load($this->filename);
    }
    
    public function __destruct()
    {
        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
    }
    
    /**
     * @return Spreadsheet
     */
    public function getSpreadsheet(): Spreadsheet
    {
        return $this->spreadsheet;
    }
    
    /**
     * Parses header of spreadsheet.
     * @return array Array of header data.
     * @throws Exception
     */
    public function getHeader()
    {
        $header = [];
        
        /** @var Worksheet $worksheet */
        $worksheet = $this->spreadsheet->getActiveSheet();
        $row = $worksheet->getRowIterator(1)->current();
        
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        foreach($cellIterator as $cell) {
            $header[] = $cell->getCalculatedValue();
        }
        
        return $header;
    }
    
    /**
     * Parses data with header.
     * @param int $start Index of starting row.
     * @return array Array of parsed data.
     * @throws Exception
     */
    public function parse($start = 2)
    {
        $output = [];
        $header = $this->getHeader();
    
        /** @var Worksheet $worksheet */
        $worksheet = $this->spreadsheet->getActiveSheet();
        foreach($worksheet->getRowIterator($start) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            
            $line = [];
            $column = 0;
            foreach($cellIterator as $cell) {
                if($cell !== null) {
                    $line[$header[$column]] = $cell->getValue();
                }
                
                $column++;
            }
            
            $output[] = $line;
        }
        
        return $output;
    }
    
    /**
     * Parses data without header.
     * @param int $start Index of starting row.
     * @return array Array of parsed data.
     * @throws Exception
     */
    public function parseAll($start = 1)
    {
        $output = [];
        
        /** @var Worksheet $worksheet */
        $worksheet = $this->spreadsheet->getActiveSheet();
        foreach($worksheet->getRowIterator($start) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            
            $line = [];
            $column = 0;
            foreach($cellIterator as $cell) {
                if($cell !== null) {
                    $line[] = $cell->getValue();
                }
                
                $column++;
            }
            
            $output[] = $line;
        }
        
        return $output;
    }
}
