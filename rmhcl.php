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
    // This file is used for deleting the cache and compile directories as
    // they are created by different uid's - therefore sometimes not allowing
    // users to delete them via FTP 

    // Set this to true if you want to remove the cache and compile
    // directories

    $allow_rm = false;

    function rmrf($dir, $sub = false) {
        if (file_exists($dir)) {
            if ($dp = @opendir($dir)) {
                while (false !== ($file = readdir($dp))) {
                    if ($file !== '.' && $file !== '..') {
                        if (!is_dir($file)) {
                            if (substr($file, -4, 4) == '.dbc' || substr($file, -4, 4) == '.php') {
                                unlink($dir.'/'.$file);
                            } else {
                                rmrf($dir.'/'.$file, true);
                            }
                        } else {
                            rmrf($dir.'/'.$file, true);
                        }
                    }
                }
                closedir($dp);
                if ($sub) {
                    rmdir($dir);
                }
            }
        }
    }

    if ($allow_rm) {
        rmrf(dirname(__FILE__).DIRECTORY_SEPARATOR.'cache');
        rmrf(dirname(__FILE__).DIRECTORY_SEPARATOR.'compile');
        echo('cache and compile directories removed.');
    } else {
        echo('You need to set $allow_rm to true in this file first.');
    }

?>