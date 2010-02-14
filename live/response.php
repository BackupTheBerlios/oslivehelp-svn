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
    // This file is a relay between the javascript chat class and the php classes 

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->chat();
    $inc->operator();
    $inc->live();
    $inc->aardvark();
    
    if ($GLOBALS['chat']->blocked()) {
        $inc->finished();
        exit;
    }

    // Receive all the variables sent into the file
    $variables = $aardvark->receive();


    if(!isset($aardvark->vars['id'])) {
        $aardvark->vars['id'] = '';
    }
    if(!isset($aardvark->vars['chatid'])) {
        $aardvark->vars['chatid'] = '';
    }
    if (isset($aardvark->vars['admin'])) {
        $aardvark->vars['admin'] = 'true';
    } else {
        $aardvark->vars['admin'] = 'false';
    }
    if (isset($aardvark->vars['opchat'])) {
        $aardvark->vars['opchat'] = 'true';
    } else {
        $aardvark->vars['opchat'] = 'false';
    }
    if (isset($aardvark->vars['typing'])) {
        $aardvark->vars['typing'] = 'true';
    } else {
        $aardvark->vars['typing'] = 'false';
    }

    if (isset($aardvark->vars['live'])) {
        if (isset($aardvark->vars['endchat'])) {
            $GLOBALS['chat']->end($aardvark->vars['chatid']);
        }
        foreach ($_GET as $key => $val) {
            $_GET[substr($key, 9, strlen($key))] = addslashes($val);
            unset($_GET[$key]);
        }
        $aardvark->add('live_'.$GLOBALS['live']->chatid(), $GLOBALS['live']->response($_GET));
    } elseif (isset($aardvark->vars['request'])) {
        $aardvark->add('request_'.$GLOBALS['live']->chatid(), $GLOBALS['chat']->request($aardvark->vars['chatid'], $aardvark->vars['opchat']));
    } elseif (isset($aardvark->vars['cobrowse'])) {
        $aardvark->add('cobrowse_'.$GLOBALS['live']->chatid(), $GLOBALS['cobrowse']->response($aardvark->vars['chatid']));
    } elseif (isset($aardvark->vars['coforms']) && isset($aardvark->vars['type']) && isset($aardvark->vars['name']) && isset($aardvark->vars['value'])) {
        $GLOBALS['cobrowse']->setforms($GLOBALS['live']->chatid(), $aardvark->vars['type'], rawurldecode($aardvark->vars['name']), rawurldecode($aardvark->vars['value']), 'false');
    } elseif (isset($aardvark->vars['decline_initiate'])) {
        $GLOBALS['live']->decline_inititate();
    }

    // Send the variables across to the client
    $aardvark->send();

    // do events that need to be done at the end of the file
    $inc->finished();

?>