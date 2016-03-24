<?php

App::uses('AppHelper', 'View/Helper');


/*
* Version inspired on CsvHelper by Ketan Patel
*/


class XLSHelper extends AppHelper{

var $filename = 'export.xls';
var $line = array();
var $buffer;

    function XLSHelper(){
        $this->line = array();
        $this->buffer = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
    }


    function endRow() {
        $this->addRow($this->line);
        $this->line = array();
    }

    function addRow($row) {

        foreach($row as $k=>$v){
            if(preg_match("/^0/", $v) || preg_match("/^\+?\d{8,}$/", $v) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $v)) {
                $row[$k] = "'$v'";
            }
            if($v == 't') $row[$k] = 'True';
            if($v == 'f') $row[$k] = 'False';

            $row[$k] = preg_replace("/\r?\n/", "\\n", $v);
        }
        fputcsv($this->buffer, $row, chr(9), chr(0x0A));
    }

    function renderHeaders() {

        header("Content-Disposition: attachment; filename=".$this->filename);
        header("Content-Type: application/vnd.ms-excel");

    }

    function setFilename($filename) {
        $this->filename = $filename;
        if (strtolower(substr($this->filename, -4)) != '.xls') 
        {
            $this->filename .= '.xls';
        }
    }

    function render($outputHeaders = true, $to_encoding = null, $from_encoding ="auto") 
    {
        if ($outputHeaders) 
        {
            if (is_string($outputHeaders)) 
            {
                $this->setFilename($outputHeaders);
            }
            $this->renderHeaders();
        }
        rewind($this->buffer);
        $output = stream_get_contents($this->buffer);

        if ($to_encoding) 
        {
            $output = mb_convert_encoding($output, $to_encoding, $from_encoding);
        }
        return $this->output($output);
    }
}

?>
