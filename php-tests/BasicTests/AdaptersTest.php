<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_forms\Adapters;
use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_input\Interfaces\IEntry;


class AdaptersTest extends CommonTestClass
{
    /**
     * @param Adapters\AAdapter $adapter
     * @param string $inputType
     * @param bool $canCount
     * @param bool $canThrough
     * @param bool $canStep
     * @param bool $canSet
     * @throws FormsException
     * @dataProvider adapterProvider
     */
    public function testAdapter(Adapters\AAdapter $adapter, string $inputType, bool $canCount, bool $canThrough, bool $canStep, bool $canSet): void
    {
        $adapter->loadEntries($inputType);
        $this->assertNotEmpty($adapter->getSource());
        if ($canCount) {
            $this->assertEquals(4, $adapter->count());
            $this->assertEquals('aff', $adapter->offsetGet('foo'));
        }
        if ($canThrough) {
            foreach ($adapter as $key => $item) {
                $this->assertNotEmpty($key);
                $this->assertNotEmpty($item);
            }
        }
        if ($canStep) {
            $adapter->rewind();
            $adapter->next();
            $this->assertNotEmpty($adapter->getKey());
            $this->assertNotEmpty($adapter->getValue());
        }

        if ($canSet) {
            $this->assertFalse($adapter->offsetExists('fee'));
            $adapter->offsetSet('fee','nnn');
            $this->assertTrue($adapter->offsetExists('fee'));
            $adapter->offsetUnset('fee');
            $this->assertFalse($adapter->offsetExists('fee'));
        }
    }

    public function adapterProvider(): array
    {
        $_GET = [
            'foo' => 'aff',
            'bar' => 'poa',
            'baz' => 'cdd',
            'sgg' => 'arr',
        ];
        return [
            [new \Adapter(), '', true, true, true, true ],
            [new Adapters\VarsAdapter(), IEntry::SOURCE_GET, true, false, true, true ],
            [new Adapters\VarsAdapter(), IEntry::SOURCE_POST, false, false, false, true ],
            [new Adapters\SessionAdapter(), '', false, false, false, true ],
            [new \Files(), '', false, false, false, false ],
        ];
    }

    public function testAdapterDie()
    {
        $adapter = new Adapters\VarsAdapter();
        $this->expectException(FormsException::class);
        $adapter->loadEntries('unknown_one');
    }

    public function testAdapterFile()
    {
        $adapter = new \Files();
        $adapter->loadEntries('');
        $adapter->rewind();
        $adapter->next();
        $this->assertNotEmpty($adapter->getKey());
        $this->assertNotEmpty($adapter->getValue());
        $this->assertNotEmpty($adapter->current()->getMimeType());
        $this->assertNotEmpty($adapter->current()->getTempName());
        $this->assertNotEmpty($adapter->current()->getError());
        $this->assertNotEmpty($adapter->current()->getSize());
        $this->assertEquals(IEntry::SOURCE_FILES, $adapter->current()->getSource());
        $adapter->next();
        $adapter->next();
        $this->expectException(FormsException::class);
        $adapter->getValue(); // not exists
    }
}
