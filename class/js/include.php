<?php

    // Copyright (c) 2005 Help Center Live. All Rights Reserved

    // This file is part of Help Center Live.

    // Help Center Live is free software; you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation; either version 2 of the License, or
    // (at your option) any later version.

    // Help Center Live is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.

    // You should have received a copy of the GNU General Public License
    // along with Help Center Live; if not, write to the Free Software
    // Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

    // Contributors: Michael Bird

    // File Comments:
    // This file is used to include the javascript files that we want to use

    include_once('../include.php');
    $inc = new Includer();
    $inc->chat();
    $inc->live();
    $inc->operator();
    $inc->cobrowse();

    if (isset($_GET['opchat']) && isset($_GET['chatid'])) {
        $chatid = addslashes($_GET['chatid']);
    } elseif (isset($_GET['opchat']) && !isset($_GET['chatid'])) {
        $chatid = 'op_'.$GLOBALS['operator']->id();
    } else {
        if (isset($_GET['live']) || isset($_GET['cobrowse']) || isset($_GET['request'])) {
            $GLOBALS['live']->newvisitor();
            $chatid = $GLOBALS['live']->chatid();
        }
        if (isset($_GET['chat']) && isset($_GET['admin'])) {
            $chatid = addslashes($_GET['chatid']);
        } else {
            $GLOBALS['live']->newvisitor();
            $chatid = $GLOBALS['live']->chatid();
        }
        if (isset($_GET['monitor']) || isset($_GET['request'])) {
            $operatorid = $GLOBALS['operator']->id();
        }
    }
    
    // Prevent injection
    foreach ($_GET as $key => $val) {
    	$_GET[$key] = addslashes($val);
    }
    foreach ($_POST as $key => $val) {
    	$_POST[$key] = addslashes($val);
    }

    $misc_flag = false;
    $image_flag = false;
    $monitor_flag = false;
    $live_flag = false;
    $cobrowse_flag = false;
    $request_flag = false;
    $chat_flag = false;
    $aardvark_flag = false;
    $xmlhttprequest_flag = false;

?>
<!--

<?php
    if (isset($_GET['misc'])) {
        $type = 'misc';
        if ($misc_flag !== true) {
            include_once('misc.php');
            $misc_flag = true;
        }
    }
    if (isset($_GET['monitor'])) {
        $type = 'monitor';
        if ($misc_flag !== true) {
            include_once('misc.php');
            $misc_flag = true;
        }
        if ($xmlhttprequest_flag !== true) {
            include_once('xmlhttprequest.php');
            $xmlhttprequest_flag = true;
        }
        if ($aardvark_flag !== true) {
            include_once('aardvark.php');
            $aardvark_flag = true;
        }
        if ($monitor_flag !== true) {
            include_once('monitor.php');
            $monitor_flag = true;
        }
    }
    if (isset($_GET['live'])) {
        $type = 'live';
        if ($misc_flag !== true) {
            include_once('misc.php');
            $misc_flag = true;
        }
        if ($xmlhttprequest_flag !== true) {
            include_once('xmlhttprequest.php');
            $xmlhttprequest_flag = true;
        }
        if ($aardvark_flag !== true) {
            include_once('aardvark.php');
            $aardvark_flag = true;
        }
        if ($live_flag !== true) {
            include_once('live.php');
            $live_flag = true;
        }
    }
    if (isset($_GET['cobrowse'])) {
        $type = 'cobrowse';
        if ($misc_flag !== true) {
            include_once('misc.php');
            $misc_flag = true;
        }
        if ($xmlhttprequest_flag !== true) {
            include_once('xmlhttprequest.php');
            $xmlhttprequest_flag = true;
        }
        if ($aardvark_flag !== true) {
            include_once('aardvark.php');
            $aardvark_flag = true;
        }
        if ($cobrowse_flag !== true) {
            include_once('cobrowse.php');
            $cobrowse_flag = true;
        }
    }
    if (isset($_GET['request'])) {
        $type = 'request';
        if ($misc_flag !== true) {
            include_once('misc.php');
            $misc_flag = true;
        }
        if ($xmlhttprequest_flag !== true) {
            include_once('xmlhttprequest.php');
            $xmlhttprequest_flag = true;
        }
        if ($aardvark_flag !== true) {
            include_once('aardvark.php');
            $aardvark_flag = true;
        }
        if ($request_flag !== true) {
            include_once('request.php');
            $request_flag = true;
        }
    }
    if (isset($_GET['chat'])) {
        $type = 'chat';
        if ($misc_flag !== true) {
            include_once('misc.php');
            $misc_flag = true;
        }
        if ($xmlhttprequest_flag !== true) {
            include_once('xmlhttprequest.php');
            $xmlhttprequest_flag = true;
        }
        if ($aardvark_flag !== true) {
            include_once('aardvark.php');
            $aardvark_flag = true;
        }
        if ($chat_flag !== true) {
            include_once('chat.php');
            $chat_flag = true;
        }
    }
?>

//-->