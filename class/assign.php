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
    // This class contains functions related to the assigning of operators to departments

    class Assign {

        var $result;
        var $result2;
        var $poll;

        function delete($assignid)
        {
            $GLOBALS['db']->query('DELETE FROM `assigns` WHERE `id`="'.$assignid.'"');
        }

        function delete_department($departmentid)
        {
            $GLOBALS['db']->query('DELETE FROM `assigns` WHERE `departmentid`="'.$departmentid.'"');
        }

        function delete_operator($operatorid)
        {
            $GLOBALS['db']->query('DELETE FROM `assigns` WHERE `operatorid`="'.$operatorid.'"');
        }

        function insert($operatorid, $departmentid, $poll)
        {
            if ($departmentid === "") $departmentid='0';
            if ($operatorid === "") $operatorid='0';
            if ($poll === "") $poll='0';
        	if ($GLOBALS['db']->query('INSERT INTO `assigns` (`departmentid`, `operatorid`, `poll`) VALUES ("'.$departmentid.'", "'.$operatorid.'", "'.$poll.'")')) {
                return true;
            } else {
                return false;
            }
        }

        function listall()
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `departments` WHERE 1')) {
                foreach ($this->result as $key => $val) {
                    if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `departmentid`="'.$this->result[$key]['id'].'" ORDER BY `poll` DESC LIMIT 1')) {
                        $this->poll = $this->result2[0]['poll'];
                    } else {
                        $this->poll = -1;
                    }
                    $this->result[$key]['operators'] = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE 1');
                    foreach ($this->result[$key]['operators'] as $key2 => $val2) {
                        if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `departmentid`="'.$this->result[$key]['id'].'" AND `operatorid`="'.$this->result[$key]['operators'][$key2]['id'].'" ORDER BY `poll` ASC')) {
                            $this->result[$key]['operators'][$key2]['checked'] = 'true';
                            $this->result[$key]['operators'][$key2]['poll'] = $this->result2[0]['poll'];
                        } else {
                            $this->result[$key]['operators'][$key2]['checked'] = 'false';
                            $this->poll = $this->poll + 1;
                            $this->result[$key]['operators'][$key2]['poll'] = $this->poll;
                        }
                    }
                }
                return $this->result;
            } else {
                return false;
            }
        }

    }

?>