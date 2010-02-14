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
    // This file is the landing page for requesting live help 

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->template();
    $inc->live();
    $inc->chat();

    if ($GLOBALS['chat']->blocked()) {
        $GLOBALS['template']->assign('text', $GLOBALS['lang']['blocked']);
        $GLOBALS['template']->display('plain.tpl');
        $inc->finished();
        exit;
    }

    if (isset($_GET['departmentid'])) {
    	$department_show = false;
        $departmentid = addslashes($_GET['departmentid']);
    } else {
    	$department_show = true;
        $departmentid = '';
    }

    if (isset($_GET['nick'])) {
    	$nick_show = false;
        $nick = addslashes($_GET['nick']);
    } else {
    	$nick_show = true;
        $nick = htmlspecialchars(stripslashes($GLOBALS['live']->nick()));
    }

    if (!$nick_show && !$department_show) {
        header('Location: '.$GLOBALS['conf']['url'].'/live/request.php?request&departmentid='.$departmentid.'&nick='.urlencode($nick));
    }

    if (isset($_GET['retry'])) {
        $retry = 'true';
    } else {
        $retry = 'false';
    }

    if (!$GLOBALS['live']->status_department($departmentid)) {
        header('Location: '.$GLOBALS['conf']['url'].'/live/divert.php?divert=offline&departmentid='.$departmentid);
    }
    
    if ($department_show) {
    	$avaliable = $GLOBALS['live']->avaliable_department('');
    } else {
    	$avaliable = $GLOBALS['live']->avaliable_department($departmentid);
    }
    if (count($avaliable) == 1) {
        $departmentid = $avaliable[0]['id'];
    }

    $GLOBALS['template']->assign('avaliable', $avaliable);
    $GLOBALS['template']->assign('departmentid', $departmentid);
    $GLOBALS['template']->assign('department_show', $department_show);
    $GLOBALS['template']->assign('nick', $nick);
    $GLOBALS['template']->assign('nick_show', $nick_show);
    $GLOBALS['template']->assign('retry', $retry);

    // Display the output
    $GLOBALS['template']->display('live_main.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>