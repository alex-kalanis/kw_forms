<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_forms\Cache\Formats\Json;
use kalanis\kw_forms\Cache\Storage;
use kalanis\kw_forms\Cache\TStorage;
use kalanis\kw_storage\Storage\Target\Memory;
use kalanis\kw_storage\StorageException;


class CacheTest extends CommonTestClass
{
    /**
     * @throws StorageException
     */
    public function testStorageTrait(): void
    {
        $storagePart = new StorageTrait();
        $storagePart->deleteStored();
        $this->assertFalse($storagePart->isStored());
        $storagePart->setStorage(new Memory());
        $storagePart->deleteStored();
        $this->assertFalse($storagePart->isStored());
    }

    /**
     * @throws StorageException
     */
    public function testStorage(): void
    {
        $storage = new Storage(new Memory(), new Json());
        $storage->setAlias('test');
        $this->assertTrue($storage->store($this->contentStructure()));
        $this->assertTrue($storage->isStored());
        $data = $storage->load();
        $this->assertNotEmpty($data);
        $this->assertTrue($storage->delete());
        $this->assertFalse($storage->isStored());
    }

    /**
     * @throws StorageException
     */
    public function testStorageNothing(): void
    {
        $storage = new Storage();
        $storage->setAlias('test');
        $this->assertFalse($storage->store($this->contentStructure()));
        $this->assertFalse($storage->isStored());
        $data = $storage->load();
        $this->assertEmpty($data);
        $this->assertFalse($storage->delete());
        $this->assertFalse($storage->isStored());
    }

    /**
     * @throws StorageException
     */
    public function testStorageFailedData(): void
    {
        $mock = new Memory();
        $storage = new Storage($mock);
        $storage->setAlias('test');
        $this->assertTrue($storage->store($this->contentStructure()));
        $this->assertTrue($storage->isStored());
        $mock->save('FormStorage_test_', '----'); // boo!
        $data = $storage->load();
        $this->assertEmpty($data);
        $this->assertTrue($storage->delete());
        $this->assertFalse($storage->isStored());
    }

    protected function contentStructure(): array
    {
        return ['6g8a7' => 'dfh4dg364sd6g', 'hzsdfgh' => 35.4534, 'sfkg' => false, 'hdhg' => 'sdfh5433'];
    }
}


class StorageTrait
{
    use TStorage;

    public function getAlias(): ?string
    {
        return 'OurAlias';
    }
}
