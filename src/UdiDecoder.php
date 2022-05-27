<?php

namespace Brixion\Bardecoder;

use Exception;
use Brixion\Bardecoder\HIBCSecondaryDataDecoder;

class UdiDecoder
{
    // Debug field $count
    private static int $count = 1;
    public string $barcode;
    public string $barcode_raw;
    public string $barcode_stripped;
    public string $lic;
    public string $product_code;
    public string $packaging_index;
    public string $check_character;
    public bool $is_valid;    

    public ?bool $contains_secondary_data = null;
    public ?string $secondary_data = null;
    
    public ?string $secondary_data_flag = null;
    public ?string $lot = null;
    public ?string $expiry_date = null;

    public ?string $date_of_manufacture_data_identifier = null;
    public ?string $date_of_manufacture = null;

    public ?string $secondary_supplemental_data_identifier = null;
    public ?string $serial_number = null;

    public ?string $quantity_identifier = null;
    public ?string $quantity = null;

    /**
     * UdiDecoder constructor.
     * Removes leading and trailing asterisks from barcode
     * Removes + character and all characters before it
     * Checks if barcode is longer than 4 character after cleanup
     * @param string $barcode
     * @throws \Exception
     */
    function __construct(string $barcode)
    {
        $this->barcode_raw = $barcode;

        $barcode = trim($barcode, '*');
        $pos = strpos($barcode, '+');
        if ($pos === false) 
            throw new Exception('Barcode is invalid, no + character found');

        // We need 4 characters for LIC, at least 1 for productcode and 1 for check 
        if (strlen($barcode) < 7)
            throw new Exception('Barcode is invalid, too short');
        
        $this->barcode_stripped =  $barcode;
        
        // The check character is always last. Even on barcodes with secondary data
        $last_char = substr($barcode, -1);
        if (!preg_match('/[a-zA-Z0-9-. $\/+%]{1}/', $last_char))
            throw new Exception('Barcode is invalid, check character is not a valid character');
        $this->check_character = $last_char;

        // Get string between + and last character
        $this->barcode = substr($barcode, $pos + 1, -1);

        // Check if barcode has secondary data in it
        $pos = strpos($barcode, '/');
        if ($pos !== false) {
            $this->contains_secondary_data = true;
            
            // Primary data in barcode, secondary data in secondary data
            $this->secondary_data = substr($this->barcode, $pos);
            $this->barcode = substr($this->barcode, 0, $pos);
            $this->barcode = rtrim($this->barcode, '/');


            $secondary_decoder = new HIBCSecondaryDataDecoder($this);

            

        }

        // Check if barcode begins with $ for lot only codes
        // if (substr($this->barcode, 0, 1) == '$'){
        //     $this->getPackagingIndex();
        //     $this->getLot();
        //     $this->test();
        // }

        $this->decode();
    }

    public function decode(): void
    {
        // Debug string
        //echo self::$count++ . ' ' . $this->barcode_raw . PHP_EOL;

        $this->getLic();
        $this->getProductCode();
        $this->getPackagingIndex();
        $this->checkBarcodeValidity();
    }

    public function getLic(): void
    {
        if (!ctype_alpha($this->barcode[0]))
            throw new Exception("First character of Labeler Identification Code (LIC) must be alphabetic");
            
        $this->lic = substr($this->barcode, 0, 4);
    }

    public function getProductCode(): void
    {   
        // remove LIC and Packaging Index
        $barcode = substr($this->barcode, 4);
        $barcode = substr($barcode, 0, -1);
        
        if (!ctype_alnum($barcode))
            throw new Exception("Product Code must only contain digits and alphabetic characters");

        $this->product_code = $barcode;  
    }

    public function getPackagingIndex(): void
    {
        $barcode = substr($this->barcode, -1);

        if (!ctype_digit($barcode))
            throw new Exception("Packaging Index must be a number");

        $this->packaging_index = $barcode;
    }

    public function checkBarcodeValidity(): bool
    {
        $barcode = "+".$this->barcode;

        // define $modulo_check_characters
        require __DIR__ . '/ModuloCheckArray.php'; 
        
        $barcode_check_character = $this->check_character;

        // compare each character in barcode with $modulo_check_characters values and get the sum
        $sum = 0;
        foreach(str_split($barcode) as $char)
        {
            $sum += $modulo_check_characters[strtoupper($char)];
        }
        
        $remainder = $sum % 43;

        // get the key according to the sum value from $modulo_check_characters
        $check_character = array_search($remainder, $modulo_check_characters);

        // echo 'Check character: ' . $barcode_check_character . PHP_EOL;
        // echo 'Calculated check character: ' . $check_character . PHP_EOL.PHP_EOL;
        if ($barcode_check_character == $check_character) {
            $this->is_valid = true;
            return true;
        } else {
            $this->is_valid = false;
            return false;
        }
    }

    public function getLot()
    {
        $lot = substr($this->barcode, 1, -1);
    }

    public function test(){
        print_r($this);
        die();
    }
}