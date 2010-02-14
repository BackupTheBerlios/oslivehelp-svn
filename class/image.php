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
    // This class contains functions used to display images

    class Image {

        var $icon;

        function icon($status, $departmentid = '')
        {
            if ($departmentid !== '' && file_exists(dirname(__FILE__).'/../icons/'.$status.'_'.$departmentid.'.gif')) {
                $this->icon = array('header' => 'Content-type: image/gif', 'file' => dirname(__FILE__).'/../icons/'.$status.'_'.$departmentid.'.gif');
            } elseif ($departmentid !== '' && file_exists(dirname(__FILE__).'/../icons/'.$status.'_'.$departmentid.'.jpg')) {
                $this->icon = array('header' => 'Content-type: image/jpeg', 'file' => dirname(__FILE__).'/../icons/'.$status.'_'.$departmentid.'.jpg');
            } elseif ($departmentid !== '' && file_exists(dirname(__FILE__).'/../icons/'.$status.'_'.$departmentid.'.png')) {
                $this->icon = array('header' => 'Content-type: image/png', 'file' => dirname(__FILE__).'/../icons/'.$status.'_'.$departmentid.'.png');
            } elseif ($departmentid !== '' && file_exists(dirname(__FILE__).'/../icons/'.$status.'_'.$departmentid.'.swf')) {
                $this->icon = array('header' => 'Content-type: application/x-shockwave-flash', 'file' => dirname(__FILE__).'/../icons/'.$status.'_'.$departmentid.'.swf');
            } else {
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'.gif')) {
                    $this->icon = array('header' => 'Content-type: image/gif', 'file' => dirname(__FILE__).'/../icons/'.$status.'.gif');
                } elseif (file_exists(dirname(__FILE__).'/../icons/'.$status.'.jpg')) {
                    $this->icon = array('header' => 'Content-type: image/jpeg', 'file' => dirname(__FILE__).'/../icons/'.$status.'.jpg');
                } elseif (file_exists(dirname(__FILE__).'/../icons/'.$status.'.png')) {
                    $this->icon = array('header' => 'Content-type: image/png', 'file' => dirname(__FILE__).'/../icons/'.$status.'.png');
                } elseif (file_exists(dirname(__FILE__).'/../icons/'.$status.'.swf')) {
                    $this->icon = array('header' => 'Content-type: application/x-shockwave-flash', 'file' => dirname(__FILE__).'/../icons/'.$status.'.swf');
                }
            }
            return $this->icon;
        }

    }

?>