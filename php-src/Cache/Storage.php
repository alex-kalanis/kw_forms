<?php

namespace kalanis\kw_forms\Cache;


use kalanis\kw_storage\Interfaces\IStorage;


class Storage
{
    /** @var IStorage */
    protected $storage = null;
    /** @var Key */
    protected $key = null;

    public function __construct(IStorage $storage = null)
    {
        $this->storage = $storage;
        $this->key = new Key();
    }

    public function setAlias(string $alias=''): void
    {
        $this->key->setAlias($alias);
    }

    /**
     * Check if data are stored
     * @return bool
     */
    public function isStored(): bool
    {
        if (!$this->storage) {
            return false;
        }
        return $this->storage->exists($this->key->fromSharedKey(''));
    }

    /**
     * Save form data into storage
     * @param array $values
     * @param int|null $timeout
     */
    public function store(array $values, ?int $timeout = null): void
    {
        if (!$this->storage) {
            return;
        }
        $this->storage->save($this->key->fromSharedKey(''), serialize($values), $timeout);
    }

    /**
     * Read data from storage
     * @return array
     */
    public function load(): ?array
    {
        if (!$this->storage) {
            return [];
        }
        $values = $this->storage->load($this->key->fromSharedKey(''));
        $data = @unserialize($values);
        if (false === $data) {
            return [];
        }
        return $data;
    }

    /**
     * Remove data from storage
     */
    public function delete(): void
    {
        if (!$this->storage) {
            return;
        }
        $this->storage->remove($this->key->fromSharedKey(''));
    }
}
