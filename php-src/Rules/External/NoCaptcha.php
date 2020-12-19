<?php

/**
 * This is a PHP library that handles calling NoReCAPTCHA.
 */
namespace kalanis\kw_forms\Rules\External;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\ARule;

/**
 * The NOCAPTCHA server URL's
 */
define("NOCAPTCHA_API_SERVER", "https://www.google.com/recaptcha/api.js");
define("NOCAPTCHA_API_SECURE_SERVER", "https://www.google.com/recaptcha/api/siteverify");


/**
 * Class NoCaptcha
 * @package kalanis\kw_forms\Rules\External
 * Check if input is correctly set captcha
 */
class NoCaptcha extends ARule
{
    protected $privateKey = '';

    public function setPrivateKey(string $privateKey): self
    {
        $this->privateKey = $privateKey;
        return $this;
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     * @param IValidate $entry
     * @throws RuleException
     */
    public function validate(IValidate $entry): void
    {
        // entry has key: g-recaptcha-response
        $response = file_get_contents(NOCAPTCHA_API_SECURE_SERVER . "?secret=" . $this->privateKey . "&response=" . $entry->getValue());
        $responseStructure = json_decode($response, true);
        if ($responseStructure["success"] !== true) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }

    /**
     * Gets the challenge HTML (javascript only version).
     * This is called from the browser, and the resulting NoReCAPTCHA HTML widget
     * is embedded within the HTML form it was called from.
     *
     * @param string $pubKey
     * @return string - The HTML to be embedded in the user's form.
     */
    public static function getHtml(string $pubKey): string
    {
        return '<script src=\'' . NOCAPTCHA_API_SERVER . '\'></script>
	<div class="g-recaptcha" data-sitekey="' . $pubKey . '"></div>';
    }
}
