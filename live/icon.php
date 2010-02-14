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
    // This file displays the chat icon 

    include_once('../class/include.php');
    $inc = new Includer();
    $inc->image();
    $inc->operator();
    $inc->live();

    if (!isset($_GET['departmentid'])) {
        $_GET['departmentid'] = '0';
    }

    if (isset($_GET['picture']) && isset($_GET['id'])) {
        header('Content-Type: image/gif');
        echo($GLOBALS['operator']->picture(addslashes($_GET['id'])));
    } else {
        if (isset($_GET['status'])) {
            $icon = $GLOBALS['image']->icon(addslashes($_GET['status']), addslashes($_GET['departmentid']));
        } else {
            if ($GLOBALS['live']->status_department(addslashes($_GET['departmentid']))) {
                $icon = $GLOBALS['image']->icon('online', addslashes($_GET['departmentid']));
            } else {
                $icon = $GLOBALS['image']->icon('offline', addslashes($_GET['departmentid']));
            }
        }
        header($icon['header']);
        @readfile($icon['file']);
    }

    // do events that need to be done at the end of the file
    $inc->finished();

?>