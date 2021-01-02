<?php

namespace kalanis\kw_forms\Interfaces;


use ArrayAccess;


interface ICsrf
{
    /**
     * @param ArrayAccess $cookie
     * @param int         $expire
     */
    public function init(ArrayAccess &$cookie, int $expire = 3600);

    /**
     * @param string $codeName
     */
    public function removeToken(string $codeName): void;

    /**
     * @param string $codeName
     * @return string
     */
    public function getToken(string $codeName): string;

    /**
     * @param string $token
     * @param string $codeName
     * @return bool
     */
    public function checkToken(string $token, string $codeName): bool;

    /**
     * @return int
     */
    public function getExpire(): int;
}
