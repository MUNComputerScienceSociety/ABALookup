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

    function __construct($mailFrom, $mailFromName) {
        $this->mailFrom = $mailFrom;
        $this->mailFromName = $mailFromName;
    }

    public function getMailFrom() {
        return $this->mailFrom;
    }

    public function getMailFromName() {
        return $this->mailFromName;
    }
}
