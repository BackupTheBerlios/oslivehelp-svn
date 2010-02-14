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
    // This class contains all the Hot Page functions

    class Hotpage {

        var $result;
        var $hotpage;
        
        function check($page)
        {
            $this->hotpage = false;
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `hotpages` WHERE 1')) {
                foreach ($this->result as $key => $val) {
                    if (stristr($page, $this->result[$key]['page'])) {
                        $this->hotpage = true;
                    }
                }
            }
            return $this->hotpage;
        }

        function listall()
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `hotpages` WHERE 1')) {
                return $this->result;
            } else {
                return false;
            }
        }

        function get($id)
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `hotpages` WHERE `id`="'.$id.'"')) {
                return $this->result[0];
            } else {
                return false;
            }
        }

        function insert($page)
        {
            if ($page === '') $page = ' ';
            if ($GLOBALS['db']->query('INSERT INTO `hotpages` (`page`) VALUES ("'.$page.'")')) {
                return true;
            } else {
                return false;
            }
        }

        function update($id, $page)
        {
            if ($id === '') return false;
            if ($page === '') $page =' ';
            if ($GLOBALS['db']->query('UPDATE `hotpages` SET `page`="'.$page.'" WHERE `id`="'.$id.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function delete($id)
        {
            $GLOBALS['db']->query('DELETE FROM `hotpages` WHERE `id`="'.$id.'"');
        }

    }

?>