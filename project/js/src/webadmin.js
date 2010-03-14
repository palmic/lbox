var forms			= new Array();
var containers		= new Array();
var nodes			= new Array();
var editors			= new Array();
var metaRecordsRTEs	= new Array();
var fields			= new Array();
var dialogs			= new Array();
var klCancels		= new Array();
var klSaves			= new Array();
var resizes			= new Array();
/* Define various event handlers for Dialog*/
var handleSubmit = function() {
	if (editors[this.form.id]) {
		for (i in editors[this.form.id]) {
			editors[this.form.id][i].saveHTML();
		}
	}
	this.submit();
};
var handleCancel = function() {
	this.cancel();
};
var handleSuccessContentMetanode = function(o) {
    var json = o.responseText.substring(o.responseText.indexOf('{'), o.responseText.lastIndexOf('}') + 1);
    var data = eval('(' + json + ')');
	if (data.Exception) {
		alert('Error '+ data.Exception.code +': '+ data.Exception.message);
		return;
	}
	var data_caller_type	= data.Results.caller_type;
	var data_caller_id		= data.Results.caller_id;
	var data_type			= data.Results.type;
	var data_seq			= data.Results.seq;
	var data_lng			= data.Results.lng;
	var form				= document.getElementById('frm-metanode-'+data_caller_id+'-'+data_seq);
	var nodeInstances		= YAHOO.util.Selector.query('.metanode-'+data_caller_id+'-'+data_seq);
	var nodeContent;
	if (editors[form.id]) {
		for (i in editors[form.id]) {
		    editors[form.id][i].setEditorHTML(data.Results.content);
		}
	}
	nodes[form.id].innerHTML	= data.Results.content;
	/* recreate resize onto reloaded metanode*/
	if (resizesAllowed) {
	    resizes[form.id] = new YAHOO.util.Resize(nodes[form.id], {
			    proxy: true,
			    status: true,
			    animate: true,
			    animateDuration: .3,
			    animateEasing: YAHOO.util.Easing.easeBoth
	    });
		resizes[form.id].on('endResize', handleResize);
	}
	/* change all the metanode instances content */
	for (i in nodeInstances) {
		nodeInstances[i].style.overflow	= 'hidden';
		if (!YAHOO.util.Selector.query('form.to-edit', nodeInstances[i], true)) {
			nodeContent				= YAHOO.util.Selector.query('.content', nodeInstances[i], true);
			nodeContent.innerHTML	= data.Results.content;
		}
	}
};
var handleSuccessResize = function(o) {
    var json = o.responseText.substring(o.responseText.indexOf('{'), o.responseText.lastIndexOf('}') + 1);
    var data = eval('(' + json + ')');
	if (data.Exception) {
		alert('Error '+ data.Exception.code +': '+ data.Exception.message);
		return;
	}
	var data_caller_id		= data.Results.caller_id;
	var data_seq			= data.Results.seq;
	var nodeInstances		= YAHOO.util.Selector.query('.metanode-'+data_caller_id+'-'+data_seq);
	var form				= document.getElementById('frm-metanode-'+data_caller_id+'-'+data_seq);
	var nodeContent;
	for (i in nodeInstances) {
		if (!YAHOO.util.Selector.query('form.to-edit', nodeInstances[i], true)) {
			nodeInstances[i].style.overflow	= 'hidden';
			nodeContent							= YAHOO.util.Selector.query('.content', nodeInstances[i], true);
			nodeContent.style.width				= resizes[form.id].getWrapEl().style.width;
			nodeContent.style.height			= resizes[form.id].getWrapEl().style.height;
		}
	}
};
var handleFailureMetanode = function(o) {
    var json = o.responseText.substring(o.responseText.indexOf('{'), o.responseText.lastIndexOf('}') + 1);
    var data = eval('(' + json + ')');
	alert('communication failure!\n\nStatus:\n'+ data.Results.status);
};
var handleSuccessContentMetarecord = function(o){
	var json = o.responseText.substring(o.responseText.indexOf('{'), o.responseText.lastIndexOf('}') + 1);
    var data = eval('(' + json + ')');
	if (data.id == null) {var id = '';} else {var id = data.id;}
	/* delete previous errors */
	var errors	= YAHOO.util.Selector.query('.control label .error', document.getElementById('frm-metarecord-'+data.type+'-'+id));
	for (i in errors) {
		errors[i].parentNode.removeChild(errors[i]);
	}
	
	/* global exception */
	if (data.Exception) {
		alert('Error '+ data.Exception.code +': '+ data.Exception.message);
		return;
	}
	/* control validation errors */
	else if (data.invalidControls) {
		var controls= new Array();
		var labels	= new Array();
		var infoElms = new Array(), codeElms = new Array(), msgElms = new Array(), traceElms = new Array();
		for (i in data.invalidControls) {
			if (data.invalidControls[i]) {
				controls[i] = document.getElementById('control-frm-metarecord-'+data.type+'-'+id+'-ctrl-'+i);
				labels[i]	= YAHOO.util.Selector.query('label', controls[i], true);
				for (y in data.invalidControls[i]['invalidations']) {
					infoElms[i] = new YAHOO.util.Element(document.createElement('div'));infoElms[i].addClass('error');
					codeElms[i] = new YAHOO.util.Element(document.createElement('div'));codeElms[i].appendChild(document.createTextNode(y));
					msgElms[i]	= new YAHOO.util.Element(document.createElement('div'));msgElms[i].appendChild(document.createTextNode(data.invalidControls[i]['invalidations'][y]));
					codeElms[i].addClass('code');codeElms[i].appendTo(infoElms[i]);
					msgElms[i].addClass('message');msgElms[i].appendTo(infoElms[i]);
					infoElms[i].appendTo(YAHOO.util.Selector.query('label', controls[i], true));
				}
			}
		}
		return;
	}
	/* clone the first node into our new one */
	var metarecordFirst	= YAHOO.util.Selector.query('.metarecords-'+data.Data.type+' .metarecord', document, true);
	var metarecordClone	= new YAHOO.util.Element(metarecordFirst.cloneNode(true));
	/* insert clone before first one */
	YAHOO.util.Dom.insertBefore(metarecordClone, metarecordFirst);
	/* get height and set it to 0 */
	var cloneRegion 	= YAHOO.util.Dom.getRegion(metarecordClone);
	var cloneHeight 	= cloneRegion.bottom-cloneRegion.top;
	metarecordClone.setStyle('height', '0');
	/* set clone properties */
	var metarecordNode;
	for (i in data.Data) {
		if (metarecordNode = YAHOO.util.Dom.getElementsByClassName('metarecord-node-'+i, false, metarecordClone)[0]) {
			metarecordNode.innerHTML	= data.Data[i];
		}
	}
	/* close dialog */
	dialogs['frm-metarecord-'+data.Data['type']+'-'+id].cancel();
	/* animate-in new list node */
	var myAnim 			= new YAHOO.util.Anim(metarecordClone, {height: { to: cloneHeight}}, 1, YAHOO.util.Easing.easeOut);
	myAnim.animate();
}
var handleFailureMetarecord = function(o) {
    var json = o.responseText.substring(o.responseText.indexOf('{'), o.responseText.lastIndexOf('}') + 1);
    var data = eval('(' + json + ')');
	alert('communication failure!\n\nStatus:\n'+ data.Results.status);
};
var handleResize = function(o) {
	var formR		= YAHOO.util.Selector.query('form', YAHOO.util.Dom.getAncestorByClassName(this.getWrapEl(), 'metanode'), true);
	var typeR		= YAHOO.util.Selector.query('.type input', formR, true).value;
	var seqR		= YAHOO.util.Selector.query('.seq input', formR, true).value;
	var callerIDR	= YAHOO.util.Selector.query('.caller_id input', formR, true).value;
	var callerTypeR	= YAHOO.util.Selector.query('.caller_type input', formR, true).value;
	var lngR		= YAHOO.util.Selector.query('.lng input', formR, true).value;
	var styleR		= 'width:'+ this.getProxyEl().style.width +';height:'+ this.getProxyEl().style.height +';';
	var resizeData	= 'style[type]='+typeR+'&style[seq]='+seqR+'&style[caller_id]='+callerIDR+'&style[caller_type]='+callerTypeR+'&style[lng]='+lngR+'&style[content]='+styleR;
	var request 	= YAHOO.util.Connect.asyncRequest('POST', formR.action, {success:handleSuccessResize, failure: handleFailureMetanode, argument: ['foo','bar']}, resizeData);
};
/**
 * init richtext editors
 */
var renderRTE = function(field, form) {
		/* init richtext editors */
	    var state = 'off';
		if (YAHOO.util.Dom.getAncestorByClassName(form, 'metanode')) {
			var v_focusAtStart = true;
		}
		else {
			var v_focusAtStart = false;
		}
			if (!editors[form.id]) {
				editors[form.id]	= new Array();
			}
			editors[form.id][field.id] = new YAHOO.widget.Editor(field.id, { 
				dompath: true, /*Turns on the bar at the bottom*/ 
				animate: false, /*Animates the opening, closing and moving of Editor windows*/
				autoHeight: false,
				focusAtStart: v_focusAtStart,
				width: '724px', height: '300px',
				filterWord: true,
				ptags: true
			});
			editors[form.id][field.id].on('toolbarLoaded', function() {
			        var codeConfig = { type: 'push', label: 'Edit HTML Code', value: 'editcode' };
			        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');

			        this.toolbar.on('editcodeClick', function() {
			            var ta 		= this.get('element'),
							iframe 	= this.get('iframe').get('element');
			
			            if (state == 'on') {
			                state = 'off';
			                this.toolbar.set('disabled', false);
			                this.setEditorHTML(ta.value);
			                if (!this.browser.ie) {
			                    this._setDesignMode('on');
			                }
			
			                Dom.removeClass(iframe, 'editor-hidden');
			                Dom.addClass(ta, 'editor-hidden');
			                this.show();
			                this._focusWindow();
			            } else {
			                state = 'on';
			                this.cleanHTML();
			                Dom.addClass(iframe, 'editor-hidden');
			                Dom.removeClass(ta, 'editor-hidden');
			                this.toolbar.set('disabled', true);
			                this.toolbar.getButtonByValue('editcode').set('disabled', false);
			                this.toolbar.selectButton('editcode');
			                this.dompath.innerHTML = 'Editing HTML Code';
			                this.hide();
			            }
			            return false;
			        }, this, true);
			
			        this.on('cleanHTML', function(ev) {
			            YAHOO.log('cleanHTML callback fired..', 'info', 'example');
			            this.get('element').value = ev.html;
			        }, this, true);
			        
			        this.on('afterRender', function() {
			            var wrapper = this.get('editor_wrapper');
			            wrapper.appendChild(this.get('element'));
			            this.setStyle('width', '100%');
			            this.setStyle('height', '100%');
			            this.setStyle('visibility', '');
			            this.setStyle('top', '');
			            this.setStyle('left', '');
			            this.setStyle('position', '');
			
			            this.addClass('editor-hidden');
			        }, this, true);
			}, editors[form.id][field.id], true);
			editors[form.id][field.id].render();
			/*RTE needs a little love to work in in a Dialog that can be 
			shown and hidden; we let it know that it's being
			shown/hidden so that it can recover from these actions:*/
			dialogs[form.id].showEvent.subscribe(editors[form.id][field.id].show, editors[form.id][field.id], true);
			dialogs[form.id].hideEvent.subscribe(editors[form.id][field.id].hide, editors[form.id][field.id], true);
}

function metanodes_attach() {
	YAHOO.util.Dom.addClass(document.body, 'yui-skin-sam');
		forms		= YAHOO.util.Selector.query('.lbox-meta form');
	var dialog;
	var formID;
	var dialogForm;
	var dialogBtns;
	var editor;
	var submit;
	var keyListener;
	var klSave;
	var klCancel;
    var Dom = YAHOO.util.Dom;
	var Event = YAHOO.util.Event;
	for(i in forms) {
		dialog	= YAHOO.util.Selector.query('.dialog', forms[i], true);
		if (!dialog) {
			continue;
		}
		dialogForm				= forms[i];
		fields[forms[i].id] 	= YAHOO.util.Selector.query('.wsw', forms[i], true);
		submit 					= YAHOO.util.Selector.query('input.submit', forms[i], true);
		containers[forms[i].id]	= YAHOO.util.Dom.getAncestorByClassName(forms[i], 'lbox-meta');
		nodes[forms[i].id]		= YAHOO.util.Selector.query('.lbox-meta-content', containers[forms[i].id], true);
		nodes[forms[i].id].style.minHeight	= '20px';
		forms[i].style.display	= 'block';
		submit.disabled			= false;
		if (YAHOO.util.Dom.hasClass(containers[forms[i].id], 'metarecord')) {
			var v_hideaftersubmit	= false;
		}
		else {
			var v_hideaftersubmit	= true;
		}

		/*Instantiate the Dialogs*/
		dialogs[forms[i].id] = new YAHOO.widget.Dialog(dialogForm, 
					{ width: "750px",
					  fixedcenter : true,
					  y : 20,
					  modal : true,
					  visible : false,
					  draggable: true,
					  hideaftersubmit: v_hideaftersubmit
					 });
		/*set up buttons for the Dialog and wire them
		up to our handlers:*/
		dialogBtns = [ { text:"Save", 
							handler:handleSubmit },
						  { text:"Cancel", 
							handler:handleCancel,
							isDefault:true } ];
		dialogs[forms[i].id].cfg.queueProperty("buttons", dialogBtns);

		/* set dialog visual properties */
		if (YAHOO.util.Dom.hasClass(containers[forms[i].id], 'metarecord')) {
			forms[i].style.height	= (document.documentElement.clientHeight-(document.documentElement.clientHeight/30))+'px';
			forms[i].style.overflow	= 'scroll';
		}

		/* attach dialog cancel to Esc */
		klCancels[forms[i].id] = new YAHOO.util.KeyListener(dialogs[forms[i].id].id, 	{ 	keys:27 },  							
															{	fn:dialogs[forms[i].id].cancel,
																scope:dialogs[forms[i].id],
																correctScope:true } );
		/* klCancels[forms[i].id].enable(); */
		dialogs[forms[i].id].cfg.queueProperty("keylisteners", klCancels[forms[i].id]);

		/* nechodi
		// attach dialog save and close to ctrl+s
		klSaves[forms[i].id] = new YAHOO.util.KeyListener(forms[i], 	{ 	ctrl:true, keys:83 }, 
													   					{ 	fn:dialogs[forms[i].id].submit, 
														 					scope:dialogs[forms[i].id],
														 					correctScope:true } );
		//dialogs[forms[i].id].cfg.queueProperty("keylisteners", klSaves[forms[i].id]);*/

		YAHOO.util.Event.addListener(nodes[forms[i].id], "dblclick", dialogs[forms[i].id].show, dialogs[forms[i].id], true);

		submit.disabled			= true;
		submit.style.display	= 'none';
		
		/* attach RTEs */
		if (YAHOO.util.Dom.hasClass(containers[forms[i].id], 'metanode-richtext')) {
			/* attach dialog handlers */
			dialogs[forms[i].id].callback.success = handleSuccessContentMetanode;
			dialogs[forms[i].id].callback.failure = handleFailureMetanode;
			renderRTE(fields[forms[i].id], forms[i]);
		}
		else if (YAHOO.util.Dom.hasClass(containers[forms[i].id], 'metarecord')) {
			/* attach dialog handlers */
			dialogs[forms[i].id].callback.success = handleSuccessContentMetarecord;
			dialogs[forms[i].id].callback.failure = handleSuccessContentMetarecord;
			/* load all metarecord's RTEs */
			metaRecordsRTEs[forms[i].id]	= YAHOO.util.Selector.query('.wsw .wsw', forms[i]);
			for (rtesi in metaRecordsRTEs[forms[i].id]) {
				renderRTE(metaRecordsRTEs[forms[i].id][rtesi], forms[i]);
			}
		}

		/* render dialog*/
		dialogs[forms[i].id].render();
		
		/* attach resize*/
		if (!YAHOO.util.Dom.hasClass(containers[forms[i].id], 'metarecord')) {
			if (resizesAllowed) {
				nodes[forms[i].id].style.overflow	= 'hidden';
				resizes[forms[i].id] = new YAHOO.util.Resize(nodes[forms[i].id], {
				    proxy: true,
				    status: true,
				    animate: true,
				    animateDuration: .3,
				    animateEasing: YAHOO.util.Easing.easeBoth
				});
				resizes[forms[i].id].on('endResize', handleResize);
			}
			nodes[forms[i].id].style.border	= '1px dashed #ff0000';
			nodes[forms[i].id].style.cursor	= 'pointer';
		}
		else {
			nodes[forms[i].id].style.border	= '1px dotted #ff0000';
			nodes[forms[i].id].style.cursor	= 'pointer';
		}
	}
}
YAHOO.util.Event.onDOMReady(metanodes_attach);