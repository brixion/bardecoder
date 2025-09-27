<?php

declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use Brixion\Bardecoder\UdiDecoder;
use PHPUnit\Framework\TestCase;

final class HbicLicPrimaryDataStructureTest extends TestCase
{
    // HBIC barcode without alphabetical characters
    // this barcode with its check character is known-good
    // I am not using a DataProvider here to allow usage of array keys
    private array $hbics = [
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
            'product_code' => '3152EU',
            'packaging_index' => '1',
            'check_character' => 'G',
        ],
    ];

    /**
     * UdiDecoder can extract lic from hibc.
     */
    public function testItExtractsLicFromHibc(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode']);
            $this->assertEquals(
                $hbic['lic'],
                $decoder->lic
            );
        }
    }

    /**
     * Udidecoder can handle barcodes with leading and trailing *.
     */
    public function testItExtractsLicWithLeadingAndTrailingAsterisksBarcode(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode_asterisk']);
            $this->assertEquals(
                $hbic['lic'],
                $decoder->lic
            );
        }
    }

    /**
     * UdiDecoder can extract product code from hibc.
     *
     * @throws \Exception
     */
    public function testItExtractsProductCodeFromHibc(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode']);
            $this->assertEquals(
                $hbic['product_code'],
                $decoder->product_code
            );
        }
    }

    /**
     * UdiDecoder can extract check character from hibc.
     *
     * @throws \Exception
     */
    public function testItExtractsCheckCharacterFromHibc(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode']);
            $this->assertEquals(
                $hbic['check_character'],
                $decoder->check_character
            );
        }
    }

    /**
     * UdiDecoder can check if hibc is valid.
     */
    public function testItChecksIfHibcIsValid(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode']);
            $this->assertTrue(
                $decoder->is_valid
            );
        }
    }

    /**
     * UdiDecoder can check if hibc is invalid.
     */
    public function testItChecksIfHibcIsInvalid(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode_invalid']);
            $this->assertFalse(
                $decoder->is_valid
            );
        }
    }
}
