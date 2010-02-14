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
    // This file forms the main chat window 

    include_once('../../class/include.php');
    $inc = new Includer();
    $inc->template();
    $inc->auth();
    $inc->operator();
    $inc->department();
    $inc->chat();
    $inc->db();
    $inc->chat();

    if ($GLOBALS['chat']->blocked()) {
        $GLOBALS['template']->assign('text', $GLOBALS['lang']['blocked']);
        $GLOBALS['template']->display('plain.tpl');
        $inc->finished();
        exit;
    }

    if (isset($_GET['admin'])) {
        $GLOBALS['template']->assign('admin', 'true');
    } else {
        $GLOBALS['template']->assign('admin', 'false');
    }

    $GLOBALS['chat']->session(addslashes($_GET['chatid']));
    $GLOBALS['chat']->assign(addslashes($_GET['chatid']));

    $GLOBALS['template']->assign('chatid', addslashes($_GET['chatid']));

    if ($GLOBALS['auth']->operator() && isset($_GET['admin']) && !isset($_GET['opchat'])) {
        $GLOBALS['db']->query('UPDATE `sessions` SET `typeo`="0" WHERE `chatid`="'.addslashes($_GET['chatid']).'"');
    } else {
        $GLOBALS['db']->query('UPDATE `sessions` SET `typeg`="0" WHERE `chatid`="'.addslashes($_GET['chatid']).'"');
    }

    if (isset($_GET['opchat'])) {
        $GLOBALS['template']->assign('opchat', 'true');
        $GLOBALS['template']->assign('javascript', 'chat&opchat');
    } else {
        $GLOBALS['template']->assign('opchat', 'false');
        $GLOBALS['template']->assign('javascript', 'chat');
    }
    
    if (!$GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.addslashes($_GET['chatid']).'"')) {
        $GLOBALS['template']->assign('onload', ' onload="Chat.empty();"');
        $GLOBALS['template']->assign('text', $GLOBALS['lang']['chat_empty']);
    }

    // Display the output
    $GLOBALS['template']->display('chat.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>