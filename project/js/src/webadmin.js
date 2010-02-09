var containers		= new Array();
var nodes			= new Array();
var editors			= new Array();
var dialogs			= new Array();
var klCancels		= new Array();
var klSaves			= new Array();
var resizes			= new Array();
// Define various event handlers for Dialog
var handleSubmit = function() {
	if (editors[this.form.id]) {
		editors[this.form.id].saveHTML();
	}
	this.submit();
};
var handleCancel = function() {
	this.cancel();
};
var handleSuccessContent = function(o) {
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
	    editors[form.id].setEditorHTML(data.Results.content);
	}
	nodes[form.id].innerHTML	= data.Results.content;
	// recreate resize onto reloaded metanode
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
var handleFailure = function(o) {
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
	var request 	= YAHOO.util.Connect.asyncRequest('POST', formR.action, {success:handleSuccessResize, failure: handleFailure, argument: ['foo','bar']}, resizeData);
};

function metanodes_attach() {
	YAHOO.util.Dom.addClass(document.body, 'yui-skin-sam');
	var forms		= YAHOO.util.Selector.query('.metanode form');
	var dialog;
	var formID;
	var dialogForm;
	var dialogBtns;
	var field;
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
		field 					= YAHOO.util.Selector.query('.wsw', forms[i], true);
		submit 					= YAHOO.util.Selector.query('input.submit', forms[i], true);
		containers[forms[i].id]	= YAHOO.util.Dom.getAncestorByClassName(forms[i], 'metanode');
		nodes[forms[i].id]		= YAHOO.util.Selector.query('.content', YAHOO.util.Dom.getAncestorByClassName(forms[i], 'metanode'), true);
		nodes[forms[i].id].style.minHeight	= '20px';
		forms[i].style.display	= 'block';
		submit.disabled			= false;

		// Instantiate the Dialogs
		dialogs[forms[i].id] = new YAHOO.widget.Dialog(dialogForm, 
					{ width: "725px",
					  fixedcenter : true,
					  y : 20,
					  modal : true,
					  visible : false,
					  draggable: true
					 });
		//set up buttons for the Dialog and wire them
		//up to our handlers:
		dialogBtns = [ { text:"Save", 
							handler:handleSubmit },
						  { text:"Cancel", 
							handler:handleCancel,
							isDefault:true } ];
		dialogs[forms[i].id].cfg.queueProperty("buttons", dialogBtns);

		// attach dialog cancel to Esc
		klCancels[forms[i].id] = new YAHOO.util.KeyListener(dialogs[forms[i].id].id, 	{ 	keys:27 },  							
															{	fn:dialogs[forms[i].id].cancel,
																scope:dialogs[forms[i].id],
																correctScope:true } );
		//klCancels[forms[i].id].enable();
		dialogs[forms[i].id].cfg.queueProperty("keylisteners", klCancels[forms[i].id]);

		/* nechodi
		// attach dialog save and close to ctrl+s
		klSaves[forms[i].id] = new YAHOO.util.KeyListener(forms[i], 	{ 	ctrl:true, keys:83 }, 
													   					{ 	fn:dialogs[forms[i].id].submit, 
														 					scope:dialogs[forms[i].id],
														 					correctScope:true } );
		//dialogs[forms[i].id].cfg.queueProperty("keylisteners", klSaves[forms[i].id]);*/

		// attach dialog handlers
		dialogs[forms[i].id].callback.success = handleSuccessContent;
		dialogs[forms[i].id].callback.failure = handleFailure;

		YAHOO.util.Event.addListener(nodes[forms[i].id], "dblclick", dialogs[forms[i].id].show, dialogs[forms[i].id], true);

		submit.disabled			= true;
		submit.style.display	= 'none';
		
		// init richtext editors
	    var state = 'off';

		if (YAHOO.util.Dom.hasClass(containers[forms[i].id], 'metanode-richtext')) {
			editors[forms[i].id] = new YAHOO.widget.Editor(field.id, { 
				dompath: true, //Turns on the bar at the bottom 
				animate: false, //Animates the opening, closing and moving of Editor windows
				autoHeight: false,
				focusAtStart: true,
				width: '724px', height: '300px'
			});
			editors[forms[i].id].on('toolbarLoaded', function() {
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
			}, editors[forms[i].id], true);
			editors[forms[i].id].render();
			
			//RTE needs a little love to work in in a Dialog that can be 
			//shown and hidden; we let it know that it's being
			//shown/hidden so that it can recover from these actions:
			dialogs[forms[i].id].showEvent.subscribe(editors[forms[i].id].show, editors[forms[i].id], true);
			dialogs[forms[i].id].hideEvent.subscribe(editors[forms[i].id].hide, editors[forms[i].id], true);
		}

		// render dialog
		dialogs[forms[i].id].render();
		
		nodes[forms[i].id].style.border	= '1px dashed #ff0000';
		nodes[forms[i].id].style.cursor	= 'pointer';

		// attach resize
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
	}
}
YAHOO.util.Event.onDOMReady(metanodes_attach);