/* 
* WYSIWYG Resize (http://editorboost.net/WYSIWYGResize)
* Copyright 2012 Editorboost. All rights reserved. 
*
* Webkitresize commercial licenses may be obtained at http://editorboost.net/home/licenses.
* If you do not own a commercial license, this file shall be governed by the
* GNU General Public License (GPL) version 3. For GPL requirements, please
* review: http://www.gnu.org/copyleft/gpl.html
*
* Version date: March 19 2013
* REQUIRES: jquery 1.7.1+
*/
/*
* Modded By DeQyd <deqyd.com> for ratio resizing
*/
;(function(e){e.fn.wysiwygResize=function(t){return this.each(function(){var n=e.extend({selector:"div, span"},t);var r;var s=false;var o;var u=false;var a={getNextHighestZindex:function(e){var t=0;var n=0;var r=Array();if(e){r=e.getElementsByTagName("*")}else{r=document.getElementsByTagName("*")}for(var i=0;i<r.length;i++){if(r[i].currentStyle){n=parseFloat(r[i].currentStyle["zIndex"])}else if(window.getComputedStyle){n=parseFloat(document.defaultView.getComputedStyle(r[i],null).getPropertyValue("z-index"))}if(!isNaN(n)&&n>t){t=n}}return t+1},removeResizeElements:function(t){e(".wysiwygResize-selector").remove();e(".wysiwygResize-region").remove()},drawResizeElements:function(e,t,n,r,i){e.$docBody.append("<span class='wysiwygResize-selector' style='z-index:"+a.getNextHighestZindex(e.container)+";margin:10px;position:absolute;top:"+(i.top+n-10)+"px;left:"+(i.left+r-10)+'px;border:solid 2px red;width:6px;height:6px;cursor:se-resize;background-color:red;opacity: 0.60;-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=60)";filter: alpha(opacity=60);-moz-opacity: 0.6;\'></span>');e.$docBody.append("<span class='wysiwygResize-region wysiwygResize-region-top-right' style='position:absolute;top:"+i.top+"px;left:"+i.left+"px;border:dashed 1px grey;background-color: #EBEBE9;width:"+r+"px;height:0px;'></span>");e.$docBody.append("<span class='wysiwygResize-region wysiwygResize-region-top-down' style='position:absolute;top:"+i.top+"px;left:"+i.left+"px;border:dashed 1px grey;background-color: #EBEBE9;width:0px;height:"+n+"px;'></span>");e.$docBody.append("<span class='wysiwygResize-region wysiwygResize-region-right-down' style='position:absolute;top:"+i.top+"px;left:"+(i.left+r)+"px;border:dashed 1px grey;background-color: #EBEBE9;width:0px;height:"+n+"px;'></span>");e.$docBody.append("<span class='wysiwygResize-region wysiwygResize-region-down-left' style='position:absolute;top:"+(i.top+n)+"px;left:"+i.left+"px;border:dashed 1px grey;background-color: #EBEBE9;width:"+r+"px;height:0px;'></span>")},elementClick:function(t,r){if(e.browser.msie&&!e(r).is("td")&&e(r).attr("style")&&(e(r).attr("style").toLowerCase().indexOf("height")!=-1||e(r).attr("style").toLowerCase().indexOf("width")!=-1)){return}if(n.beforeElementSelect){n.beforeElementSelect(r)}a.removeResizeElements();o=r;var i=e(r).outerHeight();var f=e(r).outerWidth();var l=e(r).offset();a.drawResizeElements(t,r,i,f,l);var h=function(){if(s){e(o).css("width",e(".wysiwygResize-region-top-right").width()+"px").css("height",e(".wysiwygResize-region-top-down").height()+"px");a.refresh(t);var r=o;c.trigger("webkitresize-updatecrc",[a.crc(t.$container.html())]);s=false;a.reset(t);a.elementClick(t,r);if(n.afterResize){n.afterResize(o)}}};var p=function(t){if(s){var n=f;var r=i;r=t.pageY-l.top;n=t.pageX-l.left;if(u){if(n<r){n=n;var o=Number(n/f).toFixed(2);r=Math.ceil(i*o)}else{r=r;var o=Number(r/i).toFixed(2);n=Math.ceil(f*o)}}if(r<1){r=1}if(n<1){n=1}e(".wysiwygResize-selector").focus().css("top",l.top+r-10+"px").css("left",l.left+n-10+"px");e(".wysiwygResize-region-top-right").css("width",n+"px");e(".wysiwygResize-region-top-down").css("height",r+"px");e(".wysiwygResize-region-right-down").css("left",l.left+n+"px").css("height",r+"px");e(".wysiwygResize-region-down-left").css("top",l.top+r+"px").css("width",n+"px")}return false};e(".wysiwygResize-selector").mousedown(function(e){if(n.beforeResizeStart){n.beforeResizeStart(o)}s=true;return false});e(window.document).mouseup(function(){if(s){h()}});e(window.document).mousemove(function(e){p(e)});if(n.afterElementSelect){n.afterElementSelect(o)}},rebind:function(t){t.$container.find(n.selector).each(function(n,r){e(r).unbind("click");e(r).click(function(e){if(e.target==r){a.elementClick(t,r)}})})},refresh:function(t){a.rebind(t);a.removeResizeElements();if(!o){if(n.afterRefresh){n.afterRefresh(null)}return}var i=o;var s=e(i).outerHeight();var u=e(i).outerWidth();var f=e(i).offset();a.drawResizeElements(t,i,s,u,f);r=a.crc(t.$container.html());if(n.afterRefresh){n.afterRefresh(o)}},reset:function(e){if(o!=null){o=null;s=false;a.removeResizeElements();if(n.afterReset){n.afterReset()}}a.rebind(e)},crc:function(e){var t=0;if(e==null||e.length==0)return t;for(i=0;i<e.length;i++){char=e.charCodeAt(i);t=(t<<5)-t+char;t=t&t}return t}};var f=this;var l=e("body");var c=e(f);r=a.crc(c.html());var h={container:f,$container:c,$docBody:l};if(f.addEventListener){f.addEventListener("scroll",function(){a.reset(h)},false)}else if(f.attachEvent){f.attachEvent("onscroll",function(){a.reset(h)})}e(document).mouseup(function(t){if(!s){var r=t.x?t.x:t.clientX;var i=t.y?t.y:t.clientY;var o=document.elementFromPoint(r,i);if(o){var u;var f=h.$container.find(n.selector);var l=e(o).parents();for(var c=0;c<l.length;c++){for(var p=0;p<f.length;p++){if(f[p]==l[c]){u=f[p];break}}if(u){break}}if(!u){a.reset(h)}else{a.elementClick(h,u)}}}});e(document).keyup(function(e){u=e.shiftKey});e(document).keydown(function(e){u=e.shiftKey});e(document).keyup(function(e){if(e.keyCode==27){a.reset(h)}});if(!f.crcChecker){f.crcChecker=setInterval(function(){var e=a.crc(c.html());if(r!=e){c.trigger("webkitresize-crcchanged",[e])}},1e3)}e(window).resize(function(){a.reset(h)});c.bind("webkitresize-crcchanged",function(e,t){r=t;a.reset(h)});c.bind("webkitresize-updatecrc",function(e,t){r=t});a.refresh(h)})}})(jQuery)