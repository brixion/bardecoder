<?php

namespace Brixion\Bardecoder;

use Exception;
use Brixion\Bardecoder\HIBCSecondaryDataDecoder;

class UdiDecoder
{
    // Debug field $count
    private static int $count = 1;
    public ?string $barcode = null;
    public ?string $barcode_raw = null;
    public ?string $barcode_stripped = null;
    public ?string $lic = null;
    public ?string $product_code = null;
    public ?string $packaging_index = null;
    public ?string $check_character = null;
    public ?string $link_character = null;
    public ?bool $is_valid = null;

    public ?bool $contains_secondary_data = null;
    public ?string $secondary_data = null;
    public ?array $secondary_parts = null;

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

        // Debug string
        //echo PHP_EOL.self::$count++ . ' ' . $this->barcode_raw . PHP_EOL;

        $barcode = trim($barcode, '*');

        if (preg_match('/^\+?\$\$?/', $barcode)) {
            // I allow $$ instead of +$$ but this is not valid UDI
            $this->handleLotOnlyCode($barcode);
            return;
        } elseif ($barcode[0] != "+") {
            // match all groups with the character possibly enclosed by ()
            if (preg_match('/^\(?([\d]{2})\)?/', $barcode)) {
                //throw new Exception("Not supported yet");
                $this->handleGS1Code($barcode);
                return;
            } else {
                throw new Exception('Barcode is invalid, does not start with + or $ for HIBC or contain any GS1 data');
            }
        }

        $pos = strpos($barcode, "+");

        // We need 7 characters. 4 for LIC, at least 1 for productcode and 1 for check 
        if (strlen($barcode) < 7)
            throw new Exception('Barcode is invalid, too short');

        $this->barcode_stripped =  $barcode;
        $this->handleHIBCBarcode($barcode, $pos);
    }

    public function handleGS1Code($barcode)
    {
        $this->barcode_raw = $barcode;

        // remove all ( and ) characters
        $barcode = preg_replace('/[\(\)]/', '', $barcode);
        $this->barcode = $barcode;

        while (strlen($barcode) > 0) {
            $barcode = $this->handleGS1Part($barcode);
        }
    }

    public function handleGS1Part($part)
    {
        $ai = substr($part, 0, 2);
        if ($ai == "00")
            throw new Exception("GS1 AI starts with 00 (SSCC). this is not supported");
        elseif ($ai == "01")
            return $this->handleGTINPart($part);
        elseif ($ai == "17")
            return $this->handleExpiryDatePart($part);
        elseif ($ai == "11")
            return $this->handleProductionDatePart($part);
        elseif ($ai == "10")
            return $this->handleLotPart($part);
    }

    public function handleGTINPart($part): string
    {
        $gtin = substr($part, 2, 14);
        $check_character = substr($gtin, -1);
        $gtin_stripped = substr($gtin, 0, -1);
        
        $this->check_character = $check_character;
        $this->product_code = ltrim($gtin_stripped, '0');
        // remove AI and product_code
        return substr($part, (2 + 14));
    }

    public function handleExpiryDatePart($part): string
    {
        $this->expiry_date = $this->getDateFromPart($part);
        // remove AI and expiry_date
        return substr($part, (2 + 6));
    }

    public function handleProductionDatePart($part): string
    {
        $this->date_of_manufacture = $this->getDateFromPart($part);
        // remove AI and date_of_manufacture
        return substr($part, (2 + 6));
    }

    public function getDateFromPart($part): string
    {
        $yy = substr($part, 2, 2);
        $mm = substr($part, 4, 2);
        $dd = substr($part, 6, 2);
        return "20$yy-$mm-$dd";
    }

    public function handleLotPart($part): string
    {
        $pos = strpos($part, "<gs>");

        // Use max length when no end character present
        if($pos === false)
            $pos = 20;
        
        // lot is between AI and <gs>
        $this->lot = substr($part, 2, $pos - 2);
        // remove AI and lot and <gs>
        return substr($part, ($pos + 4));
    }

    public function handleHIBCBarcode($barcode, $pos)
    {
        $this->handleCheckCharacter($barcode);
        // Get all characters between + and check character
        $this->barcode = substr($barcode, $pos + 1, -1);

        $pos = strpos($barcode, '/');
        if ($pos !== false) {
            $this->contains_secondary_data = true;

            // Primary data in barcode, secondary data in secondary data
            $this->secondary_data = substr($this->barcode, $pos);
            $this->barcode = substr($this->barcode, 0, $pos);
            $this->barcode = rtrim($this->barcode, '/');
            $this->secondary_parts = explode('/', $this->secondary_data);
            $secondary_decoder = new HIBCSecondaryDataDecoder($this);
        }
        $this->decode();
    }

    public function handleLotOnlyCode($barcode)
    {
        // Left is the link character, right is the check character
        // The link character will link it to a previous primary barcode
        $lc = substr($barcode, -2);
        $this->link_character = $lc[0];
        $this->check_character = $lc[1];

        $this->barcode = substr($barcode, 0, -2);
        if ($this->barcode[0] == "+") {
            $this->barcode = substr($this->barcode, 1);
        }

        $this->secondary_parts = explode('/', $this->barcode);
        $secondary_decoder = new HIBCSecondaryDataDecoder($this);
    }

    public function handleCheckCharacter($barcode)
    {
        // The check character is always last
        $last_char = substr($barcode, -1);
        if (!preg_match('/[a-zA-Z0-9-. $\/+%]{1}/', $last_char))
            throw new Exception('Barcode is invalid, check character is not a valid character');
        $this->check_character = $last_char;
    }

    public function decode(): void
    {
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
        $barcode = "+" . $this->barcode;

        // define $modulo_check_characters
        require __DIR__ . '/ModuloCheckArray.php';

        $barcode_check_character = $this->check_character;

        // compare each character in barcode with $modulo_check_characters values and get the sum
        $sum = 0;
        foreach (str_split($barcode) as $char) {
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
}
