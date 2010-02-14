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
    // This class contains all the transcript functions

    class Transcript {

        var $result;
        var $result2;
        var $chat;
        var $text;
        var $timestamp;
        var $transcripts;

        function create($chatid)
        {
            if ($GLOBALS['operator']->autosave_transcripts()) {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"')) {
                    if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `id`="'.$chatid.'"')) {
                        $GLOBALS['db']->query('INSERT INTO `transcripts` (`timestamp`, `chatid`, `operatorid`, `departmentid`, `guest`, `hostname`) VALUES (UNIX_TIMESTAMP(), "'.$chatid.'", "'.$GLOBALS['operator']->id().'", "'.$this->result[0]['departmentid'].'", "'.addslashes($GLOBALS['chat']->guestname($chatid)).'", "'.$this->result2[0]['hostname'].'")');
                    }
                }
            }
        }

        function insert($chatid, $message, $admin)
        {
            if ($GLOBALS['operator']->autosave_transcripts($_SESSION['hcl_'.$chatid]['operatorid'])) {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `transcripts` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1')) {
                    $this->timestamp = gmdate('G:i:s', time() + (3600 * $GLOBALS['conf']['offset']));
                    if ($admin == 'true') {
                        $this->text = html_to_char(rawurldecode($message)).' ';
                        $this->text = preg_replace("/%%user%%/i", $_SESSION['hcl_'.$chatid]['guest'], $this->text);
                        $this->text = preg_replace("/url:(.*?) /i", "<a href=\"\\1\" target=\"_blank\">\\1</a> ", $this->text);
                        $this->text = preg_replace("/image:(.*?) /i", "<img src=\"\\1\" alt=\"image\" /> ", $this->text);
                        $this->text = preg_replace("/email:(.*?) /i", "<a href=\"\\1\">\\1</a> ", $this->text);
                        $this->text = preg_replace("/push:(.*?) /i", "<i>Pushed Page: \\1</i> ", $this->text);
                        $this->text = substr($this->text, 0, -1);
                        $this->text = '<span class="operator"><i>('.$this->timestamp.')</i> <b>'.$_SESSION['hcl_'.$chatid]['operator'].':</b> '.rawurldecode($this->text).'</span><br />';
                        //$this->text = '<span class="operator"><i>('.$this->timestamp.')</i> <b>'.$_SESSION['hcl_'.$chatid]['operator'].':</b> '.char_to_html($this->text).'</span><br />';
                    } else {
                        $this->text = '<span class="guest"><i>('.$this->timestamp.')</i> <b>'.$_SESSION['hcl_'.$chatid]['guest'].':</b> '.rawurldecode($message).'</span><br />';
                    }
                    $this->chat = rawurldecode($this->result[0]['transcript']).$this->text;
                    $this->chat = rawurlencode($this->chat);
                    $GLOBALS['db']->query('UPDATE `transcripts` SET `transcript`="'.$this->chat.'", `timestamp`=UNIX_TIMESTAMP() WHERE `id`="'.$this->result[0]['id'].'"');
                }
            }
        }

        function build($chatid)
        {
            $this->chat = '';
            $this->result = $GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `id`="'.$chatid.'"');
            $this->result2 = $GLOBALS['db']->query('SELECT * FROM `chat` WHERE `chatid`="'.$chatid.'" ORDER BY `timestamp` ASC');
            foreach ($this->result2 as $key => $val) {
                    $this->timestamp = gmdate('G:i:s', $this->result2[$key]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                    if ($this->result2[$key]['x'] == 'o') {
                        $this->text = html_to_char(rawurldecode($this->result2[$key]['message'])).' ';
                        $this->text = preg_replace("/%%user%%/i", $_SESSION['hcl_'.$chatid]['guest'], $this->text);
                        $this->text = preg_replace("/url:(.*?) /i", "<a href=\"\\1\" target=\"_blank\">\\1</a> ", $this->text);
                        $this->text = preg_replace("/image:(.*?) /i", "<img src=\"\\1\" alt=\"image\" /> ", $this->text);
                        $this->text = preg_replace("/email:(.*?) /i", "<a href=\"\\1\">\\1</a> ", $this->text);
                        $this->text = preg_replace("/push:(.*?) /i", "<i>Pushed Page: \\1</i> ", $this->text);
                        $this->text = substr($this->text, 0, -1);
                        $this->text = '<span class="operator"><i>('.$this->timestamp.')</i> <b>'.$_SESSION['hcl_'.$chatid]['operator'].':</b> '.char_to_html($this->text).'</span><br />';
                    } else {
                        $this->text = '<span class="guest"><i>('.$this->timestamp.')</i> <b>'.$_SESSION['hcl_'.$chatid]['guest'].':</b> '.rawurldecode($this->result2[$key]['message']).'</span><br />';
                    }
                $this->chat = $this->chat.$this->text;
            }

            $this->chat = rawurlencode($this->chat);
            $GLOBALS['db']->query('INSERT INTO `transcripts` (`timestamp`, `chatid`, `operatorid`, `departmentid`, `guest`, `hostname`, `transcript`) VALUES (UNIX_TIMESTAMP(), "'.$chatid.'", "'.$GLOBALS['operator']->id().'", "'.$_SESSION['hcl_'.$chatid]['departmentid'].'", "'.$_SESSION['hcl_'.$chatid]['guest'].'", "'.$this->result[0]['hostname'].'", "'.$this->chat.'")');
        }

        function get($chatid = '', $id = '', $admin = '')
        {
            if ($chatid == '' && $id == '') {
                $this->transcripts = array();
                if ($GLOBALS['auth']->admin() && $admin == 'admin') {
                    if ($this->result = $GLOBALS['db']->query('SELECT * FROM `departments` WHERE 1')) {
                        foreach ($this->result as $key => $val) {
                            if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `transcripts` WHERE `departmentid`="'.$this->result[$key]['id'].'" ORDER BY `timestamp` DESC')) {
                                foreach ($this->result2 as $key2 => $val2) {
                                    $this->result2[$key2]['time'] = gmdate('G:i:s D jS F Y', $this->result2[$key2]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                                }
                                $this->transcripts = array_merge($this->transcripts, array(array('name' => $GLOBALS['department']->name($this->result[$key]['id']), 'transcript' => $this->result2)));
                            }
                        }
                    }
                } else {
                    if ($this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `operatorid`="'.$GLOBALS['operator']->id().'"')) {
                        foreach ($this->result as $key => $val) {
                            if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `transcripts` WHERE `departmentid`="'.$this->result[$key]['departmentid'].'" AND `operatorid`="'.$GLOBALS['operator']->id().'" ORDER BY `timestamp` DESC')) {
                                foreach ($this->result2 as $key2 => $val2) {
                                    $this->result2[$key2]['time'] = gmdate('G:i:s D jS F Y', $this->result2[$key2]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                                }
                                $this->transcripts = array_merge($this->transcripts, array(array('name' => $GLOBALS['department']->name($this->result[$key]['departmentid']), 'transcript' => $this->result2)));
                            }
                        }
                    }
                }
                return $this->transcripts;
            } elseif ($id !== '') {
                $this->transcripts = array();
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `transcripts` WHERE `id`="'.$id.'"')) {
                    if ($GLOBALS['auth']->admin() && $admin == 'admin') {
                        $this->transcripts['id'] = $this->result[0]['id'];
                        $this->transcripts['nick'] = $this->result[0]['guest'];
                        $this->transcripts['operator'] = $GLOBALS['operator']->name($this->result[0]['operatorid']);
                        $this->transcripts['department'] = $GLOBALS['department']->name($this->result[0]['departmentid']);
                        $this->transcripts['email'] = $GLOBALS['department']->email($this->result[0]['departmentid']);
                        $this->transcripts['time'] = gmdate('G:i:s D jS F Y', $this->result[0]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                        $this->transcripts['chat'] = rawurldecode($this->result[0]['transcript']);
                        return $this->transcripts;
                    } elseif ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `operatorid`="'.$GLOBALS['operator']->id().'"')) {
                        foreach ($this->result2 as $key => $val) {
                            if ($this->result2[$key]['departmentid'] == $this->result[0]['departmentid']) {
                                $this->transcripts['id'] = $this->result[0]['id'];
                                $this->transcripts['nick'] = $this->result[0]['guest'];
                                $this->transcripts['operator'] = $GLOBALS['operator']->name($this->result[0]['operatorid']);
                                $this->transcripts['department'] = $GLOBALS['department']->name($this->result[0]['departmentid']);
                                $this->transcripts['email'] = $GLOBALS['department']->email($this->result[0]['departmentid']);
                                $this->transcripts['time'] = gmdate('G:i:s D jS F Y', $this->result[0]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                                $this->transcripts['chat'] = rawurldecode($this->result[0]['transcript']);
                                return $this->transcripts;
                            }
                        }
                    }
                } else {
                    return false;
                }
            } else {
                $this->chat = '';
                $this->result = $GLOBALS['db']->query('SELECT * FROM `traffic` WHERE `id`="'.$chatid.'"');
                if ($this->result2 = $GLOBALS['db']->query('SELECT * FROM `chat` WHERE `chatid`="'.$chatid.'" ORDER BY `timestamp` ASC')) {
                    foreach ($this->result2 as $key => $val) {
                        $this->timestamp = gmdate('G:i:s', $this->result2[$key]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
                        if ($this->result2[$key]['x'] == 'o') {
                            $this->text = html_to_char(rawurldecode($this->result2[$key]['message'])).' ';
                            $this->text = preg_replace("/%%user%%/i", $_SESSION['hcl_'.$chatid]['guest'], $this->text);
                            $this->text = preg_replace("/url:(.*?) /i", "<a href=\"\\1\" target=\"_blank\">\\1</a> ", $this->text);
                            $this->text = preg_replace("/image:(.*?) /i", "<img src=\"\\1\" alt=\"image\" /> ", $this->text);
                            $this->text = preg_replace("/email:(.*?) /i", "<a href=\"\\1\">\\1</a> ", $this->text);
                            $this->text = preg_replace("/push:(.*?) /i", "<i>Pushed Page: \\1</i> ", $this->text);
                            $this->text = substr($this->text, 0, -1);
                            $this->text = '<span class="operator"><i>('.$this->timestamp.')</i> <b>'.$_SESSION['hcl_'.$chatid]['operator'].':</b> '.char_to_html($this->text).'</span><br />';
                        } else {
                            $this->text = '<span class="guest"><i>('.$this->timestamp.')</i> <b>'.$_SESSION['hcl_'.$chatid]['guest'].':</b> '.rawurldecode($this->result2[$key]['message']).'</span><br />';
                        }
                        $this->chat = $this->chat.$this->text;
                    }
                    return $this->chat;  
                } else {
                    return 'Error';
                }
            }          
        }

    }

?>