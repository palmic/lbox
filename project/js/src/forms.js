lbox_forms_input_activate_classname = function(input) {
	input.getParent('.control').getElements('input').each(function(sibling, index) {
		if (sibling.getParent('label')) {
			switch (sibling.type) {
				case 'text':
					if (sibling.name == input.name) {sibling.getParent('label').addClass('active');}
					else 							{sibling.getParent('label').removeClass('active');}
				break;
				default:
					if (sibling.value == input.value)	{sibling.getParent('label').addClass('active');}
					else 								{sibling.getParent('label').removeClass('active');}
				break;
			}
		}
	});
}
lbox_forms_input_deactivate_classname = function(input) {
	if (input.getParent('label')) {
		input.getParent('label').removeClass('active');
	}
}
lbox_forms_show	= function(control) {
	control.getElements('input').each(function(input, index) {
		if (input.retrieve('disabledBefore')) {
			input.disabled = true;
		}
		else {
			input.disabled = false;
		}
	});
	control.getElements('select').each(function(input, index) {
		if (input.retrieve('disabledBefore')) {
			input.disabled = true;
		}
		else {
			input.disabled = false;
		}
	});
	/*if (Fx && Fx.Morph) {
		if (control.retrieve('sizeOri').y) {
			if (control.getStyle('height').toInt() == 0) {
				control.setStyle('height', 0);
				control.setStyle('display', 'block');
				if (!control.retrieve('fxmorph')) {
					control.store('fxmorph', new Fx.Morph(control.id, {duration: 'short', transition: Fx.Transitions.Sine.easeOut}));
				}
				control.retrieve('fxmorph').start({'height': [0, control.retrieve('sizeOri').y]}).chain(
						function(){ this.start({'background-color': ['#FFFFFF', '#80B0FF']}); },
						function(){ this.start({'background-color': ['#80B0FF', '#FFFFFF']}); },
						function(){ this.start({'background-color': ['#FFFFFF', '#80B0FF']}); },
						function(){ this.start({'background-color': ['#80B0FF', '#FFFFFF']}); }
				);
			}
		}
		else {
			control.setStyle('display', 'block');
			control.setStyle('height', 'auto');
		}
	}
	else {*/
		control.setStyle('display', 'block');
	/*}*/
}
lbox_forms_hide	= function(control, calledByChange) {
	control.getElements('input').each(function(input, index) {
		if (calledByChange) {
			switch (input.type) {
				case 'radio':
					input.checked	= false;
				break;
				case 'checkbox':
					input.checked	= false;
				break;
				default:
					input.value	= '';
			}
		}
		if (input.retrieve('disabledBefore') == undefined) {
			if (input.disabled) {
				input.store('disabledBefore', true);
			}
			else {
				input.store('disabledBefore', false);
			}
		}
		input.disabled = true;
		lbox_forms_input_deactivate_classname(input);
	});
	control.getElements('select').each(function(input, index) {
		if (calledByChange) {
			input.value	= '';
		}
		if (input.retrieve('disabledBefore') == undefined) {
			if (input.disabled) {
				input.store('disabledBefore', true);
			}
			else {
				input.store('disabledBefore', false);
			}
		}
		input.disabled = true;
		lbox_forms_input_deactivate_classname(input);
	});
	/*if (Fx && Fx.Morph) {
		control.store('sizeOri', control.getSize());
		control.setStyle('overflow', 'hidden');
		if (control.getStyle('height').toInt() > 0) {
			control.setStyle('height', control.getStyle('height').toInt());
			if (!control.retrieve('fxmorph')) {
				control.store('fxmorph', new Fx.Morph(control.id, {duration: 'short', transition: Fx.Transitions.Sine.easeOut}));
			}
			control.retrieve('fxmorph').start({'height': [control.retrieve('sizeOri').y, 0]});
		}
		else {
			control.setStyle('height', '0px');
		}
		control.setStyle('display', 'none');
	}
	else {*/
		control.setStyle('display', 'none');
	/*}*/
}
lbox_forms_switch_slaves_by_master = function(masterInput, calledByChange) {
	var masterControl		= masterInput.getParent('.control');
	var masterCTRLIDSimple	= masterControl.id.replace('control-'+masterControl.getParent('form').id+'-ctrl-', '');
	masterControl.getParent('form').getElements('.control').each(function(control, index) {
		/*if (!control.hasClass('control-multiple')) {*/
			if (control.title.length > 0) {
				control.title.split(";").each(function(condition, index) {
					if (condition.length > 0) {
						var condsParts 	= condition.split(":");
						var action 		= condsParts[0];
						var condsPartsStateMasterParts = condsParts[1].split("=");
						if (condsPartsStateMasterParts.length == 2) {
							var masterCTRL = condsPartsStateMasterParts[0];
							var masterValue 	= condsPartsStateMasterParts[1];
							var masterValues	= masterValue.split("|");
							var found			= false;
							if (masterCTRL == masterCTRLIDSimple) {
								if (action == 'enable') {
									switch (masterInput.type) {
										case 'radio':
											for (i in masterValues) {
												if (masterInput.value == masterValues[i]) {
													found = true;
												}
											}
											if (found) {
												if (masterInput.checked) {
													lbox_forms_show(control);
												}
												else {
													lbox_forms_hide(control, calledByChange);
												}
											}
											else {
												if (masterInput.checked) {
													lbox_forms_hide(control, calledByChange);
												}
											}
										break;
										case 'checkbox':
											for (i in masterValues) {
												if (masterInput.value == masterValues[i]) {
													found = true;
												}
											}
											if (found) {
												if (masterInput.checked) {
													lbox_forms_show(control);
												}
												else {
													/* pred zhasnutim controlu ovladaneho checkboxy je treba checknout, jestli neni checked jeste jiny, ktery ho udrzuje zapnuty */
													var foundSibling = false;
													masterControl.getElements('input').each(function(masterInputSibling, index) {
														if (masterInputSibling.checked) {
															/* check pokud je v mastervalues */
															for (i in masterValues) {
																if (masterValues[i] == masterInputSibling.id.substring(masterInputSibling.id.indexOf(masterCTRLIDSimple)+masterCTRLIDSimple.length+1)) {
																	foundSibling = true;
																}
															}
														}
													});
													if (!foundSibling) {
														lbox_forms_hide(control, calledByChange);
													}
												}
											}
										break;
										case 'text':
											for (i in masterValues) {
												if (masterInput.value == masterValues[i]) {
													found = true;
												}
											}
											if (found) {
												lbox_forms_show(control);
											}
											else {
												lbox_forms_hide(control, calledByChange);
											}
										break;
									}
									if (masterInput.tagName.toLowerCase() == 'select') {
										for (i in masterValues) {
											if (masterInput.value == masterValues[i]) {
												found = true;
											}
										}
										if (found) {
											lbox_forms_show(control);
										}
										else {
											lbox_forms_hide(control, calledByChange);
										}
									}
								}
							}
						}
						else {
							var masterCTRL = condsParts[1];
							if (masterCTRL == masterCTRLIDSimple) {
								switch (masterInput.type) {
									case 'radio':
										if (masterInput.checked) {
											lbox_forms_show(control);
										}
										break;
									case 'checkbox':
										if (masterInput.checked) {
											lbox_forms_show(control);
										}
										break;
									case 'text':
										if (masterInput.value) {
											lbox_forms_show(control);
										}
										else {
											lbox_forms_hide(control, calledByChange);
										}
										break;
								}
								if (masterInput.tagName.toLowerCase() == 'select') {
									if (masterInput.value == masterValue) {
										lbox_forms_show(control);
									}
									else {
										lbox_forms_hide(control, calledByChange);
									}
								}
							}
						}						
					}
				});
			}
		/*}*/
	});
}
window.addEvent('domready', function() {
	$$('.control').each(function(control, index) {
		if (!control.getParent('form').hasClass('disabled')) {
			if (!control.hasClass('control-multiple')) {
				control.getElements('input').each(function(input, index) {
					if (input.retrieve('disabledBefore') == undefined) {
						if (input.disabled) {
							input.store('disabledBefore', true);
						}
						else {
							input.store('disabledBefore', false);
						}
					}
					input.addEvent('change', function() {
						lbox_forms_switch_slaves_by_master(this, true);
						lbox_forms_input_activate_classname(this);
					});
					/* kvuli IEckum je treba nastavit protekani eventu click->focus->change */
					if (isIE()) {
						input.addEvent('focus', function() {this.fireEvent('change');return false;});
						input.addEvent('click', function() {this.fireEvent('focus')});
					}
					switch (input.type) {
						case "text":
							if (input.value.length > 0) {
								lbox_forms_input_activate_classname(input);
							}
						break;
						case "radio":
							if (input.checked) {
								lbox_forms_input_activate_classname(input);
							}
						break;
						case "checkbox":
							if (input.checked) {
								lbox_forms_input_activate_classname(input);
							}
						break;
					}
					lbox_forms_switch_slaves_by_master(input);
				});
				control.getElements('select').each(function(input, index) {
					if (input.retrieve('disabledBefore') == undefined) {
						if (input.disabled) {
							input.store('disabledBefore', true);
						}
						else {
							input.store('disabledBefore', false);
						}
					}
					input.addEvent('change', function() {
						lbox_forms_switch_slaves_by_master(this, true);
						lbox_forms_input_activate_classname(this);
					});
					if (isIE()) {
						/* kvuli IEckum je treba nastavit protekani eventu click->focus->change */
						input.addEvent('focus', function() {this.fireEvent('change');return false;});
						input.addEvent('click', function() {this.fireEvent('focus')});
					}
					if (input.value.length > 0) {
						lbox_forms_input_activate_classname(input);
					}
					lbox_forms_switch_slaves_by_master(input);
				});
			}			
		}
	});
});