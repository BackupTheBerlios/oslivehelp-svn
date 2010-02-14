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
    // This class is used to include other files. This keeps things neat and
    // makes sure other classes are only incuded once, when necessary.

    class Includer {

        var $arg = '';
        var $start_flag = false;
        var $template_flag = false;
        var $db_flag = false;
        var $auth_flag = false;
        var $mothership_flag = false;
        var $file_flag = false;
        var $notes_flag = false;
        var $monitor_flag = false;
        var $operator_flag = false;
        var $department_flag = false;
        var $chat_flag = false;
        var $image_flag = false;
        var $stats_flag = false;
        var $live_flag = false;
        var $poll_flag = false;
        var $transcript_flag = false;
        var $canned_flag = false;
        var $hotpage_flag = false;
        var $assign_flag = false;
        var $cobrowse_flag = false;
        var $module_flag = false;
        var $setup_flag = false;
        var $aardvark_flag = false;
        var $phpmailer_flag = false;
        var $email_flag = false;

        // Start off the script. Do actions such as starting the session that
        // always need to take place in each file.
        function Includer($arg = '')
        {
            $this->arg = $arg;
            if (!$this->start_flag) {
                $this->start_flag = true;

                // Make sure useragent is set
                if (!isset($_SERVER['HTTP_USER_AGENT'])) {
                    $_SERVER['HTTP_USER_AGENT'] = '';
                }
				
                // Block out bots
                if ($this->bot()) {
                    exit;
                }

                // Make sure we are using cookies for sessions
                ini_set('session.use_cookies', 1);
                ini_set('session.cookie_lifetime', 0);
                ini_set('session.cache_limiter', 'nocache');

                //Send P3P - Privacy Content Header to allow cookies in IE6
                header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

                // Start the session
                session_start();

                // Set the session cookie
                setcookie(session_name(), session_id(), time() + 3600, ((strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 6")!== false)? "\\" : "/"));

                // Include all the base files
                include_once(dirname(__FILE__).'/../version.php');
                include_once(dirname(__FILE__).'/../config.php');
                include_once(dirname(__FILE__).'/../class/functions.php');

                switch ($this->arg) {
                    case 'setup':
                        // See if the the setup language has changed
                        if (isset($_SESSION['hcl_language'])) {
                            include_once(dirname(__FILE__).'/../lang/'.$_SESSION['hcl_language']);
                        } else {
                            include_once(dirname(__FILE__).'/../lang/'.$conf['lang'].'.php');
                        }
                        // The database has not been installed yet so we dont want to
                        // try and connect to it as the username and password will not be set.
                        $this->db_flag = true;
                        break;
                    case 'setup_db':
                        // See if the the setup language has changed
                        if (isset($_SESSION['hcl_language'])) {
                            include_once(dirname(__FILE__).'/../lang/'.$_SESSION['hcl_language']);
                        } else {
                            include_once(dirname(__FILE__).'/../lang/'.$conf['lang'].'.php');
                        }
                        break;
                    default:
                        if (file_exists(dirname(__FILE__).'/../setup')) {
                            header('Location: '.$conf['url'].'/setup');
                            exit;
                        }
                        include_once(dirname(__FILE__).'/../lang/'.$conf['lang'].'.php');
                        break;
                }

                // Make the conf and lang arrays global so we can use them elsewhere
                $GLOBALS['conf'] = $conf;
                $GLOBALS['lang'] = $lang;

                // Report all the errors we can find
                error_reporting (E_ALL);

                // Prevent hacking attempts that make use of register_globals
                ini_set("register_globals", "0");
                foreach ($_GET as $key => $val) {
                    if (isset($$key)) {
                        unset($$key);
                    }
                }
                foreach ($_POST as $key => $val) {
                    if (isset($$key)) {
                        unset($$key);
                    }
                }
                foreach ($_COOKIE as $key => $val) {
                    if (is_array($_COOKIE[$key])) {
                        foreach($_COOKIE[$key] as $key2 => $val2) {
                            if (isset($$key)) {
                                unset($$key[$key2]);
                            }
                        }
                    } else {
                        if (isset($$key)) {
                            unset($$key);
                        }
                    }
                }

                // Remove magic_quotes.. they are horrible
                set_magic_quotes_runtime(0);
                if (get_magic_quotes_gpc()) {
                    if (isset($_GET)) {
                        foreach($_GET as $key => $val) {
                            $_GET[$key] = stripslashes($val);
                        }
                    }
                    if (isset($_POST)) {
                        foreach($_POST as $key => $val) {
                            if (is_array($_POST[$key])) {
                                foreach($_POST[$key] as $key2 => $val2) {
                                    $_POST[$key][$key2] = stripslashes($val2);
                                }
                            } else {
                                $_POST[$key] = stripslashes($val);
                            }
                        }
                    }
                    if (isset($_COOKIE)) {
                        foreach($_COOKIE as $key => $val) {
                            if (is_array($_COOKIE[$key])) {
                                foreach($_COOKIE[$key] as $key2 => $val2) {
                                    $_COOKIE[$key][$key2] = stripslashes($val2);
                                }
                            } else {
                                $_COOKIE[$key] = stripslashes($val);
                            }
                        }
                    }
                }

                $this->module();
                $GLOBALS['module']->get_config();
                
            }
        }

        function finished()
        {
            if ($this->db_flag && isset($GLOBALS['db'])) {
                $GLOBALS['db']->disconnect();
            }
        }

        function bot()
        {
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Googlebot") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Mediapartners-Google") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Googlebot-Image") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Fast Crawler") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "YahooFeedSeeker") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "YahooSeeker-Testing") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "YahooSeeker") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Yahoo-MMCrawler") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "YahooVideoSearch") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Yahoo-MMAudVid") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Yahoo-Blogs") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Lycos_Spider") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Infoseek SideWinder") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Scooter/3.3Y!CrawlX") !== false) {
                return true;
            }
            if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Wobot/1.00") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "BizBot04") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "processor/0.0ALPHA") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "EI*Net/0.1") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Ibot/1.0") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Merritt/1.0") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "StatFetcher/1.0") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "TeacherSoft/1.0") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "MyCNNSpider") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "SummyCrawler") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "OGspider") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "CyberSpyder") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "ABCdatos BotLink/1.0.2") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Ahoy! The Homepage Finder") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "AlkalineBOT") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "AnthillV1.1") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "appie/1.1") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Araneo/0.7") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "AraybOt/1.0") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "ArchitextSpider") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "arks/1.0") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "ASpider/0.09") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "ATN_Worldwide") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Atomz/1.0") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "BackRub") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "bbot/0.100") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Big Brother") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Bjaaland/0.5") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "BlackWidow") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Die Blinde Kuh") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "borg-bot/0.9") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "BoxSeaBot/0.5") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "ChristCrawler") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "CMC/0.01") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "LWP") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "CoolBot") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "CyberSpyder/2.1") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "CydralSpider") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "grabber") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "LWP::") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "elfinbot") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "htdig") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "MJ12bot") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Mediapartners-Google") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Exabot") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "FAST Enteprise Crawler") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Findexa Crawler") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "HenryTheMiragoRobot") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "IRLbot") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Lycos_Spider_") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "NetResearchServer") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "ZyBorg") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Mnogosearch") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "NaverBot") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "NextGenSearchBot") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "NimbleCrawler") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "noxtrumbot") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "NuSearch Spider") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Searchmee!") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Seekbot/1.0") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "voyager") !== false) {
            	return true;
            }
    	    if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "OmniExplorer_Bot") !== false) {
            	return true;
            }
        	if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "wwwster") !== false) {
            	return true;
            }
	        if (strpos(addslashes($_SERVER["HTTP_USER_AGENT"]), "Yahoo-Blogs") !== false) {
            	return true;
            }
            
            return false;
        }

        function template()
        {
            if (!$this->template_flag) {
                $this->template_flag = true;
                $this->module();
                include_once(dirname(__FILE__).'/../class/smarty/Smarty.class.php');
                include_once(dirname(__FILE__).'/../class/template.php');
                global $template;
                $template = new Template();
            }
        }

        function mothership()
        {
            if (!$this->mothership_flag) {
                $this->mothership_flag = true;
                include_once(dirname(__FILE__).'/../class/mothership.php');
                global $mothership;
                $mothership = new MotherShip();
            }
        }

        function db()
        {
            if (!$this->db_flag) {
                $this->db_flag = true;
                include_once(dirname(__FILE__).'/../class/database.php');
                global $db;
                $db = new Database();
                $db->connect();
            }
        }

        function auth()
        {
            if (!$this->auth_flag) {
                $this->auth_flag = true;
                $this->db();
                $this->operator();
                include_once(dirname(__FILE__).'/../class/auth.php');
                global $auth;
                $auth = new Auth();
            }
        }

        function file()
        {
            if ($this->arg == 'setup') {
                $this->db_flag = true;
            }
            if (!$this->file_flag) {
                $this->file_flag = true;
                $this->db();
                include_once(dirname(__FILE__).'/../class/file.php');
                global $file;
                $file = new File();
            }
        }

        function notes()
        {
            if (!$this->notes_flag) {
                $this->notes_flag = true;
                $this->db();
                include_once(dirname(__FILE__).'/../class/notes.php');
                global $notes;
                $notes = new Notes();
            }
        }

        function monitor()
        {
            if (!$this->monitor_flag) {
                $this->monitor_flag = true;
                $this->db();
                $this->auth();
                $this->operator();
                $this->department();
                $this->stats();
                $this->hotpage();
                $this->chat();
                include_once(dirname(__FILE__).'/../class/monitor.php');
                global $monitor;
                $monitor = new Monitor();
            }
        }

        function operator()
        {
            if (!$this->operator_flag) {
                $this->operator_flag = true;
                $this->db();
                $this->assign();
                include_once(dirname(__FILE__).'/../class/operator.php');
                global $operator;
                $operator = new Operator();
            }
        }

        function department()
        {
            if (!$this->department_flag) {
                $this->department_flag = true;
                $this->db();
                $this->assign();
                include_once(dirname(__FILE__).'/../class/department.php');
                global $department;
                $department = new Department();
            }
        }

        function chat()
        {
            if (!$this->chat_flag) {
                $this->chat_flag = true;
                $this->db();
                $this->auth();
                $this->live();
                $this->operator();
                $this->department();
                $this->file();
                $this->template();
                $this->stats();
                $this->cobrowse();
                $this->transcript();
                include_once(dirname(__FILE__).'/../class/chat.php');
                global $chat;
                $chat = new Chat();
            }
        }

        function image()
        {
            if (!$this->image_flag) {
                $this->image_flag = true;
                include_once(dirname(__FILE__).'/../class/image.php');
                global $image;
                $image = new Image();
            }
        }

        function stats()
        {
            if (!$this->stats_flag) {
                $this->stats_flag = true;
                $this->db();
                $this->operator();
                $this->department();
                include_once(dirname(__FILE__).'/../class/stats.php');
                global $stats;
                $stats = new Stats();
            }
        }

        function live()
        {
            if (!$this->live_flag) {
                $this->live_flag = true;
                $this->db();
                $this->operator();
                $this->department();
                $this->poll();
                $this->stats();
                $this->hotpage();
                include_once(dirname(__FILE__).'/../class/live.php');
                global $live;
                $live = new Live();
            }
        }

        function poll()
        {
            if (!$this->poll_flag) {
                $this->poll_flag = true;
                $this->db();
                $this->stats();
                include_once(dirname(__FILE__).'/../class/poll.php');
                global $poll;
                $poll = new Poll();
            }
        }

        function transcript()
        {
            if (!$this->transcript_flag) {
                $this->transcript_flag = true;
                $this->db();
                $this->operator();
                $this->department();
                $this->auth();
                include_once(dirname(__FILE__).'/../class/transcript.php');
                global $transcript;
                $transcript = new Transcript();
            }
        }

        function canned()
        {
            if (!$this->canned_flag) {
                $this->canned_flag = true;
                $this->db();
                $this->operator();
                $this->department();
                include_once(dirname(__FILE__).'/../class/canned.php');
                global $canned;
                $canned = new Canned();
            }
        }

        function hotpage()
        {
            if (!$this->hotpage_flag) {
                $this->hotpage_flag = true;
                $this->db();
                include_once(dirname(__FILE__).'/../class/hotpage.php');
                global $hotpage;
                $hotpage = new Hotpage();
            }
        }

        function assign()
        {
            if (!$this->assign_flag) {
                $this->assign_flag = true;
                $this->db();
                $this->operator();
                $this->department();
                include_once(dirname(__FILE__).'/../class/assign.php');
                global $assign;
                $assign = new Assign();
            }
        }

        function cobrowse()
        {
            if (!$this->cobrowse_flag) {
                $this->cobrowse_flag = true;
                $this->db();
                include_once(dirname(__FILE__).'/../class/cobrowse.php');
                global $cobrowse;
                $cobrowse = new CoBrowse();
            }
        }

        function module()
        {
            if (!$this->module_flag) {
                $this->module_flag = true;
                $this->file();
                include_once(dirname(__FILE__).'/../class/module.php');
                global $module;
                $module = new Module();
            }
        }

        function setup()
        {
            if (!$this->setup_flag) {
                $this->setup_flag = true;
                $this->file();
                include_once(dirname(__FILE__).'/../class/setup.php');
                global $setup;
                $setup = new Setup();
            }
        }

        function aardvark()
        {
            if (!$this->aardvark_flag) {
                $this->aardvark_flag = true;
                include_once(dirname(__FILE__).'/../class/aardvark.php');
                global $aardvark;
                $aardvark = new Aardvark();
            }
        }

        function phpmailer()
        {
            if (!$this->phpmailer_flag) {
                $this->phpmailer_flag = true;
                include_once(dirname(__FILE__).'/../class/phpmailer/class.phpmailer.php');
                include_once(dirname(__FILE__).'/../class/phpmailer/class.smtp.php');
                global $phpmailer;
                $phpmailer = new PHPMailer();
            }
        }

        function email()
        {
            if (!$this->email_flag) {
                $this->email_flag = true;
                $this->phpmailer();
                include_once(dirname(__FILE__).'/../class/email.php');
                global $email;
                $email = new Email();
            }
        }

    }

?>