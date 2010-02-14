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
    // This file deals with visitors that have been diverted when requesting a chat
    // because there are no operators or all the operators are busy 

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->template();
    $inc->department();
    $inc->email();
    $inc->chat();

    if ($GLOBALS['chat']->blocked()) {
        $GLOBALS['template']->assign('text', $GLOBALS['lang']['blocked']);
        $GLOBALS['template']->display('plain.tpl');
        $inc->finished();
        exit;
    }

    if (isset($_GET['departmentid'])) {
        if ($_GET['departmentid'] !== '') {
            $GLOBALS['template']->assign('departmentid', addslashes($_GET['departmentid']));
        } else {
            $GLOBALS['template']->assign('departmentid', '0');
        }
    } elseif (isset($_POST['departmentid'])) {
        if ($_POST['departmentid'] !== '') {
            $GLOBALS['template']->assign('departmentid', addslashes($_POST['departmentid']));
        } else {
            $GLOBALS['template']->assign('departmentid', '0');
        }
    } else {
        $GLOBALS['template']->assign('departmentid', '0');
    }

    if (isset($_GET['divert'])) {
        if ($_GET['divert'] == 'busy') {
            $GLOBALS['template']->assign('divert', 'busy');
        } elseif ($_GET['divert'] == 'offline') {
            $GLOBALS['template']->assign('divert', 'offline');
            $GLOBALS['template']->assign('departments', $GLOBALS['department']->listall('0'));
        }
    } elseif (isset($_POST['email_send'])) {
        $details['name'] = addslashes($_POST['name']);
        $details['from'] = addslashes($_POST['email']);
        $details['department'] = $GLOBALS['department']->name(addslashes($_POST['departmentid']));
        $details['email'] = $GLOBALS['department']->email(addslashes($_POST['departmentid']));
        $details['subject'] = addslashes($_POST['subject']);
        $details['message'] = addslashes($_POST['message']);
        $GLOBALS['email']->auth($GLOBALS['department']->email_host(addslashes($_POST['departmentid'])), $GLOBALS['department']->email_port(addslashes($_POST['departmentid'])), $GLOBALS['department']->email_username(addslashes($_POST['departmentid'])), $GLOBALS['department']->email_password(addslashes($_POST['departmentid'])));
        $GLOBALS['email']->contact($details);
        $GLOBALS['template']->assign('divert', 'email_sent');
    } else {
        $GLOBALS['template']->assign('divert', '');
    }


    $GLOBALS['template']->assign('onload', '');

    $GLOBALS['template']->assign('javascript', 'misc');

    // Display the output
    $GLOBALS['template']->display('live_divert.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>