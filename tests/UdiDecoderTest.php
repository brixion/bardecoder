<?phpdeclare(strict_types = 1)
use phpDocumentor\Reflection\Types\Void_;
;
use PHPUnit\Framework\TestCase;

final class UdiDecoder extends TestCase
{
    // Test GS1 barcode
    public function testGS1Decoder(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertInstanceOf(UdiDecoder::class, $barcode);
    }

    public function testGS1Gtin(): void 
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertEquals('950123456789034226163', $barcode->getGtin());
    }

    public function testGS1OriginCountry(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertEquals('616', $barcode->getOriginCountry());
    }

    public function testGS1NetWeight(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('01950123456789034226163103000123');
        $this->assertEquals('000123', $barcode->getNetWeight());
    }
    

    // Test HIBC barcode
    public function testHBICDecoder(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertInstanceOf(UdiDecoder::class, $barcode);
    }
    public function testHBICLic(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals('J123', $barcode->getLic());
    }
    public function testHBICProductCode(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals('AQ345', $barcode->getProductCode());
    }
    public function testHBICPackagingIndex(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals('1', $barcode->getPackagingIndex());
    }
    public function testHBICExpiringDate(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals('2023-12-31', $barcode->getExpiringDate());
    }
    public function testHBICLot(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals('BC34567', $barcode->getLot());
    }
    public function testHBICSerial(): void
    {
        $decoder = new UdiDecoder();
        $barcode = $decoder->decode('+J123AQ3451/$$3231231BC34567$4012R');
        $this->assertEquals('012R', $barcode->getSerial());
    }
}