<?php

namespace kalanis\kw_forms\Cache;


use kalanis\kw_storage\Interfaces\IStorage;


trait TStorage
{
    /** @var Storage */
    protected $storage = null;

    public function setStorage(?IStorage $storage = null): self
    {
        $this->storage = new Storage($storage);
        $this->storage->setAlias(strval($this->getAlias()));
        return $this;
    }

    /**
     * Check if data is set inside storage
     * @return bool
     */
    public function isStored(): bool
    {
        return $this->storage->isStored();
    }

    /**
     * Delete form data in storage
     */
    public function deleteStored(): void
    {
        $this->storage->delete();
    }

    abstract public function getAlias(): ?string;
}
