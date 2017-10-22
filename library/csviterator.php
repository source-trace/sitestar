<?php
/**
 * Description of CSVIterator
 *
 * @author renzhen
 * @since 2011-04-18 11:26
 * @version 2.1
 */

class CSVBadFormatException extends Exception
{
	
}
class CSVIterator implements Iterator
{	
	const ROW_SIZE = 4096;
	
	private $filePointer;
	private $currentElement;
	private $rowCounter;
	private $delimiter;
	private $headrow;
     private $isend;
	
	public function __construct( $file, $hasheader=false,$delimiter = ',' )
	{
		$this->filePointer = fopen( $file, 'r' );
		$this->delimiter   = $delimiter;
		if($hasheader) $this->headrow=fgetcsv( $this->filePointer, self::ROW_SIZE, $this->delimiter );
          $this->isend=false;
	}
	
	public function rewind()
	{
		$this->rowCounter = 0;
          $this->isend=false;
		rewind( $this->filePointer );
          if(!empty($this->headrow)) fgetcsv( $this->filePointer, self::ROW_SIZE, $this->delimiter );
          $this->currentElement = fgetcsv( $this->filePointer, self::ROW_SIZE, $this->delimiter );
	}
	
	public function current()
	{
//		$this->currentElement = fgetcsv( $this->filePointer, self::ROW_SIZE, $this->delimiter );
		if(!empty($this->headrow)){
			if(count($this->headrow)!=count($this->currentElement)){
				$this->close();
				throw new CSVBadFormatException;
			}
		     $this->currentElement =array_combine($this->headrow,$this->currentElement);
		}
		$this->rowCounter++;
		return $this->currentElement;
	}
	
	public function key()
	{
		return $this->rowCounter;
	}
	
	public function next()
	{
//		return !feof( $this->filePointer );
         $this->currentElement = fgetcsv( $this->filePointer, self::ROW_SIZE, $this->delimiter );
	}
	
	public function valid()
	{
//      if( !$this->next() )
		if( $this->currentElement===FALSE )
		{
			
			return FALSE;
		}
		return TRUE;
	}
  
      public function close(){
            fclose( $this->filePointer );
      }

} // end class
?>
