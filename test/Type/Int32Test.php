<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int32
 */
class Int32Test extends AbstractTestCase
{
    public Int32 $int32;

    public function setUp(): void
    {
        $this->int32 = new Int32();
    }

    /** @dataProvider largeReaders */
    public function testUnsignedReaderWithBigEndian(BinaryReader $brBig): void
    {
        $this->assertEquals(3, $this->int32->read($brBig));
        $this->assertEquals(157556, $this->int32->read($brBig));
        $this->assertEquals(1702065185, $this->int32->read($brBig));
        $this->assertEquals(4294967295, $this->int32->read($brBig));
    }

    /** @dataProvider largeReaders */
    public function testSignedReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(12);
        $this->assertEquals(-1, $this->int32->readSigned($brBig));
    }

    /** @dataProvider littleReaders */
    public function testReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $this->assertEquals(3, $this->int32->read($brLittle));
        $this->assertEquals(1952907266, $this->int32->read($brLittle));
        $this->assertEquals(561279845, $this->int32->read($brLittle));
        $this->assertEquals(4294967295, $this->int32->read($brLittle));
    }

    /** @dataProvider littleReaders */
    public function testSignedReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(12);
        $this->assertEquals(-1, $this->int32->readSigned($brLittle));
    }

    /** @dataProvider largeReaders */
    public function testBitReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(6);
        $brBig->readBits(4);
        $this->assertEquals(122050356, $this->int32->read($brBig));
    }

    /** @dataProvider littleReaders */
    public function testBitReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(6);
        $brLittle->readBits(4);
        $this->assertEquals(122107476, $this->int32->read($brLittle));
    }

    /** @dataProvider binaryReaders */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brBig->readBits(360);
        $this->int32->read($brBig);
    }

    /** @dataProvider binaryReaders */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brLittle->readBits(360);
        $this->int32->read($brLittle);
    }

    /** @dataProvider littleReaders */
    public function testAlternateMachineByteOrderSigned(BinaryReader $brLittle): void
    {
        $brLittle->setMachineByteOrder(Endian::BIG);
        $brLittle->setEndian(Endian::LITTLE);
        $this->assertEquals(3, $this->int32->readSigned($brLittle));
    }

    public function testEndian(): void
    {
        $this->int32->endianBig = 'X';
        $this->assertEquals('X', $this->int32->endianBig);

        $this->int32->endianLittle = 'Y';
        $this->assertEquals('Y', $this->int32->endianLittle);
    }
}
