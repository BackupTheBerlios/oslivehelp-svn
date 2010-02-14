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
    // This file allows administration of canned messages

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->template();
    $inc->canned();
    $inc->department();

    // Check to see if the person is not logged in
    $GLOBALS['auth']->check_logout();

    // Define the three sets of links in the template
    if ($GLOBALS['auth']->admin()) {
        $GLOBALS['template']->links('admin', 'admin');
    } else {
        $GLOBALS['template']->links('operator', 'operator');
    }

    if (isset($_GET['add'])) {
        $GLOBALS['template']->assign('action', 'add');
    } elseif (isset($_POST['add'])) {
        $GLOBALS['template']->assign('action', 'added');
        $GLOBALS['canned']->insert(addslashes($_POST['operator']), addslashes($_POST['department']), addslashes($_POST['subject']), addslashes($_POST['message']));
    } elseif (isset($_GET['edit'])) {
        $GLOBALS['template']->assign('action', 'edit');
        $GLOBALS['template']->assign('canned_message', $GLOBALS['canned']->get('', '', addslashes($_GET['id'])));
    } elseif (isset($_POST['edit'])) {
        $GLOBALS['template']->assign('action', 'edited');
        $GLOBALS['canned']->update(addslashes($_POST['id']), addslashes($_POST['operator']), addslashes($_POST['department']), addslashes($_POST['subject']), addslashes($_POST['message']));
    } elseif (isset($_POST['delete'])) {
        $GLOBALS['template']->assign('action', 'delete');
        // Loop though all the posted variables that have numbers as their name and conent
        // these need to be deleted (the number is the id of the db row)
        foreach ($_POST as $key => $val) {
            if ($key == $val && is_numeric($val)) {
                $GLOBALS['canned']->delete($val);
            }
        }
    } else {
        $GLOBALS['template']->assign('action', '');
    }

    $GLOBALS['template']->assign('canned', $GLOBALS['canned']->get());
    $GLOBALS['template']->assign('id', $GLOBALS['operator']->id());
    $GLOBALS['template']->assign('departments', $GLOBALS['department']->listall($GLOBALS['operator']->id()));
    
    $GLOBALS['template']->assign('links_main', array(
        array('title' => $GLOBALS['lang']['view_all'], 'url' => $GLOBALS['conf']['url'].'/admin/canned.php'),
        array('title' => $GLOBALS['lang']['add'], 'url' => $GLOBALS['conf']['url'].'/admin/canned.php?add')
        ));

    // Assign the page's title and the content template that needs to be used
    $GLOBALS['template']->assign('title', $GLOBALS['lang']['canned_messages']);
    $GLOBALS['template']->assign('content', 'admin_canned.tpl');

    // Include the javascript files
    $GLOBALS['template']->assign('javascript', 'misc&monitor');

    // Display the output
    $GLOBALS['template']->display('admin.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>