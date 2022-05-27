<?php

// HIBC barcodes from the documentation themselves
$hibcs = [
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
        'serial_number' => '77DEFG457',
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
    ],
    [
        'barcode' => '+J123AQ3451/$$3231231BC34567/$4012R',
        'barcode_asterisk' => '*+J123AQ3451/$$3231231BC34567/$4012R*',
        'barcode_invalid' => '+J123AQ3451/$$3231231BC34567/$4012Z',
        'lic' => 'J123',
        'product_code' => 'AQ345',
        'packaging_index' => '1',
        'check_character' => 'R',
        'secondary_data' =>  '$$3231231BC34567/$4012',

        'secondary_data_flag' => '$$3',
        'lot' => 'BC34567',
        'expiry_date' => '2023-12-31',
        'secondary_supplemental_data_identifier' => '$4',
        'serial_number' => '012R'
    ],
    [
        'barcode' => '+D65825032111',
        'barcode_asterisk' => '*+D65825032111*',
        'barcode_invalid' => '+D6582503211Z',
        'lic' => 'D658',
        'product_code' => '250321',
        'packaging_index' => '1',
        'check_character' => '1',
    ],
    [
        'barcode' => '+EKUR003152EU1G',
        'barcode_asterisk' => '*+EKUR003152EU1G*',
        'barcode_invalid' => '+EKUR003152EU1Z',
        'lic' => 'EKUR',
        'product_code' => '003152EU',
        'packaging_index' => '1',
        'check_character' => 'G',
    ]
];

return $hibcs;