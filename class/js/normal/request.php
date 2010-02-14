    //=======================================================================

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
    // This file is used to track visitors using javascript

    var RequestConnection1 = new Aardvark('RequestConnection1');

    function Request() {

        this.time = Misc.epoch();
        this.chatid = '<?php echo($chatid); ?>';

        this.check = function(variables)
        {
            var response = '';
            if (RequestConnection1.vars.request_<?php echo($chatid); ?>) {
                response = RequestConnection1.vars.request_<?php echo($chatid); ?>;
                this.time = Misc.epoch();
                var i = 0;
                var max = 0;
                var action = response.split('|');
                max = action.length - 1;
                for (i = 0; i < max; ++i) {
                    switch (action[i]) {
                    case 'accept':
<?php if (isset($_GET['opchat'])) { ?>
                        parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/main.php?opchat&chatid='+this.chatid+'&time='+this.time;
<?php } elseif (isset($_GET['initiate'])) { ?>
                        parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/main.php?initiate&chatid='+this.chatid+'&time='+this.time;
<?php } else { ?>
                        parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/main.php?chatid='+this.chatid+'&time='+this.time;
<?php } ?>
                        break;
                    case 'decline':
<?php if (isset($_GET['opchat'])) { ?>
                        parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/divert.php?divert=busy&chatid='+this.chatid+'&time='+this.time;
<?php } elseif (isset($_GET['initiate'])) { ?>
                        parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/divert.php?divert=busy&chatid='+this.chatid+'&time='+this.time;
<?php } else { ?>
                        parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/divert.php?divert=busy&departmentid=<?php echo(addslashes($_GET['departmentid'])); ?>&chatid='+this.chatid+'&time='+this.time;
<?php } ?>
                        break;
                    }
                }
            }
            window.setTimeout('Request.refresh();', <?php echo($GLOBALS['conf']['chat_refresh']); ?>);
        }

        this.refresh = function()
        {
<?php if (isset($_GET['opchat'])) { ?>            RequestConnection1.add('opchat', '');<?php } ?>
            RequestConnection1.add('request', '');
            RequestConnection1.add('chatid', this.chatid);
            RequestConnection1.add('time', Misc.epoch());
            RequestConnection1.send('<?php echo($GLOBALS['conf']['url']); ?>/live/response.php', 'Request.check(variables)', 'reset');
        }

    }

    var Request = new Request();

    //=======================================================================