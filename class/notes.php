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
    // This class contains functions to add, edit and delete notes

    define('HCL_NOTES_NL2BR', 1);
    define('HCL_NOTES_BR2NL', 2);
    define('HCL_NOTES_HTML_FRIENDLY', 4);

    class Notes {

        var $result;

        // This fetches all the notes an operator has and returns them in an array
        function fetch($id = '', $column = '', $action = '', $action2 = '')
        {

            $this->result = $GLOBALS['db']->query('SELECT * FROM `notes` WHERE `operatorid`="'.$GLOBALS['operator']->id().'" ORDER BY `timestamp` DESC');

            if (!$this->result) {
                return false;
            } else {

                foreach($this->result as $key => $val) {
                    $this->result[$key]['time'] = gmdate('G:i:s D jS F Y', $this->result[$key]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                }

                switch ($action) {
                    case HCL_NOTES_NL2BR:
                        foreach ($this->result as $key => $val) {
                            if ($column !== '') {
                                $this->result[$key][$column] = nl_br($this->result[$key][$column]);
                            } else {
                                foreach ($this->result[$key] as $key2 => $val2) {
                                    $this->result[$key][$key2] = nl_br($val2);
                                }
                            }
                        }
                        break;
                    case HCL_NOTES_BR2NL:
                        foreach ($this->result as $key => $val) {
                            if ($column !== '') {
                                $this->result[$key][$column] = br_nl($this->result[$key][$column]);
                            } else {
                                foreach ($this->result[$key] as $key2 => $val2) {
                                    $this->result[$key][$key2] = br_nl($val2);
                                }
                            }
                        }
                        break;
                }

                switch ($action2) {
                    case HCL_NOTES_HTML_FRIENDLY:
                        foreach ($this->result as $key => $val) {
                            if ($column !== '') {
                                $this->result[$key][$column] = htmlspecialchars($this->result[$key][$column]);
                            } else {
                                foreach ($this->result[$key] as $key2 => $val2) {
                                    $this->result[$key][$key2] = htmlspecialchars($val2);
                                }
                            }
                        }
                        break;
                }

                if ($id !== '') {
                    if ($column !== '') {
                        $this->result = $this->result[key_from_match_val2($this->result, $id)][$column];
                    } else {
                        $this->result = $this->result[key_from_match_val2($this->result, $id)];
                    }
                }

                return $this->result;
            }
        }

        function add($subject, $message)
        {
            $message = nl_br($message);
            $operatorid = $GLOBALS['operator']->id();
            if ($operatorid === '') $operatorid = ' ';
            if ($subject === '') $subject = ' ';
            if ($message === '') $message = ' ';
            $GLOBALS['db']->query('INSERT INTO `notes` (`timestamp`,`operatorid`, `subject`, `message`) VALUES (UNIX_TIMESTAMP(), "'.$operatorid.'", "'.$subject.'", "'.$message.'")');
        }

        function update($id, $subject, $message)
        {
            $message = nl_br($message);
            if ($subject === '') $subject = ' ';
            if ($message === '') $message = ' ';
            $GLOBALS['db']->query('UPDATE `notes` SET `timestamp`=UNIX_TIMESTAMP(), `subject`="'.$subject.'", `message`="'.$message.'" WHERE `id`="'.$id.'"');
        }

        function delete($id) {
            $GLOBALS['db']->query('DELETE FROM `notes` WHERE `id`="'.$id.'"');
        }

    }

?>