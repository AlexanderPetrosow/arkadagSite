try{(function($){$.fn.extend({jEditMakeAbsolute:function(rebase){return this.each(function(){var el=$(this);var pos;if(rebase){pos=el.offset();}else{pos=el.position();}
el.css({position:"absolute",marginLeft:0,marginTop:0,top:pos.top,left:pos.left,bottom:'auto',right:'auto'});if(rebase){el.detach().appendTo("body");}});}});$(document).ready(function(){$('.jmoddiv').on({mouseenter:function(){var moduleEditUrl=$(this).data('jmodediturl');var moduleTip=$(this).data('jmodtip');var moduleTarget=$(this).data('target');$('body>.btn.jmodedit').clearQueue().tooltip('dispose').remove();$(this).addClass('jmodinside').prepend('<a class="btn jmodedit" href="#" target="'+moduleTarget+'"><span class="icon-edit"></span></a>').children(":first").attr('href',moduleEditUrl).attr('title',moduleTip).tooltip({html:true,placement:'top'}).jEditMakeAbsolute(true);$('.btn.jmodedit').on({mouseenter:function(){$(this).clearQueue();},mouseleave:function(){$(this).delay(500).queue(function(next){$(this).tooltip('dispose').remove();next();});}});},mouseleave:function(){$('body>.btn.jmodedit').delay(500).queue(function(next){$(this).tooltip('dispose').remove();next();});}});var activePopover=null;$('.jmoddiv[data-jmenuedittip] .nav li,.jmoddiv[data-jmenuedittip].nav li,.jmoddiv[data-jmenuedittip] .nav .nav-child li,.jmoddiv[data-jmenuedittip].nav .nav-child li').on({mouseenter:function(){var itemids=/\bitem-(\d+)\b/.exec($(this).attr('class'));if(typeof itemids[1]=='string'){var enclosingModuleDiv=$(this).closest('.jmoddiv');var moduleEditUrl=enclosingModuleDiv.data('jmodediturl');var menuitemEditUrl=moduleEditUrl.replace(/\/index.php\?option=com_config&controller=config.display.modules([^\d]+).+$/,'/administrator/index.php?option=com_menus&view=item&layout=edit$1'+itemids[1]);}
var menuEditTip=enclosingModuleDiv.data('jmenuedittip').replace('%s',itemids[1]);var content=$('<div><a class="btn jfedit-menu" href="#" target="_blank"><span class="icon-edit"></span></a></div>');content.children('a.jfedit-menu').prop('href',menuitemEditUrl).prop('title',menuEditTip);if(activePopover){$(activePopover).popover('hide');}
$(this).popover({html:true,content:content.html(),container:'body',trigger:'manual',animation:false,placement:'bottom'}).popover('show');activePopover=this;$('body>div.popover').on({mouseenter:function(){if(activePopover){$(activePopover).clearQueue();}},mouseleave:function(){if(activePopover){$(activePopover).popover('hide');}}}).find('a.jfedit-menu').tooltip({html:true,placement:'bottom'});},mouseleave:function(){$(this).delay(1500).queue(function(next){$(this).popover('hide');next()});}});});if(typeof MooTools!="undefined"){var mHide=Element.prototype.hide;Element.implement({hide:function(){if($('.hasPopover')&&$('.hasPopover').attr('data-original-title')){return this;}
mHide.apply(this,arguments);}});}})(jQuery);}catch(e){console.error('Error in file:/joomla/core/templates/shaper_helixultimate/js/system/frontediting.js?91386e2433896fe1cc1010af6a2c4bc4; Error:'+e.message);};
try{!function(a){a.fn.speasyimagegallery=function(b){var c={showCounter:!0,showTitle:!0,showDescription:!0,parent:".speasyimagegallery-gallery"};this.each(function(){b&&a.extend(c,b);var d=this,e=function(){this.items=a(d).closest(c.parent).find(d.nodeName),this.count=this.items.length-1,this.index=this.items.index(d),this.navPrev="",this.navNext="",this.loaded=!1,this.naturalWidth=0,this.naturalHeight=0,this.init=function(){this.modal(),this.goto(this.index);var b=this;this.navNext.on("click",function(a){a.preventDefault(),b.next()}),a(document).on("click",".speasyimagegallery-image",function(a){a.preventDefault(),b.next()}),a(document).on("click",".speasyimagegallery-modal-wrapper, .speasyimagegallery-close",function(a){a.target===this&&(a.preventDefault(),b.close())}),a(document).on("keyup",function(a){39==a.keyCode&&(a.preventDefault(),b.next()),37==a.keyCode&&(a.preventDefault(),b.prev()),27==a.keyCode&&(a.preventDefault(),b.close())}),this.navPrev.on("click",function(a){a.preventDefault(),b.prev()}),a(window).on("resize",function(){var c=b.resize();a(".speasyimagegallery-modal").css({width:c.width,height:c.height})})},this.modal=function(){a('<div id="speasyimagegallery-modal" class="speasyimagegallery-modal-wrapper"><a href="#" class="speasyimagegallery-prev"><span></span></a><a href="#" class="speasyimagegallery-next"><span></span></a><div class="speasyimagegallery-modal"><a href="#" class="speasyimagegallery-close speasyimagegallery-hidden">&times;</a><div class="speasyimagegallery-modal-body"></div></div></div>').appendTo(a("body").addClass("speasyimagegallery-modal-open")),this.modal=a("#speasyimagegallery-modal"),this.navNext=this.modal.find(".speasyimagegallery-next"),this.navPrev=this.modal.find(".speasyimagegallery-prev")},this.close=function(){this.index=0,this.loaded=!0,this.naturalWidth=0,this.naturalHeight=0,a("#speasyimagegallery-modal").fadeOut(function(){a(this).remove()}),a(".speasyimagegallery-modal").animate({width:100,height:100},300,function(){a(this).remove(),a("body").removeClass("speasyimagegallery-modal-open")})},this.resize=function(){var b=a(window).width()-80,c=a(window).height()-80,d=0,e=this.naturalWidth,f=this.naturalHeight;return e>b&&(d=b/e,f*=d,e*=d),f>c&&(d=c/f,e*=d,f*=d),{width:e,height:f}},this.next=function(){this.index<this.count?this.index=this.index+1:this.index=0,this.goto(this.index)},this.prev=function(){this.index>0?this.index=this.index-1:this.index=this.count,this.goto(this.index)},this.goto=function(b){if(this.loaded===!1){var d=this,e=a(this.items[b]);d.loaded=!0,a(".speasyimagegallery-modal-body").html('<div class="speasyimagegallery-gallery-loading"></div>');var f=a("<img />").attr("src",e.attr("href")).on("load",function(){if(this.complete&&"undefined"!=typeof this.naturalWidth&&0!=this.naturalWidth){d.naturalWidth=this.naturalWidth,d.naturalHeight=this.naturalHeight;var b=d.resize();a(".speasyimagegallery-modal").animate({width:b.width,height:b.height},300,function(){var b='<div class="speasyimagegallery-image-wrapper">';b+='<img class="speasyimagegallery-image" src="'+f[0].src+'" alt="'+e.attr("data-alt")+'">',(c.showCounter||c.showTitle&&e.attr("data-title")||c.showDescription&&e.attr("data-desc"))&&(e.attr("data-title")||e.attr("data-description"))&&(b+='<div class="speasyimagegallery-image-content">',c.showCounter&&(b+='<span class="speasyimagegallery-gallery-stat">'+(d.index+1)+" of "+(d.count+1)+"</span>"),c.showTitle&&e.attr("data-title")&&(b+='<span class="speasyimagegallery-image-title">'+e.attr("data-title")+"</span>"),c.showDescription&&e.attr("data-desc")&&(b+='<div class="speasyimagegallery-image-description">'+e.attr("data-desc")+"</div>"),b+="</div>"),b+="</div>",a(".speasyimagegallery-modal-body").html(b),d.modal.find(".speasyimagegallery-hidden").removeClass("speasyimagegallery-hidden"),d.loaded=!1})}else;})}}};(new e).init()})}}(jQuery);}catch(e){console.error('Error in file:/joomla/core/components/com_speasyimagegallery/assets/js/script-min.js; Error:'+e.message);};
try{template="shaper_helixultimate";jQuery(function($){$(document).on('click','.speasyimagegallery-gallery-item',function(event){event.preventDefault();$(this).speasyimagegallery({showTitle:1,showDescription:1,showCounter:1});});})}catch(e){console.error('Error in script declaration; Error:'+e.message);};
