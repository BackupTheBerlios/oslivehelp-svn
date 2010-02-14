    //=======================================================================

    // Copyright (c) 2005 Help Center Live. All Rights Reserved

    // This file is part of Aardvark.

    // Aardvark is free software; you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation; either version 2 of the License, or
    // (at your option) any later version.

    // Aardvark is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.

    // You should have received a copy of the GNU General Public License
    // along with Help Center Live; if not, write to the Free Software
    // Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

    // Contributors: Michael Bird

    // File Comments:
    // The Aardvark class allows variables to be transferred between client and
    // server asynchronously using the XMLHttpRequest object, or the fallback method
    // if the XMLHttpRequest object is not supported.

function Aardvark(object) { this.object = object; this.xmlhttp = new XMLHttpRequest(); this.mac = navigator.platform.indexOf('Mac'); this.msie = navigator.userAgent.indexOf("MSIE"); this.image = new Image(); this.fallback = false; this.url = ''; this.variables = new Array(); this.vars = new Array(); this.connect_host = ''; this.install_host = ''; this.initiated = false; this.url_built = false; this.response = ''; this.gc = 'reset'; this.validateurl = function()
{ this.connect_host = this.url; this.install_host = document.location.toString(); this.connect_host = this.connect_host.replace(/(.*?)\/\/(.*?)\/(.*)/i, "$1//$2"); this.install_host = this.install_host.replace(/(.*?)\/\/(.*?)\/(.*)/i, "$1//$2"); this.connect_host = this.connect_host.replace(/(.*?)\/\/(.*?)/i, "$2"); this.install_host = this.install_host.replace(/(.*?)\/\/(.*?)/i, "$2"); if (this.connect_host !== this.install_host) { this.fallback = true;}
}
this.add = function(variable, data)
{ var i = this.variables.length; this.variables.push(Array()); this.variables[i].push('variable'); this.variables[i].push('data'); this.variables[i]['variable'] = escape(variable); this.variables[i]['data'] = escape(data);}
this.build = function(url)
{ if (!this.url_built) { url_location = document.location.toString(); domain = url_location.match( /:\/\/([^\/:]+)/ ); domain = domain[1]?domain[1]:''; this.add('domain', escape(domain)); if (this.variables.length > 0) { for (var i = 0; i < this.variables.length; i++) { if (url.indexOf('?') > -1) { url += '&aardvark_'+this.variables[i]['variable']+'='+this.variables[i]['data'];} else { url += '?aardvark_'+this.variables[i]['variable']+'='+this.variables[i]['data'];}
}
}
this.url = url; this.url_built = true;}
}
this.garbage = function()
{ switch (this.gc) { case 'append':
break; case 'reset':
default:
this.variables = new Array(); break;}
}
this.send = function(url, response, gc)
{ this.response = response; if (gc !== '') { this.gc = gc;}
this.build(url); this.validateurl(); this.garbage(); if (!this.fallback && this.url.substring(0, 5) !== 'https') { this.xmlhttp = new XMLHttpRequest(); this.xmlhttp.onreadystatechange = function()
{ var content = ''; var headers = new Array(); var i = 0; try { if (eval(object+".xmlhttp.readyState") == 4) { if (eval(object+".xmlhttp.status") == 200) { if (navigator.appName == 'Microsoft Internet Explorer') { headers = eval(object+".xmlhttp.getAllResponseHeaders()"); headers = headers.split("\n"); for (i = 0; i < headers.length; i++) { if (headers[i].substring(0, 11) == 'Set-Cookie:') { headers[i] = headers[i].substring(11, headers[i].length)
headers[i] = headers[i].split("; "); if (headers[i][0].substring(0, 1) == ' ') { content += headers[i][0].substring(1, headers[i][0].length) + '; ';} else { content += headers[i][0] + '; ';}
}
}
} else { headers = eval(object+".xmlhttp.getResponseHeader('Set-Cookie')"); headers = headers.split("path=/"); for (i = 0; i < headers.length; i++) { headers[i] = headers[i].split("; "); if (headers[i][0] !== '') { if (headers[i][0].substring(0, 2) == ', ') { content += headers[i][0].substring(2, headers[i][0].length) + '; ';} else if (headers[i][0].substring(0, 1) == "\n") { content += headers[i][0].substring(1, headers[i][0].length) + '; ';} else { content += headers[i][0] + '; ';}
}
}
}
content = content.replace(/, /gm, "; "); content = content.replace(/\n/gm, "; "); eval(object+".parse('"+content+"')");}
} else if (eval(object+".xmlhttp.readyState") == 0) { eval(object+".fallback = true"); eval(object+".send("+object+".url, '"+response+"', '"+gc+"')");}
} catch(e) { return false;}
}; this.xmlhttp.open("GET", this.url, true); this.xmlhttp.send(null);} else { if (navigator.platform.indexOf('Mac') > -1) { document.getElementById('aardvark_div_'+object).innerHTML = '<img alt="Aardvark" id="aardvark_img_'+this.object+'" width="0" height="0" src="'+this.url+'" />'; this.image = document.getElementById('aardvark_img_'+object);} else { this.image = new Image(); this.image.src = this.url;}
if (this.msie > -1) { setTimeout(object+".parse(document.cookie)", 1500);} else { this.image.onerror = setTimeout(object+".parse(document.cookie)", 1200);}
}
}
this.parse = function(content)
{ this.url_built = false; var variables = new Array(); content = content.split("; "); var arr = 0; var clen = content.length; var rcontent = ''; var i = 0; var varname = ''; var vardata = ''; for (i = 0; i < clen; i++) { rcontent = content[i].split('='); if (rcontent[0].substring(0, 9) == 'aardvark_') { if (rcontent[0].substring(9, rcontent[0].length) !== '') { varname = unescape(rcontent[0].substring(9, rcontent[0].length)); vardata = unescape(rcontent[1]); if (!eval("this.vars."+varname)) { this.vars.push(varname);}
if (vardata.toString() != 'undefined') { eval("this.vars."+varname+" = '"+vardata+"'");}
arr = variables.length; variables.push(Array()); variables[arr].push('variable'); variables[arr].push('data'); variables[arr]['variable'] = this.object+'.'+varname; variables[arr]['data'] = vardata; document.cookie = rcontent[0]+'=; expires=01/01/1970 00:00:00; path=/;';}
}
}
if (this.response !== '') { eval(this.response);}
}
if (navigator.platform.indexOf('Mac') > -1) { document.write('<div id="aardvark_div_'+this.object+'"></div>');}
}


    //=======================================================================
