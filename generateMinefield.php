<?php

class MinefieldGenerator {

	private $width;
	private $height;
	private $board;

	public function __construct($width, $height) {
		$this->width = $width;
		$this->height = $height;
		$this->constructBoard();
	}	

	private function constructBoard() {
		$this->initBoard();		
		$this->fillMines();
		$this->printBoard();
	}
	
	private function initBoard() {
		$this->board = array();
		for ( $row=0; $row < $this->height; $row++) {
			$this->board[$row] = array();
			for ( $column=0; $column < $this->width; $column++) {
				$this->board[$row][$column] = '.';
			}
		}
	}

	private function fillMines() {		
		$mineSpots = array();
		for ( $mine=0; $mine < $this->numOfMines(); $mine++ ) {
			$spot = $this->pickRandomSpot();
			while (in_array($spot, $mineSpots)) {
				$spot = $this->pickRandomSpot();
			}
			array_push($mineSpots, $spot);
			$this->board[$spot[0]][$spot[1]] = '*';
		}
	}

	private function numOfMines() {		
		return round(0.15625*$this->width*$this->height);
	}

	private function pickRandomSpot() {
		return array( rand(0, $this->height - 1), rand(0, $this->width - 1) );
	}	

	public function printBoard() {		
		print $this->width . " " . $this->height . "<br/>";
		for ( $row=0; $row < $this->height; $row++) {			
			for ( $column=0; $column < $this->width; $column++) {
				print $this->board[$row][$column];
			}
			print '<br/>';
		}
	}		

}

$width = $_GET["width"];
$height = $_GET["height"];
$minefield = new MinefieldGenerator($width, $height);

?>
