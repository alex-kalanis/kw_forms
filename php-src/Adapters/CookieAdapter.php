<?php

namespace kalanis\kw_forms\Adapters;


/**
 * Class CookieAdapter
 * @package kalanis\kw_forms\Adapters
 * Accessing _COOKIES via ArrayAccess
 * Also set them into the headers
 * @codeCoverageIgnore because accessing outer resources
 * "Cannot modify header information - headers already sent"
 */
class CookieAdapter extends VarsAdapter
{
    protected static $domain = '';
    protected static $path = '';
    protected static $expire = null;
    protected static $secure = false;
    protected static $httpOnly = false;
    protected static $sameSite = false;

    public static function init(string $domain, string $path, ?int $expire = null, bool $secure = false, bool $httpOnly = false, bool $sameSite = false): void
    {
        static::$domain = $domain;
        static::$path = $path;
        static::$expire = $expire;
        static::$secure = $secure;
        static::$httpOnly = $httpOnly;
        static::$sameSite = $sameSite;
    }

    public function loadEntries(string $inputType): void
    {
        $_COOKIE = $this->loadVars($_COOKIE);
        $this->vars = & $_COOKIE;
    }

    public function offsetSet($offset, $value)
    {
        $expire = is_null(static::$expire) ? null : time() + static::$expire;

        // TODO: php 7.3 required for 'samesite'
        if (73000 < PHP_VERSION_ID) {
            setcookie($offset, $value, [
                'expires'  => $expire,
                'path'     => static::$path,
                'domain'   => static::$domain,
                'secure'   => (bool)static::$secure,
                'httponly' => (bool)static::$httpOnly,
                'samesite' => static::$sameSite ? 'Strict' : 'Lax', // not in usual config
            ]);
        } else {
            setcookie($offset, $value, $expire, static::$path, static::$domain, (bool)static::$secure, (bool)static::$httpOnly);
        }
        $this->vars[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        setcookie($offset, '', (time() - 3600), static::$path, static::$domain);
        unset($this->vars[$offset]);
    }

    public function getSource(): string
    {
        return static::SOURCE_COOKIE;
    }
}
