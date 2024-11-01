var takethelead_sort;

function GLOpenModal() {
	var $ = jQuery;
	
	$('.selection-option').removeClass('selection-used');
	
	jQuery('#takethelead_sort li').each(function() {
		$('.selection-option[rel='+$(this).attr('id')+']').addClass('selection-used');
	});
	
	
	jQuery('#selection-popup').show();
	jQuery('body').css('overflow','hidden');
}

function GLCloseModal() {
	jQuery('#selection-popup').hide();
	jQuery('body').css('overflow','auto');
}

function takethelead_update_sort() {
	var order = takethelead_sort.sortable("toArray").join();
	jQuery("#takethelead_settings_sort").val(order);
}

function takethelead_enable_sort() {
	
	try {
		takethelead_sort.sortable('destroy');
	} catch(E) { }
	
	takethelead_sort = jQuery( "#takethelead_sort" ).sortable({
		cursor: "move",
		items: '> li',
		opacity:0.8,
		tolerance: 'pointer',
        forcePlaceholderSize: true,
		scrollSensitivity: 10,
		scroll:false,
		update:function(e,ui) {
			
			takethelead_update_sort();
			
		},
		sort:function(e,ui) {
			
			var o = jQuery('#takethelead_sort').offset();
			ui.helper.css({'top':e.pageY - o.top, 'left': 0});

			return true;
		}
	});
	
}

function takethelead_adjust_sections() {
	
	var collection = jQuery('.gl_section');
	var sections = [[]];
	
	collection.each(function() {
		/*
			Does it have a value?
		*/
		if (parseInt(this.value) > 0) {
			
			/*
				It does... collect its value
			*/
			if (typeof sections[this.value] == 'undefined') {
				sections[this.value] = [];
			}
			
			sections[this.value].push(this);
			
		} else {
			/*
				It does... collect its value
			*/
			if (typeof sections[0] == 'undefined') {
				sections[0] = [];
			}
			
			sections[0].push(this);
		}
		
	});
	
	/*
		get the highest section currently selected
	*/
	var highest_section = 0;
	for (i in sections) {
		if (i > highest_section) highest_section = parseInt(i);
	}

	/*
		Adjust the sections in question to reflect the highest section + 1
	*/
	var ce = {};
	var ci = 0;
	
	for (i in sections) {
		for (x in sections[i]) {
			ce = jQuery(sections[i][x]);
			
			ce.empty();
			
			for (ci = 1; ci <= (highest_section + 1);  ci++ ) {
				ce.append(jQuery("<option value='"+ci+"' "+((i == ci)? 'selected="selected"':'')+">"+ci+"</option>"));
			}
			
		}
	}
	
}

function takethelead_close(elem) {
	jQuery(elem).closest('li').remove();
	
	takethelead_adjust_sections();

	jQuery('#takethelead_sort').sortable('destroy');
		
	takethelead_enable_sort();
	
	takethelead_update_sort();
}

jQuery(document).ready(function($){
    var custom_uploader, txt;
    $('.background_button').click(function(e) {
		txt = $(this).closest('p').find('.background_text');
		
        e.preventDefault();
        if (custom_uploader) {custom_uploader.open();return;}
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Background Image',button: {text: 'Add Background Image'},multiple: false});
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            txt.val(attachment.url);
        });
        custom_uploader.open();
    });
    
    $('.takethelead-color').wpColorPicker();
    
    $(".fieldlist").hide("slow");
	$(".toggle-new-field").click(function(event){
		
		GLOpenModal();
        event.preventDefault();
        return false;
		
    });
    
	
	$('.selection-option').click(function() {
		/*
			append the selected option to the list and close the modal
		*/
		
		/*
			Get default template for selected field
		*/
		var id			= jQuery(this).attr('rel');
		var defaults	= takethelead_defaults.fields[id];

		var template	= takethelead_defaults[((takethelead_defaults.hasOwnProperty(defaults.type))? defaults.type:'other')]
			.replace(/\!K\!/g, id)
			.replace(/\!T\!/g, defaults['type'])
			.replace(/\!L\!/g, defaults['label'])
			.replace(/\!O\!/g, defaults['options'])
			.replace(/\!P\!/g, defaults['placeholder'])
            .replace(/\!MIN\!/g, defaults['min'])
            .replace(/\!MAX\!/g, defaults['max'])
            .replace(/\!STEP\!/g, defaults['step'])
            .replace(/\!INITIAL\!/g, defaults['initial'])
            .replace(/\!Q\!/g, defaults['question'])
			.replace(/\!M\!/g, defaults['mask']);
        

		var elem		= jQuery(template);
		
		/*
			Add In The Options
		*/
		elem.find('.gl_section').change(function() {
			takethelead_adjust_sections();
		});
		
		jQuery('#takethelead_sort').append(elem);
		
		
		jQuery('#takethelead_sort').sortable('destroy');
		
		takethelead_enable_sort();
		
		takethelead_adjust_sections();

		takethelead_update_sort();
		
		GLCloseModal();
		
	});
	
	
	$('.gl_section').change(function() {
		takethelead_adjust_sections();
	});
	
	$('#selection-popup #modal, #selection-popup #selection-close').click(function() {
		
		GLCloseModal();
		
	});
	
	takethelead_adjust_sections();
});