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
    // This file allows administration of chat transcripts

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->template();
    $inc->canned();
    $inc->department();
    $inc->transcript();

    // Check to see if the person is not logged in
    $GLOBALS['auth']->check_logout();

    // Define the three sets of links in the template
    if ($GLOBALS['auth']->admin()) {
        if (isset($_GET['admin'])) {
            $GLOBALS['template']->links('conf', 'conf');
        } else {
            $GLOBALS['template']->links('admin', 'admin');
        }
    } else {
        $GLOBALS['template']->links('operator', 'operator');
    }
    $GLOBALS['template']->assign('links_main', array(
        array('title' => $GLOBALS['lang']['view_all'], 'url' => $GLOBALS['conf']['url'].'/admin/transcripts.php')
        ));

    if (isset($_GET['view']) && isset($_GET['id'])) {
        $GLOBALS['template']->assign('action', 'view');
        if ($GLOBALS['auth']->admin() && isset($_GET['admin'])) {
            $GLOBALS['template']->assign('transcript', $GLOBALS['transcript']->get('', addslashes($_GET['id']), 'admin'));
        } else {
            $GLOBALS['template']->assign('transcript', $GLOBALS['transcript']->get('', addslashes($_GET['id'])));
        }
    } elseif (isset($_POST['delete'])) {
    	if ($GLOBALS['conf']['opdelete'] === true || admin()){
            $GLOBALS['template']->assign('action', 'delete');
            foreach ($_POST as $key => $val) {
                if ($key == $val && is_numeric($val)) {
                    $GLOBALS['db']->query('DELETE FROM `transcripts` WHERE `id`="'.$val.'"');
                }
            }
    	}
    } else {
        $GLOBALS['template']->assign('action', '');
    }

    if ($GLOBALS['auth']->admin() && isset($_GET['admin'])) {
        $GLOBALS['template']->assign('transcripts', $GLOBALS['transcript']->get('', '', 'admin'));
        $GLOBALS['template']->assign('departments', $GLOBALS['department']->listall());
        $GLOBALS['template']->assign('admin', 'admin');
    } else {
        $GLOBALS['template']->assign('transcripts', $GLOBALS['transcript']->get());
        $GLOBALS['template']->assign('departments', $GLOBALS['department']->listall($GLOBALS['operator']->id()));
        $GLOBALS['template']->assign('admin', '');
    }

    // Assign the page's title and the content template that needs to be used
    $GLOBALS['template']->assign('title', $GLOBALS['lang']['transcripts']);
    $GLOBALS['template']->assign('content', 'admin_transcripts.tpl');

    // Include the javascript files
    $GLOBALS['template']->assign('javascript', 'misc&monitor');

    // Display the output
    $GLOBALS['template']->display('admin.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>