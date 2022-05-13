<?php declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;

final class UdiDecoderTest extends TestCase
{
    private string $udi = '01950123456789034226163103000123';
    private string $udi_gtin = '950123456789034226163';
    private string $udi_country = '616';
    private string $udi_net_weight = '000123';

    private string $hibc = '+J123AQ3451/$$3231231BC34567$4012R';
    private string $hbic_lic = 'J123';
    private string $hbic_product_code = 'AQ3451';
    private string $hbic_packaging_index = '1';
    private string $hbic_expiring_date = '2023-12-31';
    private string $hbic_lot = 'BC34567';
    private string $hbic_serial = '012R';

    /** @test */
    public function silence_warning(): void
    {
        $this->assertTrue(true);
    }
    
    // Test GS1 barcode
    /** test */
    public function it_can_instantiate_gs1(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertInstanceOf(
            UdiDecoder::class ,
            $barcode
        );
    }
    /** test */
    public function it_returns_gtin_from_gs1(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertEquals(
            $udi_gtin,
            $barcode->getGtin()
        );
    }
    /** test */
    public function it_returns_country_from_gs1(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertEquals(
            $udi_country,
            $barcode->getOriginCountry()
        );
    }
    /** test */
    public function it_returns_netweight_from_gs1(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertEquals(
            $udi_net_weight,
            $barcode->getNetWeight()
        );
    }


    // Test HIBC barcode
    /** test */
    public function it_can_instantiate_hbic(): void
    {
        $decoder = new UdiDecoder();
        $this->assertInstanceOf(
            UdiDecoder::class ,
            $decoder
        );
    }
    /** test */
    public function it_returns_lic_from_hbic(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals(
            $hbic_lic,
            $barcode->getLic()
        );
    }
    /** test */
    public function it_returns_productcode_from_hbic(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals(
            $hbic_product_code,
            $barcode->getProductCode()
        );
    }
    /** test */
    public function it_returns_packagingindex_from_hbic(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals(
            $hbic_packaging_index,
            $barcode->getPackagingIndex()
        );
    }
    /** test */
    public function it_returns_expiringdate_from_hbic(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals(
            $hbic_expiring_date,
            $barcode->getExpiringDate()
        );
    }
    /** test */
    public function it_returns_lot_from_hbic(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals(
            $hbic_lot,
            $barcode->getLot()
        );
    }
    /** test */
    public function it_returns_serial_from_hbic(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals(
            $hbic_serial,
            $barcode->getSerial()
        );
    }
}