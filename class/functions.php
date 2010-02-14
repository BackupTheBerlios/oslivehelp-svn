<?php

    // Copyright � 2004 Michael Bird. All Rights Reserved

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
    // This class contains misc functions that are not really associated with a
    // specific class/genre of functions

    function key_from_match_val2($array, $match)
    {
        $returned = false;
        foreach ($array as $key => $val) {
            foreach ($array[$key] as $key2 => $val2) {
                if ($array[$key][$key2] == $match) {
                    return $key;
                    $returned = true;
                }
            }
        }
        if ($returned !== true) {
            return false;
        }
    }

    function nl_br($arg)
    {
         return preg_replace("!\n!iU", '<br />', $arg);
    }

    function br_nl($arg)
    {
         return preg_replace('!<br.*>!iU', "\n", $arg);
    }

    function secs_to_hms($time)
    {
        $secs = $time;
        if ($secs > 59) {
            $hours = floor($secs / 3600);
            $secs = $secs % 3600;
            $mins = floor($secs / 60);
            $secs = $secs % 60;
            if ($hours > 0) {
                return $hours.' hours '.$mins.' mins '.$secs.' secs';
            } else {
                return $mins.' mins '.$secs.' secs';
            }
        } else {
            return $secs.' secs';
        }
    }

    function ascii_to_html($ascii)
    {
        if ($ascii < '127') {
            return chr($ascii);
        } else {
            return '&#'.$ascii.';';
        }
    }

    function char_to_html($string)
    {
        $html = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $html .= '&#'.ord($string{$i}).';';
        }
        return $html;
    }

    function html_to_char($string) 
    {
        $character = split(";", $string);
        $text = '';
        foreach ($character as $key => $val) {
            if (strlen($character[$key]) > 2) {
                $ascii = substr($character[$key], 2);
                $html = chr($ascii);
                $text = $text.$html;
            }
        }
        return $text;
    }

    function parse_with_session($chatid, $string, $person = '')
    {
        if ($person == 'operator') {
            $string = str_replace('PERSON', $_SESSION['hcl_'.$chatid]['operator'], $string);
        } elseif ($person == 'guest') {
            $string = str_replace('PERSON', $_SESSION['hcl_'.$chatid]['guest'], $string);
        }
        $string = str_replace('GUEST', $_SESSION['hcl_'.$chatid]['guest'], $string);
        $string = str_replace('OPERATOR', $_SESSION['hcl_'.$chatid]['operator'], $string);
        $string = str_replace('DEPARTMENT', $_SESSION['hcl_'.$chatid]['department'], $string);
        return $string;
    }

    if (!function_exists('file_get_contents')) {
        function file_get_contents($file) {
            $file = file($file);
            return !$file ? false : implode('', $file);
        }
    }


?>