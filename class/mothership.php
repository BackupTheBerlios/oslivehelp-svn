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
    // This class contains functions that contact HCL's servers for info, such as
    // latest version info.

    class MotherShip {

        var $current;

        function check_updates()
        {
            if (function_exists('file_get_contents')) {
                $this->current = @file_get_contents('http://www.helpcenterlive.com/updates/current.php');
                if ($this->current !== $GLOBALS['conf']['version']) {
                    return true;
                } else {
                    return false;
                }
            }
                
        }

    }

?>