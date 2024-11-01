<?php

function takethelead_get_fields() {
	
	$fields = array(
	
        'yourname'      => array(
			'label'     => __('Your name', 'takethelead'),
			'type'      => 'text', 
			'mask'      => '',
			'placeholder'=> '',
			'js'		=> "function(obj){ return ((obj.value)? true: false); }",
		),
		
		'youremail'     => array(
			'label'     => __('Email address', 'takethelead'),
			'type'      => 'text', 
			'mask'      => '',
			'placeholder'=> '',
			'js'		=> 'function (obj) { return obj.value.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/); }',
		),
        
        'yourphone'       => array(
			'label'     => __('Phone number', 'takethelead'),
			'type'      => 'telephone', 
			'placeholder'=> '07123 456789',
			'mask'      => '00000 000000',
			'js'		=> "function(obj){ return ((obj.value)? true: false); }"
		),
        
		'address'       => array(
			'label'     => __('Address', 'takethelead'),
			'type'      => 'text', 
			'mask'      => '',
			'placeholder'=> '',
			'js'		=> "function(obj){ return ((obj.value)? true: false); }"
		),
		'town'       => array(
			'label'     => __('Town', 'takethelead'),
			'type'      => 'text', 
			'mask'      => '',
			'placeholder'=> '',
			'js'		=> "function(obj){ return ((obj.value)? true: false); }"
		),
		'postcode'       => array(
			'label'     => __('Postcode', 'takethelead'),
			'type'      => 'text', 
			'mask'      => '',
			'placeholder'=> '',
			'js'		=> "function(obj){ return ((obj.value)? true: false); }"
		),
        'message'       => array(
            'label'     => __('Request', 'takethelead'),
            'type'      => 'textarea',
            'mask'      => '',
            'placeholder'=> '',
            'js'		=> "function(obj){ return ((obj.value)? true: false); }"
        ),
		'businessname'   => array(
            'label'     => __('Business name', 'takethelead'),
            'type'      => 'text',
            'mask'      => '',
            'placeholder'=> '',
            'js'		=> "function(obj){ return ((obj.value)? true: false); }",
        ),
		'businesstype'    => array(
			'label'     => __('Business type', 'takethelead'),
			'type'      => 'dropdown',
			'options'   => __('Select...,Sole Proprietorship,Partnership,Limited Partnership,Corporation,Limited Liability Company (LLC),Nonprofit Organization,Cooperative (Co-op)', 'takethelead'),
			'js'		=> "function(obj){ console.log(obj.selectedIndex); return ((obj.selectedIndex != 0)? true: false); }",
		),
		'businesscategory'    => array(
			'label'     => __('Business category', 'takethelead'),
			'type'      => 'dropdown',
			'options'   => __('Select...,Accounting & Tax Services,Arts, Culture & Entertainment,Auto Sales & Service,Banking & Finance,Business Services,Community Organizations,Dentists & Orthodontists,Education,Events & Meetings,Health & Wellness,Health Care,Home Improvement,Insurance,Internet & Web Services,Legal Services,Lodging & Travel,Marketing & Advertising,News & Media,Pet Services,Real Estate,Restaurants & Nightlife,Shopping & Retail,Sports & Recreation,Transportation,Utilities,Wedding', 'takethelead'),
			'js'		=> "function(obj){ console.log(obj.selectedIndex); return ((obj.selectedIndex != 0)? true: false); }",
		),
        
		'loanamount'		=> array(
			'label'		=> __('How much money do you need?', 'takethelead'),
			'type'		=> 'dropdown',
			'options'   => __('Select...,under $5000, under $10000, under $20000, under $50000 ,under $100000, over $100000', 'takethelead'),
			'js'		=> "function(obj){ return ((obj.selectedIndex != 0)? true: false); }"
		),
        
        'loanterm'		=> array(
			'label'		=> __('Loan term', 'takethelead'),
			'type'		=> 'dropdown',
			'options'   => __('Select...,1 year,2 years,5 years,10 years, over 10 years', 'takethelead'),
			'js'		=> "function(obj){ return ((obj.selectedIndex != 0)? true: false); }"
		),
			
		'yourincome'	=> array(
			'label'		=> __('What\'s your monthly revenue?', 'takethelead'),
			'type'		=> 'dropdown',
			'options'   => __('Select...,under $5000, under $10000, under $20000, under $50000 ,under $100000, over $100000', 'takethelead'),
			'js'		=> "function(obj){ return ((obj.selectedIndex != 0)? true: false); }"
		),
		
        'loanstart'       => array(
			'label'     => __('When do you need funding?', 'takethelead'),
			'type'      => 'date', 
			'placeholder'=> 'MM/YYYY',
			'mask'      => '00/0000',
			'js'		=> "function(obj){ return ((obj.value)? true: false); }"
		),
		
		'dateofbirth'       => array(
			'label'     => __('Date of birth', 'takethelead'),
			'type'      => 'date', 
			'placeholder'=> 'DD/MM/YYYY',
			'mask'      => '00/00/0000',
			'js'		=> "function(obj){ return ((obj.value)? true: false); }"
		),
		
		'workphone'       => array(
			'label'     => __('Work phone', 'takethelead'),
			'type'      => 'telephone', 
			'placeholder'=> '01234 456789',
			'mask'      => '00000 000000',
			'js'		=> "function(obj){ return ((obj.value)? true: false); }"
		),
		
		'terms'       => array(
			'label'     => __('I agree to the Terms and Conditions', 'takethelead'),
			'type'      => 'checkbox', 
			'mask'      => '',
			'placeholder'=> '',
			'js'		=> "function(obj){ return ((obj.checked)? true: false); }"
		),
        
		'privacy'       => array(
			'label'     => __('Privacy Agreement', 'takethelead'),
			'type'      => 'checkbox', 
			'mask'      => '',
			'placeholder'=> '',
			'js'		=> "function(obj){ return ((obj.checked)? true: false); }"
		),
        
		'captcha'       => array(
			'label'     => __('Answer the sum', 'takethelead'),
			'type'      => 'captcha', 
			'mask'      => '',
			'placeholder'=> '',
			'js'		=> "function(obj){ if (obj.value == jQuery('input[name=thesum]').val()) { return true; } return false; }"
		),
        
        'slider'       => array(
            'label'     => __('I need £[value]', 'takethelead'),
            'type'      => 'range',
            'min'       => 0,
            'max'       => 10,
            'initial'   => 5,
            'step'      => 1,
            'js'		=> "function(obj){ return ((obj.value)? true: false); }"
        ),
        'comment'       => array(
            'label'     => __('This is just some plain text you can add to the form.', 'takethelead'),
            'type'      => 'plain',
            'js'		=> "function(obj){ return ((obj.value)? true: false); }"
        ),
        'conditional'       => array(
            'label'     => __('Is your business incorporated?', 'takethelead'),
            'type'      => 'conditional',
            'options'   => __('Select...,Yes,No', 'takethelead'),
			'js'		=> "function(obj){ return ((obj.selectedIndex != 0)? true: false); }",
            'question'  => __('Company number', 'takethelead'),
        ),
	);
	
	$x = takethelead_get_stored_application();
	
	$used = array();
	
	foreach ($x as $k => $v) {
		$used[] = $k;
	}
	
	return ['all' => $fields, 'used' => $used];
	
}

function takethelead_get_stored_settings() {
    
    $settings = get_option('takethelead_settings');
	if(!is_array($settings)) $settings = array();
    
	$apps = takethelead_get_stored_application();
	
    $fromemail = get_bloginfo('admin_email');
    $title = get_bloginfo('name');
    
    $default = array(
        'contentposition'       => 'ontheleft',
        'background'            => plugins_url( 'images/blue.jpg', __FILE__ ),
        'heading'               => 'Get Funded. Fast.',
        'headingblurb'          => 'Apply for a loan in less than a minute',
        'homepagecolour'        => '#FFFFFF',
        'headingcolour'         => '#FFFFFF',
        'headingsize'           => '2.4rem',
        'headingblurbcolour'    => '#FFFFFF',
        'headingblurbsize'      => '1.05rem',
        'homepagealign'         => 'homepagecenter',
        'thankyoutitle'         => __('Thank You [yourname]', 'takethelead'),
        'thankyoublurb'         => __('An application for [loanamount] has been received and is being processed. A copy of the application has been sent to [youremail]. You will be informed by email once processing is complete.', 'takethelead'),
        'thankyoutitlecolour'   => '#343848',
        'thankyoutitlesize'     => '1rem',
        'notificationsubject'   => __('New application for [loanamount] from [yourname]', 'takethelead'),
        'confirmationsubject'   => __('Loan Application', 'takethelead'),
        'confirmationmessage'   => __('Thank you for your application [yourname], we will be in contact soon. If you have any questions please reply to this email.', 'takethelead'),
        'registrationdetailsblurb'=> __('Your application details', 'takethelead'),
        'sendto'                => $fromemail,
        'fromname'              => $title,
        'fromemail'             => $fromemail,
        'sort'                  => 'loanamount,businessname,businesstype,conditional,yourname,youremail,yourphone,',
        'autocomplete'          => 'checked',
        'hideprogressbar'       => '',
        'nextbutton'            => 'Next',
        'prevbutton'            => 'Previous',
        'submitbutton'          => 'Submit',
        'buttonlabel'           => '#FFFFFF',
        'primarycolour'         => '#067398',
        'secondarycolour'       => '#CCCCCC',
    );
    
    $settings = array_merge($default, $settings);
	
	// Programatically determine how many sections exist
	
	$max_sections = 0;
	foreach ($apps as $k => $v) {
		$section = (int) $v['section'];
		
		if ($section > $max_sections) $max_sections = $section;
	}
	
	$settings['sections'] = $max_sections;
	
    return $settings;
}

function takethelead_get_stored_application() {
    $application = get_option('takethelead_application');
	
    if (!$application) {
		$application = array(
            
            'loanamount'  => array(
				'label'     => __('How much money do you need?', 'takethelead'),
				'section'   => '1',
				'type'      => 'dropdown',
				'required'  => 'checked',
				'options'   => __('Select...,under $5000, under $10000, under $20000, under $50000 ,under $100000, over $100000', 'takethelead'),
				'js'		=> "function(obj){ console.log(obj.selectedIndex); return ((obj.selectedIndex != 0)? true: false); }",
			),
            
            'businessname'   => array(
				'label'     => __('Business name', 'takethelead'),
				'section'   => '2',
				'type'      => 'text',
				'required'  => '',
				'mask'      => '',
				'placeholder'=> '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }",
			),
            
            'businesstype'    => array(
				'label'     => __('Business type', 'takethelead'),
				'section'   => '2',
				'type'      => 'dropdown',
				'required'  => 'checked',
				'options'   => __('Select...,Sole Proprietorship,Partnership,Limited Partnership,Corporation,Limited Liability Company (LLC),Nonprofit Organization,Cooperative (Co-op)', 'takethelead'),
				'js'		=> "function(obj){ console.log(obj.selectedIndex); return ((obj.selectedIndex != 0)? true: false); }",
			),
            
            'conditional'       => array(
                'label'     => __('Is your business incorporated?', 'takethelead'),
                'section'   => '2',
                'type'      => 'conditional',
                'options'   => __('Select...,Yes,No', 'takethelead'),
                'js'		=> "function(obj){ return ((obj.selectedIndex != 0)? true: false); }",
                'question'  => __('Company number', 'takethelead'),
            ),
            
            'yourname'      => array(
				'label'     => __('Your name', 'takethelead'),
				'section'   => '3',
				'type'      => 'text', 
				'required'  => 'checked',
				'mask'      => '',
				'placeholder'=> '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }",
			),
			'youremail'     => array(
				'label'     => __('Email address', 'takethelead'),
				'section'   => '3',
				'type'      => 'text', 
				'required'  => 'checked',
				'mask'      => '',
				'placeholder'=> '',
				'js'		=> 'function (obj) { return obj.value.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/); }',
			),
			'yourphone'       => array(
				'label'     => __('Phone number', 'takethelead'),
				'section'   => '3',
				'type'      => 'telephone', 
				'required'  => '',
				'placeholder'=> '0123-456789',
				'mask'      => '0000-000000',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
            
            'address'       => array(
				'label'     => __('Address', 'takethelead'),
				'section'   => '0',
				'type'      => 'text', 
				'required'  => '',
				'mask'      => '',
				'placeholder'=> '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
			'town'       => array(
				'label'     => __('Town', 'takethelead'),
				'section'   => '0', 
				'type'      => 'text', 
				'required'  => '',
				'mask'      => '',
				'placeholder'=> '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
			'postcode'       => array(
				'label'     => __('Postcode', 'takethelead'),
				'section'   => '0', 
				'type'      => 'text', 
				'required'  => '',
				'mask'      => '',
				'placeholder'=> '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
            
            'message'       => array(
				'label'     => __('Request', 'takethelead'),
				'section'   => '0', 
				'type'      => 'textarea', 
				'required'  => '',
				'mask'      => '',
				'placeholder'=> '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
            
			'businesscategory'    => array(
				'label'     => __('Business category', 'takethelead'),
				'section'   => '0',
				'required'  => 'checked',
				'options'   => __('Select...,Accounting & Tax Services,Arts, Culture & Entertainment,Auto Sales & Service,Banking & Finance,Business Services,Community Organizations,Dentists & Orthodontists,Education,Events & Meetings,Health & Wellness,Health Care,Home Improvement,Insurance,Internet & Web Services,Legal Services,Lodging & Travel,Marketing & Advertising,News & Media,Pet Services,Real Estate,Restaurants & Nightlife,Shopping & Retail,Sports & Recreation,Transportation,Utilities,Weddings', 'takethelead'),
				'js'		=> "function(obj){ console.log(obj.selectedIndex); return ((obj.selectedIndex != 0)? true: false); }",
			),
			
            'loanterm'		=> array(
                'label'		=> __('Loan term', 'takethelead'),
                'section'   => '0',
                'type'		=> 'dropdown',
                'required'  => '',
                'options'   => __('Select...,1 year,2 years,5 years,10 years, over 10 years', 'takethelead'),
                'js'		=> "function(obj){ return ((obj.selectedIndex != 0)? true: false); }"
            ),
			'yourincome'    => array(
				'label'     => __('What\'s your monthly revenue?', 'takethelead'),
				'section'   => '2',
				'type'      => 'dropdown',
				'required'  => 'checked',
				'options'   => __('Select...,under $5000, under $10000, under $20000, under $50000 ,under $100000, over $100000', 'takethelead'),
				'js'		=> "function(obj){ return ((obj.selectedIndex != 0)? true: false); }",
			),
            'loanstart'       => array(
                'label'     => __('When do you need funding?', 'takethelead'),
                'section'   => '0',
                'type'      => 'date',
                'required'  => '',
                'placeholder'=> 'MM/YYYY',
                'mask'      => '00/0000',
                'js'		=> "function(obj){ return ((obj.value)? true: false); }"
            ),
			'dateofbirth'       => array(
				'label'     => __('Date of birth', 'takethelead'),
				'section'   => '0',
				'type'      => 'date', 
				'required'  => '',
				'placeholder'=> 'DD/MM/YYYY',
				'mask'      => '00/00/0000',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
			'workphone'       => array(
				'label'     => __('Work Phone', 'takethelead'),
				'section'   => '0',
				'type'      => 'telephone', 
				'required'  => '',
				'placeholder'=> '0123-456789',
				'mask'      => '0000-000000',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
			'terms'       => array(
				'label'     => __('I agree to the Terms and Conditions', 'takethelead'),
				'section'   => '0',
				'type'      => 'checkbox', 
				'required'  => '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
			'privacy'       => array(
				'label'     => __('Privacy Agreement', 'takethelead'),
				'section'   => '0',
				'type'      => 'checkbox', 
				'required'  => '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
			'captcha'       => array(
				'label'     => __('Answer the sum', 'takethelead'),
				'section'   => '0',
				'type'      => 'captcha', 
				'required'  => 'checked',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
            'slider'       => array(
				'label'     => __('Move the slider', 'takethelead'),
				'section'   => '0',
				'type'      => 'range', 
				'min'       => 0,
                'max'       => 10,
                'initial'   => 5,
                'step'      => 1,
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
            ),
            'comment'       => array(
				'label'     => __('This is just some plain text you can add to the form.', 'loanfinder'),
				'section'   => '0',
				'type'      => 'plain', 
				'required'  => '',
				'js'		=> "function(obj){ return ((obj.value)? true: false); }"
			),
            
		);
	}
    return $application;
}

function takethelead_get_stored_styles() {
    
    $styles = get_option('takethelead_styles');
	if(!is_array($styles)) $styles = array();
    
    $default = array(
        'fontcolour'        => '#343848',
        'primarycolour'     => '#067398',
        'secondarycolour'   => '#CCCCCC',
    );
    
    $styles = array_merge($default, $styles);
	
    return $styles;
}

function takethelead_splice($a1,$a2) {
	foreach ($a2 as $a2k => $a2v) {
		if (is_array($a2v)) {
			if (!isset($a1[$a2k])) $a1[$a2k] = $a2v;
			else {
				if (is_array($a1[$a2k])) $a1[$a2k] = takethelead_splice($a1[$a2k],$a2v);
			}
		} else {
			if (!isset($a1[$a2k])) $a1[$a2k] = $a2v;
		}
	}
	return $a1;
}