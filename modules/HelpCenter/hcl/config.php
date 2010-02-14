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
    // This file allows Help Center to integrate into Help Center Live 

    // This sets HCL to use the module or not.
    $GLOBALS['conf']['modules']['HelpCenter']['active'] = true;

    // This sets HCL to display the module in the main admin links.
    $GLOBALS['conf']['modules']['HelpCenter']['display'] = false;

    // The title of the module.
    $GLOBALS['conf']['modules']['HelpCenter']['title'] = 'Help Center';

    // The main file that will be accessible by end users.
    $GLOBALS['conf']['modules']['HelpCenter']['user'] = '/modules/HelpCenter/index.php';

    // The main file that will be accessible by the operators.
    $GLOBALS['conf']['modules']['HelpCenter']['operator'] = '';
    
    // The main file that will be accessible by the administators.
    $GLOBALS['conf']['modules']['HelpCenter']['admin'] = '';
    
    // The main file that will be accessible in the config settings menu.
    $GLOBALS['conf']['modules']['HelpCenter']['conf'] = '';

    // The url to the module
    $GLOBALS['conf']['modules']['HelpCenter']['realurl'] = $GLOBALS['conf']['url'].'/modules/HelpCenter/';
    
    if (isset($_GET['type'])) {
        $GLOBALS['conf']['modules']['HelpCenter']['url'] = $GLOBALS['conf']['url'].'/admin/module.php?module=HelpCenter&type='.addslashes($_GET['type']).'&file=/modules/HelpCenter/';
    } else {
        $GLOBALS['conf']['modules']['HelpCenter']['url'] = $GLOBALS['conf']['url'].'/module.php?module=HelpCenter&file=';
    }


    // Get the PHP_SELF with GET vars
    $args = '';
    $getargs = '';
    foreach ($_GET as $key => $val) {
        if ($key == 'module' || $key == 'file' || $key == 'type') {
            $getargs .= '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
            if ($args == '') {
                $args .= '?'.$key.'='.$val;
            } else {
                $args .= '&'.$key.'='.$val;
            }
        }
    }

    $GLOBALS['conf']['modules']['HelpCenter']['selfurl'] = $_SERVER['PHP_SELF'].$args;

    // Used for when forms are posted via GET
    $GLOBALS['conf']['modules']['HelpCenter']['getargs'] = $getargs;

?>