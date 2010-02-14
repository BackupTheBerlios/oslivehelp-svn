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
    // This class contains all the functions that will be used to read and write to files

    class Live {

        var $result;
        var $result2;
        var $results;
        var $live_idle;
        var $status;
        var $avaliable;
        var $assignid;
        var $operatorid;
        var $response;
        var $ip;
        var $hostname;
        var $new_visit;

        function chatid($id = '')
        {
            if ($id !== '') {
                setcookie('hcl_chatid', $id, time() + 360000000, '/');
                $_SESSION['hcl_chatid'] = $id;
                if (isset($_SESSION['hcl_chatid'])) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (!isset($_COOKIE['hcl_chatid']) && !isset($_SESSION['hcl_chatid'])) {
                    return false;
                } else {
                    if (isset($_COOKIE['hcl_chatid']) && !isset($_SESSION['hcl_chatid'])) {
                        $_SESSION['hcl_chatid'] = $_COOKIE['hcl_chatid'];
                    }
                    return $_SESSION['hcl_chatid'];
                }
            }
        }

        function status_department($departmentid = '')
        {
            $this->status = false;
            $this->live_idle = time() - $GLOBALS['conf']['live_timeout'];

            // If department specified then find operators' status for that department only,
            // otherwise grab all operators' status.
            if ($departmentid !== '') {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `departmentid`="'.$departmentid.'"');
            } else {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE 1');
            }
            if ($this->result) {
                foreach ($this->result as $key => $val) {
                    if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$this->result[$key]['operatorid'].'"')) {
                        if ($this->result2[0]['status'] == '1' && $this->result2[0]['timestamp'] >= $this->live_idle) {
                            $this->status = true;
                        }
                    }
                }
            }
            return $this->status;
        }

        function status_operator($operatorid = '')
        {
            $this->status = false;
            $this->live_idle = time() - $GLOBALS['conf']['live_timeout'];

            // If operator specified then find that operator's status only,
            // otherwise grab all operators' status.
            if ($operatorid !== '') {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `operatorid`="'.$operatorid.'"');
            } else {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE 1');
            }
            if ($this->result) {
                foreach ($this->result as $key => $val) {
                    if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$this->result[$key]['operatorid'].'"')) {
                        if ($this->result2[0]['status'] == '1' && $this->result2[0]['timestamp'] >= $this->live_idle) {
                            $this->status = true;
                        }
                    }
                }
            }
            return $this->status;
        }

        function avaliable_department($departmentid = '', $option = '')
        {
            $this->live_idle = time() - $GLOBALS['conf']['live_timeout'];
            if ($departmentid !== '') {
                if ($this->status_department($departmentid)) {
                    $this->avaliable = array(array('id' => $departmentid, 'email' => $GLOBALS['department']->email($departmentid), 'name' => $GLOBALS['department']->name($departmentid), 'status' => 'true'));
                } else {
                    $this->avaliable = array(array('id' => $departmentid, 'email' => $GLOBALS['department']->email($departmentid), 'name' => $GLOBALS['department']->name($departmentid), 'status' => 'false'));
                }
            } else {
                $this->avaliable = array();
                if ($option == 'all') {
                    $this->results = $GLOBALS['db']->query('SELECT * FROM `departments` WHERE 1 ORDER BY `order` ASC');
                } else {
                    $this->results = $GLOBALS['db']->query('SELECT * FROM `departments` WHERE `visible`="1" ORDER BY `order` ASC');
                }
                if ($this->results) {
                    foreach ($this->results as $key => $val) {
                        if ($this->status_department($this->results[$key]['id'])) {
                            $status = 'true';
                        } else {
                            $status = 'false';
                        }
                        $this->avaliable = array_merge($this->avaliable, array(array('id' => $this->results[$key]['id'], 'email' => $this->results[$key]['email'], 'name' => $this->results[$key]['name'], 'status' => $status)));
                    }
                }
            }
            if ($option == 'all') {
                foreach ($this->avaliable as $key => $val) {
                    $this->result[$key]['operators'] = array();
                    if ($this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `departmentid`="'.$this->avaliable[$key]['id'].'" ORDER BY `poll` ASC')) {
                        foreach ($this->result as $key2 => $val2) {
                            $this->result2 = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$this->result[$key2]['operatorid'].'"');
                            $this->avaliable[$key]['operators'][$key2]['id'] = $this->result2[0]['id'];
                            $this->avaliable[$key]['operators'][$key2]['name'] = $GLOBALS['operator']->name($this->result2[0]['id']);
                            if ($this->result2[0]['status'] == '1' && $this->result2[0]['timestamp'] >= $this->live_idle) {
                                $this->avaliable[$key]['operators'][$key2]['status'] = 'true';
                            } else {
                                $this->avaliable[$key]['operators'][$key2]['status'] = 'false';
                            }
                        }
                    }
                }
            }
            return $this->avaliable;
        }

        function avaliable_operator($operatorid = '', $option = '')
        {
            $this->live_idle = time() - $GLOBALS['conf']['live_timeout'];
            if ($operatorid !== '') {
                if ($this->status_operator($operatorid)) {
                    $this->avaliable = array(array('id' => $operatorid, 'username' => $GLOBALS['operator']->username($operatorid), 'name' => $GLOBALS['operator']->name($operatorid), 'status' => 'true'));
                } else {
                    $this->avaliable = array(array('id' => $operatorid, 'username' => $GLOBALS['operator']->username($operatorid), 'name' => $GLOBALS['operator']->name($operatorid), 'status' => 'false'));
                }
            } else {
                $this->avaliable = array();
                if ($this->results = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE 1 GROUP BY `operatorid`')) {
                    foreach ($this->results as $key => $val) {
                        if ($this->status_operator($this->results[$key]['operatorid'])) {
                            $this->avaliable = array_merge($this->avaliable, array(array('id' => $this->results[$key]['operatorid'], 'username' => $GLOBALS['operator']->username($this->results[$key]['operatorid']), 'name' => $GLOBALS['operator']->name($this->results[$key]['operatorid']), 'status' => 'true')));
                        } else {
                            $this->avaliable = array_merge($this->avaliable, array(array('id' => $this->results[$key]['operatorid'], 'username' => $GLOBALS['operator']->username($this->results[$key]['operatorid']), 'name' => $GLOBALS['operator']->name($this->results[$key]['operatorid']), 'status' => 'false')));
                        }
                    }
                }
            }
            return $this->avaliable;
        }

        function nick($nick = '')
        {
            if ($nick !== '') {
                setcookie('hcl_nick', $nick, time() + 360000000, '/');
            } else {
                if (isset($_COOKIE['hcl_nick'])){
                    return $_COOKIE['hcl_nick'];
                } else {
                    return '';
                }
            }
            return false;
        }

        function request($departmentid, $nick, $chatid)
        {
            if ($nick == '') {
                if (isset($_COOKIE['hcl_nick'])){
                    $nick = $_COOKIE['hcl_nick'];
                } else {
                    $nick = 'Guest';
                }
            }
            if ($departmentid == '') {
                echo('Error: $departmentid must be passed into $GLOBALS[\'live\']->request();');
            }
            $GLOBALS['db']->query('DELETE FROM `cobrowse` WHERE chatid="'.$chatid.'"');
            $GLOBALS['db']->query('DELETE FROM `polling` WHERE `chatid`="'.$chatid.'"');
            $GLOBALS['db']->query('UPDATE `traffic` SET `requests`=`requests`+1 WHERE `id`="'.$chatid.'"');
            $this->assignid = $GLOBALS['poll']->getoperator($departmentid, $chatid);
            $this->result = $GLOBALS['db']->query('SELECT `operatorid` FROM `assigns` WHERE `id`="'.$this->assignid.'"');
            $this->operatorid = $this->result[0]['operatorid'];
            $GLOBALS['db']->query('DELETE FROM `sessions` WHERE `chatid`="'.$chatid.'"');
            $GLOBALS['db']->query('INSERT INTO `sessions` (chatid,assignid,operatorid,departmentid,nick,timeo,timeg,alert) VALUES ("'.$chatid.'", "'.$this->assignid.'", "'.$this->operatorid.'", "'.$departmentid.'", "'.$nick.'", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), "request")');
            $GLOBALS['stats']->request($chatid);
        }

        function initiate($chatid)
        {
            if ($GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" AND `alert`="initiated"')) {
                $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="request" WHERE `chatid`="'.$chatid.'"');
                $GLOBALS['db']->query('UPDATE `traffic` SET `requests`=`requests`+1 WHERE `id`="'.$chatid.'"');
                $GLOBALS['stats']->request($chatid);
            }
        }

        function opchat($operatorid_to, $operatorid_from)
        {
            $GLOBALS['db']->query('DELETE FROM `chat` WHERE `chatid`="'.$this->chatid().'" AND `operatorid`="0"');
            $GLOBALS['db']->query('INSERT INTO `sessions` (chatid,assignid,departmentid,operatorid,nick,timeo,timeg,alert) VALUES ("'.$this->chatid().'", "0", "0", "'.$operatorid_to.'", "'.$GLOBALS['operator']->name($operatorid_from).'", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), "opchat")');
            $GLOBALS['stats']->opchat($GLOBALS['operator']->id());
        }

        function newvisitor()
        {
            if (!$this->chatid() || !$GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `id`="'.$this->chatid().'"')) {
                $GLOBALS['db']->query('INSERT INTO `traffic` (`timestamp`) VALUES (UNIX_TIMESTAMP())');
                $this->chatid($GLOBALS['db']->id());
            }
        }

        function response($array)
        {
            $this->response = '|';
            $this->ip = $_SERVER['REMOTE_ADDR'];
            $this->hostname = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $this->new_visit = time() - $GLOBALS['conf']['traffic_newvisit'];

            // Set cobrowse page
            if (isset($array['footprint'])) {
                $GLOBALS['cobrowse']->changepage($this->chatid(), $array['page']);
            }

            // Check to see if we have assigned the visitor a chatid already.
            // if not, they are new visitor.
            if (!$this->chatid() || !$GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `id`="'.$this->chatid().'"')) {
                $GLOBALS['db']->query('INSERT INTO `traffic` (`timestamp`, `ip`, `hostname`, `useragent`, `referrer`, `requests`, `visits`, `start`) VALUES (UNIX_TIMESTAMP(), "'.$this->ip.'", "'.$this->hostname.'", "'.str_replace('|', '/', $array['useragent']).'", "'.$array['refurl'].'", "0", "1", UNIX_TIMESTAMP())');
                $this->chatid($GLOBALS['db']->id());
                $GLOBALS['stats']->visit($this->chatid(), str_replace('|', '/', $array['useragent']));
                $GLOBALS['stats']->hit($this->chatid());
                $GLOBALS['stats']->referrer($this->chatid(), $array['refurl']);
                $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE 1');
                foreach ($this->result as $key => $val) {
                    $GLOBALS['db']->query('INSERT INTO `activity` (`timestamp`, `operatorid`, `status`) VALUES (UNIX_TIMESTAMP(), "'.$this->result[$key]['id'].'", "newvisitor")');
                }
            } elseif ($GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `id`="'.$this->chatid().'" AND `start`="0"')) {
                $GLOBALS['db']->query('UPDATE `traffic` SET `timestamp`=UNIX_TIMESTAMP(), `ip`="'.$this->ip.'", `hostname`="'.$this->hostname.'", `useragent`="'.str_replace('|', '/', $array['useragent']).'", `referrer`="'.$array['refurl'].'", `requests`="0", `visits`="1", `start`=UNIX_TIMESTAMP() WHERE `id`="'.$this->chatid().'"');
                $GLOBALS['stats']->visit($this->chatid(), str_replace('|', '/', $array['useragent']));
                $GLOBALS['stats']->hit($this->chatid());
                $GLOBALS['stats']->referrer($this->chatid(), $array['refurl']);
                $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE 1');
                foreach ($this->result as $key => $val) {
                    $GLOBALS['db']->query('INSERT INTO `activity` (`timestamp`, `operatorid`, `status`) VALUES (UNIX_TIMESTAMP(), "'.$this->result[$key]['id'].'", "newvisitor")');
                }
            } else {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `id`="'.$this->chatid().'" AND `timestamp`<"'.$this->new_visit.'"')) {
                    $GLOBALS['db']->query('UPDATE `traffic` SET `hostname`="'.$this->hostname.'", `useragent`="'.str_replace('|', '/', $array['useragent']).'", `referrer`="'.$array['refurl'].'", `visits`="'.($this->result[0]['visits']+1).'", `start`=UNIX_TIMESTAMP() WHERE `id`="'.$this->chatid().'"');
                    $GLOBALS['db']->query('UPDATE `footprints` SET `current`="0" WHERE `chatid`="'.$this->chatid().'"');
                    $GLOBALS['db']->query('INSERT INTO `footprints` (`timestamp`, `chatid`, `page`) VALUES (UNIX_TIMESTAMP(), "'.$this->chatid().'", "'.$array['page'].'")');
                    $GLOBALS['stats']->visit($this->chatid(), str_replace('|', '/', $array['useragent']));
                    $GLOBALS['stats']->hit($this->chatid());
                    $GLOBALS['stats']->referrer($this->chatid(), $array['refurl']);
                    $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE 1');
                    foreach ($this->result as $key => $val) {
                        $GLOBALS['db']->query('INSERT INTO `activity` (`timestamp`, `operatorid`, `status`) VALUES (UNIX_TIMESTAMP(), "'.$this->result[$key]['id'].'", "newvisitor")');
                    }
                } else {
                    $this->result = $GLOBALS['db']->query('SELECT `page` FROM `footprints` WHERE `chatid`="'.$this->chatid().'" ORDER BY `id` DESC LIMIT 1');
                    if (isset($this->result) && $this->result[0]['page'] !== $array['page'] && isset($array['footprint'])) {
                        $GLOBALS['stats']->hit($this->chatid());
                        $GLOBALS['db']->query('INSERT INTO `footprints` (`timestamp`, `chatid`, `page`) VALUES (UNIX_TIMESTAMP(), "'.$this->chatid().'", "'.$array['page'].'")');
                        $this->result2 = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE 1');
                        foreach ($this->result2 as $key => $val) {
                            $GLOBALS['db']->query('INSERT INTO `activity` (`timestamp`, `operatorid`, `status`) VALUES (UNIX_TIMESTAMP(), "'.$this->result2[$key]['id'].'", "newpage")');
                            if ($GLOBALS['hotpage']->check($array['page'])) {
                                $GLOBALS['db']->query('INSERT INTO `activity` (`timestamp`, `operatorid`, `status`) VALUES (UNIX_TIMESTAMP(), "'.$this->result2[$key]['id'].'", "hotpage")');
                            }
                        }
                    }
                }
            }

            // Update the traffic time
            $GLOBALS['db']->query('UPDATE `traffic` SET `timestamp`=UNIX_TIMESTAMP() WHERE `id`="'.$this->chatid().'"');

            // Update the system so visitor does not timeout
            // The reason why the visitors time is updated in both system and traffic is because
            // the session needs to be deleted when the visitor times out so they are removed from
            // the traffic monitor however the time in the traffic table just needs to stop as it
            // will be used in the statistics.
            // So you're thinking what's the difference between the activity and system tables?
            // Well, activity is used to send information such as page changes etc to the operators
            // but system is just used to keep track of the visitors.. whether they are still active or
            // have timed out. Why not join them in the same table? One good reason.. once an activity
            // row has been read, it gets deleted so that it is not acted upon again, however the system
            // rows need to stay in place until the visitor leaves.

            if ($GLOBALS['db']->query('SELECT * FROM `system` WHERE `chatid`="'.$this->chatid().'"')) {
                $GLOBALS['db']->query('UPDATE `system` SET `timestamp`=UNIX_TIMESTAMP() WHERE `chatid`="'.$this->chatid().'"');
            } else {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE 1');
                foreach ($this->result as $key => $val) {
                    $GLOBALS['db']->query('INSERT INTO `system` (`timestamp`, `operatorid`, `chatid`) VALUES (UNIX_TIMESTAMP(), "'.$this->result[$key]['id'].'", "'.$this->chatid().'")');
                }
            }

            if (isset($array['cobrowse'])) {
                $GLOBALS['cobrowse']->avaliable($this->chatid());
            }

            if ($this->status_department($array['departmentid'])) {
                $this->response .= 'online|';
            } else {
                $this->response .= 'offline|';
            }

            // Check to see if anyone has initiated a chat with this visitor
            if ($GLOBALS['db']->query('SELECT `id` FROM `sessions` WHERE `chatid`="'.$this->chatid().'" AND `alert`="initiate"')) {
                $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="initiated" WHERE `chatid`="'.$this->chatid().'"');
                $this->response .= 'initiate|';
            }

            if ($GLOBALS['cobrowse']->changedpage($this->chatid())) {
                $this->response .= 'cobrowse|';
            }

            return $this->response;
        }

        function decline_initiate($id, $chatid)
        {
            $GLOBALS['db']->query('DELETE FROM `session` WHERE `chatid`="'.$chatid.'"');
            $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE 1');
            foreach ($this->result as $key => $val) {
                $GLOBALS['db']->query('INSERT INTO `activity` (`timestamp`, `operatorid`, `status`) VALUES (UNIX_TIMESTAMP(), "'.$this->result[$key]['id'].'", "declineinitiate")');
            }
        }

    }


?>