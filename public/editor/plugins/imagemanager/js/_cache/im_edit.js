(function($){var count=0;function ImageSelection(ta,s){var t=this,id;s=t.settings=$.extend({},s);t.id=id='imageSelection'+(count++);t.mode=s.mode;$(ta).after('<div id="'+id+'_container" style="position:relative">'+'<div id="'+id+'_view">'+'<div id="'+id+'_sel" class="selection"></div>'+'</div>'+'<span id="'+id+'_tl" class="selection-corner selection-corner-tl"></span>'+'<span id="'+id+'_tc" class="selection-corner selection-corner-tc"></span>'+'<span id="'+id+'_tr" class="selection-corner selection-corner-tr"></span>'+'<span id="'+id+'_cl" class="selection-corner selection-corner-cl"></span>'+'<span id="'+id+'_cr" class="selection-corner selection-corner-cr"></span>'+'<span id="'+id+'_bl" class="selection-corner selection-corner-bl"></span>'+'<span id="'+id+'_bc" class="selection-corner selection-corner-bc"></span>'+'<span id="'+id+'_br" class="selection-corner selection-corner-br"></span>'+'</div>');t.scrollContainer=$(s.scroll_container);t.container=$('#'+id+'_container');t.selection=$('#'+id+'_sel');t.cornerTL=$('#'+id+'_tl');t.cornerTC=$('#'+id+'_tc');t.cornerTR=$('#'+id+'_tr');t.cornerCL=$('#'+id+'_cl');t.cornerCR=$('#'+id+'_cr');t.cornerBL=$('#'+id+'_bl');t.cornerBC=$('#'+id+'_bc');t.cornerBR=$('#'+id+'_br');t.offset=t.container.offset();t.setImage(ta);t.container.mousedown(function(e){var el=e.target;if(t.mode=='none')return;if(el.id==id+'_mainImg')return t.drag(e,'sel');if(el.id==id+'_selectionImg')return t.drag(e,'move');if(el.nodeName=='SPAN')return t.drag(e,el.className.replace(/selection\-corner(-|\s+)/g,''));});};$.extend(ImageSelection.prototype,{getX:function(e){return(e.clientX-this.settings.delta_x)+this.scrollContainer[0].scrollLeft;},getY:function(e){return(e.clientY-this.settings.delta_y)+this.scrollContainer[0].scrollTop;},setMode:function(m){var t=this;if(t.mode!=m){t.container.removeClass(t.mode);t.mode=m;t.container.addClass(m);if(m=='none'){t.reset();t.targetImg.show();t.container.hide();}else{t.targetImg.hide();t.container.show();}if(m=='resize'){t.cornersVisible=1;t.setRect(0,0,t.maxW,t.maxH).show();}else{t.cornersVisible=0;t.setRect(0,0,0,0);t.hide();}}return t;},setBounderyRect:function(x,y,w,h){var t=this;t.minX=x;t.minY=y;t.maxW=w;t.maxH=h;},setImage:function(ta){var t=this;ta=$(ta);if(t.mode!='none')ta.hide();t.container.find('img').remove();t.container.append($(ta).clone().attr('id',t.id+'_mainImg').addClass('mainimage'));t.mainImage=$('#'+t.id+'_mainImg').show();t.selection.append($(ta).clone().attr('id',t.id+'_selectionImg').addClass('selimage'));t.selectionImg=t.selection.find('img').show();t.targetImg=ta;t.setBounderyRect(0,0,ta.width(),ta.height());return t;},setRect:function(x,y,w,h,ns){var t=this,s=t.settings;if(w<0){w=w *-1;x-=w;if(x<0)w+=x;}if(h<0){h=h *-1;y-=h;if(y<0)h+=y;}x=x<0?0:x;y=y<0?0:y;if(t.mode=='crop'){w=w>t.maxW-x?t.maxW-x:w;h=h>t.maxH-y?t.maxH-y:h;}if(t.x!=x){t.selection.css('left',t.x=x);if(t.selectionImg)t.selectionImg.css('left',0-x-1);}if(t.y!=y){t.selection.css('top',t.y=y);if(t.selectionImg)t.selectionImg.css('top',0-y-1);}if(t.w!=w)t.selection.css('width',0).css('width',(t.w=w)-2);if(t.h!=h)t.selection.css('height',0).css('height',(t.h=h)-2);if(t.mode=='resize'){t.selectionImg.css({left:0,top:0,width:t.w,height:t.h});if(!ns)t.mainImage.css({width:t.w,height:t.h});}$(t).trigger('imgselection:change',[x,y,w,h]);if(!ns)t.cornersVisible=1;t.drawCorners().show();return this;},show:function(){var t=this;if(!t.visible){t.selection.show();if(t.cornersVisible)t.container.find('span').show();t.visible=1;}return t;},hide:function(){var t=this;if(t.visible){t.selection.hide();t.container.find('span').hide();t.visible=0;}return t;},reset:function(){var t=this,w=t.targetImg.width(),h=t.targetImg.height();t.mainImage.css({width:w,height:h});t.selectionImg.css({width:w,height:h});t.setRect(0,0,w,h);t.setBounderyRect(0,0,w,h);return t;},drawCorners:function(){var t=this;if(t.cornersVisible){t.cornerTL.css({left:t.x-4,top:t.y-4}).show();t.cornerTC.css({left:t.x+Math.round((t.w-8)/2),top:t.y-4}).show();t.cornerTR.css({left:t.x+t.w-3,top:t.y-4}).show();t.cornerCL.css({left:t.x-4,top:t.y+Math.round((t.h-8)/2)}).show();t.cornerCR.css({left:t.x+t.w-3,top:t.y+Math.round((t.h-8)/2)}).show();t.cornerBL.css({left:t.x-4,top:t.y+t.h-3}).show();t.cornerBC.css({left:t.x+Math.round((t.w-8)/2),top:t.y+t.h-3}).show();t.cornerBR.css({left:t.x+t.w-3,top:t.y+t.h-3}).show();}return t;},startDrag:function(e,o){var t=this,sx=t.getX(e),sy=t.getY(e);if(o.start)o.start.call(t,sx,sy);function drag(e){if(o.drag)o.drag.call(t,t.getX(e),t.getY(e));e.preventDefault();return false;};function up(){if(o.end)o.end.call(t,t.getX(e),t.getY(e));$().unbind('mouseup',up);$().unbind('mousemove',drag);e.preventDefault();return false;};$().mousemove(drag);$().mouseup(up);e.preventDefault();return false;},drag:function(e,ty){var t=this,sx,sy,rx,ry,rw,rh,mw,mh,a;t.startDrag(e,{start:function(x,y){sx=x;sy=y;rx=t.x;ry=t.y;rw=t.w;rh=t.h;mw=t.maxW;mh=t.maxH;a=(rw/rh)||0;if(rw==0&&rh==0)t.hide();t.cornersVisible=0;t.container.find('span').hide();t.selection.addClass('selection-corner-'+ty);},drag:function(cx,cy){var x=rx,y=ry,w=rw,h=rh,dx,dy,p=t.proportional||e.shiftKey;dx=cx-sx;dy=cy-sy;switch(ty){case 'sel':dx=p?Math.round(dy * a):dx;w=dx;h=dy;x=sx;y=sy-1;break;case 'tl':dx=p?Math.round(dy * a):dx;x=rx+dx;y=ry+dy;w=rw-dx;h=rh-dy;break;case 'tc':y=ry+dy;h=rh-dy;break;case 'tr':dx=p?Math.round(-dy * a):dx;y=ry+dy;w=rw+dx;h=rh-dy;break;case 'cl':x=rx+dx;w=rw-dx;break;case 'cr':w=rw+dx;break;case 'bl':dx=p?Math.round(-dy * a):dx;x=rx+dx;w=rw-dx;h=rh+dy;break;case 'bc':h=rh+dy;break;case 'br':dx=p?Math.round(dy * a):dx;w=rw+dx;h=rh+dy;break;case 'move':x=rx+dx;y=ry+dy;x=x+rw>mw?mw-rw:x;y=y+rh>mh?mh-rh:y;break;}if(ty!='move'){w=x<0?w+x:w;h=y<0?h+y:h;}t.setRect(x,y,w,h,1);},end:function(){t.cornersVisible=1;if(t.mode=='resize'){t.setRect(0,0,t.w,t.h);t.setBounderyRect(0,0,t.w,t.h);}t.drawCorners();t.selection.removeClass('selection-corner-'+ty);}});}});$.createImageSelection=function(ta,s){return new ImageSelection(ta,s);};})(jQuery);(function($){$.ImageUtils=function(ta){var t=this,d=document,ss;ta=$(ta);t.target=ta;if($.browser.msie){d.namespaces.add("v","urn:schemas-microsoft-com:vml");ss=d.createStyleSheet();ss.cssText="v\\:*{behavior:url(#default#VML);display:inline-block;margin:0;padding:0}";}else{ta.after('<canvas id="editImageCanvas" style="display:none"></canvas>');t.canvas=document.getElementById('editImageCanvas');t.context=t.canvas.getContext('2d');}};$.extend($.ImageUtils.prototype,{render:function(){var t=this;t.img=new Image();$(t.img).load(function(){if(t.canvas){t.canvas.width=t.img.width;t.canvas.height=t.img.height;$(t.canvas).css({width:t.img.width,height:t.img.height}).show();t.context.drawImage(t.img,0,0);}else t.target.after('<v:image id="editImageVML" src="'+t.img.src+'" style="width:'+t.img.width+'px;height:'+t.img.height+'px"></v:image>');t.target.hide();$(t).trigger('ImageUtils:load');});t.img.src=t.target.attr('src');},flip:function(d){var t=this,ctx=t.context;if(!t.canvas){$('#editImageVML').css('flip',d=='h'?'x':'y');return;}if(d=='h'){ctx.save();ctx.scale(-1,1);ctx.drawImage(t.img,-t.img.width,0);ctx.restore();}else{ctx.save();ctx.scale(1,-1);ctx.drawImage(t.img,0,-t.img.height);ctx.restore();}},rotate:function(a){var t=this,img=t.img,can=t.canvas,ctx=t.context,rad=a * Math.PI/180;if(!t.canvas){$('#editImageVML').attr('rotation',a);return;}ctx.save();switch(a){case 90:can.width=img.height;can.height=img.width;ctx.rotate(rad);ctx.drawImage(img,0,-img.height);$(t.canvas).css({width:img.height,height:img.width});break;case 180:can.width=img.width;can.height=img.height;ctx.rotate(rad);ctx.drawImage(img,-img.width,-img.height);break;case 270:can.width=img.height;can.height=img.width;ctx.rotate(rad);ctx.drawImage(img,-img.width,0);break;}ctx.restore();$(t.canvas).css({width:can.width,height:can.height});},destroy:function(){var t=this;if(t.canvas)$(t.canvas).remove();else $('#editImageVML').remove();t.target.show();}});})(jQuery);(function($){window.EditDialog={currentWin:$.WindowManager.find(window),init:function(){var t=this,args;args=t.args=$.extend({path:'{0}',visual_path:'/'},t.currentWin.getArgs());if(t.currentWin.features){t.currentWin.features.onbeforeclose=function(){if(t.imgPath!=t.targetPath){$.WindowManager.confirm($.translate('{#edit_image.confirm_no_save}'),function(s){if(s)t.currentWin.close();});return false;}};}$(window).bind('resize',function(e){t.resizeView();});t.imageSelection=$.createImageSelection($('#editImage'),{scroll_container:$('#imageWrapper'),delta_x:26+($.browser.msie?2:0),delta_y:90+($.browser.msie?2:0)});$(t.imageSelection).bind('imgselection:change',function(e,x,y,w,h){var f=document.forms[0];if(this.mode=='resize'){f.resize_w.value=w;f.resize_h.value=h;}else{f.crop_x.value=x;f.crop_y.value=y;f.crop_w.value=w;f.crop_h.value=h;}});t.loadImage({path:args.path,url:args.url,initial:1});t.resizeView();$(['save','revert','crop','resize','flip','rotate']).each(function(i,v){var a=$('#'+v);a.click(function(){if(!a.hasClass('disabled')&&!a.hasClass('active')){t[v]();$('div.panel').hide();$('#'+v+'_tools').show();a.addClass('active');}});});$('a.apply').click(function(e){t.apply();});$('a.cancel').click(function(e){t.cancel();});},apply:function(){},cancel:function(){$('div.panel').hide();$('#toolbar a').removeClass('active');this.imageSelection.setMode('none');if(this.imgUtils){this.imgUtils.destroy();this.imgUtils=null;}$.WindowManager.hideProgress();},save:function(){var t=this,f=document.forms[0];f.save_filename.value=t.targetPath.substring(t.targetPath.lastIndexOf('/')+1);t.apply=function(){RPC.exec('im.getConfig',{path:t.imgPath},function(data){var config=data.result,f=document.forms[0];if(!RPC.handleError({message:'Get config error',visual_path:t.args.visual_path,response:data})){if(config['filesystem.clean_names']=="true")$('#save_filename').val($.cleanName($('#save_filename').val()));$.WindowManager.showProgress({message:$.translate("{#edit_image.saving_wait}")});RPC.exec('im.saveImage',{path:t.imgPath,target:$('#save_filename').val()},function(data){var res;$.WindowManager.hideProgress();if(!RPC.handleError({message:'Save error',visual_path:t.args.visual_path,response:data})){res=RPC.toArray(data.result);if(t.args.onsave)return t.insertFile(res[0].file);if(res.length>0)t.loadImage({path:res[0].file,initial:1});$('#save,#revert').addClass('disabled');t.cancel();}});}});};},revert:function(e){var t=this;$.WindowManager.confirm($.translate("{#edit_image.confirm_revert}"),function(s){if(s){$('#save,#revert').addClass('disabled');t.loadImage({path:t.targetPath});}});},resize:function(){var t=this,f=document.forms[0];t.cancel();t.imageSelection.setMode('resize');t.imageSelection.proportional=f.resize_prop.checked;$(f.resize_prop).click(function(){t.imageSelection.proportional=f.resize_prop.checked;});$('#resize_tools input[@type=text]').change(function(e){if(f.resize_prop.checked){if(e.target.id=="resize_w")f.resize_h.value=Math.round(t.imageSelection.h *(parseInt(f.resize_w.value)/t.imageSelection.w));else f.resize_w.value=Math.round(t.imageSelection.w *(parseInt(f.resize_h.value)/t.imageSelection.h));}t.imageSelection.setRect(0,0,parseInt(f.resize_w.value),parseInt(f.resize_h.value));});t.apply=function(){$.WindowManager.showProgress({message:$.translate('{#edit_image.please_wait}')});t.execRPC('im.resizeImage',{path:t.imgPath,width:f.resize_w.value,height:f.resize_h.value,temp:true},'{#error.resize_failed}');};},crop:function(){var t=this,f=document.forms[0];t.cancel();t.imageSelection.setMode('crop');t.imageSelection.proportional=f.crop_prop.checked;$(f.crop_prop).click(function(){t.imageSelection.proportional=f.crop_prop.checked;});$('#crop_tools input[@type=text]').change(function(e){if(f.crop_prop.checked){if(e.target.id=="crop_w")f.crop_h.value=Math.round(t.imageSelection.h *(parseInt(f.crop_w.value)/t.imageSelection.w));else f.crop_w.value=Math.round(t.imageSelection.w *(parseInt(f.crop_h.value)/t.imageSelection.h));}t.imageSelection.setRect(parseInt(f.crop_x.value),parseInt(f.crop_y.value),parseInt(f.crop_w.value),parseInt(f.crop_h.value));});t.apply=function(){$.WindowManager.showProgress({message:$.translate('{#edit_image.please_wait}')});t.execRPC('im.cropImage',{path:t.imgPath,left:f.crop_x.value,top:f.crop_y.value,width:f.crop_w.value,height:f.crop_h.value,temp:true},'{#error.crop_failed}');};},flip:function(){var t=this,f=document.forms[0],axis;$('#flip_tools input').attr('checked','');t.cancel();t.imageSelection.setMode('none');t.imgUtils=new $.ImageUtils($('#editImage'));$(t.imgUtils).bind('ImageUtils:load',function(){$('#flip_tools input').click(function(){t.imgUtils.flip(axis=$('#flip_tools input:checked').val());});t.apply=function(){$.WindowManager.showProgress({message:$.translate('{#edit_image.please_wait}')});if(axis)t.execRPC('im.flipImage',{path:t.imgPath,horizontal:axis=='h',vertical:axis=='v',temp:true},'{#error.flip_failed}');else t.cancel();};});t.imgUtils.render();},rotate:function(){var t=this,f=document.forms[0],ang;$('#rotate_tools input').attr('checked','');t.cancel();t.imageSelection.setMode('none');t.imgUtils=new $.ImageUtils($('#editImage'));$(t.imgUtils).bind('ImageUtils:load',function(){$('#rotate_tools input').click(function(){t.imgUtils.rotate(ang=parseInt($('#rotate_tools input:checked').val()));});t.apply=function(){$.WindowManager.showProgress({message:$.translate('{#edit_image.please_wait}')});if(ang)t.execRPC('im.rotateImage',{path:t.imgPath,angle:ang,temp:true},'{#error.rotate_failed}');else t.cancel();};});t.imgUtils.render();},execRPC:function(m,a,er){var t=this;RPC.exec(m,a,function(data){var res=RPC.toArray(data.result);$.WindowManager.hideProgress();if(!RPC.handleError({message:er,response:data})){$('#save,#revert').removeClass('disabled');t.loadImage({path:res[0].file});}});},insertFile:function(p){var t=this,s=t.args;RPC.insertFile({relative_urls:s.relative_urls,document_base_url:s.document_base_url,default_base_url:s.default_base_url,no_host:s.remove_script_host||s.no_host,path:p,progress_message:$.translate("{#common.image_data}"),insert_filter:s.insert_filter,oninsert:function(o){s.onsave(o);t.currentWin.close();}});},loadImage:function(o,cb){var t=this;$('#crop,#resize,#flip,#rotate').addClass('disabled');$.WindowManager.showProgress({message:$.translate(o.initial?"{#edit_image.loading}":"{#edit_image.please_wait}")});RPC.exec('im.getMediaInfo',{path:o.path,url:o.url},function(data){var res=RPC.toArray(data.result);if(!RPC.handleError({message:'Generic error',response:data})){if(o.initial)t.imageURL=res[0].url;if(o.initial)t.targetPath=res[0].path;t.imgPath=res[0].path;$('#editImage').load(function(){$.WindowManager.hideProgress();t.imageSelection.setImage($('#editImage'));$('#crop,#resize,#flip,#rotate').removeClass('disabled');t.cancel();});$('#editImage').error(function(){$.WindowManager.hideProgress();});$('#editImage').attr('src','../../stream/index.php?cmd=im.streamFile&path='+escape(res[0].path)+'&rnd='+new Date().getTime());if(cb)cb(res);}});},resizeView:function(){$('#imageWrapper').css({'width':(window.innerWidth||$.winWidth())-30,'height':(window.innerHeight||$.winHeight())-100});},isDemo:function(){if(this.args.is_demo){$.WindowManager.info($.translate('{#error.demo}'));return true;}}};$(function(e){EditDialog.init();});})(jQuery);