<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gerryhall
 * Date: 13-02-18
 * Time: 3:27 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ABALookup\Configuration;

class Mail
{
    protected $mailFrom;
    protected $mailFromName;
    protected $verificationMessage;
    protected $url;

    function __construct($mailFrom, $mailFromName, $url, $verificationMessage) {
        $this->mailFrom = $mailFrom;
        $this->mailFromName = $mailFromName;
        $this->url = $url;
        $this->verificationMessage = $verificationMessage;
    }

    public function getMailFrom() {
        return $this->mailFrom;
    }

    public function getMailFromName() {
        return $this->mailFromName;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getVerificationMessage() {
        return $this->verificationMessage;
    }
}
