/*
---

name: Fx.Toggle.js

description: Adds toggle behavior for elements, like for dates

license: MIT-style license.

author: Stian Didriksen <stian@nooku.org>

requires: [Class, Fx]

provides: Fx.Toggle

...
*/

Fx.Toggle = new Class({

	Extends: Fx.Slide,

	options: {
		lang: {
			edit: 'Edit',
			ok: 'OK',
			cancel: 'Cancel'
		},
		animate: false,
		updateOnChange: true,
		onInit: Class.empty,
		onClose: function(){
			this.preview.getElement('.toggle-preview').set('html', this.getValue());
		},
		onOK: function(){
			this.preview.getElement('.toggle-preview').set('html', this.getValue());
		},
		onEdit: Class.empty,
	},

	initialize: function(target, options){
	
		this.setOptions(options);
		this.target = target;
		this.wrap	= this.options.wrap || this.target;

		this.fireEvent('onInit');
		
		this.preview = this.createPreview();
		this.element = this.createElement();
		
		
		if(this.options.animate) {
			this.parent(this.element, this.options);
			this.hide();
		}
	},
	
	getValue: function(){
	
		var tag = this.target.get('tag');
		if(tag == 'select') {
			var selected = $$(this.target.options).filter(':selected');
			return selected ? selected[0].get('html') : $(this.target.options[0]).get('html');
		} else {
			return this.target.get('value');
		}
		
	},
	
	createPreview: function(){
		return new Element('div', {'class': 'preview toggle-closed'}).injectBefore(this.wrap).adopt(
			this.createPreviewPane(),
			this.createPreviewButton()
		);
	},
	
	createPreviewPane: function(){
		return new Element('div', {'class': 'toggle-preview'}).set('html', this.getValue());
	},
	
	createPreviewButton: function(){
		return new Element('a', {
			href:'#',
			'class': 'toggle',
			events: {
				click: function(event){
					new Event(event).stop();
					$$(this.element, this.preview).addClass('toggle-open').removeClass('toggle-closed');
					
					if(this.options.animate) {
						this.slideIn();
					}
				}.bindWithEvent(this)
			}
		}).set('html', this.options.lang.edit);
	},
	
	createElement: function(){
		
		this.controls = this.createControls();
		
		if(this.options.updateOnChange) this.target.addEvent('change', function(){
			this.preview.getElement('.toggle-preview').set('html', this.getValue());
		}.bind(this));
		
		return new Element('div', {'class': 'toggle-closed'}).injectBefore(this.wrap).adopt([this.wrap, this.controls]);
		
	},

	createControls: function(){

		return new Element('div', {'class': 'toggle-controls'}).adopt([
			new Element('button', {
				'class': 'toggle-ok button',
				type: 'button',
				events: {
					click: function(event){
						new Event(event).stop();
						$$(this.element, this.element.getPrevious()).removeClass('toggle-open').addClass('toggle-closed');
						this.fireEvent('onOK');
					}.bindWithEvent(this)
				}
			}).set('text', this.options.lang.ok),
			new Element('a', {
				'class': 'toggle-cancel',
				href: '#',
				events: {
					click: function(event){
						new Event(event).stop();
						$$(this.element, this.element.getPrevious()).removeClass('toggle-open').addClass('toggle-closed');
						this.revertChange();
						this.fireEvent('onClose');
					}.bindWithEvent(this)
				}
			}).set('text', this.options.lang.cancel)
		]);

	},
	
	revertChange: function(){
		
		var tag = this.target.get('tag');
		if(tag == 'select') {
			var defaultSelected = $$(this.target.options).filter(function(option){
				return option.defaultSelected;
			});
			this.target.selectedIndex = defaultSelected.length ? defaultSelected[0].index : 0;
		}

	}

});

Fx.Toggle.Date = new Class({

	Extends: Fx.Toggle,

	options: {
		selector: '.datetime',
		lang: {
			months: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'],
			year: 'Year',
			date: 'Date',
			hours: 'Hour',
			minutes: 'Minute'
		},
		updateOnChange: false,
		onInit: function(){
			this.months = $$(this.options.lang.months.map(function(month, i){
				return new Element('option', {value:i}).set('text', month);
			}));

			this.datetime = (new Date).parseDatetimeString(this.target.get('value'));
		},
		onClose: function(){
			var parent = this.element, preview = parent.getPrevious();
			value = this.target;
			output  = preview.getElement('time');
			output.set('datetime', value.defaultValue);
			datetime = (new Date).parseDatetimeString(value.defaultValue);
			output.set('text', datetime.toDatetimeString()).set('title', datetime.toLocaleString());
		},
		onOK: function(){
			var parent = this.element, preview = parent.getPrevious();
			value = this.target;
			output  = preview.getElement('time');
			value.value = output.get('datetime') + ':00';
			datetime = (new Date).parseDatetimeString(output.get('datetime'));
			output.set('text', datetime.toDatetimeString()).set('title', datetime.toLocaleString());
		}
	},

	initialize: function(el, options){

		this.parent(el, options);
		
//		$$('.datetime').each(function(el){

		this.value = value = this.getValue();
					
		datetime = this.datetime;
		preview = this.preview;
		
		time = preview.getElement('time');
		time.$input = el;
		
		var inputs = $$(
			new Element('input', {
				//type: 'number',
				type: 'text',
				maxlength: 4,
				step: 1,
				min: 1970,
				pattern: '[0-9]{4}',
				value: datetime.getUTCFullYear(),
				placeholder: this.options.lang.year,
				//required: true,
				size: 4,
				'class': 'datetime-year'
			}),
			new Element('select', {
				'class': 'datetime-month'
			}).adopt(this.months.clone()),
			new Element('input', {
				//type: 'number',
				type: 'text',
				maxlength: 2,
				step: 1,
				min: 1,
				max: 31,
				pattern: '[0-9]{1,2}',
				size: 2,
				value: datetime.getUTCDate(),
				placeholder: this.options.lang.date
			}),
			/*
			new Element('input', {
				type: 'time',
				maxlength: 10,
				size: 8,
				value: datetime.getUTCHoursMinutes()
			})
			//*/
			new Element('input', {
				//type: 'number',
				type: 'text',
				maxlength: 2,
				step: 1,
				min: 0,
				max: 23,
				pattern: '[0-9]{1,2}',
				size: 2,
				value: datetime.getUTCHours(),
				placeholder: this.options.lang.hours
			}),
			new Element('input', {
				//type: 'number',
				type: 'text',
				maxlength: 2,
				step: 1,
				min: 0,
				max: 59,
				pattern: '[0-9]{1,2}',
				size: 2,
				value: datetime.getUTCMinutes(),
				placeholder: this.options.lang.minutes
			})
		), setters = ['setUTCFullYear', 'setUTCMonth', 'setUTCDate', /*'setUTCHoursMinutes',*/ 'setUTCHours', 'setUTCMinutes'];
		
		inputs.each(function(input, i){
			input.time = time;
			
			setDatetime = function(event, setter){
			
				if(!this.checkValidity()) return;
				
				datetime = (new Date).parseDatetimeString(this.time.get('datetime'));
				datetime[setter](this.value);
				
				//this.time.$input.value = datetime.toDatetimeString() + ':00';
				
				this.time
						.set('text', datetime.toDatetimeString())
						.set('datetime', datetime.toDatetimeString())
						.set('title', datetime.toLocaleString());
				
			}.bindWithEvent(input, setters[i]);
			
			input.addEvent('change', setDatetime);
		}.bind(this));
		
		//inputs.include(this.createControls());

		//this.element.adopt(inputs);
		
		inputs.injectBefore(this.controls);
		
		new Element('span').set('text', '-').injectBefore(inputs[1]).clone().injectAfter(inputs[1]);
		new Element('span').set('text', ':').injectBefore(inputs.getLast());

		//@TODO support Fx animations later on			
//			this.parent(element, options);
		
		el.set('type', 'hidden');
//		this.target.setStyle('');
	
//		}.bind(this));

	},
	
	createElement: function(){
	
		element = this.parent();
		
		
	
		return element;

	},
	
	createPreviewPane: function(){
		return new Element('time', {datetime: this.getValue()}).set('text', this.datetime.toDatetimeString()).set('title', this.datetime.toLocaleString());
	}

});

Element.implement({

	toggle: function(options){
		return new Fx.Toggle(this, options);
	},
	
	toggleDate: function(options){
		return new Fx.Toggle.Date(this, options);
	}

});