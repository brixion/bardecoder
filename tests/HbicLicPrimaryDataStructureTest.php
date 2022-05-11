<?php declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;
use phpDocumentor\Reflection\Types\Void_;

final class HbicLicPrimaryDataStructureTest extends TestCase
{
    // HBIC barcode without alphabetical characters
    private string $hibc = '+D65825032111';
    private string $lic = 'D658';
    private string $product_code = '250321';
    private string $packaging_index = '1';
    private string $check_character = '1';

    //HBIC barcode with alphabetical characters
    private string $alph_hbic = '+EKUR003152EU1G';
    private string $alph_lic = 'EKUR';
    private string $alph_product_code = '003152EU';
    private string $alph_packaging_index = '1';
    private string $alph_check_character = 'G';


    /** @test
     * UdiDecoder can extract lic from hibc
     */
    public function it_can_extract_lic_from_hibc(): void
    {
        $decoder = new UdiDecoder('+D65825032111');
        $this->assertEquals(
            'D658',
            $decoder->lic
        );
    }

    /** @test
     * UdiDecoder can extract lic from alphabetical hibc
     */
    public function it_can_extract_lic_from_alphabetical_hibc(): void
    {
        $decoder = new UdiDecoder('+EKUR003152EU1G');
        $this->assertEquals(
            'EKUR',
            $decoder->lic
        );
    }

    /** @test 
     * Udidecoder can handle barcodes with leading and trailing *
    */
    public function it_can_extract_lic_with_leading_and_trailing_asterisks_barcode(): void
    {
        $decoder = new UdiDecoder('*+D65825032111*');
        $this->assertEquals(
            'D658',
            $decoder->lic
        );
    }

    /** @test 
     * Udidecoder can handle alphabetical barcodes with leading and trailing *
     * @throws \Exception
    */
    public function it_can_extract_lic_with_leading_and_trailing_asterisks_in_alphabetical_barcode(): void
    {
        $decoder = new UdiDecoder('*+EKUR003152EU1G*');
        $this->assertEquals(
            'EKUR',
            $decoder->lic
        );
    }

    /** @test
     * UdiDecoder can extract product code from hibc
     * @throws \Exception
     */
    public function it_can_extract_product_code_from_hibc(): void
    {
        $decoder = new UdiDecoder('+D65825032111');
        $this->assertEquals(
            '250321',
            $decoder->product_code
        );
    }
    /** @test
     * UdiDecoder can extract product code from alphabetical hibc
     * @throws Exception
     */
    public function it_can_extract_product_code_from_alphabetical_hibc(): void
    {
        $decoder = new UdiDecoder('+EKUR003152EU1G');
        $this->assertEquals(
            '003152EU',
            $decoder->product_code
        );
    }

    /** @test
     * UdiDecoder can extract check character from hibc
     * @throws \Exception
     */
    public function it_can_extract_check_character_from_hibc(): void
    {
        $decoder = new UdiDecoder('+D65825032111');
        $this->assertEquals(
            '1',
            $decoder->check_character
        );
    }

    /** @test
     * UdiDecoder can extract check character from alphabetical hibc
     * @throws \Exception
     */
    public function it_can_extract_check_character_from_alphabetical_hibc(): void
    {
        $decoder = new UdiDecoder('+EKUR003152EU1G');
        $this->assertEquals(
            'G',
            $decoder->check_character
        );
    }

    /** @test
     * UdiDecoder can check if hibc is valid
     */
    public function it_can_check_if_hibc_is_valid(): void
    {
        $decoder = new UdiDecoder('+D65825032111');
        $this->assertTrue(
            $decoder->is_valid
        );
    }
    /** @test
     * UdiDecoder can check if hibc is invalid
     */
    public function it_can_check_if_hibc_is_invalid(): void
    {
        $decoder = new UdiDecoder('+EKUR003152EU10');
        $this->assertFalse(
            $decoder->is_valid
        );
    }
}