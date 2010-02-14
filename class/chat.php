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
    // This class contains all the core functions required when chatting
    // if a function related to a chat session is not in here, it will
    // be in a more specific class, such as transftering files is delt with
    // by the File class

    class Chat {

        var $result;
        var $result2;
        var $message;
        var $push;
        var $awake;
        var $canned;
        var $response;
        var $assignid;
        var $idle;
        var $operatorid;

        function session($chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            $_SESSION['hcl_'.$chatid]['isoperator'] = $GLOBALS['auth']->operator();
            $_SESSION['hcl_'.$chatid]['operatorid'] = $this->operatorid($chatid);
            $_SESSION['hcl_'.$chatid]['departmentid'] = $this->departmentid($chatid);
            $_SESSION['hcl_'.$chatid]['operator'] = $GLOBALS['operator']->name($this->operatorid($chatid));
            $_SESSION['hcl_'.$chatid]['department'] = $GLOBALS['department']->name($this->departmentid($chatid));
            $_SESSION['hcl_'.$chatid]['guest'] = $this->guestname($chatid);
        }

        function assign($chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            $GLOBALS['template']->assign('_CHAT', $_SESSION['hcl_'.$chatid]);
        }

        function operatorid($chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"');
            return $this->result[0]['operatorid'];
        }

        function departmentid($chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"');
            return $this->result[0]['departmentid'];
        }

        function blocked()
        {
            $blocked = false;
            $ip = split(',', $GLOBALS['conf']['block']);
            foreach ($ip as $key => $val) {
                if (trim($val) == $_SERVER['REMOTE_ADDR']) {
                    $blocked = true;
                }
            }
            return $blocked;
        }

        function guestname($chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"');
            return $this->result[0]['nick'];
        }

        function hold($admin, $chatid = '') {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            if ($admin == 'true') {
                $time = time() + $GLOBALS['conf']['chat_hold'];
                $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="hold", `timeo`='.$time.' WHERE `chatid`="'.$chatid.'"');
                return true;
            } else {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"');
                if ($this->result[0]['alert'] == "hold") {
                    $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="holding" WHERE `chatid`="'.$chatid.'"');
                    return true;
                }
            }
            return false;
        }

        function resume($admin, $chatid = '') {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            if ($admin == 'true') {
                $time = time() + 15;
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"');
                if (isset($this->result[0])) {
                    $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="resume", `timeo`='.$time.' WHERE `chatid`="'.$chatid.'"');
                    return true;
                }
            } else {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"');
                if (isset($this->result[0])) {
                    if ($this->result[0]['alert'] == "resume") {
                        $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="resumed" WHERE `chatid`="'.$chatid.'"');
                        return true;
                    }
                }
            }
            return false;
        }

        function parse_message($admin, $chatid, $message, $x, $allowpush = '')
        {
            $this->push = '';
            $this->message = $message;
            //$this->message = preg_replace("/&#(\d+);/me", "chr('\\1')", $this->message);
            //$this->message = htmlspecialchars($this->message, ENT_NOQUOTES);
            // do admin parsing if the person receiving this message is a guest
            if ($x == 'o') {
                $this->message .= '&#32;';
                $this->message = preg_replace("/&#37;&#37;&#117;&#115;&#101;&#114;&#37;&#37;/i", char_to_html($_SESSION['hcl_'.$chatid]['guest']), $this->message);
                $this->message = preg_replace("/&#117;&#114;&#108;&#58;(.*?)&#32;/ie", "'<a href=\"'.html_to_char('\\1').'\" target=\"_blank\">\\1</a>&#32;'", $this->message);
                $this->message = preg_replace("/&#105;&#109;&#97;&#103;&#101;&#58;(.*?)&#32;/ie", "'<img src=\"'.html_to_char('\\1').'\" alt=\"\" />&#32;'", $this->message);
                $this->message = preg_replace("/&#101;&#109;&#97;&#105;&#108;&#58;(.*?)&#32;/ie", "'<a href=\"mailto:'.html_to_char('\\1').'\">\\1</a>&#32;'", $this->message);
                if ($admin == 'false' && $allowpush == '') {
                    $matches = '';
                	if (preg_match("/&#112;&#117;&#115;&#104;&#58;(.*?)&#32;/i", $this->message, $matches)) {
                        $this->push = html_to_char($matches[1]);
                    }
                }
                $this->message = preg_replace("/&#112;&#117;&#115;&#104;&#58;(.*?)&#32;/ie", "'<i>".$GLOBALS['lang']['pushed_page'].": <a href=\"'.html_to_char('\\1').'\" target=\"_blank\">\\1</a></i>&#32;'", $this->message);
            }
            return $this->message;
        }

        function send($chatid, $message, $admin, $opchat = '')
        {
            if ($admin == 'true') {
                $x = 'o';
            } else {
                $x = 'g';
            }
            if ($message !== '' && $chatid !== '') {
                if ($opchat == 'true') {
                    if ($_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && $admin == 'true') {
                    	if ($chatid === '') $chatid = '0';
                    	if ($message === '') $message = " ";
                    	if ($x === '') $x = ' ';
                        $GLOBALS['db']->query('INSERT INTO `chat` (`chatid`, `operatorid`, `timestamp`, `message`, `x`, `operator`, `guest`) VALUES ("'.$chatid.'", "0", UNIX_TIMESTAMP(), "'.$message.'", "'.$x.'", "1", "0")');
                    } else {
                    	if ($chatid === '') $chatid = '0';
                    	if ($message === '') $message = " ";
                    	if ($x === '') $x = ' ';
                    	$GLOBALS['db']->query('INSERT INTO `chat` (`chatid`, `operatorid`, `timestamp`, `message`, `x`, `operator`, `guest`) VALUES ("'.$chatid.'", "0", UNIX_TIMESTAMP(), "'.$message.'", "'.$x.'", "0", "1")');
                    }
                } else {
                    if ($_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && $admin == 'true') {
                        $GLOBALS['db']->query('INSERT INTO `chat` (`chatid`, `operatorid`, `timestamp`, `message`, `x`, `operator`, `guest`) VALUES ("'.$chatid.'", "'.$GLOBALS['operator']->id().'", UNIX_TIMESTAMP(), "'.$message.'", "'.$x.'", "1", "0")');
                    } else {
                        $GLOBALS['db']->query('INSERT INTO `chat` (`chatid`, `operatorid`, `timestamp`, `message`, `x`, `operator`, `guest`) VALUES ("'.$chatid.'", "'.$_SESSION['hcl_'.$chatid]['operatorid'].'", UNIX_TIMESTAMP(), "'.$message.'", "'.$x.'", "0", "1")');
                    }
                    $GLOBALS['transcript']->insert($chatid, $message, $admin);
                }
            }

        }

        function get_push()
        {
            if ($this->push !== '') {
                return $this->push;
            } else {
                return false;
            }
        }

        function keep_awake($admin, $chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            $this->awake = time() + $GLOBALS['conf']['chat_timeout'];
            if ($_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && $admin == 'true') {
                $GLOBALS['db']->query('UPDATE `sessions` SET `timeo`="'.$this->awake.'" WHERE `chatid`="'.$chatid.'"');
            } else {
                $GLOBALS['db']->query('UPDATE `sessions` SET `timeg`="'.$this->awake.'" WHERE `chatid`="'.$chatid.'"');
            }
        }

        function get_message($admin, $chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            if ($_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && $admin == 'true') {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `chat` WHERE `message`!="" AND `chatid`="'.$chatid.'" AND `operator`="0" ORDER BY `id` ASC LIMIT 1');
            } else {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `chat` WHERE `message`!="" AND `chatid`="'.$chatid.'" AND `guest`="0" ORDER BY `id` ASC LIMIT 1');
            }
            if ($this->result) {
                $_SESSION['hcl_'.$chatid]['x'] = $this->result[0]['x'];
                $_SESSION['hcl_'.$chatid]['message'] = $this->parse_message($admin, $chatid, $this->result[0]['message'], $this->result[0]['x']);
                if ($_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && $admin == 'true') {
                    $this->result = $GLOBALS['db']->query('UPDATE `chat` SET `operator`="1" WHERE `id`="'.$this->result[0]['id'].'"');
                } else {
                    $this->result = $GLOBALS['db']->query('UPDATE `chat` SET `guest`="1" WHERE `id`="'.$this->result[0]['id'].'"');
                }
                return $_SESSION['hcl_'.$chatid]['message'];
            } else {
                return false;
            }
        }

        function get_messages($admin, $chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `chat` WHERE `message`!="" AND `chatid`="'.$chatid.'" ORDER BY `id` ASC')) {
                foreach ($this->result as $key => $val) {
                    $this->result[$key]['message'] = $this->parse_message($admin, $chatid, $this->result[$key]['message'], $this->result[$key]['x'], 'false');
                    if ($this->result[$key]['x'] == 'o') {
                        $this->result[$key]['name'] = $GLOBALS['operator']->name($this->result[$key]['operatorid']);
                    } else {
                        $this->result[$key]['name'] = $_SESSION['hcl_'.$chatid]['guest'];
                    }
                    if ($_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && $admin == 'true') {
                        $GLOBALS['db']->query('UPDATE `chat` SET `operator`="1" WHERE `id`="'.$this->result[$key]['id'].'"');
                    } else {
                        $GLOBALS['db']->query('UPDATE `chat` SET `guest`="1" WHERE `id`="'.$this->result[$key]['id'].'"');
                    }
                }
                return $this->result;
            } else {
                return false;
            }
        }

        function allow_transfer($admin, $chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            if ($admin !== 'true' && $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" AND `transfer`="yes"')) {
                $GLOBALS['db']->query('UPDATE `sessions` SET `transfer`="working" WHERE `chatid`="'.$chatid.'"');
                return true;
            } else {
                return false;
            }
        }

        function get_transfer($admin, $chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1');
            if ($admin == 'true' && $_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && $GLOBALS['operator']->id() !== $this->result[0]['operatorid']) {
                if ($this->result[0]['alert'] == 'transferred') {
                    $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="transfer_completing" WHERE `id`="'.$this->result[0]['id'].'"');
                    $_SESSION['hcl_'.$chatid]['operator'] = $GLOBALS['operator']->name($this->result[0]['operatorid']);
                    $_SESSION['hcl_'.$chatid]['department'] = $GLOBALS['department']->name($this->result[0]['departmentid']);
                    return true;
                } else {
                    return false;
                }
            } elseif ($admin == 'false') {
                if ($this->result[0]['alert'] == 'transfer_completing') {
                    $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="neutral" WHERE `id`="'.$this->result[0]['id'].'"');
                    $_SESSION['hcl_'.$chatid]['operator'] = $GLOBALS['operator']->name($this->result[0]['operatorid']);
                    $_SESSION['hcl_'.$chatid]['department'] = $GLOBALS['department']->name($this->result[0]['departmentid']);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function no_transfer($admin, $chatid = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            if ($admin == 'true' && $_SESSION['hcl_'.$chatid]['isoperator'] == 'true') {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `alert`="notransfer" AND `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1')) {
                    $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="neutral" WHERE `id`="'.$this->result[0]['id'].'"');
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function get_files($admin, $chatid = '', $client = '')
        {
            if ($chatid == '') {
                $chatid = $GLOBALS['live']->chatid();
            }
            if ($admin == 'true' && $_SESSION['hcl_'.$chatid]['isoperator'] == 'true') {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `files` WHERE `chatid`="'.$chatid.'" AND `status`="user"');
                if ($this->result) {
                    $GLOBALS['db']->query('UPDATE `files` SET `status`="operator_received" WHERE `id`="'.$this->result[0]['id'].'"');
                }
            } else {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `files` WHERE `chatid`="'.$chatid.'" AND `status`="operator"');
                if ($this->result) {
                    $GLOBALS['db']->query('UPDATE `files` SET `status`="user_received" WHERE `id`="'.$this->result[0]['id'].'"');
                }
            }
            if ($this->result) {
                if ($client == 'true') {
                    return $this->result[0];
                } else {
                    $_SESSION['hcl_'.$chatid]['fileid'] = $this->result[0]['id'];
                    $_SESSION['hcl_'.$chatid]['filesize'] = $this->result[0]['size'];
                    return rawurlencode($this->result[0]['size']);
                }
            } else {
                return false;
            }
        }

        function get_canned($operatorid, $departmentid = '')
        {
            $this->canned = array(
                'all' => array(),
                'op' => array(),
                'dep' => array(),
                'both' => array()
            );
            $this->canned['all'] = $GLOBALS['db']->query('SELECT * FROM `canned` WHERE `departmentid`="0" AND `operatorid`="0"');
            $this->canned['op'] = $GLOBALS['db']->query('SELECT * FROM `canned` WHERE `departmentid`="0" AND `operatorid`="'.$operatorid.'"');
            $this->canned['dep'] = $GLOBALS['db']->query('SELECT * FROM `canned` WHERE `departmentid`="'.$departmentid.'" AND `operatorid`="0"');
            $this->canned['both'] = $GLOBALS['db']->query('SELECT * FROM `canned` WHERE `departmentid`="'.$departmentid.'" AND `operatorid`="'.$operatorid.'"');
            return $this->canned;
        }

        function end($chatid)
        {
            $GLOBALS['db']->query('DELETE FROM `sessions` WHERE chatid="'.$chatid.'"');
            $GLOBALS['db']->query('DELETE FROM `cobrowse` WHERE chatid="'.$chatid.'"');
            $GLOBALS['db']->query('DELETE FROM `coforms` WHERE chatid="'.$chatid.'"');
            $GLOBALS['db']->query('DELETE FROM `comarker` WHERE chatid="'.$chatid.'"');
        }

        function clean()
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `chat` WHERE 1 GROUP BY `chatid`')) {
                foreach ($this->result as $key => $val) {
                    if (!$GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$this->result[$key]['chatid'].'"')) {
                        $this->result2 = $GLOBALS['db']->query('SELECT * FROM `chat` WHERE `chatid`="'.$this->result[$key]['chatid'].'" ORDER BY `timestamp` DESC LIMIT 1');
                        if ($this->result2[0]['timestamp'] < (time() - 3600)) {
                            $GLOBALS['db']->query('DELETE FROM `chat` WHERE `chatid`="'.$this->result[$key]['chatid'].'"');
                        }
                    }
                }
            }
        }

        function timestamp($chatid, $format = '')
        {
            $this->result = $GLOBALS['db']->query('SELECT * FROM `chat` WHERE `chatid`="'.$chatid.'" ORDER BY `timestamp` DESC LIMIT 1');
            if ($format == '') {
                return $this->result[0]['timestamp'];
            } else {
                return gmdate($format, $this->result[0]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
            }
        }

        function checksum($chatid, $admin)
        {
            if ($admin == 'true') {
                if ($this->result = $GLOBALS['db']->query('SELECT `id` FROM `chat` WHERE `chatid`="'.$chatid.'" AND `operator`="1"')) {
                    return count($this->result);
                } else {
                    return 0;
                }
            } else {
                if ($this->result = $GLOBALS['db']->query('SELECT `id` FROM `chat` WHERE `chatid`="'.$chatid.'" AND `guest`="1"')) {
                    return count($this->result);
                } else {
                    return 0;
                }
            }
        }

        function response($chatid, $typing, $admin, $checksum = '', $copage = '')
        {
            $type_limit = 5;
            $chat_limit = time() - $GLOBALS['conf']['chat_timeout'];
            $this->response = '|';
            
            if ($admin == 'true') {

                $this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'"');
                if ($this->result[0]['alert'] !== 'hold' && $this->result[0]['alert'] !== 'holding') {
                    $GLOBALS['db']->query('UPDATE `sessions` SET `timeo`=UNIX_TIMESTAMP() WHERE `chatid`="'.$chatid.'"');
                }

                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1')) {
                    if ($this->result[0]['typeg'] !== '0') {
                        $this->response .= 'typing|';
                        if ($this->result[0]['typeg'] < $type_limit) {
                            $GLOBALS['db']->query('UPDATE `sessions` SET `typeg`="0" WHERE `chatid`="'.$chatid.'"');
                        }
                    }
                    if ($this->result[0]['timeg'] < $chat_limit) {
                        $this->response .= 'close|';
                    }
                } else {
                    $this->response .= 'close|';
                }

                if ($checksum !== '' && intval($checksum) !== intval($this->checksum($chatid, $admin))) {
                    $this->response .= 'display|';
                }

                if ($message = $this->get_message($admin, $chatid)) {
                    $this->response .= 'message:'.$message.'|';
                }

                if ($this->allow_transfer($admin, $chatid)) {
                    $this->response .= 'upload|';
                }

                if ($filesize = $this->get_files($admin, $chatid)) {
                    $this->response .= 'download:'.$filesize.':'.$_SESSION['hcl_'.$chatid]['fileid'].'|';
                }

                if ($this->get_transfer($admin, $chatid)) {
                    $this->response .= 'transfer-yes|';
                    $this->response .= 'transfer-operator:'.$_SESSION['hcl_'.$chatid]['operator'].'|';
                    $this->response .= 'transfer-department:'.$_SESSION['hcl_'.$chatid]['department'].'|';
                }

                if ($GLOBALS['chat']->no_transfer($admin, $chatid)) {
                    $this->response .= 'transfer-no|';
                }

                if ($checksum !== '') {
                    if ($typing == 'true') {
                        $GLOBALS['db']->query('UPDATE `sessions` SET `typeo`=UNIX_TIMESTAMP() WHERE `chatid`="'.$chatid.'"');
                    } else {
                        $GLOBALS['db']->query('UPDATE `sessions` SET `typeo`="0" WHERE `chatid`="'.$chatid.'"');
                    }
                }

            } else {

                $GLOBALS['db']->query('UPDATE `sessions` SET `timeg`=UNIX_TIMESTAMP() WHERE `chatid`="'.$chatid.'"');

                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1')) {
                    if ($this->result[0]['typeo'] !== '0') {
                        $this->response .= 'typing|';
                        if ($this->result[0]['typeo'] < $type_limit) {
                            $GLOBALS['db']->query('UPDATE `sessions` SET `typeo`="0" WHERE `chatid`="'.$chatid.'"');
                        }
                    }
                    if ($this->result[0]['timeo'] < $chat_limit) {
                        $this->response .= 'close|';
                    }
                } else {
                    $this->response .= 'close|';
                }

                if ($checksum !== '' && intval($checksum) !== intval($this->checksum($chatid, $admin))) {
                    $this->response .= 'display|';
                }

                if ($message = $this->get_message($admin, $chatid)) {
                    $this->response .= 'message:'.$message.'|';
                }

                if ($push = $this->get_push()) {
                    $this->response .= 'push:'.$push.'|';
                    $this->keep_awake($admin, $chatid);
                }

                if ($this->allow_transfer($admin, $chatid)) {
                    $this->response .= 'upload|';
                }

                if ($this->hold($admin, $chatid)) {
                    $this->response .= 'hold|';
                }
                
                if ($this->resume($admin, $chatid)) {
                    $this->response .= 'resume|';
                }

                if ($filesize = $this->get_files($admin, $chatid)) {
                    $this->response .= 'download:'.$filesize.':'.$_SESSION['hcl_'.$chatid]['fileid'].'|';
                }

                if ($this->get_transfer($admin, $chatid)) {
                    $this->response .= 'transfer-yes|';
                    $this->response .= 'transfer-operator:'.$_SESSION['hcl_'.$chatid]['operator'].'|';
                    $this->response .= 'transfer-department:'.$_SESSION['hcl_'.$chatid]['department'].'|';
                }

                if ($GLOBALS['chat']->no_transfer($admin, $chatid)) {
                    $this->response .= 'transfer-no|';
                }

                if ($GLOBALS['cobrowse']->started()) {
                    $this->response .= 'cobrowse|';
                } elseif (!$GLOBALS['cobrowse']->getpage($chatid)) {
                    $this->response .= 'cobrowse-disconnect|';
                }
                
                if ($GLOBALS['cobrowse']->changedpage($chatid, $copage)) {
                    $this->response .= 'copage:'.urlencode($GLOBALS['cobrowse']->getpage($chatid)).'|';
                }

                if ($forms = $GLOBALS['cobrowse']->getforms($chatid, 'false')) {
                    foreach ($forms as $key => $val) {
                        $this->response .= 'coforms:'.$forms[$key]['type'].':'.rawurlencode(html_to_char(html_to_char($forms[$key]['name']))).':'.rawurlencode(html_to_char(html_to_char($forms[$key]['value']))).'|';
                    }
                }

                if ($marker = $GLOBALS['cobrowse']->getmarker($chatid)) {
                    $this->response .= 'comarker:'.$marker['type'].':'.$marker['x'].':'.$marker['y'].'|';
                }

                if ($GLOBALS['cobrowse']->clearmarkers($chatid, 'false')) {
                    $this->response .= 'comarker-clear|';
                }

                if ($GLOBALS['cobrowse']->enabled($chatid)) {
                    $this->response .= 'cobrowse-allow|';
                } else {
                    $this->response .= 'cobrowse-disallow|';
                }

                if ($typing == 'true') {
                    $GLOBALS['db']->query('UPDATE `sessions` SET `typeg`=UNIX_TIMESTAMP() WHERE `chatid`="'.$chatid.'"');
                } else {
                    $GLOBALS['db']->query('UPDATE `sessions` SET `typeg`="0" WHERE `chatid`="'.$chatid.'"');
                }

            }

            return $this->response;

        }

        function transfer($chatid, $operatorid, $departmentid)
        {
            $this->result = $GLOBALS['db']->query('SELECT * FROM `assigns` WHERE `operatorid`="'.$operatorid.'" AND `departmentid`="'.$departmentid.'"');
            $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="transfer", `departmentid`="'.$departmentid.'", `operatorid`="'.$operatorid.'", `assignid`="'.$this->result[0]['id'].'" WHERE `chatid`="'.$chatid.'"');
        }

        function request($chatid, $opchat)
        {
            $this->response = '|';
            if ($opchat == 'true') {
                $this->idle = time() - $GLOBALS['conf']['live_timeout'];
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="op_'.$GLOBALS['operator']->id().'" AND `departmentid`="0" AND `assignid`="0" ORDER BY `id` DESC LIMIT 1')) {
                    switch ($this->result[0]['alert']) {
                    case 'accept':
                        $this->response .='accept|';
                        break;
                    case 'decline':
                        $this->response .='decline|';
                        break;
                    default:
                        if ($this->result[0]['timeo'] < $this->idle) {
                            $this->response .='decline|';
                        }
                        break;
                    }
                } else {
                    $this->response .='decline|';
                }
            } else {
                $this->idle = time() - $GLOBALS['conf']['session_timeout'];
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `sessions` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1')) {
                    switch ($this->result[0]['alert']) {
                    case 'accept':
                        $this->response .='accept|';
                        break;
                    case 'decline':
                        // Poll to the next operator
                        if ($this->assignid = $GLOBALS['poll']->nextoperator($chatid)) {
                            $GLOBALS['db']->query('UPDATE `sessions` SET `assignid`="'.$this->assignid.'", `timeo`=UNIX_TIMESTAMP(), `timeg`=UNIX_TIMESTAMP() WHERE `chatid`="'.$chatid.'"');
                        } else {
                            $this->response .='decline|';
                        }
                        break;
                    default:
                        if ($this->result[0]['timeo'] < $this->idle) {
                            $this->assignid = $GLOBALS['poll']->nextoperator($chatid);
                            if ($this->assignid !== '0' && $this->assignid !== 0 && $this->assignid !== '') {
                                $this->result = $GLOBALS['db']->query('SELECT `operatorid` FROM `assigns` WHERE `id`="'.$this->assignid.'"');
                                $this->operatorid = $this->result[0]['operatorid'];
                                $GLOBALS['db']->query('UPDATE `sessions` SET `assignid`="'.$this->assignid.'", `operatorid`="'.$this->operatorid.'", `alert`="request", `timeo`=UNIX_TIMESTAMP(), `timeg`=UNIX_TIMESTAMP() WHERE `chatid`="'.$chatid.'"');
                            } else {
                                $GLOBALS['db']->query('UPDATE `sessions` SET `alert`="decline" WHERE `chatid`="'.$chatid.'"');
                                $GLOBALS['db']->query('DELETE FROM `polling` WHERE `chatid`="'.$chatid.'"');
                                $this->response .='decline|';
                            }
                        }
                        break;
                    }
                } else {
                    $this->response .= 'decline|';
                }

            }
            return $this->response;
        }

        function typing($chatid)
        {
            // This is only for external clients
            $GLOBALS['db']->query('UPDATE `sessions` SET `typeo`=UNIX_TIMESTAMP() WHERE `chatid`="'.$chatid.'"');
        }

        function typed($chatid)
        {
            // This is only for external clients
            $GLOBALS['db']->query('UPDATE `sessions` SET `typeo`="0" WHERE `chatid`="'.$chatid.'"');
        }

    }

?>