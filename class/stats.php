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
    // This class contains functions to gather and retreive statistics

    class Stats {

        var $result;
        var $result2;
        var $stats;
        var $count;
        var $timestamp;
        var $timestamp_from;
        var $timestamp_to;

        function visitors($from_day, $from_month, $from_year, $to_day, $to_month, $to_year)
        {
            $this->timestamp = mktime(0, 0, 0, $from_month, $from_day, $from_year);
            $this->timestamp_to = mktime(0, 0, 0, $to_month, $to_day, $to_year);
            $this->stats = array();
            $this->stats['visits'] = '0';
            $this->stats['hits'] = '0';
            $this->stats['requests'] = '0';
            while ($this->timestamp <= $this->timestamp_to) {
                $this->result = $GLOBALS['db']->query('SELECT * FROM `stats_visitors` WHERE `timestamp`="'.$this->timestamp.'"');
                $this->stats['visits'] = $this->stats['visits'] + $this->result[0]['visits'];
                $this->stats['hits'] = $this->stats['hits'] + $this->result[0]['hits'];
                $this->stats['requests'] = $this->stats['requests'] + $this->result[0]['requests'];
                $this->timestamp = $this->timestamp + (60*60*24);
            }
            if ($this->stats['visits'] > 0) {
                $this->stats['hits_per_visit'] = round(($this->stats['hits'] / $this->stats['visits']), 2);
            } else {
                $this->stats['hits_per_visit'] = '?';
            }
            return $this->stats;
        }

        function referrers($limit = 25)
        {
            $this->result = $GLOBALS['db']->raw_query('SELECT `url`, COUNT(*) AS `count` FROM `'.$GLOBALS['conf']['prefix'].'stats_referrers` GROUP BY `url` ORDER BY `count` DESC LIMIT '.$limit, 'stats_referrers');
            if ($this->result[0]) {
                foreach ($this->result as $key => $val) {
                    if (strlen($this->result[$key]['url']) > 75) {
                        $this->result[$key]['title'] = substr($this->result[$key]['url'], 0, 55).'...'.substr($this->result[$key]['url'], -20, 20);
                    } else {
                        $this->result[$key]['title'] = $this->result[$key]['url'];
                    }
                }
            }
            return $this->result;
        }

        function reviews($id, $limit = 5)
        {
            if ($this->result = $GLOBALS['db']->query('SELECT * FROM `reviews` WHERE `operatorid`="'.$id.'" ORDER BY `timestamp` DESC LIMIT '.$limit.';')) {
            	foreach ($this->result as $key => $val) {
               	 	$this->result[$key]['time'] = date('G:i:s D jS F Y', $this->result[$key]['timestamp'] + (3600 * $GLOBALS['conf']['offset']));
            	}
            	return $this->result;
            } else {
            	return array();
            }
        }

        function operator($id)
        {  
            $this->result = $GLOBALS['db']->query('SELECT * FROM `operators` WHERE `id`="'.$id.'"');
            $this->result[0]['onlinetime'] = secs_to_hms($this->result[0]['onlinetime']);
            $this->result2 = $GLOBALS['db']->query('SELECT * FROM `reviews` WHERE `operatorid`="'.$id.'"');
            $total = 0;
            if (isset($this->result2[0])) {
                foreach ($this->result2 as $key => $val) {
                    $total += $this->result2[$key]['rating'];
                }
                $this->result[0]['rating'] = $total / count($this->result2);
            } else {
                $this->result[0]['rating'] = 0;
            }
            return $this->result[0];
        }

        function accept($id)
        {
            // called when a chat request is accepted. $id is operators id
            $this->result = $GLOBALS['db']->query('SELECT `accepts` FROM `operators` WHERE `id`="'.$id.'"');
            $this->count = $this->result[0]['accepts'] + 1;
            if ($GLOBALS['db']->query('UPDATE `operators` SET `accepts`="'.$this->count.'" WHERE `id`="'.$id.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function decline($id)
        {
            // called when a chat request is declined. $id is operators id
            $this->result = $GLOBALS['db']->query('SELECT `declines` FROM `operators` WHERE `id`="'.$id.'"');
            $this->count = $this->result[0]['declines'] + 1;
            if ($GLOBALS['db']->query('UPDATE `operators` SET `declines`="'.$this->count.'" WHERE `id`="'.$id.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function initiate($id)
        {
            // called when a chat request is initiated by the operator. $id is operators id
            $this->result = $GLOBALS['db']->query('SELECT `initiates` FROM `operators` WHERE `id`="'.$id.'"');
            $this->count = $this->result[0]['initiates'] + 1;
            if ($GLOBALS['db']->query('UPDATE `operators` SET `initiates`="'.$this->count.'" WHERE `id`="'.$id.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function transfer_accept($id)
        {
            // called when a transfer request is accepted. $id is the receiving operator
            $this->result = $GLOBALS['db']->query('SELECT `accepts_transfer` FROM `operators` WHERE `id`="'.$id.'"');
            $this->count = $this->result[0]['accepts_transfer'] + 1;
            if ($GLOBALS['db']->query('UPDATE `operators` SET `accepts_transfer`="'.$this->count.'" WHERE `id`="'.$id.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function transfer_decline($id)
        {
            // called when a transfer request is accepted. $id is the receiving operator
            $this->result = $GLOBALS['db']->query('SELECT `declines_transfer` FROM `operators` WHERE `id`="'.$id.'"');
            $this->count = $this->result[0]['declines_transfer'] + 1;
            if ($GLOBALS['db']->query('UPDATE `operators` SET `declines_transfer`="'.$this->count.'" WHERE `id`="'.$id.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function opchat_accept($id)
        {
            // called when an operator chat is accepted
            $this->result = $GLOBALS['db']->query('SELECT `accepts_opchat` FROM `operators` WHERE `id`="'.$id.'"');
            $this->count = $this->result[0]['accepts_opchat'] + 1;
            if ($GLOBALS['db']->query('UPDATE `operators` SET `accepts_opchat`="'.$this->count.'" WHERE `id`="'.$id.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function opchat_decline($id)
        {
            // called when an operator chat is declined
            $this->result = $GLOBALS['db']->query('SELECT `declines_opchat` FROM `operators` WHERE `id`="'.$id.'"');
            $this->count = $this->result[0]['declines_opchat'] + 1;
            if ($GLOBALS['db']->query('UPDATE `operators` SET `declines_opchat`="'.$this->count.'" WHERE `id`="'.$id.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function visit($chatid, $useragent)
        {
            // called when there is a new visitor
            // $useragent has been javascript escape()
            $this->timestamp = mktime(0, 0, 0, gmdate("n", time() + (3600 * $GLOBALS['conf']['offset'])), gmdate("j", time() + (3600 * $GLOBALS['conf']['offset'])), gmdate("Y", time() + (3600 * $GLOBALS['conf']['offset'])));
            $this->result = $GLOBALS['db']->query('SELECT `visits` FROM `stats_visitors` WHERE `timestamp`="'.$this->timestamp.'"');
            if (!$this->result) {
                $GLOBALS['db']->query('INSERT INTO `stats_visitors` (`timestamp`) VALUES ("'.$this->timestamp.'")');
                $this->result = $GLOBALS['db']->query('SELECT `visits` FROM `stats_visitors` WHERE `timestamp`="'.$this->timestamp.'"');
            }
            $this->count = $this->result[0]['visits'] + 1;
            if ($GLOBALS['db']->query('UPDATE `stats_visitors` SET `visits`="'.$this->count.'" WHERE `timestamp`="'.$this->timestamp.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function hit($chatid)
        {
            // called when a visitor hits a new page
            $this->timestamp = mktime(0, 0, 0, gmdate("n", time() + (3600 * $GLOBALS['conf']['offset'])), gmdate("j", time() + (3600 * $GLOBALS['conf']['offset'])), gmdate("Y", time() + (3600 * $GLOBALS['conf']['offset'])));
            $this->result = $GLOBALS['db']->query('SELECT `hits` FROM `stats_visitors` WHERE `timestamp`="'.$this->timestamp.'"');
            if (!$this->result) {
                $GLOBALS['db']->query('INSERT INTO `stats_visitors` (`timestamp`) VALUES ("'.$this->timestamp.'")');
                $this->result = $GLOBALS['db']->query('SELECT `hits` FROM `stats_visitors` WHERE `timestamp`="'.$this->timestamp.'"');
            }
            $this->count = $this->result[0]['hits'] + 1;
            if ($GLOBALS['db']->query('UPDATE `stats_visitors` SET `hits`="'.$this->count.'" WHERE `timestamp`="'.$this->timestamp.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function request($chatid)
        {
            // called when a new chat request is made
            $this->timestamp = mktime(0, 0, 0, gmdate("n", time() + (3600 * $GLOBALS['conf']['offset'])), gmdate("j", time() + (3600 * $GLOBALS['conf']['offset'])), gmdate("Y", time() + (3600 * $GLOBALS['conf']['offset'])));
            $this->result = $GLOBALS['db']->query('SELECT `requests` FROM `stats_visitors` WHERE `timestamp`="'.$this->timestamp.'"');
            if (!$this->result) {
                $GLOBALS['db']->query('INSERT INTO `stats_visitors` (`timestamp`) VALUES ("'.$this->timestamp.'")');
                $this->result = $GLOBALS['db']->query('SELECT `requests` FROM `stats_visitors` WHERE `timestamp`="'.$this->timestamp.'"');
            }
            $this->count = $this->result[0]['requests'] + 1;
            if ($GLOBALS['db']->query('UPDATE `stats_visitors` SET `requests`="'.$this->count.'" WHERE `timestamp`="'.$this->timestamp.'"')) {
                return true;
            } else {
                return false;
            }
        }

        function referrer($chatid, $referrer)
        {
            // called when there is a visitor with a referral url avalaible
            // $referrer has been javascript escape()
            $GLOBALS['db']->query('INSERT INTO `stats_referrers` (`chatid`, `timestamp`, `url`) VALUES ("'.$chatid.'", UNIX_TIMESTAMP(), "'.$referrer.'")');
        }

        function opchat($chatid)
        {
            // called when a new operator chat request is made
        }

        function poll($chatid, $assignid)
        {
            //called when a chat request is polled to an operator
        }

    }

?>