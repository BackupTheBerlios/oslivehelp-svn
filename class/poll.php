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
    // This polling file finds an operator for the chat session.
    // It balances the load so that the operator with the minimum number of chats gets
    // the request. If there are two operators with the same least number of chats then
    // it selects the one with the lowest polling number. It also checks to make sure
    // that the chat request is not sent to the same operator it was sent to last.

    class Poll {

        var $result;
        var $result2;
        var $assignid;
        var $count;
        var $top_count;
        var $polled;
        var $live_idle;

        function getoperator($departmentid, $chatid)
        {
            // get the current polling method
            switch ($GLOBALS['conf']['poll']) {

            // if making custom polling methods, put a new case statement here and make sure
            // $this->assignid contains the row id from the assigns table of the operator
            // that is next to be polled by the end of the procedure.

            case 'load_balanced':
            default:
                    // Select the operator with the least chats.
                    // First we need to make a really high number of chats that an operator can't possibly have
                    // then lower it with each count.
                    // $_SESSION['hcl_polling'] = array(); - don't think this is needed any more
                    $this->live_idle = time() - $GLOBALS['conf']['live_timeout'];
                    $this->top_count = 1000000;
                    $this->assignid = 0;
                    // Find all the operators in the department the chat is being requested
                    if ($this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `departmentid`="'.$departmentid.'" ORDER BY `poll` ASC')) {
                        // loop through them all in order of their polling number
                        foreach ($this->result as $key => $val) {
                            // this operator has not been polled before
                            $this->polled = false;
                            // if there are any previous operators that have been polled, find them
                            if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `polling` WHERE `chatid`="'.$chatid.'"')) {
                                // loop through the operators that have already been polled to see if this one is in there
                                foreach ($this->result2 as $key2 => $val2) {
                                    if ($this->result2[$key2]['assignid'] == $this->result[$key]['id']) {
                                        // the operator has been polled before
                                        $this->polled = true;
                                    }
                                }
                            }
                            // find the current operator's information
                            $this->result2 = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$this->result[$key]['operatorid'].'"');
                            // if they have not been polled before, and are online and not idle..
                            if (!$this->polled && $this->result2[0]['status'] == '1' && $this->result2[0]['timestamp'] > $this->live_idle) {
                                // count the number of active chat sessions they have at the moment
                                if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `operatorid`="'.$this->result[$key]['operatorid'].'"')) {
                                    $this->count = count($this->result2);
                                } else {
                                    $this->count = 0;
                                }
                                // if their number of active chat sessions is less that the current lowest, change
                                // the next polled operator to be this operator
                                if ($this->count < $this->top_count) {
                                    $this->top_count = $this->count;
                                    $this->assignid = $this->result[$key]['id'];
                                }
                            }
                        }
                    }
                    // if there is an operator ready to be polled..
                    if ($this->assignid !== 0) {
                        // set them to have been polled so they wont be polled again
                        $GLOBALS['db']->query('INSERT INTO `polling` (`chatid`, `assignid`) VALUES ("'.$chatid.'", "'.$this->assignid.'")');
                    }
                    break;
            }

            // if there is an operator ready to be polled..
            if ($this->assignid !== 0) {
                // update their stats
                $GLOBALS['stats']->poll($chatid, $this->assignid);
            // otherwise end the chat request
            } else {
                $this->result = $GLOBALS['db']->query('DELETE FROM `sessions` WHERE `chatid`="'.$chatid.'"');
            }
            
            // return the next operator to be polled
            return $this->assignid;
        }

        function nextoperator($chatid)
        {
            $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"');
            // get the next operator and return their assignid
            return $this->getoperator($this->result[0]['departmentid'], $chatid);
        }

    }

?>