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
    // This file allows administration of hot pages

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->template();
    $inc->module();

    // Check to see if the person is not logged in
    $GLOBALS['auth']->check_logout();

    // Define the three sets of links in the template
    if (isset($_GET['type'])) {
        if ($_GET['type'] == 'operator') {
            if ($GLOBALS['auth']->operator()) {
                $GLOBALS['template']->links('operator', 'operator');
            }
        } elseif ($_GET['type'] == 'admin') {
            if ($GLOBALS['auth']->admin()) {
                $GLOBALS['template']->links('admin', 'admin');
            }
        } elseif ($_GET['type'] == 'conf') {
            if ($GLOBALS['auth']->admin()) {
                $GLOBALS['template']->links('conf', 'conf');
            }
        }
    } else {
        if ($GLOBALS['auth']->operator()) {
            $GLOBALS['template']->links('operator', 'operator');
        }
    }

    $GLOBALS['template']->assign('links_main', '');

    $GLOBALS['template']->assign('modules', $GLOBALS['module']->links(addslashes($_GET['type']), 'true'));

    // Assign the page's title and the content template that needs to be used
    $GLOBALS['template']->assign('title', $GLOBALS['lang']['modules']);
    $GLOBALS['template']->assign('content', 'admin_modules.tpl');

    // Include the javascript files
    $GLOBALS['template']->assign('javascript', 'misc&monitor');

    // Display the output
    $GLOBALS['template']->display('admin.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>