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
    // This file allows the uploading of files that are going to be transferred 

    include_once('../../class/include.php');
    $inc = new Includer();
    $inc->template();
    $inc->file();

    if (isset($_FILES['file'])) {
        $GLOBALS['template']->assign('submit', 'true');
        if ($_FILES["file"]["size"] > 0) {
            $size = $GLOBALS['file']->insert('false', $_FILES["file"], addslashes($_GET['chatid']));
            $GLOBALS['template']->assign('onload', ' onload="window.opener.parent.window.Chat.file(\''.$size.'\');window.close();"');
            $GLOBALS['template']->assign('fail', 'false');
        } else {
            $GLOBALS['template']->assign('fail', 'true');
        }
    }

    $GLOBALS['template']->assign('chatid', addslashes($_GET['chatid']));
    $GLOBALS['template']->assign('max_size', $GLOBALS['file']->max_size());

    // Display the output
    $GLOBALS['template']->display('chat_upload.tpl');
    
    // do events that need to be done at the end of the file
    $inc->finished();
    
?>