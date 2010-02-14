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
    // This file is used to trn sounds on and off, enable window focusing and
    // displaying the operators picture if they want it displayed 

    include_once('../../class/include.php');
    $inc = new Includer();
    $inc->template();
    $inc->operator();
    $inc->chat();
    $inc->db();

    if (isset($_GET['admin'])) {
        $GLOBALS['template']->assign('admin', 'true');
    } else {
        $GLOBALS['template']->assign('admin', 'false');
    }

    $GLOBALS['chat']->assign(addslashes($_GET['chatid']));
    $GLOBALS['template']->assign('chatid', addslashes($_GET['chatid']));
    $GLOBALS['template']->assign('showpic', $GLOBALS['operator']->showpic($GLOBALS['chat']->operatorid()));
    $GLOBALS['template']->assign('onload', ' onload="parent.window.Chat.refresh();parent.window.Chat.timer();" onunload="parent.window.Chat.save();"');

    // Display the output
    $GLOBALS['template']->display('chat_top.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>