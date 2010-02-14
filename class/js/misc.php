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
    // This file contains any misc javascript functions

    // This is a compressed version for faster page loading.
    // The normal vesrion is in the /normal directory

function Misc() { this.date = new Date(); this.mac = navigator.platform.indexOf('Mac'); this.text = ''; this.action = false; this.epoch = function()
{ this.date = new Date(); return this.date.getTime();}
this.alert_mac = function(text)
{ this.text = text; if (this.mac > -1) { setTimeout('alert(Misc.text);', 100);}
}
this.statusbar = function(text)
{ window.status = text;}
this.confirm_action = function()
{ this.action = confirm('<?php echo($GLOBALS['lang']['confirm_action']); ?>'); if (this.action == false) { return false;} else { return true;}
}
}
var Misc = new Misc(); 

    //=======================================================================
