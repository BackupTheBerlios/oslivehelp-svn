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
    // This file contains the setup routine 

    include_once('../class/include.php');

    if (isset($_POST['four']) || isset($_POST['five'])) {
        $inc = new Includer('setup_db');
    } else {
        $inc = new Includer('setup');
    }

    $inc->template();
    $inc->file();
    $inc->setup();

    if (isset($_POST['one'])) {
        if (isset($_POST['submit'])) {
            $GLOBALS['setup']->language(addslashes($_POST['language']));
        }
        // Create the .htaccess files as some FTP clients cannot see them and therefore
        // do not upload them.
        if (!file_exists(dirname(__FILE__).'/../cache/.htaccess')) {
            $GLOBALS['file']->create(dirname(__FILE__).'/../cache/.htaccess', "<Limit GET POST>\ndeny from all\n</Limit>");
        }
        if (!file_exists(dirname(__FILE__).'/../compile/.htaccess')) {
            $GLOBALS['file']->create(dirname(__FILE__).'/../compile/.htaccess', "<Limit GET POST>\ndeny from all\n</Limit>");
        }
        $GLOBALS['template']->assign('step', '2');
    } elseif (isset($_POST['two'])) {
        if (isset($_POST['submit'])) {
            $GLOBALS['setup']->conf(addslashes($_POST['host']), addslashes($_POST['database']), addslashes($_POST['username']), addslashes($_POST['password']), addslashes($_POST['prefix']), addslashes($_POST['url']), addslashes($_POST['monitor_traffic']), addslashes($_POST['template']), addslashes($_POST['company']), $_SESSION['hcl_language']);
        }
        $GLOBALS['template']->assign('step', '3');
    } elseif (isset($_POST['three'])) {
        $GLOBALS['template']->assign('install_upgrade', addslashes($_POST['install_upgrade']));
        $GLOBALS['template']->assign('step', '4');
    } elseif (isset($_POST['four'])) {
        if (isset($_POST['submit'])) {
            if ($_POST['install_upgrade'] == 'install') {
                $GLOBALS['setup']->install_db();
            } else {
                $GLOBALS['setup']->upgrade_db();
            }
        }
        @chmod(dirname(__FILE__).'/../config.php', 0644);
        if ($GLOBALS['file']->check_write(dirname(__FILE__).'/../config.php')) {
            $GLOBALS['template']->assign('step', '5');
        } else {
            $GLOBALS['template']->assign('step', '6');
        }
    } elseif (isset($_POST['five'])) {
        if (isset($_POST['skip'])) {
            $GLOBALS['template']->assign('step', '6');
        } else {
            @chmod(dirname(__FILE__).'/../config.php', 0644);
            if ($GLOBALS['file']->check_write(dirname(__FILE__).'/../config.php')) {
                $GLOBALS['template']->assign('step', '5');
            } else {
                $GLOBALS['template']->assign('step', '6');
            }
        }
    } else {
        $GLOBALS['template']->assign('step', '1');
    }

    if (isset($_SESSION['hcl_language'])) {
        $GLOBALS['template']->assign('lang_file', $_SESSION['hcl_language']);
    } else {
        $GLOBALS['template']->assign('lang_file', 'english.php');
    }

    $GLOBALS['template']->assign('template_dir', 'Bliss');
    #Small hack to fix an issue with HTTP_HOST not being defined
    error_reporting (0);
    if (@isset($_SERVER['HTTP_HOST']))
    @$GLOBALS['template']->assign('url', 'http://'.$_SERVER['HTTP_HOST'].substr(strrev(strstr(strrev(substr(strrev(strstr(strrev($_SERVER['PHP_SELF']), '/')), 0, -1)), '/')), 0, -1));
    else
    @$GLOBALS['template']->assign('url', $conf['url']);    
    error_reporting (E_ALL);
    #End hack
    
    $GLOBALS['template']->assign('language', $GLOBALS['setup']->language());
    $GLOBALS['template']->assign('template', $GLOBALS['setup']->template());

    // Display the output
    $GLOBALS['template']->display('setup.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>