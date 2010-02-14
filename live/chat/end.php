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
    // This file is called when a chat ends

    include_once('../../class/include.php');
    $inc = new Includer();
    $inc->template();
    $inc->auth();
    $inc->operator();
    $inc->department();
    $inc->chat();
    $inc->db();
    $inc->transcript();
    $inc->email();

    if (isset($_GET['admin'])) {
        $GLOBALS['template']->assign('admin', 'true');
        $admin = 'true';
    } else {
        $GLOBALS['template']->assign('admin', 'false');
        $admin = 'false';
    }

    $GLOBALS['chat']->assign(addslashes($_GET['chatid']));
    $GLOBALS['template']->assign('chatid', addslashes($_GET['chatid']));
    $chatid = addslashes($_GET['chatid']);

    if ((isset($_GET['admin']) && $_SESSION['hcl_'.addslashes($_GET['chatid'])]['isoperator'] == 'true') || isset($_GET['opchat'])) {
        if (isset($_GET['save']) && !$GLOBALS['operator']->autosave_transcripts()) {
            // The operator does not have autosave transcripts on
            $GLOBALS['transcript']->build($chatid);
        }
        $GLOBALS['chat']->end($chatid);
        $GLOBALS['template']->assign('onload', ' onload="window.close();";');
        $GLOBALS['template']->assign('visitor', 'false');
    } else {
        // It is a visitor, so end the chat and carry out post-chat features
        $GLOBALS['chat']->end($chatid);
        $GLOBALS['template']->assign('visitor', 'true');
        $GLOBALS['template']->assign('chatid', $chatid);
        if (isset($_GET['print'])) {
            $GLOBALS['template']->assign('display', 'print');
            $GLOBALS['template']->assign('onload', ' onload="window.print();"');
            $GLOBALS['template']->assign('nick', $_SESSION['hcl_'.$chatid]['guest']);
            $GLOBALS['template']->assign('operator', $_SESSION['hcl_'.$chatid]['operator']);
            $GLOBALS['template']->assign('department', $_SESSION['hcl_'.$chatid]['department']);
            $GLOBALS['template']->assign('email', $GLOBALS['department']->email($_SESSION['hcl_'.$chatid]['departmentid']));
            $GLOBALS['template']->assign('time', $GLOBALS['chat']->timestamp($chatid, 'G:i:s D jS F Y'));
            $GLOBALS['template']->assign('chat', $GLOBALS['transcript']->get($chatid));
        } elseif (isset($_POST['email'])) {
            $GLOBALS['template']->assign('display', 'email');
            $guest['name'] = $_SESSION['hcl_'.$chatid]['guest'];
            $guest['email'] = addslashes($_POST['email']);
            $details['guest'] = $_SESSION['hcl_'.$chatid]['guest'];
            $details['operator'] = $_SESSION['hcl_'.$chatid]['operator'];
            $details['department'] = $_SESSION['hcl_'.$chatid]['department'];
            $details['email'] = $GLOBALS['department']->email($_SESSION['hcl_'.$chatid]['departmentid']);
            $details['time'] = $GLOBALS['chat']->timestamp($chatid, 'G:i:s D jS F Y');
            $details['transcript'] = $GLOBALS['transcript']->get($chatid);
            $GLOBALS['email']->auth($GLOBALS['department']->email_host($_SESSION['hcl_'.$chatid]['departmentid']), $GLOBALS['department']->email_port($_SESSION['hcl_'.$chatid]['departmentid']), $GLOBALS['department']->email_username($_SESSION['hcl_'.$chatid]['departmentid']), $GLOBALS['department']->email_password($_SESSION['hcl_'.$chatid]['departmentid']));
            $GLOBALS['email']->transcript($guest, $details);
        } elseif (isset($_POST['review'])) {
            $GLOBALS['template']->assign('display', 'review');
            if (isset($_POST['1'])) {
                $rating = '1';
            }
            if (isset($_POST['2'])) {
                $rating = '2';
            }
            if (isset($_POST['3'])) {
                $rating = '3';
            }
            if (isset($_POST['4'])) {
                $rating = '4';
            }
            if (isset($_POST['5'])) {
                $rating = '5';
            }
            if (!isset($rating)) {
                $rating = '0';
            }
            $review = addslashes($_POST['message']);
            if ($transcript = $GLOBALS['db']->query('SELECT * FROM `transcripts` WHERE `chatid`="'.$chatid.'" ORDER BY `timestamp` DESC LIMIT 1')) {
                $transcriptid = $transcript[0]['id'];
            } else {
                $transcriptid = 0;
            }
            $operatorid = $_SESSION['hcl_'.$chatid]['operatorid'];
            $GLOBALS['operator']->review($operatorid, $chatid, $transcriptid, $rating, $review);
        } else {
            $GLOBALS['template']->assign('display', 'default');
            if (isset($_GET['close'])) {
                $GLOBALS['template']->assign('end_message', parse_with_session($chatid, $GLOBALS['lang']['chat_end_visitor']));
            } else {
                $GLOBALS['template']->assign('end_message', parse_with_session($chatid, $GLOBALS['lang']['chat_end_operator']));
            }
        }
    }

    // Display the output
    $GLOBALS['template']->display('chat_end.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>