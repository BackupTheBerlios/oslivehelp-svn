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
    // This file contains a relay to connect the javascript classes to the php classes
    // in the request monitor

    include_once('../../class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->operator();
    $inc->monitor();
    $inc->aardvark();

    // Receive all the variables sent into the file
    $variables = $aardvark->receive();

    // Check to see if the person is not logged in
    $GLOBALS['auth']->check_logout();

    if(!isset($aardvark->vars['id'])) {
        $aardvark->vars['id'] = '';
    }
    if(!isset($aardvark->vars['chatid'])) {
        $aardvark->vars['chatid'] = '';
    }

    if (isset($aardvark->vars['monitor'])) {
        $aardvark->add('monitor_'.$GLOBALS['operator']->id(), $GLOBALS['monitor']->response());
    }
    if (isset($aardvark->vars['accept_chat'])) {
        $GLOBALS['monitor']->accept_chat($aardvark->vars['id'], $aardvark->vars['chatid']);
    }
    if (isset($aardvark->vars['decline_chat'])) {
        $GLOBALS['monitor']->decline_chat($aardvark->vars['id'], $aardvark->vars['chatid']);
    }
    if (isset($aardvark->vars['accept_opchat'])) {
        $GLOBALS['monitor']->accept_opchat($aardvark->vars['id'], $aardvark->vars['chatid']);
    }
    if (isset($aardvark->vars['decline_opchat'])) {
        $GLOBALS['monitor']->decline_opchat($aardvark->vars['id'], $aardvark->vars['chatid']);
    }
    if (isset($aardvark->vars['accept_transfer'])) {
        $GLOBALS['monitor']->accept_transfer($aardvark->vars['id'], $aardvark->vars['chatid']);
    }
    if (isset($aardvark->vars['decline_transfer'])) {
        $GLOBALS['monitor']->decline_transfer($aardvark->vars['id'], $aardvark->vars['chatid']);
    }
    if (isset($aardvark->vars['status'])) {
        $GLOBALS['monitor']->status($aardvark->vars['x']);
    }
    if (isset($aardvark->vars['sounds'])) {
        $GLOBALS['monitor']->sounds($aardvark->vars['x']);
    }

    // Send the variables across to the client
    $aardvark->send();
    
    // do events that need to be done at the end of the file
    $inc->finished();
?>