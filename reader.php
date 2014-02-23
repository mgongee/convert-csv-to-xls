<?php

class csv2xlsReader {
	
	public $fileContents = array();
	
	public function __construct($filename) {
		$this->read($filename);
	}
	
	public function read($filename) {
		if (($handle = fopen($filename, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$this->fileContents[] =  $data;
			}
			fclose($handle);
		}
    }
    
	public function printContents() {
		echo('<pre>'.print_r($this->fileContents,true).'</pre>');
	}
	
	public function getData() {
		return $this->fileContents;
	}
}	

