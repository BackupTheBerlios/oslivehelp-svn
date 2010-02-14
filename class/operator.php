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
    // This class contains operator related functions. Functions to monitor authenticity
    // of operators are in the Auth class, not the Operator class.

    class Operator {

        var $result;
        var $details;
        var $name;

        function id()
        {
            if (isset($_SESSION['hcl_username']) && isset($_SESSION['hcl_password'])) {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `username`="'.$_SESSION['hcl_username'].'" AND `password`="'.$_SESSION['hcl_password'].'"')) {
                    return $this->result[0]['id'];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function name($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            $this->result = $GLOBALS['db']->query('SELECT `username`, `firstname`, `lastname` FROM `operators` WHERE `id`="'.$id.'"');
            $this->name = $GLOBALS['conf']['operator_name'];
            $this->name = str_replace('USERNAME', htmlspecialchars($this->result[0]['username']), $this->name);
            $this->name = str_replace('FIRSTNAME', htmlspecialchars($this->result[0]['firstname']), $this->name);
            $this->name = str_replace('LASTNAME', htmlspecialchars($this->result[0]['lastname']), $this->name);
            return $this->name;
        }

        function username($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            $this->result = $GLOBALS['db']->query('SELECT `username` FROM `operators` WHERE `id`="'.$id.'"');
            return $this->result[0]['username'];
        }

        function online($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            if ($GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$id.'" AND `timestamp`>"'.(time() - $GLOBALS['conf']['live_timeout']).'"')) {
                return true;
            } else {
                return false;
            }
        }

        function sounds($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            $this->result = $GLOBALS['db']->query('SELECT `sounds` FROM `operators` WHERE `id`="'.$id.'"');
            if ($this->result[0]['sounds'] == '1') {
                return true;
            } else {
                return false;
            }
        }

        function autosave_transcripts($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$id.'"');
            if ($this->result[0]['autosave'] == '1') {
                return true;
            } else {
                return false;
            }
        }

        function picture($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            if ($this->result = $GLOBALS['db']->query('SELECT `picture` FROM `operators` WHERE `id`="'.$id.'"')) {
                return base64_decode($this->result[0]['picture']);
            } else {
                return '';
            }
        }

        function boot($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            if ($id == '0') {
                $GLOBALS['db']->query('DELETE FROM `activity` WHERE 1');
                $GLOBALS['db']->query('DELETE FROM `sessions` WHERE 1');
                $GLOBALS['db']->query('UPDATE `operators` SET `timestamp`="9" WHERE 1');
            } else {
                $GLOBALS['db']->query('DELETE FROM `activity` WHERE `operatorid`="'.$id.'"');
                $GLOBALS['db']->query('DELETE FROM `sessions` WHERE `operatorid`="'.$id.'"');
                $GLOBALS['db']->query('UPDATE `operators` SET `timestamp`="9" WHERE `id`="'.$id.'"');
            }
        }

        function showpic($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            if ($this->result = $GLOBALS['db']->query('SELECT `showpic` FROM `operators` WHERE `id`="'.$id.'"')) {
                return $this->result[0]['showpic'];
            } else {
                return false;
            }
        }

        function get($id = '')
        {
            if ($id == '') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$id.'"')) {
                return $this->result[0];
            } else {
                return false;
            }
        }

        function add($username, $new_password, $new_password_again, $firstname, $lastname, $email, $picture, $showpic, $autosave, $level)
        {
            if ($GLOBALS['auth']->admin() && md5($new_password) == md5($new_password_again)) {
                if ($picture !== '') {
                    $file = $picture['tmp_name'];
                    $size = $picture['size'];
                    if ($username === '') $username = ' ';
                    if ($new_password === ' ') $new_password = ' ';
                    if ($firstname === '') $firstname = ' ';
                    if ($lastname === '') $lastname = ' ';
                    if ($email === '') $email = ' ';
                    if ($blob === '') $blob = ' ';
                    if ($showpic === '') $showpic = '0';
                    if ($autosave === '') $autosave = '1';
                    if ($level === '') $level = '0';
                    if ($size > 0) {
                        $image = fopen($file, "rb");
                        $blob = base64_encode(fread($image, filesize($file)));
                        $this->result = $GLOBALS['db']->query('INSERT INTO `operators` (`username`, `password`, `firstname`, `lastname`, `email`, `picture`, `showpic`, `autosave`, `level`) VALUES ("'.$username.'", "'.md5($new_password).'", "'.$firstname.'", "'.$lastname.'", "'.$email.'", "'.$blob.'", "'.$showpic.'", "'.$autosave.'", "'.$level.'")');
                    } else {
                        $this->result = $GLOBALS['db']->query('INSERT INTO `operators` (`username`, `password`, `firstname`, `lastname`, `email`, `picture`, `showpic`, `autosave`, `level`) VALUES ("'.$username.'", "'.md5($new_password).'", "'.$firstname.'", "'.$lastname.'", "'.$email.'", " ", "'.$showpic.'", "'.$autosave.'", "'.$level.'")');
                    }
                } else {
                    if ($username === '') $username = ' ';
                    if ($new_password === ' ') $new_password = ' ';
                    if ($firstname === '') $firstname = ' ';
                    if ($lastname === '') $lastname = ' ';
                    if ($email === '') $email = ' ';
                    if ($blob === '') $blob = ' ';
                    if ($showpic === '') $showpic = '0';
                    if ($autosave === '') $autosave = '1';
                    if ($level === '') $level = '0';
                    $this->result = $GLOBALS['db']->query('INSERT INTO `operators` (`username`, `password`, `firstname`, `lastname`, `email`, `picture`, `showpic`, `autosave`, `level`) VALUES ("'.$username.'", "'.md5($new_password).'", "'.$firstname.'", "'.$lastname.'", "'.$email.'", " ", "'.$showpic.'", "'.$autosave.'", "'.$level.'")');
                }
                if ($this->result) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function update($id, $old_password, $new_password, $new_password_again, $firstname, $lastname, $email, $picture, $showpic, $autosave, $level = '', $username = '')
        {
            if ($id == '0') {
                $id = $this->id();
                if (!$this->id()) {
                    return false;
                }
            }
            
            $this->result = $GLOBALS['db']->query('SELECT `password` FROM `operators` WHERE `id`="'.$id.'"');
            $file = $picture['tmp_name'];
            $size = $picture['size'];
            if ($size > 0) {
                $image = fopen($file, "rb");
                $blob = base64_encode(fread($image, filesize($file)));
            }
            
            // Check to see if the password needs to be changed. Make sure the person doing that has the right authentication,
            // so is either the operator editing their details or an admin.
            if ((md5($old_password) == $this->result[0]['password'] || $GLOBALS['auth']->admin()) && $new_password !== '' && $new_password == $new_password_again) {
                if ($level !== '' && $username !== '' && $GLOBALS['auth']->admin()) {
                    if ($size > 0) {
                        if ($GLOBALS['conf']['demomode']===true && $username==='admin'){
                        	// we can't change the password because of demomode...
                        } else {
                    	    $GLOBALS['db']->query('UPDATE `operators` SET `password`="'.md5($new_password).'", `firstname`="'.$firstname.'", `lastname`="'.$lastname.'", `email`="'.$email.'", `autosave`="'.$autosave.'", `showpic`="'.$showpic.'", `picture`="'.$blob.'", `level`="'.$level.'", `username`="'.$username.'" WHERE `id`="'.$id.'"');
                        }
                    } else {
                    	if ($GLOBALS['conf']['demomode']===true && $username==='admin'){
                        	// we can't change the password because of demomode...
                        } else {
                            $GLOBALS['db']->query('UPDATE `operators` SET `password`="'.md5($new_password).'", `firstname`="'.$firstname.'", `lastname`="'.$lastname.'", `email`="'.$email.'", `autosave`="'.$autosave.'", `showpic`="'.$showpic.'", `level`="'.$level.'", `username`="'.$username.'" WHERE `id`="'.$id.'"');
                        }
                    }
                } else {
                    if ($size > 0) {
                        $GLOBALS['db']->query('UPDATE `operators` SET `password`="'.md5($new_password).'", `firstname`="'.$firstname.'", `lastname`="'.$lastname.'", `email`="'.$email.'", `autosave`="'.$autosave.'", `showpic`="'.$showpic.'", `picture`="'.$blob.'" WHERE `id`="'.$id.'"');
                    } else {
                        $GLOBALS['db']->query('UPDATE `operators` SET `password`="'.md5($new_password).'", `firstname`="'.$firstname.'", `lastname`="'.$lastname.'", `email`="'.$email.'", `autosave`="'.$autosave.'", `showpic`="'.$showpic.'", `picture`=" " WHERE `id`="'.$id.'"');
                    }
                }
                if ($id !== '0') {
                    return $GLOBALS['lang']['operator_edited'].'<br />'.$GLOBALS['lang']['operator_password_updated'];
                } else {
                    return $GLOBALS['lang']['details_updated'].'<br />'.$GLOBALS['lang']['password_updated'];
                }
            } else {
                if ($level !== '' && $username !== '' && $GLOBALS['auth']->admin()) {
                    if ($size > 0) {
                        $GLOBALS['db']->query('UPDATE `operators` SET `firstname`="'.$firstname.'", `lastname`="'.$lastname.'", `email`="'.$email.'", `autosave`="'.$autosave.'", `showpic`="'.$showpic.'", `picture`="'.$blob.'", `level`="'.$level.'", `username`="'.$username.'" WHERE `id`="'.$id.'"');
                    } else {
                        $GLOBALS['db']->query('UPDATE `operators` SET `firstname`="'.$firstname.'", `lastname`="'.$lastname.'", `email`="'.$email.'", `autosave`="'.$autosave.'", `showpic`="'.$showpic.'", `picture`=" ", `level`="'.$level.'", `username`="'.$username.'" WHERE `id`="'.$id.'"');
                    }
                } else {
                    if ($size > 0) {
                        $GLOBALS['db']->query('UPDATE `operators` SET `firstname`="'.$firstname.'", `lastname`="'.$lastname.'", `email`="'.$email.'", `autosave`="'.$autosave.'", `showpic`="'.$showpic.'", `picture`="'.$blob.'" WHERE `id`="'.$id.'"');
                    } else {
                        $GLOBALS['db']->query('UPDATE `operators` SET `firstname`="'.$firstname.'", `lastname`="'.$lastname.'", `email`="'.$email.'", `autosave`="'.$autosave.'", `showpic`="'.$showpic.'" `picture`=" " WHERE `id`="'.$id.'"');
                    }
                }
                if ($id !== '0') {
                    return $GLOBALS['lang']['operator_edited'].'<br />'.$GLOBALS['lang']['operator_password_unchanged'];
                } else {
                    return $GLOBALS['lang']['details_updated'].'<br />'.$GLOBALS['lang']['password_unchanged'];
                }
            }
        }

        function listall()
        {
            $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE 1');
            if ($this->result) {
                foreach ($this->result as $key => $val) {
                    if ($GLOBALS['db']->query('SELECT * FROM `activity` WHERE `operatorid`="'.$this->result[$key]['id'].'" AND `status`="loggedin"')) {
                        $this->result[$key]['monitor_status'] = 'client';
                    } elseif ($this->online($this->result[$key]['id'])) {
                        $this->result[$key]['monitor_status'] = 'web';
                    } else {
                        $this->result[$key]['monitor_status'] = 'none';
                    }
                }
            }
            return $this->result;
        }

        function delete($id)
        {
            if ($GLOBALS['db']->query('DELETE FROM `operators` WHERE `id`="'.$id.'"')) {
                $GLOBALS['assign']->delete_operator($id);
                return true;
            } else {
                return false;
            }
        }

        function review($operatorid, $chatid, $transcriptid, $rating, $review)
        {
            if ($rating === '') $rating = '0';
            if ($review === '') $review = ' ';
            $GLOBALS['db']->query('INSERT INTO `reviews` (`operatorid`, `chatid`, `transcriptid`, `timestamp`, `rating`, `review`) VALUES ("'.$operatorid.'", "'.$chatid.'", "'.$transcriptid.'", UNIX_TIMESTAMP(), "'.$rating.'", "'.$review.'")');
        }

    }

?>