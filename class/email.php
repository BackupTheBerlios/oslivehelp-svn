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
    // This class allows emails to be sent using the email templates and
    // data passed into the functions

    class Email {

        function auth($host, $port, $username, $password)
        {
            $GLOBALS['phpmailer']->IsMail(); // set mailer to use SMTP
            $GLOBALS['phpmailer']->Host = $host; // specify main and backup server
            $GLOBALS['phpmailer']->SMTPAuth = true; // turn on SMTP authentication
            $GLOBALS['phpmailer']->Username = $username;  // SMTP username
            $GLOBALS['phpmailer']->Password = $password; // SMTP password
            $GLOBALS['phpmailer']->Port = $port; // SMTP port
        }

        function send($subject, $message, $to, $toname, $from, $fromname)
        {
            $GLOBALS['phpmailer']->From = $from;
            $GLOBALS['phpmailer']->FromName = $fromname;
            $GLOBALS['phpmailer']->AddAddress($to, $toname);
            $GLOBALS['phpmailer']->AddReplyTo($from, $fromname);

            $GLOBALS['phpmailer']->WordWrap = 50;
            $GLOBALS['phpmailer']->IsHTML(true);
            $GLOBALS['phpmailer']->Subject = $subject;
            $GLOBALS['phpmailer']->Body = $message;

            if ($GLOBALS['phpmailer']->Send()) {
                return true;
            } else {
                return false;
            }
        }

        function transcript($guest, $details)
        {
            // open the email template and replace the tags.
            $file = dirname(__FILE__).'/../templates/'.$GLOBALS['conf']['template'].'/email_transcript.tpl';
            if ($fp = fopen($file, 'r')) {
                $message = fread($fp, filesize($file));
                fclose($fp);
            } else {
                $message = '';
            }
            foreach ($GLOBALS['conf'] as $key => $val) {
                if (!is_array($GLOBALS['conf'][$key])) {
                    $message = str_replace('{$conf.'.$key.'}', $val, $message);
                }
            }
            foreach ($GLOBALS['lang'] as $key => $val) {
                $message = str_replace('{$lang.'.$key.'}', $val, $message);
            }
            foreach ($details as $key => $val) {
                $message = str_replace('{$details.'.$key.'}', $val, $message);
            }
            if ($this->send($GLOBALS['conf']['company']." - ".$GLOBALS['lang']['chat_transcript'], $message, $guest['email'], $guest['name'], $details['email'], $GLOBALS['conf']['company'])) {
                return true;
            } else {
                return false;
            }
        }

        function contact($details)
        {
            if ($this->send($details['subject'], $details['message'], $details['email'], $details['department'], $details['from'], $details['name'])) {
                return true;
            } else {
                return false;
            }
        }

    }

?>