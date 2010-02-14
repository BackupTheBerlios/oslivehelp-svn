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
    // This file allows administration of the hcl implementation code

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->template();
    $inc->department();

    // Check to see if the person is not logged in
    $GLOBALS['auth']->check_logout();

    // Define the three sets of links in the template
    if ($GLOBALS['auth']->admin()) {
        $GLOBALS['template']->links('conf', 'conf');
    } else {
        header('Location: '.$GLOBALS['conf']['url'].'/admin/index.php');
    }

    $GLOBALS['template']->assign('links_main', array(
        array('title' => $GLOBALS['lang']['image_code'], 'url' => $GLOBALS['conf']['url'].'/admin/code.php?image'),
        array('title' => $GLOBALS['lang']['text_code'], 'url' => $GLOBALS['conf']['url'].'/admin/code.php?text'),
        array('title' => $GLOBALS['lang']['html_code'], 'url' => $GLOBALS['conf']['url'].'/admin/code.php?html'),
        array('title' => $GLOBALS['lang']['invisible_code'], 'url' => $GLOBALS['conf']['url'].'/admin/code.php?invisible')
        ));

    if (isset($_GET['text'])) {
        $GLOBALS['template']->assign('action', 'text');
    } elseif (isset($_GET['html'])) {
        $GLOBALS['template']->assign('action', 'html');
    } elseif (isset($_GET['invisible'])) {
        $GLOBALS['template']->assign('action', 'invisible');
    } else {
        $GLOBALS['template']->assign('action', 'image');
    }

    if (isset($_POST['departmentid'])) {
        $GLOBALS['template']->assign('departmentid', $_POST['departmentid']);
    } else {
        $GLOBALS['template']->assign('departmentid', '');
    }

    if (isset($_POST['cobrowse'])) {
        $GLOBALS['template']->assign('cobrowse', $_POST['cobrowse']);
    } else {
        $GLOBALS['template']->assign('cobrowse', '');
    }

    $GLOBALS['template']->assign('departments', $GLOBALS['department']->listall('', 'all'));

    // Assign the page's title and the content template that needs to be used
    $GLOBALS['template']->assign('title', $GLOBALS['lang']['generate_code']);
    $GLOBALS['template']->assign('content', 'admin_code.tpl');

    // Include the javascript files
    $GLOBALS['template']->assign('javascript', '');

    // Display the output
    $GLOBALS['template']->display('admin.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>