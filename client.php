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
    // This file is used to exchange data between HCL and the WinApp, or any
    // other external application that interacts with HCL

    include_once('./class/include.php');
    $inc = new Includer();
    $inc->auth();
    $inc->monitor();
    $inc->image();
    $inc->department();
    $inc->chat();
    $inc->canned();
    $inc->file();
    $inc->cobrowse();

    // Stop db cache
    $GLOBALS['conf']['safe_mode'] = true;

    if (isset($_GET['cobrowsepage'])) {
        $_SESSION['hcl_cobrowse'] = 'true';
        header('Location: '.rawurldecode(addslashes($_GET['url'])));
        exit;
    }

    // Check that the login info is correct
    if (!$GLOBALS['auth']->operator()) {
        if (isset($_GET['username']) && isset($_GET['password'])) {
            $login = $GLOBALS['auth']->login(addslashes($_GET['username']), addslashes($_GET['password']), 'no_md5');
            if (!$login) {
                echo('[login|failure]');
                exit;
            }
        } else {
            echo('[login|no_input]');
            exit;
        }
    }

    if (isset($_GET['exit'])) {
        $GLOBALS['monitor']->status('off');
        $GLOBALS['db']->query('UPDATE `operators` SET `timestamp`="9" WHERE `id`="'.$GLOBALS['operator']->id().'"');
        // Tell eveyone the operator has logged out
        $GLOBALS['db']->query('DELETE FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'"');
        $GLOBALS['monitor']->status('off');
        
        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['connect'])) {
        // Delete old connection status
        $GLOBALS['db']->query('DELETE FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'" AND `status`="loggedout"');


        // Let the client know which HCL version is in use
        echo('[hcl|'.$GLOBALS['conf']['version'].']');

        $departments = $GLOBALS['department']->listall('', 'all');
        if (isset($departments[0])) {
            foreach ($departments as $key => $val) {
                echo('[department|'.$departments[$key]['id'].'|'.$departments[$key]['name'].']');
            }
        }

        // Let the client know what template is in use
        echo('[template|'.$GLOBALS['conf']['template'].']');

        // Let the client know how much its allowed to upload at once
        echo('[maxupload|'.$GLOBALS['file']->max_size().']');

        echo('[login|success]');
    
        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['disconnect'])) {
        // Delete old connection status
        $GLOBALS['db']->query('DELETE FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'"');
        $GLOBALS['monitor']->status('off');
        $GLOBALS['db']->query('UPDATE `operators` SET `timestamp`="9" WHERE `id`="'.$GLOBALS['operator']->id().'"');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['accept_chat'])) {
        $GLOBALS['monitor']->accept_chat(0, addslashes($_GET['chatid']));
        echo('[accept_chat|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['decline_chat'])) {
        $GLOBALS['monitor']->decline_chat(0, addslashes($_GET['chatid']));
        echo('[decline_chat|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['accept_opchat'])) {
        $GLOBALS['monitor']->accept_opchat(0, addslashes($_GET['chatid']));
        echo('[accept_opchat|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['decline_opchat'])) {
        $GLOBALS['monitor']->decline_opchat(0, addslashes($_GET['chatid']));
        echo('[decline_opchat|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['accept_transfer'])) {
        $GLOBALS['monitor']->accept_transfer(0, addslashes($_GET['chatid']));
        echo('[accept_transfer|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['decline_transfer'])) {
        $GLOBALS['monitor']->decline_transfer(0, addslashes($_GET['chatid']));
        echo('[decline_chat|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['hold_chat'])) {
        if ($GLOBALS['chat']->hold('true', addslashes($_GET['chatid']))) {
            echo('[hold_chat|success]');
        } else {
            echo('[hold_chat|failure]');
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['resume_chat'])) {
        if ($GLOBALS['chat']->resume('true', addslashes($_GET['chatid']))) {
            echo('[resume_chat|success]');
        } else {
            echo('[resume_chat|failure]');
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['online'])) {
        $GLOBALS['monitor']->status('on');
        echo('[online|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['offline'])) {
        $GLOBALS['monitor']->status('off');
        echo('[offline|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['initiate'])) {
        $GLOBALS['monitor']->initiate(addslashes($_GET['departmentid']), $GLOBALS['operator']->id(), addslashes($_GET['chatid']));
        echo('[initiate|success]');
 
        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['canned'])) {
        $canned = $GLOBALS['canned']->get($GLOBALS['operator']->id(), addslashes($_GET['departmentid']));
        if (isset($canned['all'][0])) {
            foreach ($canned['all'] as $key => $val) {
                echo('[canned|all|'.char_to_html($canned['all'][$key]['subject']).'|'.char_to_html($canned['all'][$key]['message']).']');
            }
        }
        if (isset($canned['op'][0])) {
            foreach ($canned['op'] as $key => $val) {
                echo('[canned|op|'.char_to_html($canned['op'][$key]['subject']).'|'.char_to_html($canned['op'][$key]['message']).']');
            }
        }
        if (isset($canned['dep'][0])) {
            foreach ($canned['dep'] as $key => $val) {
                echo('[canned|dep|'.char_to_html($canned['dep'][$key]['subject']).'|'.char_to_html($canned['dep'][$key]['message']).']');
            }
        }
        if (isset($canned['both'][0])) {
            foreach ($canned['both'] as $key => $val) {
                echo('[canned|both|'.char_to_html($canned['both'][$key]['subject']).'|'.char_to_html($canned['both'][$key]['message']).']');
            }
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['footprints'])) {
        $info = $GLOBALS['monitor']->info(addslashes($_GET['chatid']));
        if (isset($info['footprints'][0])) {
            foreach ($info['footprints'] as $key => $val) {
                echo('[footprint|'.$info['footprints'][$key]['hotpage'].'|'.$info['footprints'][$key]['url'].'|'.$info['footprints'][$key]['time'].']');
            }
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['history'])) {
        $info = $GLOBALS['monitor']->info(addslashes($_GET['chatid']));
        if (isset($info['history'][0])) {
            foreach ($info['history'] as $key => $val) {
                echo('[history|'.$info['history'][$key]['hotpage'].'|'.$info['history'][$key]['url'].'|'.$info['history'][$key]['time'].']');
            }
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['end_chat'])) {
        $GLOBALS['chat']->end(addslashes($_GET['chatid']));
        echo('[end_chat|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['send']) && isset($_GET['message'])) {
        if ($_GET['message'] !== '') {
            $GLOBALS['chat']->session(addslashes($_GET['chatid']));
            $GLOBALS['chat']->send(addslashes($_GET['chatid']), addslashes($_GET['message']), 'true', 'false');
        }
        echo('[send|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['typing'])) {
        $GLOBALS['chat']->typing(addslashes($_GET['chatid']));
        echo('[typing|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['typed'])) {
        $GLOBALS['chat']->typed(addslashes($_GET['chatid']));
        echo('[typed|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['transfer'])) {
        $GLOBALS['chat']->transfer(addslashes($_GET['chatid']), addslashes($_GET['operatorid']), addslashes($_GET['departmentid']));
        echo('[transferchat|pending]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['transferchat'])) {
        $transfer = $GLOBALS['live']->avaliable_department('', 'all');
        if (isset($transfer[0])) {
            foreach ($transfer as $key => $val) {
                if (isset($transfer[$key]['operators'][0])) {
                    foreach($transfer[$key]['operators'] as $key2 => $val2) {
                        echo('[transferchat|'.$transfer[$key]['id'].'|'.$transfer[$key]['name'].'|'.$transfer[$key]['operators'][$key2]['id'].'|'.$transfer[$key]['operators'][$key2]['name'].'|'.$transfer[$key]['operators'][$key2]['status'].']');
                    }
                }
            }
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['transfermessages'])) {
        $GLOBALS['chat']->session(addslashes($_GET['chatid']));
        $messages = $GLOBALS['chat']->get_messages('true', addslashes($_GET['chatid']));
        if (isset($messages[0])) {
            foreach ($messages as $key => $val) {
                echo('[message|'.char_to_html($messages[$key]['name']).'|'.char_to_html($messages[$key]['message']).']');
            }
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['transferfile'])) {
        if ($_FILES['file']['size'] > 0) {
            $GLOBALS['file']->insert('true', $_FILES['file'], addslashes($_GET['chatid']));
            echo('[transferfile|success]');
        } else {
            echo('[transferfile|failure]');
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['requestfile'])) {
        $GLOBALS['file']->request_transfer(addslashes($_GET['chatid']));
        echo('[requestfile|success]');

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['cobrowse_start'])) {
        if ($GLOBALS['cobrowse']->start(addslashes($_GET['chatid']))) {
            echo('[cobrowse_start|success]');
        } else {
            echo('[cobrowse_start|failure]');
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['cobrowse_stop'])) {
        if ($GLOBALS['cobrowse']->disconnect(addslashes($_GET['chatid']))) {
            echo('[cobrowse_stop|success]');
        } else {
            echo('[cobrowse_stop|failure]');
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['cobrowse_changepage'])) {
        if ($GLOBALS['cobrowse']->changepage(addslashes($_GET['chatid']), rawurldecode(addslashes($_GET['url'])))) {
            echo('[cobrowse_changepage|success]');
        } else {
            echo('[cobrowse_changepage|failure]');
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['cobrowse_marker'])) {
        if ($_GET['cobrowse_marker'] == 'clear') {
            if ($GLOBALS['cobrowse']->clearmarkers(addslashes($_GET['chatid']), 'true')) {
                echo('[cobrowse_marker|cleared]');
            } else {
                echo('[cobrowse_marker|failure]');
            }
        } else {
            if ($GLOBALS['cobrowse']->setmarker(addslashes($_GET['chatid']), addslashes($_GET['x']), addslashes($_GET['y']), addslashes($_GET['cobrowse_marker']))) {
                echo('[cobrowse_marker|success|'.addslashes($_GET['cobrowse_marker']).'|'.addslashes($_GET['x']).'|'.addslashes($_GET['y']).']');
            } else {
                echo('[cobrowse_marker|failure]');
            }
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['coforms'])) {
        if ($GLOBALS['cobrowse']->setforms(addslashes($_GET['chatid']), addslashes($_GET['type']), char_to_html(rawurldecode(addslashes($_GET['name']))), char_to_html(rawurldecode(addslashes($_GET['value']))), 'true')) {
            echo('[coforms|success]');
        } else {
            echo('[coforms|failure]');
        }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();

        exit;
    } elseif (isset($_GET['cobrowse'])) {
        if ($GLOBALS['db']->query('SELECT * FROM `cobrowse` WHERE `chatid`="'.addslashes($_GET['chatid']).'"')) {
            
            $GLOBALS['chat']->session(addslashes($_GET['chatid']));

            if (!$GLOBALS['db']->query('SELECT `id` FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'"')) {
                echo('[close|true]');
            } else {
                if ($forms = $GLOBALS['cobrowse']->getforms(addslashes($_GET['chatid']), 'true')) {
                    foreach ($forms as $key => $val) {
                        echo('[element|'.$forms[$key]['type'].'|'.$forms[$key]['name'].'|'.$forms[$key]['value'].']');
                    }
                } else {
                    echo('[activity|false]');
                }

            }
        } else {
            echo('[close|true]');
        }
    } elseif (isset($_GET['chat'])) {

        $GLOBALS['chat']->session(addslashes($_GET['chatid']));

        // Do processing
        if (!$GLOBALS['db']->query('SELECT `id` FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'"')) {
            echo('[close|true]');
        } else {

            $message = false;
            $typing = false;

            $action = $GLOBALS['chat']->response(addslashes($_GET['chatid']), 'false', 'true', '');

            if (preg_match("/\|message:(.*)\|/", $action, $matches)) {
                $message = true;
                echo('[message|'.$matches[1].']');
            }

            if (isset($_GET['resume'])) {
                $messages = $GLOBALS['chat']->get_messages('true', addslashes($_GET['chatid']));
                foreach ($messages as $key => $val) {
                    echo('[message_resume|'.$messages[$key]['x'].'|'.$messages[$key]['name'].'|'.$messages[$key]['message'].']');
                }
            }

            if (preg_match("/\|download:(.*):(.*)\|/", $action, $matches)) {
                echo('[transferfile|'.char_to_html(urldecode($matches[1])).'|'.$GLOBALS['conf']['url'].'/live/chat/download.php?client&id='.$matches[2].'&admin&time='.time().']');
            }

            if ($action !== '') {
                $action = explode('|', $action);
                foreach ($action as $key => $val) {
                    switch ($action[$key]) {
                        case 'typing':
                            echo('[typing|true]');
                            $typing = true;
                            break;
                        case 'transfer-yes':
                            echo('[transferchat|success]');
                            break;
                        case 'transfer-no':
                            echo('[transferchat|failure]');
                            break;
                        case 'close':
                            echo('[close|true]');
                            exit;
                            break;
                    }
                }
            }

            if ($message || !$typing) {
                //not typing
                echo('[typing|false]');
            }

            if ($GLOBALS['cobrowse']->enabled(addslashes($_GET['chatid']))) {
                echo('[cobrowse|true]');
            } else {
                echo('[cobrowse|false]');
            }

        }
        
    } elseif(isset($_GET['monitor'])) {

        if(isset($_GET['id'])) {
            $id = addslashes($_GET['id']);
            if ($result = $GLOBALS['db']->query('SELECT `id` FROM `activity` WHERE `id`="'.$id.'"')) {
            	if ($result[0]['status'] != 'loggedin') {
                	$GLOBALS['db']->query('UPDATE `activity` SET `status`="loggedin" WHERE `id`="'.$id.'"');
                }
            } else {
                if ($GLOBALS['conf']['boot_client']) {
                    // Stop if the operator has logged out
                    echo('[logout|true]');
                    exit;
                }
            }
        } else {
            // Tell eveyone the operator has logged in
            $GLOBALS['db']->query('DELETE FROM `activity` WHERE `operatorid`="'.$GLOBALS['operator']->id().'"');
            $GLOBALS['db']->query('INSERT INTO `activity` (`operatorid`, `status`) VALUES ("'.$GLOBALS['operator']->id().'", "loggedin")');
            $id = $GLOBALS['db']->id();
        }

        echo('[id|'.$id.']');

        // Do processing
        if ($GLOBALS['db']->query('SELECT `id` FROM `activity` WHERE  `id`="'.$id.'"')) {

            if ($GLOBALS['monitor']->response() == '|') {
                echo('[activity|false]');
            } else {
                echo('[activity|true]');
            }

            $GLOBALS['monitor']->result = array();
            $GLOBALS['monitor']->result2 = array();
            $GLOBALS['monitor']->sounds = array();
            $GLOBALS['monitor']->chats = false;
            $GLOBALS['monitor']->visitors = false;

            if ($chats = $GLOBALS['monitor']->chats(true)) {
                foreach ($chats as $key => $val) {
                    echo('[chat|'.$chats[$key]['chatid'].'|'.$chats[$key]['assignid'].'|'.$chats[$key]['departmentid'].'|'.$chats[$key]['operatorid'].'|'.$GLOBALS['department']->name($chats[$key]['departmentid']).'|'.$chats[$key]['nick'].'|'.$chats[$key]['alert'].'|'.$chats[$key]['timeo'].'|'.$chats[$key]['timeg'].'|'.$chats[$key]['typeo'].'|'.$chats[$key]['typeg'].'|'.$chats[$key]['transfer'].'|'.$chats[$key]['type'].'|'.$chats[$key]['title'].'|'.$chats[$key]['name'].']');
                }
            }
            if ($visitors = $GLOBALS['monitor']->visitors()) {
                foreach ($visitors as $key => $val) {
                    $timeonsite = secs_to_hms(time() - $visitors[$key]['start']);
                    $timeonpage = secs_to_hms(time() - $visitors[$key]['pagestart']);
                    echo('[visitor|'.$visitors[$key]['id'].'|'.$visitors[$key]['timestamp'].'|'.$visitors[$key]['ip'].'|'.str_replace('|', '/', $visitors[$key]['useragent']).'|'.str_replace('|', '/', $visitors[$key]['hostname']).'|'.str_replace('|', '/', $visitors[$key]['referrer']).'|'.$visitors[$key]['requests'].'|'.$visitors[$key]['visits'].'|'.$visitors[$key]['start'].'|'.str_replace('|', '/', $visitors[$key]['url']).'|'.$visitors[$key]['chatting'].'|'.$visitors[$key]['status'].'|'.$timeonsite.'|'.$timeonpage.'|'.$visitors[$key]['keywords']['engine'].'|'.$visitors[$key]['keywords']['words'].']');
                }
            }
            if ($operators = $GLOBALS['live']->avaliable_operator()) {
                foreach ($operators as $key => $val) {
                    echo('[operator|'.$operators[$key]['id'].'|'.$operators[$key]['username'].'|'.$operators[$key]['name'].'|'.$operators[$key]['status'].']');
                }
            }
            if ($sounds = $GLOBALS['monitor']->sounds()) {
                foreach ($sounds as $key => $val) {
                    echo('[sound|'.$sounds[$key]['name'].']');
                }
            }

        }
    }

        // Update cache
        $GLOBALS['conf']['safe_mode'] = false;
        $db->clear_cache();
        // do events that need to be done at the end of the file
        $inc->finished();
        
?>