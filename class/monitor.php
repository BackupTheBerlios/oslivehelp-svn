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
    // This class contains all the authentication functions

    class Monitor {

        var $result;
        var $result2;
        var $sounds;
        var $onload;
        var $chats;
        var $visitors;
        var $idle;
        var $info;
        var $response;
        var $traffic_idle;
        var $live_idle;
        var $initiate_limit;
        var $chat_limit;
        var $new_time;
        var $assignid;
        var $operatorid;

        function Monitor()
        {
            $this->sounds = array();
            $this->chats = false;
            $this->visitors = false;
        }

        function chats($client = false)
        {
            if (!$this->chats) {
                $this->idle = time() - $GLOBALS['conf']['traffic_timeout'];
                if ($client) {
                    $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE (`alert`="transferring" OR `alert`="request" OR `alert`="received" OR `alert`="opchatreceived" OR `alert`="holding") AND `operatorid`="'.$GLOBALS['operator']->id().'" ORDER BY `id` DESC');
                } else {
                    $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE (`alert`="transferring" OR `alert`="request" OR `alert`="received" OR `alert`="opchatreceived") AND `operatorid`="'.$GLOBALS['operator']->id().'" ORDER BY `id` DESC');
                }
                if ($this->result) {
                    if ($this->onload !== '') {
                        $this->onload = 'window.focus();';
                    }
                    foreach ($this->result as $key => $val) {
                        switch ($this->result[$key]['alert']) {
                        case 'holding':
                            $this->result[$key]['type'] = 'holding';
                            $this->result[$key]['title'] = $GLOBALS['lang']['chat_request'];
                            $this->result[$key]['name'] = htmlspecialchars($this->result[$key]['nick']);
                            break;
                        case 'transferring':
                            $this->result[$key]['type'] = 'transfer';
                            $this->result[$key]['title'] = $GLOBALS['lang']['chat_transfer'];
                            $this->result[$key]['name'] = str_replace('OPERATOR', $GLOBALS['operator']->name($this->result[$key]['operatorid']), str_replace('GUEST', htmlspecialchars($this->result[$key]['nick']), $GLOBALS['lang']['chat_transferring']));
                            $this->sounds = array_merge($this->sounds, array(array('file' => 'transfer', 'text' => $GLOBALS['lang']['incoming_transfer'])));
                            break;
                        case 'request':
                        case 'received':
                            if ($this->result[$key]['assignid'] !== '0' && $this->result[$key]['departmentid'] !== '0') {
                                $this->result[$key]['type'] = 'chat';
                                $this->result[$key]['title'] = $GLOBALS['lang']['chat_request'];
                                $this->result[$key]['name'] = htmlspecialchars($this->result[$key]['nick']);
                                $this->sounds = array_merge($this->sounds, array(array('file' => 'request', 'text' => $GLOBALS['lang']['incoming_chat'])));
                            } else {
                                $this->result[$key]['type'] = 'opchat';
                                $this->result[$key]['title'] = $GLOBALS['lang']['chat_operator_request'];
                                $this->result[$key]['name'] = htmlspecialchars($GLOBALS['operator']->name($this->result[$key]['chatid']));
                                $this->sounds = array_merge($this->sounds, array(array('file' => 'opchat', 'text' => $GLOBALS['lang']['incoming_opchat'])));
                            }
                            break;
                        }
                    }
                }

                // Delete expired system activity so that monitor does not keep refreshing itself
                $GLOBALS['db']->query('DELETE FROM `system` WHERE `timestamp`<"'.$this->idle.'" AND `operatorid`="'.$GLOBALS['operator']->id().'"');

                $this->chats = true;
                return $this->result;
            } else {
                return false;
            }
        }

        function visitors()
        {
            if (!$this->visitors && $GLOBALS['conf']['monitor_traffic']) {
                $this->idle = time() - $GLOBALS['conf']['traffic_timeout'];
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `timestamp`>"'.$this->idle.'" ORDER BY `id` DESC')) {
                    foreach ($this->result as $key => $val) {
                        $this->result2 = $GLOBALS['db']->query('SELECT * FROM `footprints` WHERE `chatid`="'.$this->result[$key]['id'].'" ORDER BY `id` DESC LIMIT 1');
                        $this->result[$key]['page'] = $this->result2[0]['page'];
                        $this->result[$key]['pagestart'] = $this->result2[0]['timestamp'];
                        $this->result[$key]['keywords'] = $this->keywords($this->result[$key]['referrer']);
                        $this->result2 = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$this->result[$key]['id'].'" ORDER BY `id` DESC LIMIT 1');
                        if ($this->result2) {
                            $this->result[$key]['chatting'] = 'true';
                            $this->result[$key]['status'] = $GLOBALS['lang']['chatting'];
                            $this->result[$key]['chat_operator'] = $GLOBALS['operator']->name($this->result2[0]['operatorid']);
                            $this->result[$key]['chat_department'] = $GLOBALS['department']->name($this->result2[0]['departmentid']);
                        } else {
                            $this->result[$key]['chatting'] = 'false';
                            if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `hotpages` WHERE 1')) {
                                foreach ($this->result2 as $key2 => $val2) {
                                    if (stristr($this->result[$key]['page'], $this->result2[$key2]['page'])) {
                                        $this->result[$key]['status'] = $GLOBALS['lang']['on_hotpage'];
                                    } else {
                                        $this->result[$key]['status'] = $GLOBALS['lang']['browsing'];
                                    }
                                }
                            } else {
                                $this->result[$key]['status'] = $GLOBALS['lang']['browsing'];
                            }
                        }
                    }
                }
                if ($GLOBALS['db']->query('SELECT `id` FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'" AND `status`="newvisitor" AND `timestamp`>"'.$this->idle.'"')) {
                    $this->sounds = array_merge($this->sounds, array(array('file' => 'visitor', 'text' => '')));
                }
                if ($GLOBALS['db']->query('SELECT `id` FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'" AND `status`="hotpage" AND `timestamp`>"'.$this->idle.'"')) {
                    $this->sounds = array_merge($this->sounds, array(array('file' => 'hotpage', 'text' => '')));
                }

                // Delete expired system activity so that monitor does not keep refreshing itself
                $GLOBALS['db']->query('DELETE FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'" AND `status`!="loggedout" AND `status`!="loggedin"');
                $GLOBALS['db']->query('DELETE FROM `activity` WHERE `timestamp`<"'.$this->idle.'"');

                if (is_array($this->result)) {
                	if (count($this->result) > 0) {
                    	foreach ($this->result as $key => $val) {
                        	if (strlen($this->result[$key]['page']) > 60) {
                            	$this->result[$key]['url'] = $this->result[$key]['page'];
                            	$this->result[$key]['page'] = substr($this->result[$key]['url'], 0, 40).'...'.substr($this->result[$key]['url'], -20, 20);
                        	} else {
                            	$this->result[$key]['url'] = $this->result[$key]['page'];
                            }
                        }
                    }
                }

                // Dont display visitors that have not got their page logged yet
                if (is_array($this->result)) {
                	if (count($this->result) > 0) {
                    	foreach ($this->result as $key => $val) {
                        	if ($this->result[$key]['page'] == '') {
                            	unset($this->result[$key]);
                            }
                        }
                    }
                }

                $this->visitors = true;
                
                if (is_array($this->result)) {
                	if (count($this->result) > 0) {
                    	$this->result = array_values($this->result);
                	} else {
                    	$this->result = array();
                	}
                }
                
                return $this->result;
            } else {
                return false;
            }
        }

        function status($arg)
        {
            if ($arg == 'on'){
                $arg = 1;
            } else {
                $arg = 0;
            }
            $GLOBALS['db']->query('UPDATE `operators` SET `status`="'.$arg.'" WHERE `id`="'.$GLOBALS['operator']->id().'"');
        }

        function sounds($arg = '')
        {
            if ($arg == 'on'){
                $GLOBALS['db']->query('UPDATE `operators` SET `sounds`="1" WHERE `id`="'.$GLOBALS['operator']->id().'"');
            } elseif ($arg == 'off'){
                $GLOBALS['db']->query('UPDATE `operators` SET `sounds`="0" WHERE `id`="'.$GLOBALS['operator']->id().'"');
            } else {
                $this->chats();
                $this->visitors();
                if ($GLOBALS['operator']->sounds()) {
                    return $this->sounds;
                } else {
                    foreach ($this->sounds as $key => $val) {
                        $this->sounds[$key]['file'] = '';
                    }
                    return $this->sounds;
                }
            }
        }

        function keywords($input)
        {
            if (strstr($input, '?q=') || strstr($input, '&q=')) {
                parse_str(str_replace('?', '&', $input), $output);
                $words = $output['q'];
            } elseif (strstr($input, '?p=') || strstr($input, '&p=')) {
                parse_str(str_replace('?', '&', $input), $output);
                $words = $output['p'];
            } elseif (strstr($input, '?query=') || strstr($input, '&query=')) {
                parse_str(str_replace('?', '&', $input), $output);
                $words = $output['query'];
            }

            if (preg_match("/^(http:\/\/)?([^\/]+)/i", $input, $matches)) {
                $host = $matches[2];
                if (preg_match("/([^\.\/]+)\.[^\.\/]+$/", $host, $matches)) {
                    if ($matches[1] == "google" || $matches[1] == "yahoo" || $matches[1] == "overture" || $matches[1] == "altavista" || $matches[1] == "ask" || $matches[1] == "excite" || $matches[1] == "lycos" || $matches[1] == "hotbot" || $matches[1] == "webcrawler") {
                        $engine = $matches[1];
                        $keywords['engine'] = ucfirst($engine);
                    } else {
                        $keywords['engine'] = 'Unknown';
                    }
                } else {
                    $keywords['engine'] = 'Unknown';
                }
            } else {
                $keywords['engine'] = 'Unknown';
            }

            if (isset($words)) {
                $keywords['words'] = $words;
            } else {
                $keywords['engine'] = 'None';
                $keywords['words'] = 'None';
            }
            return $keywords;
        }

        function onload()
        {
            $this->chats();
            return $this->onload;
        }

        function info($chatid)
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `id`="'.$chatid.'"')) {
                if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `footprints` WHERE `chatid`="'.$chatid.'" AND `current`="1" ORDER BY `id` DESC')) {
                    $this->result[0]['page'] = $this->result2[0]['page'];
                    $this->result[0]['page_time'] = secs_to_hms(time() - $this->result2[0]['timestamp']);
                    $this->result[0]['footprints'] = $this->result2;
                }
                $this->result[0]['site_time'] = secs_to_hms(time() - $this->result[0]['start']);
                if (isset($this->result[0]['footprints'][0])) {
                    foreach ($this->result[0]['footprints'] as $key => $val) {
                        $this->result[0]['footprints'][$key]['time'] = gmdate('G:i:s D jS F Y', $this->result[0]['footprints'][$key]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                        if ($GLOBALS['hotpage']->check($this->result[0]['footprints'][$key]['page'])) {
                            $this->result[0]['footprints'][$key]['hotpage'] = 'true';
                        } else {
                            $this->result[0]['footprints'][$key]['hotpage'] = 'false';
                        }
                        if (strlen($this->result[0]['footprints'][$key]['page']) > 60) {
                            $this->result[0]['footprints'][$key]['url'] = $this->result[0]['footprints'][$key]['page'];
                            $this->result[0]['footprints'][$key]['page'] = substr($this->result[0]['footprints'][$key]['url'], 0, 40).'...'.substr($this->result[0]['footprints'][$key]['url'], -20, 20);
                        } else {
                            $this->result[0]['footprints'][$key]['url'] = $this->result[0]['footprints'][$key]['page'];
                        }
                    }
                }
                if ($this->result[0]['history'] = $GLOBALS['db']->query('SELECT * FROM `footprints` WHERE `chatid`="'.$chatid.'" AND `current`="0" ORDER BY `id` DESC')) {
                    if (isset($this->result[0]['history'][0])) {
                        foreach ($this->result[0]['history'] as $key => $val) {
                            $this->result[0]['history'][$key]['time'] = gmdate('G:i:s D jS F Y', $this->result[0]['history'][$key]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                            if ($GLOBALS['hotpage']->check($this->result[0]['history'][$key]['page'])) {
                                $this->result[0]['history'][$key]['hotpage'] = 'true';
                            } else {
                                $this->result[0]['history'][$key]['hotpage'] = 'false';
                            }
                            if (strlen($this->result[0]['history'][$key]['page']) > 60) {
                                $this->result[0]['history'][$key]['url'] = $this->result[0]['history'][$key]['page'];
                                $this->result[0]['history'][$key]['page'] = substr($this->result[0]['history'][$key]['url'], 0, 40).'...'.substr($this->result[0]['history'][$key]['url'], -20, 20);
                            } else {
                                $this->result[0]['history'][$key]['url'] = $this->result[0]['history'][$key]['page'];
                            }
                        }
                    }
                }
                if (isset($this->result[0]['page'])) {
                    if (strlen($this->result[0]['page']) > 60) {
                        $this->result[0]['url'] = $this->result[0]['page'];
                        $this->result[0]['page'] = substr($this->result[0]['url'], 0, 40).'...'.substr($this->result[0]['url'], -20, 20);
                    } else {
                        $this->result[0]['url'] = $this->result[0]['page'];
                    }
                } else {
                    $this->result[0]['page'] = '';
                    $this->result[0]['url'] = '';
                }
                if (isset($this->result[0]['referrer'])) {
                    if (strlen($this->result[0]['referrer']) > 60) {
                        $this->result[0]['refurl'] = $this->result[0]['referrer'];
                        $this->result[0]['referrer'] = substr($this->result[0]['refurl'], 0, 40).'...'.substr($this->result[0]['refurl'], -20, 20);
                    } else {
                        $this->result[0]['refurl'] = $this->result[0]['referrer'];
                    }
                } else {
                    $this->result[0]['referrer'] = '';
                    $this->result[0]['refurl'] = '';
                }
            }
            return $this->result[0];
        }

        function initiate($departmentid, $operatorid, $chatid)
        {
            $this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `departmentid`="'.$departmentid.'" AND `operatorid`="'.$operatorid.'"');
            $GLOBALS['stats']->initiate($GLOBALS['operator']->id());
            $GLOBALS['db']->query('INSERT INTO `sessions` (chatid,assignid,departmentid,operatorid,nick,timeo,timeg,alert) VALUES ("'.$chatid.'", "'.$this->result[0]['id'].'", "'.$departmentid.'", "'.$operatorid.'", "Guest", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), "initiate")');
        }

        function response()
        {
            $this->response = '|';
            $this->traffic_idle = time() - $GLOBALS['conf']['traffic_timeout'];
            $this->live_idle = time() - $GLOBALS['conf']['live_timeout'];
            $this->chat_limit = time() - $GLOBALS['conf']['chat_timeout'];
            $this->initiate_limit = $this->chat_limit + 360;

            // Update operator status so they do not timeout
            $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$GLOBALS['operator']->id().'"');
            if ($this->result) {
                if ($this->result[0]['timestamp'] == '9') {
                    $this->response .= 'boot|';
                    $GLOBALS['db']->query('UPDATE `operators` SET `timestamp`="0" WHERE `id`="'.$GLOBALS['operator']->id().'"');
                } else {
                    if ($this->result[0]['timestamp'] > $this->live_idle && $this->result[0]['status'] == '1') {
                        $this->onlinetime = $this->result[0]['onlinetime'] + (time() - $this->result[0]['timestamp']);
                    } else {
                        $this->onlinetime = $this->result[0]['onlinetime'];
                    }
                    $GLOBALS['db']->query('UPDATE `operators` SET `timestamp`=UNIX_TIMESTAMP(), `onlinetime`="'.$this->onlinetime.'" WHERE `id`="'.$GLOBALS['operator']->id().'"');
                }
            }

            // Delete old sessions
            if ($GLOBALS['db']->query('DELETE FROM `sessions` WHERE `timeg`<"'.$this->chat_limit.'" AND `timeo`<"'.$this->chat_limit.'" AND `alert`!="initiate"') > 0) {
                $this->response .= 'request-cancelled|';
            }

            // Clean up old conversations to save space
            $GLOBALS['chat']->clean();

            // Check for chat requests
            
            // Loop each department that the operator belongs too
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `operatorid`="'.$GLOBALS['operator']->id().'"')) {
                foreach ($this->result as $key => $val) {
                    if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `alert`="request" AND `departmentid`="'.$this->result[$key]['departmentid'].'" AND `assignid`="'.$this->result[$key]['id'].'" ORDER BY `id` DESC')) {
                        $this->response .= 'request-incoming|';
                        // Update request so that it does not keep refreshing the traffic monitor
                        $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="received" WHERE `id`="'.$this->result2[0]['id'].'"');
                    }
                    if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `alert`="received" AND `departmentid`="'.$this->result[$key]['departmentid'].'" AND `assignid`="'.$this->result[$key]['id'].'" ORDER BY `id` DESC')) {
                        $this->response .= 'request-waiting|';
                    }
                    if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `alert`="transfer" AND `departmentid`="'.$this->result[$key]['departmentid'].'" AND `assignid`="'.$this->result[$key]['id'].'" ORDER BY `id` DESC')) {
                        $this->response .= 'request-incoming|';
                        // Update request so that it does not keep refreshing the traffic monitor
                        $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="transferring" WHERE `id`="'.$this->result2[0]['id'].'"');
                    }
                    if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `alert`="transferring" AND `departmentid`="'.$this->result[$key]['departmentid'].'" AND `assignid`="'.$this->result[$key]['id'].'" ORDER BY `id` DESC')) {
                        $this->response .= 'request-waiting|';
                    }
                }
            }
    
            if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `operatorid`="'.$GLOBALS['operator']->id().'" AND `alert`="opchat" AND `departmentid`="0" AND `assignid`="0" ORDER BY `id` DESC')) {
                $this->response .= 'request-incoming|';
                // Update request so that it does not keep refreshing the traffic monitor
                $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="received" WHERE `id`="'.$this->result2[0]['id'].'"');               
            }
            if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `operatorid`="'.$GLOBALS['operator']->id().'" AND `alert`="received" AND `departmentid`="0" AND `assignid`="0" ORDER BY `id` DESC')) {
                $this->response .= 'request-waiting|';
            }

            // Check for visitor activity
            if ($GLOBALS['conf']['monitor_traffic']) {
                if($GLOBALS['db']->query('DELETE FROM `system` WHERE `timestamp`<"'.$this->traffic_idle.'"')) {
                    $this->response .= 'activity|';
                } elseif ($GLOBALS['db']->query('SELECT * FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'" AND `timestamp`>"'.$this->traffic_idle.'"')) {
                    $this->response .= 'activity|';
                }
            }


            return $this->response;

        }

        function accept_chat($id, $chatid)
        {
            if ($id == 0) {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC');
                $id = $this->result[0]['id'];
            }
            $this->new_time = time() + $GLOBALS['conf']['chat_timeout'];
            $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="accept", `operatorid`="'.$GLOBALS['operator']->id().'", `timeo`="'.$this->new_time.'", `timeg`="'.$this->new_time.'" WHERE `id`="'.$id.'" AND `chatid`="'.$chatid.'"');
            $GLOBALS['db']->query('DELETE FROM `chat` WHERE `chatid`="'.$chatid.'"');
            $GLOBALS['stats']->accept($GLOBALS['operator']->id());
            // Autosave transcript
            $GLOBALS['transcript']->create($chatid);
        }

        function decline_chat($id, $chatid)
        {
            if ($id == 0) {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC');
                $id = $this->result[0]['id'];
            }
            $this->assignid = $GLOBALS['poll']->nextoperator($chatid);
            if ($this->assignid !== '0' && $this->assignid !== 0 && $this->assignid !== '') {
                $this->result = $GLOBALS['db']->query('SELECT `operatorid` FROM `assigns` WHERE `id`="'.$this->assignid.'"');
                $this->operatorid = $this->result[0]['operatorid'];
                $GLOBALS['db']->query('UPDATE `sessions` SET `assignid`="'.$this->assignid.'", `operatorid`="'.$this->operatorid.'", `alert`="request", `timeo`=UNIX_TIMESTAMP(), `timeg`=UNIX_TIMESTAMP() WHERE `id`="'.$id.'" AND `chatid`="'.$chatid.'"');
            } else {
                $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="decline" WHERE `id`="'.$id.'" AND `chatid`="'.$chatid.'"');
                $GLOBALS['db']->query('DELETE FROM `polling` WHERE `chatid`="'.$chatid.'"');
            }
            $GLOBALS['stats']->decline($GLOBALS['operator']->id());
        }

        function accept_opchat($id, $chatid)
        {
            if ($id == 0) {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC');
                $id = $this->result[0]['id'];
            }
            $this->new_time = time() + $GLOBALS['conf']['chat_timeout'];
            $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="accept", `timeo`="'.$this->new_time.'", `timeg`="'.$this->new_time.'" WHERE `id`="'.$id.'" AND `chatid`="'.$chatid.'"');
            $GLOBALS['stats']->opchat_accept($GLOBALS['operator']->id());
        }

        function decline_opchat($id, $chatid)
        {
            if ($id == 0) {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC');
                $id = $this->result[0]['id'];
            }
            $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="decline", `timeo`="'.$this->new_time.'", `timeg`="'.$this->new_time.'" WHERE `id`="'.$id.'" AND `chatid`="'.$chatid.'"');
            $GLOBALS['stats']->opchat_decline($GLOBALS['operator']->id());
        }

        function accept_transfer($id, $chatid)
        {
            if ($id == 0) {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC');
                $id = $this->result[0]['id'];
            }
            $this->new_time = time() + $GLOBALS['conf']['chat_timeout'];
            $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="transferred", `operatorid`="'.$GLOBALS['operator']->id().'", `timeo`="'.$this->new_time.'", `timeg`="'.$this->new_time.'" WHERE `id`="'.$id.'" AND `chatid`="'.$chatid.'"');
            $GLOBALS['stats']->transfer_accept($GLOBALS['operator']->id());
        }

        function decline_transfer($id, $chatid)
        {
            if ($id == 0) {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC');
                $id = $this->result[0]['id'];
            }
            $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="notransfer" WHERE `id`="'.$id.'" AND `chatid`="'.$chatid.'"');
            $GLOBALS['stats']->transfer_decline($GLOBALS['operator']->id());
        }

    }

?>