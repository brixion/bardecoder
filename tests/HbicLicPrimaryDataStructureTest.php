<?php declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;

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
        $decoder = new UdiDecoder();
        $decoder->decode('+D65825032111');
        $this->assertEquals(
            'D658',
            $decoder->getLic()
        );
    }

    /** @test
     * UdiDecoder can extract lic from alphabetical hibc
     */
    public function it_can_extract_lic_from_alphabetical_hibc(): void
    {
        $decoder = new UdiDecoder();
        $decoder->decode('+EKUR003152EU1G');
        $this->assertEquals(
            'EKUR',
            $decoder->getLic()
        );
    }

    /** @test 
     * Udidecoder can handle barcodes with leading and trailing *
    */
    public function it_can_handle_leading_and_trailing_asterisks(): void
    {
        $decoder = new UdiDecoder();
        $decoder->decode('*+D65825032111*');
        $this->assertEquals(
            'D658',
            $decoder->getLic()
        );
    }

    /** @test 
     * Udidecoder can handle alphabetical barcodes with leading and trailing *
    */
    public function it_can_handle_alphabetical_leading_and_trailing_asterisks(): void
    {
        $decoder = new UdiDecoder();
        $decoder->decode('*+EKUR003152EU1G*');
        $this->assertEquals(
            'EKUR',
            $decoder->getLic()
        );
    }

    /** @test
     * UdiDecoder can extract product code from hibc
     */
    public function it_can_extract_product_code_from_hibc(): void
    {
        $decoder = new UdiDecoder();
        $decoder->decode('+D65825032111');
        $this->assertEquals(
            '250321',
            $decoder->getProductCode()
        );
    }
    /** @test
     * UdiDecoder can extract product code from alphabetical hibc
     */
    public function it_can_extract_product_code_from_alphabetical_hibc(): void
    {
        $decoder = new UdiDecoder();
        $decoder->decode('+EKUR003152EU1G');
        $this->assertEquals(
            '003152EU',
            $decoder->getProductCode()
        );
    }
}