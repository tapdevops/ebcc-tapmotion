/*
 * Ext JS Library 1.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */

Ext.grid.EditorGrid=function(_1,_2){Ext.grid.EditorGrid.superclass.constructor.call(this,_1,_2);this.container.addClass("xedit-grid");if(!this.selModel){this.selModel=new Ext.grid.CellSelectionModel();}this.activeEditor=null;this.addEvents({"beforeedit":true,"afteredit":true,"validateedit":true});this.on("bodyscroll",this.stopEditing,this);this.on(this.clicksToEdit==1?"cellclick":"celldblclick",this.onCellDblClick,this);};Ext.extend(Ext.grid.EditorGrid,Ext.grid.Grid,{isEditor:true,clicksToEdit:2,trackMouseOver:false,onCellDblClick:function(g,_4,_5){this.startEditing(_4,_5);},onEditComplete:function(ed,_7,_8){this.editing=false;this.activeEditor=null;ed.un("specialkey",this.selModel.onEditorKey,this.selModel);if(String(_7)!=String(_8)){var r=ed.record;var _a=this.colModel.getDataIndex(ed.col);var e={grid:this,record:r,field:_a,originalValue:_8,value:_7,row:ed.row,column:ed.col,cancel:false};if(this.fireEvent("validateedit",e)!==false&&!e.cancel){r.set(_a,e.value);delete e.cancel;this.fireEvent("afteredit",e);}}this.view.focusCell(ed.row,ed.col);},startEditing:function(_c,_d){this.stopEditing();if(this.colModel.isCellEditable(_d,_c)){this.view.focusCell(_c,_d);var r=this.dataSource.getAt(_c);var _f=this.colModel.getDataIndex(_d);var e={grid:this,record:r,field:_f,value:r.data[_f],row:_c,column:_d,cancel:false};if(this.fireEvent("beforeedit",e)!==false&&!e.cancel){this.editing=true;(function(){var ed=this.colModel.getCellEditor(_d,_c);ed.row=_c;ed.col=_d;ed.record=r;ed.on("complete",this.onEditComplete,this,{single:true});ed.on("specialkey",this.selModel.onEditorKey,this.selModel);this.activeEditor=ed;var v=r.data[_f];ed.startEdit(this.view.getCell(_c,_d),v);}).defer(50,this);}}},stopEditing:function(){if(this.activeEditor){this.activeEditor.completeEdit();}this.activeEditor=null;}});
