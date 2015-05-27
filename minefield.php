<?php

/**
 * Minefield Class
 * 
 * Reads from file a minefield board of the form:
 * 
 * 		*... 
 *		..*.  
 *		....
 * 
 * and transforms it to the following form:
 * 
 * 		*211 
 *		12*1 
 *		0111
 *
 * @author Nikos Kirtsis <nkirtsis@gmail.com>
 * @copyright 2015 Nikos Kirtsis
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Minefield {
	
	/**
	 * @var int 	the width of the minefield board
	 */
	private $width;

	/**
	 * @var int 	the height of the minefield board
	 */
	private $height;

	/**
	 * @var array 	the minefield board
	 */
	private $board = array();

	/**
	 * Construct minefield board given input file.
	 * 
	 * @param string $file 	the input file that contains the board in dot form
	 */
	public function __construct($file) {
		$this->loadBoardFromFile($file);
		$this->fillNumbers();
	}

	/**
	 * Loads the board from file into memory.
	 * 
	 * @param string $file 	the input file that contains the board in dot form
	 */
	private function loadBoardFromFile($file) {		
		if ($handle = fopen($file, "r")) {
			$dimensions = explode(" ", trim(fgets($handle)));
			$this->width = $dimensions[0];
			$this->height = $dimensions[1];						
			while ( ($line = fgets($handle)) !== false ) {
				array_push($this->board, str_split(trim($line)));
			}
			fclose($handle);
		} else {						
			// or throw new Exception("Error opening the file!");			
			print "Error opening the file!";
		}
	}

	/**
	 * Replaces dots with numbers in the minefield board.	 
	 */
	private function fillNumbers() {
		for ( $row=0; $row < $this->height; $row++) {			
			for ( $column=0; $column < $this->width; $column++) {				
				if ($this->board[$row][$column] === '.') {
					$neighbors = $this->getNeighborSpots(array($row, $column));					
					$mines = $this->countNeighborMines($neighbors);
					$this->board[$row][$column] = $mines;
				}
			}
		}
	}

	/**
	 * Returs the neighbors of a given spot.
	 * 
	 * @param 	array 	$spot 	square's coordinates
	 * @return 	array 			array of neighbors' coordinates
	 */
	private function getNeighborSpots(array $spot) {
		return array(
				array($spot[0]-1, $spot[1]-1),
				array($spot[0]-1, $spot[1]  ),
				array($spot[0]-1, $spot[1]+1),
				array($spot[0]  , $spot[1]-1),
				array($spot[0]  , $spot[1]+1),
				array($spot[0]+1, $spot[1]-1),
				array($spot[0]+1, $spot[1]  ),
				array($spot[0]+1, $spot[1]+1)
			);
	}

	/**
	 * Counts the mines in the neighborhood.
	 * 
	 * @param 	array 	$spots 	array of neighbors' coordinates
	 * @return 	int 	$mines 	the number of the mines in the meighborhood
	 */
	private function countNeighborMines(array $spots) {		
		$mines = 0;
		foreach ( $spots as $spot ) {				
			if ( $this->isValidSpot($spot) AND $this->isMine($spot) ) {
				$mines++;
			}
		}
		return $mines;
	}

	/**
	 * Checks if given spot is valid (inside board).
	 * 
	 * @param 	array 		$spot 	neighbor's coordinates
	 * @return 	boolean				whether the given spot is valid. Defaults TRUE.
	 */
	private function isValidSpot(array $spot) {				
		if ( ! array_key_exists($spot[0], $this->board) OR
				! array_key_exists($spot[1], $this->board[$spot[0]] )) {
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Checks if given spot is a mine.
	 * 
	 * @param 	array 		$spot 	neighbor's coordinates
	 * @return 	boolean				whether the given spot is mine. Defaults FALSE.
	 */
	private function isMine(array $spot) {
		if ( $this->board[$spot[0]][$spot[1]] === "*" ) {				
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Prints pretty the minefield board.
	 */
	public function printBoard() {		
		for ( $row=0; $row < $this->height; $row++) {			
			for ( $column=0; $column < $this->width; $column++) {
				print $this->board[$row][$column] . " ";
			}
			print "<br/>";
		}
	}	


}

// call something like: 	http://localhost/Westwing/minefield.php?file=minefieldSmall.txt
$file = $_GET["file"];
$minefield = new Minefield($file);
$minefield->printBoard();

?>