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
    // This file allows administration of live help icons

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->template();
    $inc->department();
    $inc->file();

    // Check to see if the person is not logged in
    $GLOBALS['auth']->check_logout();

    // Define the three sets of links in the template
    if ($GLOBALS['auth']->admin()) {
        $GLOBALS['template']->links('conf', 'conf');
    } else {
        header('Location: '.$GLOBALS['conf']['url'].'/admin/index.php');
    }

    $array = array();
    $array[0]['title'] = $GLOBALS['lang']['default'];
    $array[0]['url'] = $GLOBALS['conf']['url'].'/admin/icons.php?id=0';
    $count = 1;

    $departments = $GLOBALS['department']->listall();
    if (isset($departments[0])) {
        foreach ($departments as $key => $val) {
            $array[$count]['title'] = $departments[$key]['name'];
            $array[$count]['url'] = $GLOBALS['conf']['url'].'/admin/icons.php?id='.$departments[$key]['id'];
            $count++;
        }
    }

    $GLOBALS['template']->assign('links_main', $array);

    if (isset($_POST['edit'])) {
        if ($_FILES['online']['size'] > 0) {
            if (!isset($_POST['default_online']) || isset($_POST['default_online']) !== 'true') {
                $GLOBALS['file']->delete_icon(addslashes($_POST['id']), 'online');
                $GLOBALS['file']->upload_icon(addslashes($_POST['id']), $_FILES['online'], 'online');
            }
        } elseif (isset($_POST['default_online']) == 'true') {
            $GLOBALS['file']->delete_icon(addslashes($_POST['id']), 'online');
        }
        if ($_FILES['offline']['size'] > 0) {
            if (!isset($_POST['default_online']) || isset($_POST['default_online']) !== 'true') {
                $GLOBALS['file']->delete_icon(addslashes($_POST['id']), 'offline');
                $GLOBALS['file']->upload_icon(addslashes($_POST['id']), $_FILES['offline'], 'offline');
            }
        } elseif (isset($_POST['default_offline']) == 'true') {
            $GLOBALS['file']->delete_icon(addslashes($_POST['id']), 'offline');
        }
    }

    if (isset($_GET['id'])) {
        $GLOBALS['template']->assign('id', $_GET['id']);
        if ($_GET['id'] == '0') {
            $GLOBALS['template']->assign('name', $GLOBALS['lang']['default']);
        } else {
            $GLOBALS['template']->assign('name', $GLOBALS['department']->name(addslashes($_GET['id'])));
        }
    } else {
        $GLOBALS['template']->assign('id', '0');
        $GLOBALS['template']->assign('name', $GLOBALS['lang']['default']);
    }

    $GLOBALS['template']->assign('departments', $GLOBALS['department']->listall('', 'all'));

    // Assign the page's title and the content template that needs to be used
    $GLOBALS['template']->assign('title', $GLOBALS['lang']['icons']);
    $GLOBALS['template']->assign('content', 'admin_icons.tpl');

    // Include the javascript files
    $GLOBALS['template']->assign('javascript', 'misc');

    // Display the output
    $GLOBALS['template']->display('admin.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>