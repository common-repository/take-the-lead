
//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches
var section = 1;

function takethelead_test_current_section() {
	
	var errors = 0;

	jQuery(".section"+section+" :input.required").map(function(){

		if (takethelead_fields.hasOwnProperty(this.name)) {
			if( !takethelead_fields[this.name].callback(this) ) {
				jQuery(this).parents('p').addClass('warning');
				errors++;
			} else if (jQuery(this).val()) {
				jQuery(this).parents('p').removeClass('warning');
			}
		}
		
	});
	
	if(errors > 0){
		takethelead_set_height(current_fs);
		current_fs.show();
		return true;
	} else {
		takethelead_set_height(current_fs);
        current_fs.show();
		return false;
	}

}

function takethelead_clear_errors() {
	jQuery('.warning').removeClass('warning');
}

function takethelead_set_height(element) {
	
	element.show();

	var w = element.width();
	var h = element.height() + 20;
			
	element.closest('.fieldsets').css('height',h);
	element.hide();
	
}

jQuery(document).ready(function($) {
    
	var globalFieldsets = 0;
	
    $('fieldset').each(function() {
		globalFieldsets++;
		$(this).data('slide_number',globalFieldsets);
		
		if (globalFieldsets == 1) {
			
			takethelead_set_height($(this));
			
			$(this).show();
			
		}

	});
    
    $('.conditional_hidden').hide();
    $('select[name=conditional]').change(function(e) {

		jQuery('fieldset').filter(':visible').attr('style','display: block; height: auto;');
        if (this.selectedIndex != 1) {
            if ($('.conditional_hidden').is(":visible"))
                $('.conditional_hidden').slideToggle('fast',function() {
					takethelead_set_height(current_fs);
					current_fs.show();
					current_fs.css('position','absolute');
				});
        } else {
            if (!$('.conditional_hidden').is(":visible"))
                $('.conditional_hidden').slideToggle('fast',function() { 
					takethelead_set_height(current_fs);
					current_fs.show();
					current_fs.css('position','absolute');
				});
        }
    });

	$('#takethelead input[type=range]').on('input',function() {
		var parent = jQuery(this).closest('p');
		
		parent.find('.rangeoutput').text(this.value);
	}).trigger('input');
    
    $('#takethelead :input').change(function() { 
	
		if (this.tagName.toLowerCase() == 'select' || this.tagName.toLowerCase() == 'checkbox') {
			var fieldset = $(this).closest('fieldset');
			var inputfields = fieldset.find('.inputfield :input');
			
			if ((inputfields.last()[0] == this) && takethelead_auto_complete) {
				takethelead_go_next();
			}
		}
		
    });
	
	$('#takethelead').submit(function() {

		var nextSection = section + 1;
		var pass = takethelead_test_current_section();
		if (!pass) {
			if ($('.section'+nextSection).size()) {
				takethelead_go_next();
			} else {
				// Do Ajax Submission
				
				var data = {};
				var nd = $(this).serializeArray();
				for (i in nd) {
					data[nd[i].name] = nd[i].value;
				}
				
				data['action'] = 'ajax_submit';
				
				/*
					collect all the user submitted data
				*/
				jQuery('fieldset').stop();

				jQuery('fieldset.section'+section).find('.buttons').hide();
				jQuery('fieldset.section'+section).find('.buttons_working').show();
				
				// jQuery('fieldset').find('[type=button],[type=submit]').prop('disabled',true);

				var previousSlide = section;
				jQuery.post(takethelead_ajax_url, data, function(response) {
					
					if (response.success == true) {

						jQuery('.section_success_title').html(response.title);
						jQuery('.section_success_blurb').html(response.message);

						jQuery('#progressbar').hide();
						
						takethelead_set_height(jQuery('fieldset.section_success'));
						
						takethelead_go_slide('_success','ltr');
	
					} else {
						jQuery('fieldset.section'+section).find('.buttons').show();
						jQuery('fieldset.section'+section).find('.buttons_working').hide();
					}
					
				},'json');
				
			}

		}
		
		return false;
		
	});
	
	
	current_fs = jQuery('fieldset').filter(':visible')
	
	console.log('here', current_fs);
});

function takethelead_goto_slide(slide) {
    if (current_fs > slide) var direction = 'rtl';
    else var direction = 'ltr';
    takethelead_go_slide(slide,direction);
}


function takethelead_go_slide(slide,direction) {

	if(animating) return false;
	animating = true;
	
	var fs = jQuery('fieldset').filter(':visible');
	next_fs = jQuery('fieldset.section'+slide);
	
	takethelead_set_height(next_fs);
	
	//show the next fieldset
	
	next_fs.css({'left':((direction == 'ltr')? '-100%':'100%'),'top':0});
	next_fs.show(); 

	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			
			if (direction == 'ltr') {
				var a = (100 * now).toString() + '%';
				var b = '-'+(100 * (1 - now)).toString() + '%';
				current_fs.css({'left':b});
				next_fs.css({'left':a});
			} else {
				var a = '-'+(100 * now).toString() + '%';
				var b = (100 * (1 - now)).toString() + '%';
				current_fs.css({'left':b});
				next_fs.css({'left':a});
			}
			opacity = 1 - now;
			
			next_fs.css({'opacity':opacity});
			
		}, 
		duration: 800, 
		complete: function(){
			
			current_fs.hide();
			current_fs.attr('style','');
			current_fs = next_fs;
			console.log('setting:214', current_fs);
			current_fs.attr('style','');
			current_fs.show();
			
			animating = false;
		}
	});
	
}

function takethelead_go_next() {

	if (animating) return false;
	
	var fs = jQuery('fieldset.section'+section);
	next_fs = fs.next();

	var tested = takethelead_test_current_section();

	if (!tested && !next_fs.is('.section_success')) {
		
		section++;
		//activate next step on progressbar using the index of next_fs
		jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");
		takethelead_go_slide(section, 'ltr');
		
	}

}

jQuery(".next").click(takethelead_go_next);

jQuery(".previous").click(function(){

	if (animating) return false;
	
	current_fs = jQuery(this).closest('fieldset');
	console.log('setting:250',current_fs);
	
	previous_fs = current_fs.prev();

	//de-activate current step on progressbar
	jQuery("#progressbar li").eq(jQuery("fieldset").index(current_fs)).removeClass("active");
	
	section--;
	
	takethelead_clear_errors();
	takethelead_go_slide(section, 'rtl');
	
});