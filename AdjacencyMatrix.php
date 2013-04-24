<?php

/**
 * A square adjacency matrix which is optimally memory efficient for representing boolean values.
 * Uses just 1 bit of memory per cell in the matrix.
 */
class AdjacencyMatrix
{
    /**
     * This is a long string used as memory for the matrix.
     * If the matrix was 16x16, then strlen($memory) === 4. Each "character" 
     * in a php string is 1 byte because php strings have no encoding. And a 
     * byte is 8 bits. So, each character can store 8 boolean values, 
     * if we set the bit to 0 for false, and set it to 1 for true.
     * 
     * We use a single long string instead of using a string for each row 
     * in the matrix, because it has far less memory overhead this way.
     * 
     * 
     * @var string
     */
    protected $memory;
    
    /**
     *
     * @var int
     */
    protected $matrixWidth;
    
    /**
     * Initializes the matrix, making it ready to use. All entries are 
     * initialized to false.
     * 
     * 
     * @param int $width how many items need to be represented in the matrix.
     * For example, if $width was 5, then the matrix dimensions would be 5x5, 
     * which ultimately holds 25 entries.
     */
    public function __construct($width)
    {
        $this->matrixWidth = $width;
        $this->memory = $this->allocateMemory($width);
    }
    
    
    /**
     * Creates the string of memory
     * @param type $matrixWidth the width of the desired matrix. 
     * @return string
     */
    protected function allocateMemory($matrixWidth)
    {
        // we use a square matrix, so twice the width
        // this is also how many entries or "cells" the matrix will store
        $bitsNeeded = $matrixWidth * $matrixWidth;
        
        //round up to the next biggest byte
        $bytesNeeded = ceil($bitsNeeded / 8);
        
        //a character which has all bits set to 0
        $nullByte = chr(0);
        
        // make the string
        return str_repeat($nullByte, $bytesNeeded);
    }
    
    protected function bitIndexToByteOffset($index)
    {
        return (int) floor($index / 8);
    }
    
    public function get($row, $col)
    {
        $this->checkBounds($row, $col);
        $bitIndex = ($row * $this->matrixWidth) + $col;
        $byteOffset = $this->bitIndexToByteOffset($bitIndex);
        $byte = $this->memory[$byteOffset];
        $bit = byte % 8;
        return ($byte >> $bit - 1) & 1;
    }
    
    public function set($row, $col, $boolean)
    {
        $this->checkBounds($row, $col);
        $bitIndex = ($row * $this->matrixWidth) + $col;
        $byteOffset = $this->bitIndexToByteOffset($bitIndex);
        $byte = $this->memory[$byteOffset];
        $bit = $bitIndex - (8 * $byteOffset);
        echo "bofs $byteOffset\n";
        echo "bit  $bit\n";
        //if bit was 5, mask would be 0010000
        $mask = (1 << $bit);
        
        if ($boolean) {
            //turn the bit on
            $newByte = $byte | $mask;
            printf("mask %08b\n", $mask);
            printf("byte %08b\n", $byte);
            printf("nbyt %08b\n", $newByte);
        } else {
            //turn the bit off
            $newByte = $byte ^ $mask;
        }
        
        $this->memory[$byteOffset] = $newByte;
    }
    
    public function __toString()
    {
        $buf = '';
        foreach (str_split($this->memory) as $char)
        {
            $buf .= sprintf("%08b", $char) . ' ';
        }
        return $buf;
    }
    
    protected function checkBounds($row, $col)
    {
        $max = $this->matrixWidth - 1;
        if ($row < 0 || $row > $max)
        {
            throw new OutOfBoundsException("row value out of bounds. row=$row, max=$max");
        }
        
        if ($col < 0 || $col > $max)
        {
            throw new OutOfBoundsException("col value out of bounds. col=$col, max=$max");
        }
    }
}