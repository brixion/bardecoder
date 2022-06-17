<?php

declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;
use phpDocumentor\Reflection\Types\Void_;

final class HbicLicSecondaryDataStructureTest extends TestCase
{
    private string $hibc001 = '+J014652230580/$$3220909K010025/16D20200311Z';
    private string $hibc002 = '*+D002606030421/$$323101700056020/16D20201020Z*';

    // HIBC barcodes from the documentation themselves
    // I am not using a DataProvider here to allow usage of array keys
    private array $hibcs = [
        [
            "barcode" => "+E490HE21KL1/$+HEK13044/16D20220531/Q1$",
            "lic" => "E490",
            "product_code" => "HE21KL",
            "secondary_data" => "$+HEK13044/16D20220531/Q1",
            "packaging_index" => "1",
            "check_character" => "$",
            "link_character" => "L",
            "lot" => "HEK13044",
            "date_of_manufacture" => "2022-05-31",
            "quantity" => "1",

        ],
        [
            "barcode" => "+DVIV627261AN1/$$3250303Z025XHKW",
            "lic" => "DVIV",
            "secondary_data" => "$$3250303Z025XHK",
            "check_character" => "W",
            "link_character" => "K",
            "packaging_index" => "1",
            "product_code" => "627261AN",
            "lot" => "Z025XH",
            "expiry_date" => "2025-03-03"
        ],
        // 1
        [
            'barcode' => '+D002624225011/$$705224372/16D202201195',
            'lic' => 'D002',
            'product_code' => '62422501',
            'packaging_index' => '1',
            'secondary_data' => '$$705224372/16D20220119',
            'lot' => '05224372',
            'expiry_date' => null,
            'date_of_manufacture' => '2022-01-19',
            'check_character' => '5',
            'link_character' => '9',
        ],
        // 2
        [
            'barcode' => '+D002624225101/$$704814060/16D202109022',
            'lic' => 'D002',
            'product_code' => '62422510',
            'packaging_index' => '1',
            'secondary_data' => '$$704814060/16D20210902',
            'lot' => '04814060',
            'expiry_date' => null,
            'date_of_manufacture' => '2021-09-02',
            'check_character' => '2',
            'link_character' => '2',
        ],
        // 3
        [
            'barcode' => '+D002659740V1/$$704978087T',
            'lic' => 'D002',
            'product_code' => '659740V',
            'packaging_index' => '1',
            'secondary_data' => '$$704978087',
            'lot' => '04978087',
            'expiry_date' => null,
            'check_character' => 'T',
            'link_character' => '7',
        ],
        // 4
        [
            'barcode' => '+D0038012221/$$322102500057270C',
            'lic' => 'D003',
            'product_code' => '801222',
            'packaging_index' => '1',
            'secondary_data' => '$$322102500057270',
            'lot' => '00057270',
            'expiry_date' => '2022-10-25',
            'check_character' => 'C',
            'link_character' => '0',
        ],
        // 5
        [
            'barcode' => '+D01060701574N1/$$323103120120005967',
            'lic' => 'D010',
            'product_code' => '60701574N',
            'packaging_index' => '1',
            'secondary_data' => '$$32310312012000596',
            'lot' => '2012000596',
            'expiry_date' => '2023-10-31',
            'check_character' => '7',
            'link_character' => '6',
        ],
        // 6
        [
            'barcode' => '+D661H008181/+$K42888/14D202601134',
            'lic' => 'D661',
            'product_code' => 'H00818',
            'packaging_index' => '1',
            'secondary_data' => '+$K42888/14D20260113',
            'lot' => 'K42888',
            'expiry_date' => '2026-01-13',
            'check_character' => '4',
            'link_character' => '3',
        ],
        // 7
        [
            'barcode' => '+$$02231162074ZA',
            'check_character' => 'A',
            'link_character' => 'Z',
            'secondary_data' => '$$02231162074ZA',
            'expiry_date' => '2023-02-28',
            'secondary_data' => null,
            'lot' => '1162074',
        ],
        // 8
        [
            'barcode' => '+$$0624J139276%5',
            'check_character' => '5',
            'link_character' => '%',
            'secondary_data' => '$$0624J139276%5',
            'expiry_date' => '2024-06-30',
            'secondary_data' => null,
            'lot' => 'J139276',
        ],
        // 9
        [
            // primary / secondary with expiry date and lot / date of manufacture / serial with mod check
            'barcode' => '+A99912345/$$52001510X3/16D20111212/S77DEFG457',
            'barcode_asterisk' => '*+A99912345/$$52001510X3/16D20111212/S77DEFG457*',
            'barcode_invalid' => '+A99912345/$$52001510X3/16D20111212/S77DEFG45Z',
            'lic' => 'A999',
            'product_code' => '1234',
            'packaging_index' => '5',
            'check_character' => '7',
            'link_character' => '5',
            'secondary_data' => '$$52001510X3/16D20111212/S77DEFG45',

            'secondary_data_flag' => '$$5',
            'expiry_date' => '2020-01-15',
            'lot' => '10X3',
            'date_of_manufacture_data_identifier' => '16D',
            'date_of_manufacture' => '2011-12-12',
            'secondary_supplemental_data_identifier' => 'S',
            'serial_number' => '77DEFG45',
        ],
        // 10
        [
            // primary / secondary with lot only / date of manufacture / expiry date with mod check
            'barcode' => '+A99912345/$10X3/16D20111231/14D202001313',
            'barcode_asterisk' => '*+A99912345/$10X3/16D20111231/14D202001313*',
            'barcode_invalid' => '+A99912345/$10X3/16D20111231/14D20200131Z',
            'lic' => 'A999',
            'product_code' => '1234',
            'packaging_index' => '5',
            'check_character' => '3',
            'link_character' => '1',
            'secondary_data' => '$10X3/16D20111231/14D20200131',

            'secondary_data_flag' => '$',
            'lot' => '10X3',
            'date_of_manufacture_data_identifier' => '16D',
            'date_of_manufacture' => '2011-12-31',
            'secondary_supplemental_data_identifier' => '14D',
            'expiry_date' => '2020-01-31',
        ],
        // 11
        [
            // primary with 9 unit of measure / secondary with lot only / date of manufacture / expiry date / quantity identifier with mod check
            'barcode' => '+A99912349/$10X3/16D20111231/14D20200131/Q500Z',
            'barcode_asterisk' => '*+A99912349/$10X3/16D20111231/14D20200131/Q500Z*',
            'barcode_invalid' => '+A99912349/$10X3/16D20111231/14D20200131/Q500A',
            'lic' => 'A999',
            'product_code' => '1234',
            'packaging_index' => '9',
            'check_character' => 'Z',
            'link_character' => '0',
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
        // 12
        [
            // Taken from documentation as well
            'barcode' => '+J123AQ3451/$$3231231BC34567/S4012R',
            'barcode_asterisk' => '*+J123AQ3451/$$3231231BC34567/S4012R*',
            'barcode_invalid' => '+J123AQ3451/$$3231231BC34567/S4012Z',
            'lic' => 'J123',
            'product_code' => 'AQ345',
            'packaging_index' => '1',
            'check_character' => 'R',
            'link_character' => '2',
            'secondary_data' =>  '$$3231231BC34567/S4012',

            'secondary_data_flag' => '$$3',
            'expiry_date' => '2023-12-31',
            'lot' => 'BC34567',
            'secondary_supplemental_data_identifier' => 'S',
            'serial_number' => '4012',
        ],
        [
            'barcode' => '+EDMG2203430/$$0723244375H',
            'lic' => 'EDMG',
            'product_code' => '220343',
            'packaging_index' => '0',
            'check_character' => 'H',
            'link_character' => '5',
            'secondary_data' => '$$0723244375',
            'lot' => '244375',
            'expiry_date' => '2023-07-31',
        ],
        // barcode that was requested to test
        [
            'barcode' => '+J003A1411P00003001/$$32601270000276973O',
            'lic' => 'J003',
            'product_code' => 'A1411P0000300',
            'packaging_index' => '1',
            'check_character' => 'O',
            'link_character' => '3',
            'secondary_data' => '$$32601270000276973',
            'lot' => '0000276973',
            'expiry_date' => '2026-01-27'
        ],
        // legacy quantity barcode
        [
            'barcode' => '+EOROCDF366A01501/$$900001122321007217',
            'lic' => 'EORO',
            'product_code' => 'CDF366A0150',
            'packaging_index' => '1',
            'check_character' => '7',
            'link_character' => '1',
            'secondary_data' => '$$90000112232100721',
            'lot' => '2100721',
            'expiry_date' => '2023-12-31'
        ],
        [ 
            'barcode' => '+E235817C19/$$9000067101822%',
            'lic' => 'E235',
            'product_code' => '817C1',
            'packaging_index' => '9',
            'check_character' => '%',
            'link_character' => '2',
            'secondary_data' => '$$9000067101822',
            'lot' => '101822',
            'expiry_date' => null,
        ],
        [ 
            'barcode' => '+E1652101109/$$9000063260831L01656I',
            'lic' => 'E165',
            'product_code' => '210110',
            'packaging_index' => '9',
            'check_character' => 'I',
            'link_character' => '6',
            'secondary_data' => '$$9000063260831L01656',
            'lot' => 'L01656',
            'expiry_date' => '2026-08-31',
        ],
        [
            'barcode' => '+E2513079NL1/$$90001020331222021050425-1P',
            'lic' => 'E251',
            'product_code' => '3079NL',
            'packaging_index' => '1',
            'check_character' => 'P',
            'link_character' => '1',
            'secondary_data' => '$$90001020331222021050425-1',
            'lot' => '2021050425-1',
            'expiry_date' => '2022-03-31',
        ],
        [
            'barcode' => '+E25131041/$$9000012103124181561K',
            'lic' => 'E251',
            'product_code' => '3104',
            'packaging_index' => '1',
            'check_character' => 'K',
            'link_character' => '1',
            'secondary_data' => '$$9000012103124181561',
            'lot' => '181561',
            'expiry_date' => '2024-10-31',
        ]
    ];

        
    /** @test
     * 
     */
    public function it_extracts_lic_test(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['lic'])) {
                $this->assertEquals($hibc['lic'], $decoder->lic);
            } else {
                $this->assertEquals(null, $decoder->lic);
            }
        }
    }

    /** @test
     * 
     */
    public function it_extracts_product_code_test(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['product_code'])) {
                $this->assertEquals($hibc['product_code'], $decoder->product_code);
            } else {
                $this->assertEquals(null, $decoder->product_code);
            }
        }
    }

    /** @test
     * Disabled because it is not needed at this time
     */
    public function it_extracts_packaging_index_test(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['packaging_index'])) {
                $this->assertEquals($hibc['packaging_index'], $decoder->packaging_index);
            } else {
                $this->assertEquals(null, $decoder->packaging_index);
            }
        }
    }

    /** @test
     * 
     */
    public function it_extracts_check_character_test(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['check_character'])) {
                $this->assertEquals($hibc['check_character'], $decoder->check_character);
            } else {
                $this->assertEquals(null, $decoder->check_character);
            }
        }
    }

    /** @test
     * 
     */
    public function it_extracts_secondary_data_test(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['secondary_data'])) {
                $this->assertEquals($hibc['secondary_data'], $decoder->secondary_data);
            } else {
                $this->assertEquals(null, $decoder->secondary_data);
            }
        }
    }

    /** test
     * 
     */
    public function is_valid_test(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            $this->assertEquals($decoder->is_valid, true);
        }
    }

    /** test
     * Disabled because it is not needed at this time
     */
    public function is_invalid_test(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['barcode_invalid'])) {
                $this->assertEquals($hibc['barcode_invalid'], $decoder->barcode_invalid);
            } else {
                $this->assertEquals(null, $decoder->barcode_invalid);
            }
        }
    }

    /** @test
     * 
     */
    public function it_extracts_lot(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['lot'])) {
                $this->assertEquals($hibc['lot'], $decoder->lot);
            } else {
                $this->assertEquals(null, $decoder->lot);
            }
        }
    }

    /** @test
     * 
     */
    public function it_extracts_expiry_date(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['expiry_date'])) {
                $this->assertEquals($hibc['expiry_date'], $decoder->expiry_date);
            } else {
                $this->assertEquals(null, $decoder->expiry_date);
            }
        }
    }

    /** @test
     * 
     */
    public function it_extracts_date_of_manufacture(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['date_of_manufacture'])) {
                $this->assertEquals($hibc['date_of_manufacture'], $decoder->date_of_manufacture);
            } else {
                $this->assertEquals(null, $decoder->date_of_manufacture);
            }
        }
    }

    /** @test
     * 
     */
    public function it_extracts_serial_number(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['serial_number'])) {
                $this->assertEquals($hibc['serial_number'], $decoder->serial_number);
            } else {
                $this->assertEquals(null, $decoder->serial_number);
            }
        }
    }

    /** test
     * Disabled because it is not needed at this time
     */
    public function it_extracts_quantity_identifier(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['quantity_identifier'])) {
                $this->assertEquals($hibc['quantity_identifier'], $decoder->quantity_identifier);
            } else {
                $this->assertEquals(null, $decoder->quantity_identifier);
            }
        }
    }

    /** test
     * Disabled because it is not needed at this time
     */
    public function it_extracts_quantity(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['quantity'])) {
                $this->assertEquals($hibc['quantity'], $decoder->quantity);
            } else {
                $this->assertEquals(null, $decoder->quantity);
            }
        }
    }

    /** test
     * 
     */
    public function it_extracts_link_character(): void
    {
        foreach ($this->hibcs as $hibc) {
            $decoder = new UdiDecoder($hibc['barcode']);
            if (isset($hibc['link_character'])) {
                $this->assertEquals($hibc['link_character'], $decoder->link_character);
            } else {
                $this->assertEquals(null, $decoder->link_character);
            }
        }
    }
}
