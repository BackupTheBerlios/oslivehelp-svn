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
    // This file forces the download of a transferred file

    include_once('../../class/include.php');
    $inc = new Includer();
    $inc->db();
    $inc->file();

    if (isset($_GET['admin'])) {
        $admin = 'true';
    } else {
        $admin = 'false';
    }

    if (isset($_GET['client'])) {
        $client = 'true';
    } else {
        $client = 'false';
    }

    if (isset($_GET['id'])) {
        if ($files = $GLOBALS['file']->get($admin, addslashes($_GET['id']), $client)) {
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: private");
			header('Content-Type: '.$files['type']);
            header('Content-Length: '.strlen($files['file']));
            header('Content-Disposition: attachment; filename='.$files['name']);
            echo($files['file']);
        }
    } else {
        if (isset($_SESSION['hcl_'.addslashes($_GET['chatid'])]['fileid'])) {
            if ($files = $GLOBALS['file']->get($admin, $_SESSION['hcl_'.addslashes($_GET['chatid'])]['fileid'], $client)) {
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: private");
				header('Content-Type: '.$files['type']);
           	 	header('Content-Length: '.strlen($files['file']));
            	header('Content-Disposition: attachment; filename='.$files['name']);
                echo($files['file']);
            }
        }
    }
    
    // do events that need to be done at the end of the file
    $inc->finished();

?>