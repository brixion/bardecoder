<?php

namespace Brixion\Bardecoder;

use Exception;
use PDO;

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

class HIBCSecondaryDataDecoder
{

    function __construct(UdiDecoder &$decoder)
    {
        $this->decoder = $decoder;

        if (empty($this->decoder->secondary_parts))
            throw new Exception("No secondary data found");

        foreach ($this->decoder->secondary_parts as $part) {
            // part is too short or empty to be valid
            if (!is_string($part) || strlen($part) < 2) {
                continue;
            }

            // $$[0-7] -> date field
            if (preg_match('/^\$\$[0-7]/', $part)) {
                $this->handleDateField($part);
            } elseif ($part[0] == "$") {
                $this->decoder->lot = substr($part, 1);
            } elseif (substr($part, 0, 3) == "16D") {
                $this->decoder->date_of_manufacture = $this->getDateFromYYYYMMDD(substr($part, 3));
            } elseif (substr($part, 0, 3) == "14D") {
                $this->decoder->expiry_date = $this->getDateFromYYYYMMDD(substr($part, 3));
            } elseif (substr($part, 0, 1) == "S") {
                $this->decoder->serial_number = substr($part, 1);
            }
        }
    }

    public function getDateFromYYYYMMDD($date)
    {
        return substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
    }

    public function handleDateField($part)
    {
        $this->decoder->serial_number = null;
        switch ($part[2]) {
            case '0':
            case '1':
                // first day of month, date in MMYY format
                $date = substr($part, 3, 4);
                $year = "20" . substr($date, 5, 2);
                $month = substr($date, 3, 2);

                $this->decoder->expiry_date = "$year-$month-01";
                $this->decoder->lot = substr($part, 7);
                break;
            case '2':
                // MMDDYY format
                $date = substr($part, 3, 6);
                $year = "20" . substr($date, 4, 2);
                $month = substr($date, 0, 2);
                $day = substr($date, 2, 2);

                $this->decoder->expiry_date = "$year-$month-$day";
                $this->decoder->lot = substr($part, 9);
                break;
            case '3':
                // YYMMDD format
                $year = "20" . substr($part, 3, 2);
                $month = substr($part, 5, 2);
                $day = substr($part, 7, 2);

                $this->decoder->expiry_date = "$year-$month-$day";
                $this->decoder->lot = substr($part, 9);
                break;
            case '4':
                // YYMMDDHH format
                $year = "20" . substr($part, 3, 2);
                $month = substr($part, 7, 2);
                $day = substr($part, 5, 2);
                $hour = substr($part, 9, 2);
                $lot = substr($part, 11);

                $this->decoder->expiry_date = "$year-$month-$day $hour";
                $this->decoder->lot = $lot;
                break;
            case '5':
                // YYJJJ julian date format
                $this->decoder->expiry_date = $this->julianToDate(substr($part, 3, 5));
                $this->decoder->lot = substr($part, 8);
                break;
            case '6':
                // YYJJJHH julian date format
                $this->decoder->expiry_date = jdtojulian(substr($part, 3, 5)) . " " . substr($part, 9, 2);
                $this->decoder->lot = substr($part, 10);
                break;
            case '7':
                // expiry_date is null, lot follows
                $this->decoder->expiry_date = null;
                $this->decoder->lot = substr($part, 3);
                break;
            default:
                throw new Exception("Unknown date format: $part");
        }
    }

    public function julianToDate($part)
    {
        if (strlen($part) != 5)
            throw new Exception("First part is a julian date, but is not 5 characters long");

        // get the first 2 characters from $part for the year, rest is days
        $year = "20" . substr($part, 0, 2);
        $julian_date = substr($part, 2) - 1;

        // use the year and the number of days to calculate date
        return date("Y-m-d", strtotime("$year-01-01 +$julian_date days"));
    }
}
