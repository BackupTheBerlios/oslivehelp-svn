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
    // This file allows operators to logout

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->template();

    $GLOBALS['auth']->logout();

    // Define the three sets of links in the template
    $GLOBALS['template']->links('none', 'none');
    $GLOBALS['template']->assign('links_main', array(
        array('title' => $GLOBALS['lang']['login'], 'url' => $GLOBALS['conf']['url'].'/admin/login.php')
        ));

    // Assign the page's title and the content template that needs to be used
    $GLOBALS['template']->assign('title', $GLOBALS['lang']['logout']);
    $GLOBALS['template']->assign('content', 'admin_logout.tpl');

    // Display the output
    $GLOBALS['template']->display('admin.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();

?>