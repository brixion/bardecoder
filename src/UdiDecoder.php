<?php

namespace Brixion\Bardecoder;

use Exception;

class UdiDecoder
{
    // Debug field $count
    private static $count = 1;
    public string $lic;
    public string $product_code;
    public string $packaging_index;
    public string $check_character;
    public bool $is_valid;

    public function decode(string $barcode): void
    {
        // Debug string
        echo self::$count++ . ' ' . $barcode . PHP_EOL;

        $barcode = trim($barcode, '*');
        // Check for + character
        if (substr($barcode, 0, 1) !== '+')
            throw new Exception('Barcode must start with +');
        
        // remove + from barcode
        $barcode = substr($barcode, 1);

        $this->getLic($barcode);
        $this->getProductCode($barcode);
        $this->getPackagingIndex($barcode);
        $this->getCheckCharacter($barcode);
        $this->checkBarcodeValidity($barcode);
    }
    public function getLic(string $barcode): string
    {
        // check if first character is alpabetical
        if (!ctype_alpha(substr($barcode, 0, 1)))
            throw new Exception("First character of Labeler Identification Code (LIC) must be alphabetic");
            
        $this->lic = substr($barcode, 0, 4);
        return $this->lic;
    }

    public function getProductCode(string $barcode): string
    {
        // remove lic from barcode
        $barcode = substr($barcode, 4);
        // remove unit and check character from barcode
        $barcode = substr($barcode, 0, -2);

        if (!ctype_alnum($barcode))
            throw new Exception("Product Code must only contain digits and alphabetic characters");

        $this->product_code = $barcode;
        return $this->product_code;     
    }

    public function getPackagingIndex(string $barcode): string
    {
        // get the second to last character
        $barcode = substr($barcode, -2)[0];

        // check if it is a digit
        if (!ctype_digit($barcode))
            throw new Exception("Packaging Index must be a number");

        $this->packaging_index = $barcode;
        return $barcode;
    }
    
    public function getCheckCharacter(string $barcode): string
    {
        // get the last character
        $barcode = substr($barcode, -1);

        // check if it is alnum
        if (!ctype_alnum($barcode))
            throw new Exception("Check character must be alphanumeric");
        
        $this->check_character = $barcode;
        return $barcode;
    }

    public function checkBarcodeValidity(string $barcode): bool
    {
        // shitty fix for a bug I made myself. Will fix with refactoring
        $barcode = "+".$barcode;
        $barcode = substr($barcode, 0, -1);

        // define $modulo_check_characters
        require __DIR__ . '/ModuloCheckArray.php'; 
        
        $barcode_check_character = $this->getCheckCharacter($barcode);
        echo "Check character: " . $barcode_check_character . PHP_EOL;
        // compare each character in barcode with $modulo_check_characters values and get the sum
        $sum = 0;
        //var_dump(str_split($barcode));
        foreach(str_split($barcode) as $char)
        {
            $sum += $modulo_check_characters[$char];
        }
        
        $remainder = $sum % 43;

        // get the key according to the sum value from $modulo_check_characters
        $check_character = array_search($remainder, $modulo_check_characters);

        if ($barcode_check_character == $check_character) {
            $this->is_valid = true;
            echo 'Barcode is valid' . PHP_EOL;
            return true;
        } else {
            $this->is_valid = false;
            echo 'Barcode is not valid' . PHP_EOL;
            return false;
        }
    }
}