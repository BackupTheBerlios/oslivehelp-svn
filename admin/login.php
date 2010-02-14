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
    // This file allows operators to login

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->template();

    if (isset($_POST['username']) && isset($_POST['password'])) {
            $GLOBALS['auth']->login(addslashes($_POST['username']), addslashes($_POST['password']));
    }

    // Define the three sets of links in the template
    $GLOBALS['template']->links('none', 'none');
    $GLOBALS['template']->assign('links_main', '');

    // If the person is logged in, load sound files and redirect them to the index page
    if ($GLOBALS['auth']->operator()) {
        header('Location: '.$GLOBALS['conf']['url'].'/admin/index.php');
    } else {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $GLOBALS['template']->assign('login_text', $GLOBALS['lang']['login_failure']);
        }
        // Assign the page's title and the content template that needs to be used
        $GLOBALS['template']->assign('title', $GLOBALS['lang']['login']);
        $GLOBALS['template']->assign('content', 'admin_login.tpl');
    	// Display the output
    	$GLOBALS['template']->display('admin.tpl');
    }
    
    // do events that need to be done at the end of the file
    $inc->finished();

?>