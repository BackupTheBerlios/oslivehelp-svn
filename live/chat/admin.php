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
    // This file contains the operators special capabilites during a chat

    include_once('../../class/include.php');
    $inc = new Includer();
    $inc->template();
    $inc->chat();
    $inc->monitor();
    $inc->db();
    $inc->file();
    $inc->live();
    $inc->canned();

    if (isset($_GET['admin'])) {
        $GLOBALS['template']->assign('admin', 'true');
    } else {
        $GLOBALS['template']->assign('admin', 'false');
    }
    $GLOBALS['chat']->assign(addslashes($_GET['chatid']));
    $GLOBALS['template']->assign('chatid', addslashes($_GET['chatid']));

    if (isset($_POST['submit'])) {
        $GLOBALS['template']->assign('submit', 'true');
    } else {
        $GLOBALS['template']->assign('submit', 'false');
    }

    if (isset($_FILES['file'])) {
        if ($_FILES["file"]["size"] > 0) {
            $size = $GLOBALS['file']->insert('true', $_FILES["file"], addslashes($_GET['chatid']));
            $GLOBALS['template']->assign('onload', ' onload="parent.window.Chat.file(\''.$size.'\');"');
            $GLOBALS['template']->assign('fail', 'false');
        } else {
            $GLOBALS['template']->assign('fail', 'true');
        }
    }

    if (isset($_GET['request_transfer'])) {
        $GLOBALS['template']->assign('request_transfer', 'true');
        $GLOBALS['file']->request_transfer(addslashes($_GET['chatid']));
    }

    if (isset($_GET['canned'])) {
        $GLOBALS['template']->assign('type', 'canned');
        $GLOBALS['template']->assign('canned', $GLOBALS['canned']->get($_SESSION['hcl_'.addslashes($_GET['chatid'])]['operatorid'], $_SESSION['hcl_'.addslashes($_GET['chatid'])]['departmentid']));
    } elseif (isset($_GET['transfer'])) {
        $GLOBALS['template']->assign('type', 'transfer');
        $GLOBALS['template']->assign('transfer', $GLOBALS['live']->avaliable_department('', 'all'));
    } elseif (isset($_GET['file'])) {
        $GLOBALS['template']->assign('type', 'file');
        $GLOBALS['template']->assign('info', $GLOBALS['monitor']->info(addslashes($_GET['chatid'])));
        $GLOBALS['template']->assign('max_size', ini_get('upload_max_filesize'));
    } elseif (isset($_GET['info'])) {
        if (isset($_GET['history'])) {
            $GLOBALS['template']->assign('history', 'true');
        }
        $GLOBALS['template']->assign('type', 'info');
        $GLOBALS['template']->assign('info', $GLOBALS['monitor']->info(addslashes($_GET['chatid'])));
    }

    // Display the output
    $GLOBALS['template']->display('chat_admin.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>