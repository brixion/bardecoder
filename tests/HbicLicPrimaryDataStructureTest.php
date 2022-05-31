<?php

declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;
use phpDocumentor\Reflection\Types\Void_;

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
            'product_code' => '003152EU',
            'packaging_index' => '1',
            'check_character' => 'G',
        ]
    ];

    /** @test
     * UdiDecoder can extract lic from hibc
     */
    public function it_can_extract_lic_from_hibc(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode']);
            $this->assertEquals(
                $hbic['lic'],
                $decoder->lic
            );
        }
    }

    /** @test 
     * Udidecoder can handle barcodes with leading and trailing *
     */
    public function it_can_extract_lic_with_leading_and_trailing_asterisks_barcode(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode_asterisk']);
            $this->assertEquals(
                $hbic['lic'],
                $decoder->lic
            );
        }
    }

    /** @test
     * UdiDecoder can extract product code from hibc
     * @throws \Exception
     */
    public function it_can_extract_product_code_from_hibc(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode']);
            $this->assertEquals(
                $hbic['product_code'],
                $decoder->product_code
            );
        }
    }

    /** @test
     * UdiDecoder can extract check character from hibc
     * @throws \Exception
     */
    public function it_can_extract_check_character_from_hibc(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode']);
            $this->assertEquals(
                $hbic['check_character'],
                $decoder->check_character
            );
        }
    }

    /** @test
     * UdiDecoder can check if hibc is valid
     */
    public function it_can_check_if_hibc_is_valid(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode']);
            $this->assertEquals(
                true,
                $decoder->is_valid
            );
        }
    }

    /** @test
     * UdiDecoder can check if hibc is invalid
     */
    public function it_can_check_if_hibc_is_invalid(): void
    {
        foreach ($this->hbics as $hbic) {
            $decoder = new UdiDecoder($hbic['barcode_invalid']);
            $this->assertEquals(
                false,
                $decoder->is_valid
            );
        }
    }
}
