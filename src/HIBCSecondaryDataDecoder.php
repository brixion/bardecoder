<?php

namespace Brixion\Bardecoder;

/**
 * Class UdiDecoder
 * @package Brixion\Bardecoder
 * 
 * probably wrong also but i tried
 * +primary_data/$lot_number/expiry_date/manufacture_date/serial_number
 * +primary_data/$$5expiry_date/expiry_date/manufacture_date/serial_number
 * +primary_data/$[a-zA-Z0-9]no_date_fields/serial_number
 * +primary_data/$$[0-9]date_field_specified_format/lot_number_no_serial
 * +primary_data/$$+[0-9]date_field_specified_format/serial_number_no_lot
 * +primary_data/$[a-zA-Z0-9]no_date_fields/serial_number
 * +primary_data/$[a-zA-Z0-9]no_date_fields/serial_number
 * +primary_data/$[a-zA-Z0-9]no_date_fields/serial_number
 * 
 * +primary_data/[0-9]julian_date/expiry_date/manufacture_date/serial_number <- legacy, for backwards compatibility
 * +primary_data/$+[a-zA-Z0-9]serial_number_only <- legacy, for backwards compatibility
 * 
 * 
 * probably wrong
 * +primary_data/[0-9]julian_date
 * +primary_data/$[a-zA-Z0-9](lot||serial)
 * +primary_data/$+[a-zA-Z0-9]serial <- backwads compatible
 * +primary_data/$$[0-9]expiry_date/lot/
 */

class HIBCSecondaryDataDecoder {

    function __construct(UdiDecoder &$decoder)
    {   
        // split $this->secondary data into parts using /
        $parts = explode('/', $decoder->secondary_data);
        

    }
}