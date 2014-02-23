<?php

class csv2xlsProcessor {
	
	public $fileContents = array();
	public $processedContents = array();
	
	public $columnsKeys = array(
		'Grand Total',		
		'Billing Description',
		'Shipping Country',
		'Weight',
		'Quantity Items'
	);
	
	public $columnsIndexes = array( //starting from 0
		'Grand Total'			=> 6,
		'Billing Description'	=> 7,
		'Shipping Country'		=> 15,
		'Weight'				=> 2,
		'Quantity Items'		=> 3
	);
	
	public function __construct($fileContents) {
		$this->headers = $fileContents[0];
		$this->getColumnIndexes();
		$this->fileContents = array_slice($fileContents, 1);
	}
	
	public function getColumnIndexes() {
		foreach ($this->columnsKeys as $key) {
			foreach ($this->headers as $i => $columnHeader) {
				if ($columnHeader == $key) {
					$this->columnsIndexes[$key] = $i; 
				}
			}
		}
	}
	
	public function process() {
		/*
		2rd step:
		delete record if "Shipping Country" is (not equal) to "Italia"
		*/
		$this->processedContents = array();
		foreach ($this->fileContents as $row) {
			if ($row[$this->columnsIndexes['Shipping Country']] == 'Italia') {
				$this->processedContents[] = $row;
			}
		}

		foreach ($this->processedContents as &$row) {
			/*
			1nd step:
			clear "Grand Total" if "Billing Description" is (not equal) to COD
			 */
			if ($row[$this->columnsIndexes['Billing Description']] != 'COD') {
				$row[$this->columnsIndexes['Grand Total']] = '';
			}			

			/*
			3th step:
			"Weight" set value to "1"
			*/
			$row[$this->columnsIndexes['Weight']] = '1';
			
			/*
			4th step:
			"Quantity Items" set value to "1" 
			*/
			$row[$this->columnsIndexes['Quantity Items']] = '1';
		}
		
    }
	
	public function printContents() {
		echo('<pre>'.print_r($this->processedContents,true).'</pre>');
	}
	
	public function getData() {
		$result = array_merge(
			array($this->headers),
			$this->processedContents
		);
		return $result;
	}
}