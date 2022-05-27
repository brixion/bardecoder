<?php declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;
use phpDocumentor\Reflection\Types\Void_;

final class HbicLicSecondaryDataStructureTest extends TestCase
{
    private string $hibc001 = '+J014652230580/$$3220909K010025/16D20200311Z';
    private string $hibc002 = '*+D002606030421/$$323101700056020/16D20201020Z*';

    // HIBC barcodes from the documentation themselves
    private array $hibcs = [
            // [
            //     // testing barcode
            //     'barcode' => '+J014652230580/$$01222iamalotnr/16D20200311Z',
            //     'lic' => 'J014',
            //     'product_code' => '65223058',
            //     'packaging_index' => '0',
            //     'check_character' => 'Z',
            //     'secondary_data' => '$$01222iamalotnr/16D20200311Z',

            //     'secondary_data_flag' => '$$0',
            //     'expory_date' => '2022-12-01',
            //     'lot' => 'iamalotnr',

            // ],
            [
                // primary / secondary with expiry date and lot / date of manufacture / serial with mod check
                'barcode' => '+A99912345/$$52001510X3/16D20111212/S77DEFG457',
                'barcode_asterisk' => '*+A99912345/$$52001510X3/16D20111212/S77DEFG457*',
                'barcode_invalid' => '+A99912345/$$52001510X3/16D20111212/S77DEFG45Z',
                'lic' => 'A999',
                'product_code' => '1234',
                'packaging_index' => '5',
                'check_character' => '7',
                'secondary_data' => '$$52001510X3/16D20111212/S77DEFG45',
                
                'secondary_data_flag' => '$$5',
                'expiry_date' => '2020-01-15',
                'lot' => '10X3',
                'date_of_manufacture_data_identifier' => '16D',
                'date_of_manufacture' => '2011-12-12',
                'secondary_supplemental_data_identifier' => 'S',
                'serial_number' => '77DEFG45',
            ],
            [
                // primary / secondary with lot only / date of manufacture / expiry date with mod check
                'barcode' => '+A99912345/$10X3/16D20111231/14D202001313',
                'barcode_asterisk' => '*+A99912345/$10X3/16D20111231/14D202001313*',
                'barcode_invalid' => '+A99912345/$10X3/16D20111231/14D20200131Z',
                'lic' => 'A999',
                'product_code' => '1234',
                'packaging_index' => '5',
                'check_character' => '3',
                'secondary_data' => '$10X3/16D20111231/14D20200131',

                'secondary_data_flag' => '$',
                'lot' => '10X3',
                'date_of_manufacture_data_identifier' => '16D',
                'date_of_manufacture' => '2011-12-31',
                'secondary_supplemental_data_identifier' => '14D',
                'expiry_date' => '2020-01-31',
            ],
            [
                 // primary with 9 unit of measure / secondary with lot only / date of manufacture / expiry date / quantity identifier with mod check
                'barcode' => '+A99912349/$10X3/16D20111231/14D20200131/Q500Z',
                'barcode_asterisk' => '*+A99912349/$10X3/16D20111231/14D20200131/Q500Z*',
                'barcode_invalid' => '+A99912349/$10X3/16D20111231/14D20200131/Q500A',
                'lic' => 'A999',
                'product_code' => '1234',
                'packaging_index' => '9',
                'check_character' => 'Z',
                'secondary_data' => '$10X3/16D20111231/14D20200131/Q500',

                'secondary_data_flag' => '$',
                'lot' => '10X3',
                'date_of_manufacture_data_identifier' => '16D',
                'date_of_manufacture' => '2011-12-31',
                'secondary_supplemental_data_identifier' => '14D',
                'expiry_date' => '2020-01-31',
                'quantity_identifier' => 'Q',
                'quantity' => '500',
            ],
            [
                // Taken from documentation as well
                'barcode' => '+J123AQ3451/$$3231231BC34567/S4012R',
                'barcode_asterisk' => '*+J123AQ3451/$$3231231BC34567/S4012R*',
                'barcode_invalid' => '+J123AQ3451/$$3231231BC34567/S4012Z',
                'lic' => 'J123',
                'product_code' => 'AQ345',
                'packaging_index' => '1',
                'check_character' => 'R',
                'secondary_data' =>  '$$3231231BC34567/S4012',

                'secondary_data_flag' => '$$3',
                'expiry_date' => '2023-12-31',
                'lot' => 'BC34567',
                'secondary_supplemental_data_identifier' => 'S',
                'serial_number' => '4012',
            ]
        ];
        
    /** @test
      * 
     */
    public function all_barcodes_pass_lic_test(): void
    {
        foreach($this->hibcs as $hibc){
            $decoder = new UdiDecoder($hibc['barcode']);
            $this->assertEquals($hibc['lic'], $decoder->lic);
        }
    }

    /** @test
      * 
     */
    public function all_barcodes_pass_product_code_test(): void
    {
        foreach($this->hibcs as $hibc){
            $decoder = new UdiDecoder($hibc['barcode']);
            $this->assertEquals($hibc['product_code'], $decoder->product_code);
        }
    }

    /** @test
      * 
     */
    public function all_barcodes_pass_packaging_index_test(): void
    {
        foreach($this->hibcs as $hibc){
            $decoder = new UdiDecoder($hibc['barcode']);
            $this->assertEquals($hibc['packaging_index'], $decoder->packaging_index);
        }
    }

    /** @test
      * 
     */
    public function all_barcodes_pass_check_character_test(): void
    {
        foreach($this->hibcs as $hibc){
            $decoder = new UdiDecoder($hibc['barcode']);
            $this->assertEquals($hibc['check_character'], $decoder->check_character);
        }
    }

    /** @test
      * 
     */
    public function all_barcodes_pass_secondary_data_test(): void
    {
        foreach($this->hibcs as $hibc){
            $decoder = new UdiDecoder($hibc['barcode']);
            $this->assertEquals($hibc['secondary_data'], $decoder->secondary_data);
        }
    }

    /** test
      * 
     */
    public function all_barcodes_pass_is_valid_test(): void
    {
        foreach($this->hibcs as $hibc){
            $decoder = new UdiDecoder($hibc['barcode']);
            $this->assertEquals($decoder->is_valid, true);
        }
    }

    /** @test
      * 
     */
    public function all_barcodes_pass_is_invalid_test(): void
    {
        foreach($this->hibcs as $hibc){
            $decoder = new UdiDecoder($hibc['barcode_invalid']);
            $this->assertEquals($decoder->is_valid, false);
        }
    }

    /** @test
      * 
     */
    public function all_barcodes_can_extract_lot(): void
    {
        foreach($this->hibcs as $hibc){
            $decoder = new UdiDecoder($hibc['barcode']);
            $this->assertEquals($hibc['lot'], $decoder->lot);
        }
    }

    /** @test
      * 
     */
    public function all_barcodes_can_extract_expiry_date(): void
    {
        foreach($this->hibcs as $hibc){
            if(isset($hibc['expiry_date'])){
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals($hibc['expiry_date'], $decoder->expiry_date);
            } else {
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals(null, $decoder->expiry_date);
            }
        }
    }

    /** @test
      * 
     */
    public function all_bacodes_can_extract_date_of_manufacture(): void
    {
        foreach($this->hibcs as $hibc){
            if(isset($hibc['date_of_manufacture'])){
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals($hibc['date_of_manufacture'], $decoder->date_of_manufacture);
            } else {
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals(null, $decoder->date_of_manufacture);
            }
        }
    }

    /** @test
      * 
     */
    public function all_barcodes_can_extract_serial_number(): void
    {
        foreach($this->hibcs as $hibc){
            if(isset($hibc['serial_number'])){
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals($hibc['serial_number'], $decoder->serial_number);
            } else {
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals(null, $decoder->serial_number);
            }
        }
    }

    /** test
      * 
     */
    public function all_barcodes_can_extract_quantity_identifier(): void
    {
        foreach($this->hibcs as $hibc){
            if(isset($hibc['quantity_identifier'])){
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals($hibc['quantity_identifier'], $decoder->quantity_identifier);
            } else {
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals(null, $decoder->quantity_identifier);
            }
        }
    }

    /** test
      * 
     */
    public function all_barcodes_can_extract_quantity(): void
    {
        foreach($this->hibcs as $hibc){
            if(isset($hibc['quantity'])){
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals($hibc['quantity'], $decoder->quantity);
            } else {
                $decoder = new UdiDecoder($hibc['barcode']);
                $this->assertEquals(null, $decoder->quantity);
            }
        }
    }
}