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
    // This file is used to try and connect the visitor with an operator 

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->template();
    $inc->live();
    $inc->chat();
    $inc->operator();

    if ($GLOBALS['chat']->blocked()) {
        $GLOBALS['template']->assign('text', $GLOBALS['lang']['blocked']);
        $GLOBALS['template']->display('plain.tpl');
        $inc->finished();
        exit;
    }

    if (isset($_GET['opchat'])) {
        if (isset($_POST['operatorid'])) {
            $operatorid = addslashes($_POST['operatorid']);
            if (isset($_POST['submit']) && ($_POST['operatorid'] == '')) {
                header('Location: '.$GLOBALS['conf']['url'].'/live/operator.php?retry');
            } else {
                $GLOBALS['live']->chatid('op_'.$GLOBALS['operator']->id());
                $GLOBALS['live']->opchat($operatorid, $GLOBALS['operator']->id());
            }
        } else {
            header('Location: '.$GLOBALS['conf']['url'].'/live/operator.php?retry');
        }
        $GLOBALS['template']->assign('javascript', 'request&opchat');
        $GLOBALS['template']->assign('onload', ' onload="Request.refresh();"');
    } elseif (isset($_GET['initiate'])) {
        $GLOBALS['live']->initiate(addslashes($_GET['chatid']));
        $GLOBALS['template']->assign('javascript', 'request&initiate');
        $GLOBALS['template']->assign('onload', ' onload="Request.refresh();"');
    } elseif (isset($_GET['request'])) {
        if (isset($_POST['departmentid'])) {
            $departmentid = addslashes($_POST['departmentid']);
        } else if (isset($_GET['departmentid'])) {
            $departmentid = addslashes($_GET['departmentid']);    
        } else {
        	$departmentid= '';
        }
        if (isset($_POST['nick'])) {
            $nick = addslashes($_POST['nick']);
        } else if (isset($_GET['nick'])) {
            $nick = addslashes($_GET['nick']);
        } else {
        	$nick = '';
        }
        if ($departmentid != '') {
            if (isset($_POST['submit']) && ($departmentid == '' || $nick == '')) {
                header('Location: '.$GLOBALS['conf']['url'].'/live/main.php?retry');
            } else {
                $GLOBALS['live']->nick($nick);
                $GLOBALS['live']->request($departmentid, $nick, $GLOBALS['live']->chatid());
            }
            $GLOBALS['template']->assign('javascript', 'request&departmentid='.$departmentid);
            $GLOBALS['template']->assign('onload', ' onload="Request.refresh();"');
        } else {
            header('Location: '.$GLOBALS['conf']['url'].'/live/main.php?retry');
        }
    }

    // Display the output
    $GLOBALS['template']->display('live_request.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();

?>