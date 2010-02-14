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
    // This files displays all the chat messages

    include_once('../../class/include.php');
    $inc = new Includer();
    $inc->chat();
    $inc->template();

    if (isset($_GET['admin'])) {
        $GLOBALS['template']->assign('admin', 'true');
        $admin = 'true';
    } else {
        $GLOBALS['template']->assign('admin', 'false');
        $admin = 'false';
    }

    $GLOBALS['chat']->assign(addslashes($_GET['chatid']));
    $GLOBALS['template']->assign('chatid', addslashes($_GET['chatid']));
    $GLOBALS['template']->assign('messages', $GLOBALS['chat']->get_messages($admin, addslashes($_GET['chatid'])));
    if (isset($_GET['admin']) && $_SESSION['hcl_'.addslashes($_GET['chatid'])]['isoperator'] == 'true') {
        $GLOBALS['template']->assign('onload', ' onload="scroll(0,10000000);parent.window.Chat.checksum = '.$GLOBALS['chat']->checksum(addslashes($_GET['chatid']), 'true').';"');
        $GLOBALS['template']->assign('now_chatting', parse_with_session(addslashes($_GET['chatid']), $GLOBALS['lang']['now_chatting'], 'guest'));
    } else {
        $GLOBALS['template']->assign('onload', ' onload="scroll(0,10000000);parent.window.Chat.checksum = '.$GLOBALS['chat']->checksum(addslashes($_GET['chatid']), 'false').';"');
        $GLOBALS['template']->assign('now_chatting', parse_with_session(addslashes($_GET['chatid']), $GLOBALS['lang']['now_chatting'], 'operator'));
    }

    // Display the output
    $GLOBALS['template']->display('chat_display.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>