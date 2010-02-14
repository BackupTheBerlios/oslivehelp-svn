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
    // This file is a wrapper for the module files 

    include_once('./class/include.php');
    global $inc;
    $inc = new Includer();
    $inc->template();
    $inc->module();

    if ($GLOBALS['module']->exists(addslashes($_GET['module']))) {
        if ($GLOBALS['module']->active(addslashes($_GET['module']))) {
            $GLOBALS['module']->get_config(addslashes($_GET['module']));
            // Get the main file if one isn't specified
            if (!isset($_GET['file'])) {
                $_GET['file'] = $GLOBALS['conf']['modules'][addslashes($_GET['module'])]['user'];
            }
            // Display the output
            if (file_exists(dirname(__FILE__).'/templates/'.$GLOBALS['conf']['template'].'/'.'mod_'.addslashes($_GET['module']).'.tpl')) {
                $GLOBALS['template']->assign('title', $GLOBALS['conf']['modules'][addslashes($_GET['module'])]['title']);
                $GLOBALS['template']->assign('content', 'module.tpl');
                $GLOBALS['template']->display('mod_'.addslashes($_GET['module']).'.tpl');
            } else {
                $GLOBALS['template']->display('module.tpl');
            }
        } else {
            echo($GLOBALS['lang']['mod_not_active']);
        }
    } else {
        echo($GLOBALS['lang']['mod_doesnt_exist']);
    }

    // do events that need to be done at the end of the file
    $inc->finished();
    
?>