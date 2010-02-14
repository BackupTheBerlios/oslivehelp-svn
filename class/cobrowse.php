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
    // This class contains all the CoBrowse functions

    class CoBrowse {

        var $result;
        var $result2;
        var $page;
        var $response;

        function avaliable($chatid)
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `footprints` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1')) {
                if ($this->result[0]['cobrowse'] !== '1') {
                    $GLOBALS['db']->query('UPDATE `footprints` SET `cobrowse`="1" WHERE `id`="'.$this->result[0]['id'].'"');
                }
                return true;
            } else {
                return false;
            }
        }

        function enabled($chatid) {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `footprints` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1')) {
                if ($this->result[0]['cobrowse'] == '1') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function start($chatid)
        {
            $GLOBALS['db']->query('DELETE FROM `cobrowse` WHERE `chatid`="'.$chatid.'"');
            $this->result = $GLOBALS['db']->query('SELECT * FROM `footprints` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1');
            if ($this->result[0]['cobrowse'] == '1') {
                if ($GLOBALS['db']->query('INSERT INTO `cobrowse` (`chatid`, `page`) VALUES ("'.$chatid.'", "'.$this->result[0]['page'].'")')) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function disconnect($chatid)
        {
            if ($GLOBALS['db']->query('DELETE FROM `cobrowse` WHERE `chatid`="'.$chatid.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function changepage($chatid, $url)
        {
            if ($this->page = $this->getpage($chatid)) {
                if ($this->page !== $url) {
                    $GLOBALS['db']->query('UPDATE `cobrowse` SET `page`="'.$url.'" WHERE `chatid`="'.$chatid.'"');
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function changedpage($chatid, $url = '')
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `cobrowse` WHERE `chatid`="'.$chatid.'"')) {
                if ($url != '') {
                    if ($this->result[0]['page'] !== $url) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    $this->result2 = $GLOBALS['db']->query('SELECT * FROM `footprints` WHERE `chatid`="'.$chatid.'" ORDER BY `id` DESC LIMIT 1');
                    if ($this->result[0]['page'] !== $this->result2[0]['page']) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }

        function getpage($chatid)
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `cobrowse` WHERE `chatid`="'.$chatid.'"')) {
                return $this->result[0]['page'];
            } else {
                return false;
            }
        }

        function setmarker($chatid, $x, $y, $type)
        {
            if ($GLOBALS['db']->query('INSERT INTO `comarker` (`chatid`, `x`, `y`, `type`) VALUES ("'.$chatid.'", "'.$x.'", "'.$y.'", "'.$type.'")')) {
                return true;
            } else {
                return false;
            }
        }

        function getmarker($chatid)
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `comarker` WHERE `chatid`="'.$chatid.'" AND `receivedg`="0" LIMIT 1')) {
                $GLOBALS['db']->query('UPDATE `comarker` SET `receivedg`="1" WHERE `id`="'.$this->result[0]['id'].'"');
                return $this->result[0];
            } else {
                return false;
            }
        }

        function clearmarkers($chatid, $admin)
        {
            if ($admin == 'true') {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `comarker` WHERE `chatid`="'.$chatid.'"')) {
                    foreach ($this->result as $key => $val) {
                        $GLOBALS['db']->query('UPDATE `comarker` SET `clear`="1" WHERE `id`="'.$this->result[0]['id'].'"');
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `comarker` WHERE `chatid`="'.$chatid.'" AND `clear`="1"')) {
                    foreach ($this->result as $key => $val) {
                        $GLOBALS['db']->query('DELETE FROM `comarker` WHERE `id`="'.$this->result[0]['id'].'"');
                    }
                    return true;
                } else {
                    return false;
                }
            }
        }

        function getforms($chatid, $admin, $update = '')
        {
            if ($admin == 'true') {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `coforms` WHERE `chatid`="'.$chatid.'" AND `receivedo`="0"')) {
                    foreach ($this->result as $key => $val) {
                        $GLOBALS['db']->query('UPDATE `coforms` SET `receivedo`="1" WHERE `id`="'.$this->result[$key]['id'].'"');
                    }
                    return $this->result;
                } else {
                    return false;
                }
            } else {
                if ($this->result = $GLOBALS['db']->query('SELECT * FROM `coforms` WHERE `chatid`="'.$chatid.'" AND `receivedg`="0"')) {
                    if ($update !== 'false') {
                        foreach ($this->result as $key => $val) {
                            $GLOBALS['db']->query('UPDATE `coforms` SET `receivedg`="1" WHERE `id`="'.$this->result[$key]['id'].'"');
                        }
                    }
                    return $this->result;
                } else {
                    return false;
                }
            }
        }

        function setforms($chatid, $type, $name, $value, $admin)
        {
            $name = char_to_html($name);
            $value = char_to_html($value);
            if ($admin == 'true') {
                if ($GLOBALS['db']->query('INSERT INTO `coforms` (`chatid`, `receivedo`, `type`, `name`, `value`) VALUES ("'.$chatid.'", "1", "'.$type.'", "'.$name.'", "'.$value.'")')) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($GLOBALS['db']->query('INSERT INTO `coforms` (`chatid`, `receivedg`, `type`, `name`, `value`) VALUES ("'.$chatid.'", "1", "'.$type.'", "'.$name.'", "'.$value.'")')) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        function started()
        {
            if ($GLOBALS['db']->query('SELECT * FROM `cobrowse` WHERE `notified`="0" AND `chatid`="'.$GLOBALS['live']->chatid().'"')) {
                return true;
            } else {
                return false;
            }
        }

        function notify()
        {
            $GLOBALS['db']->query('UPDATE `cobrowse` SET `notified`="1" WHERE `chatid`="'.$GLOBALS['live']->chatid().'"');
        }

        function response()
        {
            $this->response = '|';
            if (!$this->getpage($GLOBALS['live']->chatid())) {
                $this->response .= 'disconnect|';
            } else {
                if ($this->getforms($GLOBALS['live']->chatid(), 'false', 'false')) {
                    $this->response .= 'getforms|';
                } else {
                    $this->response .= 'handleforms|';
                }
            }
            return $this->response;
        }

    }

?>