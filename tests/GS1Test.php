<?php

declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;

final class UdiDecoderTest extends TestCase
{
    // Test GS1 barcode
    /** test */
    public function it_can_instantiate_gs1(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertInstanceOf(
            UdiDecoder::class,
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
            UdiDecoder::class,
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
