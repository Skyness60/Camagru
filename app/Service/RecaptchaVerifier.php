<?php
namespace App\Service;

class RecaptchaVerifier
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function verify(string $response, string $remoteIp): bool
    {
        if (empty($response) || empty($this->secret)) {
            return false;
        }
        $verify = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->secret) .
            '&response=' . urlencode($response) .
            '&remoteip=' . $remoteIp
        );
        $captchaSuccess = json_decode($verify, true);
        return !empty($captchaSuccess['success']);
    }
}
