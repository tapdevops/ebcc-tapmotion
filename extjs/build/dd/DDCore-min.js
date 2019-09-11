/*
 * Ext JS Library 1.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */

(function(){var _1=Ext.EventManager;var _2=Ext.lib.Dom;Ext.dd.DragDrop=function(id,_4,_5){if(id){this.init(id,_4,_5);}};Ext.dd.DragDrop.prototype={id:null,config:null,dragElId:null,handleElId:null,invalidHandleTypes:null,invalidHandleIds:null,invalidHandleClasses:null,startPageX:0,startPageY:0,groups:null,locked:false,lock:function(){this.locked=true;},unlock:function(){this.locked=false;},isTarget:true,padding:null,_domRef:null,__ygDragDrop:true,constrainX:false,constrainY:false,minX:0,maxX:0,minY:0,maxY:0,maintainOffset:false,xTicks:null,yTicks:null,primaryButtonOnly:true,available:false,hasOuterHandles:false,b4StartDrag:function(x,y){},startDrag:function(x,y){},b4Drag:function(e){},onDrag:function(e){},onDragEnter:function(e,id){},b4DragOver:function(e){},onDragOver:function(e,id){},b4DragOut:function(e){},onDragOut:function(e,id){},b4DragDrop:function(e){},onDragDrop:function(e,id){},onInvalidDrop:function(e){},b4EndDrag:function(e){},endDrag:function(e){},b4MouseDown:function(e){},onMouseDown:function(e){},onMouseUp:function(e){},onAvailable:function(){},defaultPadding:{left:0,right:0,top:0,bottom:0},constrainTo:function(_1d,pad,_1f){if(typeof pad=="number"){pad={left:pad,right:pad,top:pad,bottom:pad};}pad=pad||this.defaultPadding;var b=Ext.get(this.getEl()).getBox();var ce=Ext.get(_1d);var s=ce.getScroll();var c,cd=ce.dom;if(cd==document.body){c={x:s.left,y:s.top,width:Ext.lib.Dom.getViewWidth(),height:Ext.lib.Dom.getViewHeight()};}else{xy=ce.getXY();c={x:xy[0]+s.left,y:xy[1]+s.top,width:cd.clientWidth,height:cd.clientHeight};}var _25=b.y-c.y;var _26=b.x-c.x;this.resetConstraints();this.setXConstraint(_26-(pad.left||0),c.width-_26-b.width-(pad.right||0));this.setYConstraint(_25-(pad.top||0),c.height-_25-b.height-(pad.bottom||0));},getEl:function(){if(!this._domRef){this._domRef=Ext.getDom(this.id);}return this._domRef;},getDragEl:function(){return Ext.getDom(this.dragElId);},init:function(id,_28,_29){this.initTarget(id,_28,_29);_1.on(this.id,"mousedown",this.handleMouseDown,this);},initTarget:function(id,_2b,_2c){this.config=_2c||{};this.DDM=Ext.dd.DDM;this.groups={};if(typeof id!=="string"){id=Ext.id(id);}this.id=id;this.addToGroup((_2b)?_2b:"default");this.handleElId=id;this.setDragElId(id);this.invalidHandleTypes={A:"A"};this.invalidHandleIds={};this.invalidHandleClasses=[];this.applyConfig();this.handleOnAvailable();},applyConfig:function(){this.padding=this.config.padding||[0,0,0,0];this.isTarget=(this.config.isTarget!==false);this.maintainOffset=(this.config.maintainOffset);this.primaryButtonOnly=(this.config.primaryButtonOnly!==false);},handleOnAvailable:function(){this.available=true;this.resetConstraints();this.onAvailable();},setPadding:function(_2d,_2e,_2f,_30){if(!_2e&&0!==_2e){this.padding=[_2d,_2d,_2d,_2d];}else{if(!_2f&&0!==_2f){this.padding=[_2d,_2e,_2d,_2e];}else{this.padding=[_2d,_2e,_2f,_30];}}},setInitPosition:function(_31,_32){var el=this.getEl();if(!this.DDM.verifyEl(el)){return;}var dx=_31||0;var dy=_32||0;var p=_2.getXY(el);this.initPageX=p[0]-dx;this.initPageY=p[1]-dy;this.lastPageX=p[0];this.lastPageY=p[1];this.setStartPosition(p);},setStartPosition:function(pos){var p=pos||_2.getXY(this.getEl());this.deltaSetXY=null;this.startPageX=p[0];this.startPageY=p[1];},addToGroup:function(_39){this.groups[_39]=true;this.DDM.regDragDrop(this,_39);},removeFromGroup:function(_3a){if(this.groups[_3a]){delete this.groups[_3a];}this.DDM.removeDDFromGroup(this,_3a);},setDragElId:function(id){this.dragElId=id;},setHandleElId:function(id){if(typeof id!=="string"){id=Ext.id(id);}this.handleElId=id;this.DDM.regHandle(this.id,id);},setOuterHandleElId:function(id){if(typeof id!=="string"){id=Ext.id(id);}_1.on(id,"mousedown",this.handleMouseDown,this);this.setHandleElId(id);this.hasOuterHandles=true;},unreg:function(){_1.un(this.id,"mousedown",this.handleMouseDown);this._domRef=null;this.DDM._remove(this);},isLocked:function(){return (this.DDM.isLocked()||this.locked);},handleMouseDown:function(e,oDD){if(this.primaryButtonOnly&&e.button!=0){return;}if(this.isLocked()){return;}this.DDM.refreshCache(this.groups);var pt=new Ext.lib.Point(Ext.lib.Event.getPageX(e),Ext.lib.Event.getPageY(e));if(!this.hasOuterHandles&&!this.DDM.isOverTarget(pt,this)){}else{if(this.clickValidator(e)){this.setStartPosition();this.b4MouseDown(e);this.onMouseDown(e);this.DDM.handleMouseDown(e,this);this.DDM.stopEvent(e);}else{}}},clickValidator:function(e){var _42=Ext.lib.Event.getTarget(e);return (this.isValidHandleChild(_42)&&(this.id==this.handleElId||this.DDM.handleWasClicked(_42,this.id)));},addInvalidHandleType:function(_43){var _44=_43.toUpperCase();this.invalidHandleTypes[_44]=_44;},addInvalidHandleId:function(id){if(typeof id!=="string"){id=Ext.id(id);}this.invalidHandleIds[id]=id;},addInvalidHandleClass:function(_46){this.invalidHandleClasses.push(_46);},removeInvalidHandleType:function(_47){var _48=_47.toUpperCase();delete this.invalidHandleTypes[_48];},removeInvalidHandleId:function(id){if(typeof id!=="string"){id=Ext.id(id);}delete this.invalidHandleIds[id];},removeInvalidHandleClass:function(_4a){for(var i=0,len=this.invalidHandleClasses.length;i<len;++i){if(this.invalidHandleClasses[i]==_4a){delete this.invalidHandleClasses[i];}}},isValidHandleChild:function(_4d){var _4e=true;var _4f;try{_4f=_4d.nodeName.toUpperCase();}catch(e){_4f=_4d.nodeName;}_4e=_4e&&!this.invalidHandleTypes[_4f];_4e=_4e&&!this.invalidHandleIds[_4d.id];for(var i=0,len=this.invalidHandleClasses.length;_4e&&i<len;++i){_4e=!_2.hasClass(_4d,this.invalidHandleClasses[i]);}return _4e;},setXTicks:function(_52,_53){this.xTicks=[];this.xTickSize=_53;var _54={};for(var i=this.initPageX;i>=this.minX;i=i-_53){if(!_54[i]){this.xTicks[this.xTicks.length]=i;_54[i]=true;}}for(i=this.initPageX;i<=this.maxX;i=i+_53){if(!_54[i]){this.xTicks[this.xTicks.length]=i;_54[i]=true;}}this.xTicks.sort(this.DDM.numericSort);},setYTicks:function(_56,_57){this.yTicks=[];this.yTickSize=_57;var _58={};for(var i=this.initPageY;i>=this.minY;i=i-_57){if(!_58[i]){this.yTicks[this.yTicks.length]=i;_58[i]=true;}}for(i=this.initPageY;i<=this.maxY;i=i+_57){if(!_58[i]){this.yTicks[this.yTicks.length]=i;_58[i]=true;}}this.yTicks.sort(this.DDM.numericSort);},setXConstraint:function(_5a,_5b,_5c){this.leftConstraint=_5a;this.rightConstraint=_5b;this.minX=this.initPageX-_5a;this.maxX=this.initPageX+_5b;if(_5c){this.setXTicks(this.initPageX,_5c);}this.constrainX=true;},clearConstraints:function(){this.constrainX=false;this.constrainY=false;this.clearTicks();},clearTicks:function(){this.xTicks=null;this.yTicks=null;this.xTickSize=0;this.yTickSize=0;},setYConstraint:function(iUp,_5e,_5f){this.topConstraint=iUp;this.bottomConstraint=_5e;this.minY=this.initPageY-iUp;this.maxY=this.initPageY+_5e;if(_5f){this.setYTicks(this.initPageY,_5f);}this.constrainY=true;},resetConstraints:function(){if(this.initPageX||this.initPageX===0){var dx=(this.maintainOffset)?this.lastPageX-this.initPageX:0;var dy=(this.maintainOffset)?this.lastPageY-this.initPageY:0;this.setInitPosition(dx,dy);}else{this.setInitPosition();}if(this.constrainX){this.setXConstraint(this.leftConstraint,this.rightConstraint,this.xTickSize);}if(this.constrainY){this.setYConstraint(this.topConstraint,this.bottomConstraint,this.yTickSize);}},getTick:function(val,_63){if(!_63){return val;}else{if(_63[0]>=val){return _63[0];}else{for(var i=0,len=_63.length;i<len;++i){var _66=i+1;if(_63[_66]&&_63[_66]>=val){var _67=val-_63[i];var _68=_63[_66]-val;return (_68>_67)?_63[i]:_63[_66];}}return _63[_63.length-1];}}},toString:function(){return ("DragDrop "+this.id);}};})();if(!Ext.dd.DragDropMgr){Ext.dd.DragDropMgr=function(){var _69=Ext.EventManager;return {ids:{},handleIds:{},dragCurrent:null,dragOvers:{},deltaX:0,deltaY:0,preventDefault:true,stopPropagation:true,initalized:false,locked:false,init:function(){this.initialized=true;},POINT:0,INTERSECT:1,mode:0,_execOnAll:function(_6a,_6b){for(var i in this.ids){for(var j in this.ids[i]){var oDD=this.ids[i][j];if(!this.isTypeOfDD(oDD)){continue;}oDD[_6a].apply(oDD,_6b);}}},_onLoad:function(){this.init();_69.on(document,"mouseup",this.handleMouseUp,this,true);_69.on(document,"mousemove",this.handleMouseMove,this,true);_69.on(window,"unload",this._onUnload,this,true);_69.on(window,"resize",this._onResize,this,true);},_onResize:function(e){this._execOnAll("resetConstraints",[]);},lock:function(){this.locked=true;},unlock:function(){this.locked=false;},isLocked:function(){return this.locked;},locationCache:{},useCache:true,clickPixelThresh:3,clickTimeThresh:350,dragThreshMet:false,clickTimeout:null,startX:0,startY:0,regDragDrop:function(oDD,_71){if(!this.initialized){this.init();}if(!this.ids[_71]){this.ids[_71]={};}this.ids[_71][oDD.id]=oDD;},removeDDFromGroup:function(oDD,_73){if(!this.ids[_73]){this.ids[_73]={};}var obj=this.ids[_73];if(obj&&obj[oDD.id]){delete obj[oDD.id];}},_remove:function(oDD){for(var g in oDD.groups){if(g&&this.ids[g][oDD.id]){delete this.ids[g][oDD.id];}}delete this.handleIds[oDD.id];},regHandle:function(_77,_78){if(!this.handleIds[_77]){this.handleIds[_77]={};}this.handleIds[_77][_78]=_78;},isDragDrop:function(id){return (this.getDDById(id))?true:false;},getRelated:function(_7a,_7b){var _7c=[];for(var i in _7a.groups){for(j in this.ids[i]){var dd=this.ids[i][j];if(!this.isTypeOfDD(dd)){continue;}if(!_7b||dd.isTarget){_7c[_7c.length]=dd;}}}return _7c;},isLegalTarget:function(oDD,_80){var _81=this.getRelated(oDD,true);for(var i=0,len=_81.length;i<len;++i){if(_81[i].id==_80.id){return true;}}return false;},isTypeOfDD:function(oDD){return (oDD&&oDD.__ygDragDrop);},isHandle:function(_85,_86){return (this.handleIds[_85]&&this.handleIds[_85][_86]);},getDDById:function(id){for(var i in this.ids){if(this.ids[i][id]){return this.ids[i][id];}}return null;},handleMouseDown:function(e,oDD){this.currentTarget=Ext.lib.Event.getTarget(e);this.dragCurrent=oDD;var el=oDD.getEl();this.startX=Ext.lib.Event.getPageX(e);this.startY=Ext.lib.Event.getPageY(e);this.deltaX=this.startX-el.offsetLeft;this.deltaY=this.startY-el.offsetTop;this.dragThreshMet=false;this.clickTimeout=setTimeout(function(){var DDM=Ext.dd.DDM;DDM.startDrag(DDM.startX,DDM.startY);},this.clickTimeThresh);},startDrag:function(x,y){clearTimeout(this.clickTimeout);if(this.dragCurrent){this.dragCurrent.b4StartDrag(x,y);this.dragCurrent.startDrag(x,y);}this.dragThreshMet=true;},handleMouseUp:function(e){if(!this.dragCurrent){return;}clearTimeout(this.clickTimeout);if(this.dragThreshMet){this.fireEvents(e,true);}else{}this.stopDrag(e);this.stopEvent(e);},stopEvent:function(e){if(this.stopPropagation){e.stopPropagation();}if(this.preventDefault){e.preventDefault();}},stopDrag:function(e){if(this.dragCurrent){if(this.dragThreshMet){this.dragCurrent.b4EndDrag(e);this.dragCurrent.endDrag(e);}this.dragCurrent.onMouseUp(e);}this.dragCurrent=null;this.dragOvers={};},handleMouseMove:function(e){if(!this.dragCurrent){return true;}if(Ext.isIE&&(e.button!==0&&e.button!==1&&e.button!==2)){this.stopEvent(e);return this.handleMouseUp(e);}if(!this.dragThreshMet){var _93=Math.abs(this.startX-Ext.lib.Event.getPageX(e));var _94=Math.abs(this.startY-Ext.lib.Event.getPageY(e));if(_93>this.clickPixelThresh||_94>this.clickPixelThresh){this.startDrag(this.startX,this.startY);}}if(this.dragThreshMet){this.dragCurrent.b4Drag(e);this.dragCurrent.onDrag(e);if(!this.dragCurrent.moveOnly){this.fireEvents(e,false);}}this.stopEvent(e);return true;},fireEvents:function(e,_96){var dc=this.dragCurrent;if(!dc||dc.isLocked()){return;}var x=Ext.lib.Event.getPageX(e);var y=Ext.lib.Event.getPageY(e);var pt=new Ext.lib.Point(x,y);var _9b=[];var _9c=[];var _9d=[];var _9e=[];var _9f=[];for(var i in this.dragOvers){var ddo=this.dragOvers[i];if(!this.isTypeOfDD(ddo)){continue;}if(!this.isOverTarget(pt,ddo,this.mode)){_9c.push(ddo);}_9b[i]=true;delete this.dragOvers[i];}for(var _a2 in dc.groups){if("string"!=typeof _a2){continue;}for(i in this.ids[_a2]){var oDD=this.ids[_a2][i];if(!this.isTypeOfDD(oDD)){continue;}if(oDD.isTarget&&!oDD.isLocked()&&oDD!=dc){if(this.isOverTarget(pt,oDD,this.mode)){if(_96){_9e.push(oDD);}else{if(!_9b[oDD.id]){_9f.push(oDD);}else{_9d.push(oDD);}this.dragOvers[oDD.id]=oDD;}}}}}if(this.mode){if(_9c.length){dc.b4DragOut(e,_9c);dc.onDragOut(e,_9c);}if(_9f.length){dc.onDragEnter(e,_9f);}if(_9d.length){dc.b4DragOver(e,_9d);dc.onDragOver(e,_9d);}if(_9e.length){dc.b4DragDrop(e,_9e);dc.onDragDrop(e,_9e);}}else{var len=0;for(i=0,len=_9c.length;i<len;++i){dc.b4DragOut(e,_9c[i].id);dc.onDragOut(e,_9c[i].id);}for(i=0,len=_9f.length;i<len;++i){dc.onDragEnter(e,_9f[i].id);}for(i=0,len=_9d.length;i<len;++i){dc.b4DragOver(e,_9d[i].id);dc.onDragOver(e,_9d[i].id);}for(i=0,len=_9e.length;i<len;++i){dc.b4DragDrop(e,_9e[i].id);dc.onDragDrop(e,_9e[i].id);}}if(_96&&!_9e.length){dc.onInvalidDrop(e);}},getBestMatch:function(dds){var _a6=null;var len=dds.length;if(len==1){_a6=dds[0];}else{for(var i=0;i<len;++i){var dd=dds[i];if(dd.cursorIsOver){_a6=dd;break;}else{if(!_a6||_a6.overlap.getArea()<dd.overlap.getArea()){_a6=dd;}}}}return _a6;},refreshCache:function(_aa){for(var _ab in _aa){if("string"!=typeof _ab){continue;}for(var i in this.ids[_ab]){var oDD=this.ids[_ab][i];if(this.isTypeOfDD(oDD)){var loc=this.getLocation(oDD);if(loc){this.locationCache[oDD.id]=loc;}else{delete this.locationCache[oDD.id];}}}}},verifyEl:function(el){try{if(el){var _b0=el.offsetParent;if(_b0){return true;}}}catch(e){}return false;},getLocation:function(oDD){if(!this.isTypeOfDD(oDD)){return null;}var el=oDD.getEl(),pos,x1,x2,y1,y2,t,r,b,l;try{pos=Ext.lib.Dom.getXY(el);}catch(e){}if(!pos){return null;}x1=pos[0];x2=x1+el.offsetWidth;y1=pos[1];y2=y1+el.offsetHeight;t=y1-oDD.padding[0];r=x2+oDD.padding[1];b=y2+oDD.padding[2];l=x1-oDD.padding[3];return new Ext.lib.Region(t,r,b,l);},isOverTarget:function(pt,_bd,_be){var loc=this.locationCache[_bd.id];if(!loc||!this.useCache){loc=this.getLocation(_bd);this.locationCache[_bd.id]=loc;}if(!loc){return false;}_bd.cursorIsOver=loc.contains(pt);var dc=this.dragCurrent;if(!dc||!dc.getTargetCoord||(!_be&&!dc.constrainX&&!dc.constrainY)){return _bd.cursorIsOver;}_bd.overlap=null;var pos=dc.getTargetCoord(pt.x,pt.y);var el=dc.getDragEl();var _c3=new Ext.lib.Region(pos.y,pos.x+el.offsetWidth,pos.y+el.offsetHeight,pos.x);var _c4=_c3.intersect(loc);if(_c4){_bd.overlap=_c4;return (_be)?true:_bd.cursorIsOver;}else{return false;}},_onUnload:function(e,me){Ext.dd.DragDropMgr.unregAll();},unregAll:function(){if(this.dragCurrent){this.stopDrag();this.dragCurrent=null;}this._execOnAll("unreg",[]);for(i in this.elementCache){delete this.elementCache[i];}this.elementCache={};this.ids={};},elementCache:{},getElWrapper:function(id){var _c8=this.elementCache[id];if(!_c8||!_c8.el){_c8=this.elementCache[id]=new this.ElementWrapper(Ext.getDom(id));}return _c8;},getElement:function(id){return Ext.getDom(id);},getCss:function(id){var el=Ext.getDom(id);return (el)?el.style:null;},ElementWrapper:function(el){this.el=el||null;this.id=this.el&&el.id;this.css=this.el&&el.style;},getPosX:function(el){return Ext.lib.Dom.getX(el);},getPosY:function(el){return Ext.lib.Dom.getY(el);},swapNode:function(n1,n2){if(n1.swapNode){n1.swapNode(n2);}else{var p=n2.parentNode;var s=n2.nextSibling;if(s==n1){p.insertBefore(n1,n2);}else{if(n2==n1.nextSibling){p.insertBefore(n2,n1);}else{n1.parentNode.replaceChild(n2,n1);p.insertBefore(n1,s);}}}},getScroll:function(){var t,l,dde=document.documentElement,db=document.body;if(dde&&(dde.scrollTop||dde.scrollLeft)){t=dde.scrollTop;l=dde.scrollLeft;}else{if(db){t=db.scrollTop;l=db.scrollLeft;}else{}}return {top:t,left:l};},getStyle:function(el,_d8){return Ext.fly(el).getStyle(_d8);},getScrollTop:function(){return this.getScroll().top;},getScrollLeft:function(){return this.getScroll().left;},moveToEl:function(_d9,_da){var _db=Ext.lib.Dom.getXY(_da);Ext.lib.Dom.setXY(_d9,_db);},numericSort:function(a,b){return (a-b);},_timeoutCount:0,_addListeners:function(){var DDM=Ext.dd.DDM;if(Ext.lib.Event&&document){DDM._onLoad();}else{if(DDM._timeoutCount>2000){}else{setTimeout(DDM._addListeners,10);if(document&&document.body){DDM._timeoutCount+=1;}}}},handleWasClicked:function(_df,id){if(this.isHandle(id,_df.id)){return true;}else{var p=_df.parentNode;while(p){if(this.isHandle(id,p.id)){return true;}else{p=p.parentNode;}}}return false;}};}();Ext.dd.DDM=Ext.dd.DragDropMgr;Ext.dd.DDM._addListeners();}Ext.dd.DD=function(id,_e3,_e4){if(id){this.init(id,_e3,_e4);}};Ext.extend(Ext.dd.DD,Ext.dd.DragDrop,{scroll:true,autoOffset:function(_e5,_e6){var x=_e5-this.startPageX;var y=_e6-this.startPageY;this.setDelta(x,y);},setDelta:function(_e9,_ea){this.deltaX=_e9;this.deltaY=_ea;},setDragElPos:function(_eb,_ec){var el=this.getDragEl();this.alignElWithMouse(el,_eb,_ec);},alignElWithMouse:function(el,_ef,_f0){var _f1=this.getTargetCoord(_ef,_f0);var fly=el.dom?el:Ext.fly(el);if(!this.deltaSetXY){var _f3=[_f1.x,_f1.y];fly.setXY(_f3);var _f4=fly.getLeft(true);var _f5=fly.getTop(true);this.deltaSetXY=[_f4-_f1.x,_f5-_f1.y];}else{fly.setLeftTop(_f1.x+this.deltaSetXY[0],_f1.y+this.deltaSetXY[1]);}this.cachePosition(_f1.x,_f1.y);this.autoScroll(_f1.x,_f1.y,el.offsetHeight,el.offsetWidth);return _f1;},cachePosition:function(_f6,_f7){if(_f6){this.lastPageX=_f6;this.lastPageY=_f7;}else{var _f8=Ext.lib.Dom.getXY(this.getEl());this.lastPageX=_f8[0];this.lastPageY=_f8[1];}},autoScroll:function(x,y,h,w){if(this.scroll){var _fd=Ext.lib.Dom.getViewWidth();var _fe=Ext.lib.Dom.getViewHeight();var st=this.DDM.getScrollTop();var sl=this.DDM.getScrollLeft();var bot=h+y;var _102=w+x;var _103=(_fd+st-y-this.deltaY);var _104=(_fe+sl-x-this.deltaX);var _105=40;var _106=(document.all)?80:30;if(bot>_fd&&_103<_105){window.scrollTo(sl,st+_106);}if(y<st&&st>0&&y-st<_105){window.scrollTo(sl,st-_106);}if(_102>_fe&&_104<_105){window.scrollTo(sl+_106,st);}if(x<sl&&sl>0&&x-sl<_105){window.scrollTo(sl-_106,st);}}},getTargetCoord:function(_107,_108){var x=_107-this.deltaX;var y=_108-this.deltaY;if(this.constrainX){if(x<this.minX){x=this.minX;}if(x>this.maxX){x=this.maxX;}}if(this.constrainY){if(y<this.minY){y=this.minY;}if(y>this.maxY){y=this.maxY;}}x=this.getTick(x,this.xTicks);y=this.getTick(y,this.yTicks);return {x:x,y:y};},applyConfig:function(){Ext.dd.DD.superclass.applyConfig.call(this);this.scroll=(this.config.scroll!==false);},b4MouseDown:function(e){this.autoOffset(Ext.lib.Event.getPageX(e),Ext.lib.Event.getPageY(e));},b4Drag:function(e){this.setDragElPos(Ext.lib.Event.getPageX(e),Ext.lib.Event.getPageY(e));},toString:function(){return ("DD "+this.id);}});Ext.dd.DDProxy=function(id,_10e,_10f){if(id){this.init(id,_10e,_10f);this.initFrame();}};Ext.dd.DDProxy.dragElId="ygddfdiv";Ext.extend(Ext.dd.DDProxy,Ext.dd.DD,{resizeFrame:true,centerFrame:false,createFrame:function(){var self=this;var body=document.body;if(!body||!body.firstChild){setTimeout(function(){self.createFrame();},50);return;}var div=this.getDragEl();if(!div){div=document.createElement("div");div.id=this.dragElId;var s=div.style;s.position="absolute";s.visibility="hidden";s.cursor="move";s.border="2px solid #aaa";s.zIndex=999;body.insertBefore(div,body.firstChild);}},initFrame:function(){this.createFrame();},applyConfig:function(){Ext.dd.DDProxy.superclass.applyConfig.call(this);this.resizeFrame=(this.config.resizeFrame!==false);this.centerFrame=(this.config.centerFrame);this.setDragElId(this.config.dragElId||Ext.dd.DDProxy.dragElId);},showFrame:function(_114,_115){var el=this.getEl();var _117=this.getDragEl();var s=_117.style;this._resizeProxy();if(this.centerFrame){this.setDelta(Math.round(parseInt(s.width,10)/2),Math.round(parseInt(s.height,10)/2));}this.setDragElPos(_114,_115);Ext.fly(_117).show();},_resizeProxy:function(){if(this.resizeFrame){var el=this.getEl();Ext.fly(this.getDragEl()).setSize(el.offsetWidth,el.offsetHeight);}},b4MouseDown:function(e){var x=Ext.lib.Event.getPageX(e);var y=Ext.lib.Event.getPageY(e);this.autoOffset(x,y);this.setDragElPos(x,y);},b4StartDrag:function(x,y){this.showFrame(x,y);},b4EndDrag:function(e){Ext.fly(this.getDragEl()).hide();},endDrag:function(e){var lel=this.getEl();var del=this.getDragEl();del.style.visibility="";this.beforeMove();lel.style.visibility="hidden";Ext.dd.DDM.moveToEl(lel,del);del.style.visibility="hidden";lel.style.visibility="";this.afterDrag();},beforeMove:function(){},afterDrag:function(){},toString:function(){return ("DDProxy "+this.id);}});Ext.dd.DDTarget=function(id,_124,_125){if(id){this.initTarget(id,_124,_125);}};Ext.extend(Ext.dd.DDTarget,Ext.dd.DragDrop,{toString:function(){return ("DDTarget "+this.id);}});
