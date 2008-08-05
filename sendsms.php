#!/usr/local/bin/php
<?php
/**
 * Send SMS Messages via Clickatell
 *
 * @author      Jacques Marneweck <jacques@php.net>
 * @copyright   2003-2007 Jacques Marneweck.  All rights strictly reserved.
 * @package     SMS_Clickatell
 * @version     $Id: sendsms.php 2 2007-11-11 15:03:14Z jacques $
 */

require_once 'PEAR.php';
require_once 'SMS/Clickatell.php';
require_once 'Console/Getopt.php';

set_time_limit(0);
error_reporting(E_ALL);

$options = getopt("d:m:");

if (!isset($options['d']) || !isset($options['m'])) {
        die ("This script is supposed to be called from Exim/Nagios.");
}

$sms = new SMS_Clickatell;
$res = $sms->init (
    array (
        'user'      => 'username',
        'pass'      => 'password',
        'api_id'    => 'api_id',
    )
);
if (PEAR::isError($res)) {
    die ($res->getMessage());
}
$res = $sms->auth();
if (PEAR::isError($res)) {
    die ($res->getMessage());
}

$climsgid = md5(md5(uniqid(mt_rand(), true)) .
                md5(uniqid(rand(), true)) .
                md5(uniqid(`hostname`)));
$sent = $sms->sendmsg (
    array (
        'from' => 'NOSTROMO',
        'to' => $options['d'],
        'text' => $options['m'],
        'msg_type' => 'SMS_TEXT',
        'climsgid' => $climsgid,
    )
);
if (PEAR::isError($sent)) {
    die ($sent->getMessage());
}