<?php

add_action('init', 'takethelead_settings_init');
add_action('admin_menu', 'takethelead_page_init');
add_action('admin_notices', 'takethelead_admin_notice' );
add_action('admin_enqueue_scripts', 'takethelead_scripts_init');

function takethelead_settings_init() {
}

function takethelead_page_init() {
	add_options_page('Take the Lead', 'Take the Lead', 'manage_options', __FILE__, 'takethelead_application');
}

function takethelead_max_section() {
	$application = takethelead_get_stored_application();
	$maxsection = 1;
	foreach ($application as $key) {
		if ($key['section'] >= $maxsection) {
			$maxsection = $key['section'] +1;
		}
	}
	return $maxsection;
}

function takethelead_application (){
    
    $sections=$ontheright=$ontheleft=$homepageleft=$homepagecenter=$homepageright=$termstarget=false;
    
    $application = takethelead_get_stored_application();
    
	$maxsection = takethelead_max_section();

    
    if( isset( $_POST['Submit']) && check_admin_referer("save_takethelead")) {
		$options = array (
            'heading',
            'headingblurb',
            'thankyoutitle',
            'thankyoublurb',
            'thankyoutitlecolour',
            'thankyoutitlesize',
            'notificationsubject',
            'confirmationsubject',
            'confirmationmessage',
            'registrationdetailsblurb',
            'fromname',
            'fromemail',
            'sendto',
            'contentposition',
            'background',
            'homepagecolour',
            'headingcolour',
            'headingsize',
            'headingblurbcolour',
            'headingblurbsize',
            'homepagealign',
            'sort',
            'hideprogressbar',
            'primarycolour',
            'secondarycolour',
			'autocomplete',
            'nextbutton',
            'prevbutton',
            'submitbutton',
            'buttonlabel',
        );

		$messages = array();
		foreach ($options as $item) {
            if ($item == 'background') {
				$messages['background'] = filter_var($_POST['background'],FILTER_SANITIZE_URL);
			} elseif ($item == 'fromemail' || $item == 'sendto') {
				$messages[$item] = sanitize_email($_POST[$item]);
            } else {
				$messages[$item] = sanitize_text_field($_POST[$item]);
			}
        }
        
        update_option('takethelead_settings', $messages);
        
        takethelead_admin_notice(__('The general settings have been updated', 'takethelead'));
    }
    
    if( isset( $_POST['SaveApplication']) && check_admin_referer("save_takethelead")) {
        
        $settings	= takethelead_get_stored_settings();
		$sort		= $_POST['sort'];
		$fields		= takethelead_get_fields();
		
        $newApplication = array();
        
		// Loop through POST Variables
		foreach ($_POST['application'] as $iB => $iV) {
			
			$newApplication[$iB] = ['required' => ''];
			
			foreach ($iV as $field => $fV) {
				$newApplication[$iB][$field] = sanitize_text_field($fV);
			}
			
			$newApplication[$iB]['js'] 	= $fields['all'][$iB]['js'];
			$newApplication[$iB]['type']= $fields['all'][$iB]['type'];
        }
		$settings['sort'] = sanitize_text_field($_POST['sort']);
		update_option('takethelead_settings', $settings);
        update_option('takethelead_application', $newApplication);
		
		$application = $newApplication;
		
        takethelead_admin_notice(__('The form settings have been updated', 'takethelead'));
    }

    // Reset the settings
    if( isset( $_POST['Reset']) && check_admin_referer("save_takethelead")) {
        delete_option('takethelead_settings');
        takethelead_admin_notice(__('The general settings have been reset', 'takethelead'));
    }
    
    // Reset the forms
    if( isset( $_POST['ResetApplication']) && check_admin_referer("save_takethelead")) {
        delete_option('takethelead_application');
        takethelead_admin_notice(__('The form settings have been reset', 'takethelead'));
    }
    
    $arr = $application = takethelead_get_stored_application();
	$settings = takethelead_get_stored_settings();
    $styles = takethelead_get_stored_styles();

    ${$settings['contentposition']} = 'checked';
    
	$autocomplete = '';
	if ($settings['autocomplete'] == 'checked') {
		$autocomplete = 'checked="checked"';
	}
    
    $hideprogressbar = '';
	if ($settings['hideprogressbar'] == 'checked') {
		$hideprogressbar = 'checked="checked"';
	}
    
    ${$settings['homepagealign']} = 'checked="checked"';
    
	$fields = takethelead_get_fields();
	
    
	$dd = json_encode(['dropdown' => takethelead_build_field($settings, '!K!', ['type' => 'dropdown'], true), 'conditional' => takethelead_build_field($settings, '!K!', ['type' => 'conditional'], true),'range' => takethelead_build_field($settings, '!K!', ['type' => 'range'], true),'captcha' => takethelead_build_field($settings, '!K!', ['type' => 'captcha'], true), 'checkbox' => takethelead_build_field($settings, '!K!', ['type' => 'checkbox'], true), 'other' => takethelead_build_field($settings, '!K!', ['type' => 'text'], true), 'fields' => $fields['all']]);
	
    $content ='<script type="text/javascript">
		var takethelead_defaults = '.$dd.';
	</script>
	<div class="wrap"><h1>'.__('Take the Lead Settings', 'takethelead').'</h1>
    <div class="takethelead-settings">
    <form id="" method="post" action="">
    <div class="takethelead-options">
    
    <fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">

    <h2>'.__('General Settings', 'takethelead').'</h2>
    
    <p>'.__('Add the form to your site using widget blocks or the shortcode', 'takethelead').': [takethelead]</p>
    
    <p>'.__('If you want the funky homepage version with background image and taglines the shortcode is', 'takethelead').': [taketheleadhomepage]</p>
    
    <p><a href="https://take-the-lead.co.uk/">'.__('Help and Support', 'takethelead').'</a></p>
    
    </fieldset>';
    
    $content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
    
    <h2>'.__('Form Fields', 'takethelead').'</h2>
    
    <p>'.__('Drag and drop to change the order', 'takethelead').'. '.__('Use the Add Field button to change the fields.', 'takethelead').'. '.__('The trash can removes the field from the list.', 'takethelead').'.</p>
    
	<div id="sorting">
		<ul id="takethelead_sort">';
		 
		$sort = explode(",", $settings['sort']);
		
		foreach ($application as $k => $v) {
			
		}
		
		foreach ($sort as $key) {
			if ($arr[$key]['section'] > 0) {
				$value = $arr[$key];
				$content .= takethelead_build_field($settings, $key, $value, false);
			}
		}

		$content .= '</ul>
	</div>
        
    <input type="hidden" id="takethelead_settings_sort" name="sort" value="'.$settings['sort'].'" />
    <div class="toggle-new-field button-secondary">Add Field +</div>
    <div class="fieldlist" style="display: none;">
    <p>Select a field to add to the form from the list below:</p>
    <ul>';
    
    foreach ($arr as $key) {
        if ($key['section'] == 0) {
            $content .= '<li id="">'.$key['label'].'</li>';
        }
    }       
            
    $content .= '</ul></div>
    
    </fieldset>';
    
    // Submit Changes
    $content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
        
    <p><input type="submit" name="SaveApplication" class="button-primary" style="color: #FFF;" value="'.__('Save Form Settings', 'takethelead').'" /> <input type="submit" name="ResetApplication" class="button-secondary" value="'.__('Reset Form Settings', 'takethelead').'" onclick="return window.confirm( \'Are you sure you want to reset the form?\' );"/></p>
        
    </fieldset>
    
    <fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
    
    <h2>'.__('General Settings', 'takethelead').'</h2>
    <p>'.__('Primary Colour', 'takethelead').' <span class="description">(Labels, button background, progress bar active steps, required fields border, thank you message)</span><p>
    <p><input type="text" class="takethelead-color" label="primarycolour" name="primarycolour" value="' . $settings['primarycolour'] . '" />
    <p>'.__('Secondary Colour', 'takethelead').' <span class="description">(Progress bar inactive steps, normal fields border)</span><p>
    <p><input type="text" class="takethelead-color" label="secondarycolour" name="secondarycolour" value="' . $settings['secondarycolour'] . '" />
    <p><input type="checkbox" name="hideprogressbar" value="checked" '.$hideprogressbar.' /> '.__('Hide the progress bar', 'takethelead').'</p>
    
    </fieldset>
    
    <fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
    
    <p>'.__('Enable Autocomplete', 'takethelead').' <input type="checkbox" name="autocomplete" value="checked" '.$autocomplete.' /> <span class="description">When the last field in a section is completed the form moves to the next section.</span></p>
    
    <h2>'.__('Button Labels', 'takethelead').'</h2>
    
    <p>'.__('Next', 'takethelead').': <input type="text" style="width:6em" name="nextbutton" value="' . $settings['nextbutton'] . '" /> '.__('Previous', 'takethelead').': <input type="text" style="width:6em" name="prevbutton" value="' . $settings['prevbutton'] . '" /> '.__('Submit', 'takethelead').': <input type="text" style="width:6em" name="submitbutton" value="' . $settings['submitbutton'] . '" /></p>
    <p>Colour: <input type="text" class="takethelead-color" label="buttonlabel" name="buttonlabel" value="' . $settings['buttonlabel'] . '" />
    
    </fieldset>

    <fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
    
    <h2>'.__('Thank-you message', 'takethelead').'</h2>
    <p>'.__('Thank-you title', 'takethelead').' (h2)<br>
    <input type="text" name="thankyoutitle" value="' . $settings['thankyoutitle'] . '" /></p>
    <p>Colour: <input type="text" class="takethelead-color" label="thankyoutitlecolour" name="thankyoutitlecolour" value="' . $settings['thankyoutitlecolour'] . '" /></p>
    
    <p>'.__('Thank-you message', 'takethelead').' (uses primary colour)<br>
    <textarea style="width:100%;height:50px;" name="thankyoublurb">' . $settings['thankyoublurb'] . '</textarea></p>
    
    </fieldset>
    
    <fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
    
    <h2>'.__('Notification email', 'takethelead').'</h2>
    
    <p>'.__('Send to', 'takethelead').' (<span class="description">'.__('Defaults to the', 'loanapplication').' <a href="'. get_admin_url().'options-general.php">'.__('Admin Email', 'loanapplication').'</a> '.__('if left blank', 'loanapplication').'</span>):<br><input type="text" name="sendto" value="' . $settings['sendto'] . '" /></p>
    
    <p>'.__('Notification subject', 'takethelead').'<br>
    <input type="text" name="notificationsubject" value="' . $settings['notificationsubject'] . '" /></p>
    
    </fieldset>
    
    <fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
    
    <h2>'.__('Confirmation email', 'takethelead').'</h2>
    
    <p>'.__('From name', 'takethelead').' (<span class="description">'.__('Defaults to your', 'loanapplication').' <a href="'. get_admin_url().'options-general.php">'.__('Site Title', 'loanapplication').'</a> '.__('if left blank', 'loanapplication').'</span>):<br>
    <input type="text" name="fromname" value="' . $settings['fromname'] . '" /></p>
    
    <p>'.__('From email', 'takethelead').' (<span class="description">'.__('Defaults to the', 'loanapplication').' <a href="'. get_admin_url().'options-general.php">'.__('Admin Email', 'loanapplication').'</a> '.__('if left blank', 'loanapplication').'</span>):<br><input type="text" name="fromemail" value="' . $settings['fromemail'] . '" /></p>
    
    <p>'.__('Confirmation subject', 'takethelead').'<br>
    <input type="text" name="confirmationsubject" value="' . $settings['confirmationsubject'] . '" /></p>
    
    <p>'.__('Confirmation message', 'takethelead').'<br>
    <textarea style="width:100%;height:50px;" name="confirmationmessage">' . $settings['confirmationmessage'] . '</textarea></p>
    
    <p>'.__('Application details message', 'takethelead').'<br>
    <input type="text" name="registrationdetailsblurb" value="' . $settings['registrationdetailsblurb'] . '" /></p>

    </fieldset>
    
    <fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">

    <h2>'.__('Homepage Settings', 'takethelead').'</h2>
    
    <p>'.__('Content position', 'takethelead').': <input type="radio" name="contentposition" value="ontheleft" ' . $ontheleft . ' />On the left&nbsp;&nbsp;&nbsp;
    <input type="radio" name="contentposition" value="ontheright" ' . $ontheright . ' />On the right</p>
    
    <p>'.__('Background', 'takethelead').'</p>';
    if ($settings['background']) $content .= '<img src="'.$settings['background'].'">';
    $content .='<p><input type="text" id="takethelead_logo_image" class="background_text" name="background" value="'.$settings['background'].'"><input id="takethelead_upload_logo_image" class="background_button button" type="button" value="Upload Image" /></p>
    
    <p>'.__('Heading', 'takethelead').' (h2)<br>
    <input type="text" name="heading" value="' . $settings['heading'] . '" /></p>
    <p>Colour: <input type="text" class="takethelead-color" label="headingcolour" name="headingcolour" value="' . $settings['headingcolour'] . '" /> Font Size: <input type="text" style="width:5em" name="headingsize" value="' . $settings['headingsize'] . '" /></p>
    
    <p>'.__('Message', 'takethelead').'<br>
    <input type="text" name="headingblurb" value="' . $settings['headingblurb'] . '" /></p>
    <p>Colour: <input type="text" class="takethelead-color" label="headingblurbcolour" name="headingblurbcolour" value="' . $settings['headingblurbcolour'] . '" /> Font Size: <input type="text" style="width:5em" name="headingblurbsize" value="' . $settings['headingblurbsize'] . '" /></p>
    
    <p>'.__('Labels colour', 'takethelead').': <input type="text" class="takethelead-color" label="homepagecolour" name="homepagecolour" value="' . $settings['homepagecolour'] . '" /></p>
    
    <p class="description">'.__('Note: Thank-you message uses homepage heading and label colours', 'takethelead').'.</p>
    
    <p>Alignment: <input type="radio" name="homepagealign" value="homepageleft" ' . $homepageleft . ' /> Left <input type="radio" name="homepagealign" value="homepagecenter" ' . $homepagecenter . ' /> Centre <input type="radio" name="homepagealign" value="homepageright" ' . $homepageright . ' /> Right</p>
    
    </fieldset>';
    
    // Submit Changes
    $content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
        
    <p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="'.__('Save General Settings', 'takethelead').'" /> <input type="submit" name="Reset" class="button-secondary" value="'.__('Reset Settings', 'takethelead').'" onclick="return window.confirm( \'Are you sure you want to reset the settings?\' );"/></p>
        
    </fieldset>';
    
    $content .= wp_nonce_field("save_takethelead");
	
	/*
		Build the elements for the selection list
	*/
	
	$string = '';
	foreach ($fields['all'] as $k => $v) {
		$string .= '<div rel="'.$k.'" class="selection-option"><div class="selection-option-border"><div class="selection-option-title">'.$k.'</div><div class="selection-option-type">'.$v['type'].'</div></div></div>';
	}
	
    $content .= '</form>
	
		<div id="selection-popup">
			<div id="selection-dialog">
				<div id="selection-list">
				'.$string.'
				</div>
				<div id="selection-close">X</div>
			</div>
			<div id="modal"></div>
		</div>
	
	</div>';

	echo $content;		
}

function takethelead_build_field($settings, $k, $v, $default = false) {
	
	$maxsection = takethelead_max_section();
	
	$dd = '';
	if ($default) {
		$k = '!K!';
		$dd= $v['type'];
		$v = ['type' => '!T!', 'label' => '!L!', 'required' => '', 'section' => '', 'options' => '!O!', 'placeholder' => '!P!', 'mask' => '!M!', 'min' => '!MIN!', 'max' => '!MAX!', 'step' => '!STEP!', 'initial' => '!INITIAL!', 'question' => '!Q!'];
	}

    $content = '<li id="'.$k.'" class="ui-state-default"><table>
		<tr>
    <td class="bank_number" style="width:4%"></td>
    <td>
    <table>
    <thead>
        <tr><th>Req</th><th>Sect.</th><th>Type</th><th>Label/Options</th></tr>
        </thead>
    <tr>
    <td width="6%"><input type="checkbox" name="application['.$k.'][required]" ' . $v['required'] . ' value="checked" /></td>
    <td width="4%"><select class="gl_section" name="application['.$k.'][section]">';
	
	if (!$default) {
		for ($i = 1; $i <= $maxsection; $i++) {
			$content .= '<option '.(($v['section'] == $i)? 'selected="selected"':'').'>'.$i.'</option>';
		}
	}
	
	$content .= '</select></td>
	
    <td width="10%"><em>'.$v['type'].'</em></td>
	
    <td width="70%"><input name="application['.$k.'][label]" type="text" value="'.$v['label'].'" /></td>
    </tr>';
    
    if ($v['type'] == 'dropdown' || $dd == 'dropdown') {
        $content .= '<tr>
        <td></td>
        <td></td>
        <td><em>Options</em></td>
        <td><input name="application['.$k.'][options]" type="text" value="'.$v['options'].'" /></td>
        </tr>';
    } elseif ($v['type'] == 'conditional' || $dd == 'conditional') {
        $content .= '<tr>
        <td></td>
        <td></td>
        <td><em>Options</em></td>
        <td><input name="application['.$k.'][options]" type="text" value="'.$v['options'].'" /></td>
        </tr>
        <tr>
        <td></td>
        <td></td>
        <td><em>Question</em></td>
        <td><input name="application['.$k.'][question]" type="text" value="'.$v['question'].'" /></td>
        </tr>';
    } elseif ($v['type'] == 'captcha' || $dd == 'captcha') {
        $content .= '<tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Adds a spam checker to the form</td>
        </tr>';
    } elseif ($v['type'] == 'checkbox' || $dd == 'checkbox') {
        $content .= '<tr>
        <td></td>
        <td></td>
        <td></td>
        <td>To add a link to the caption the format is: <em>Text &lt;a href="link_url"&gt;Anchor&lt;/a&gt; more text.</em></td>
        </tr>';
    } elseif ($v['type'] == 'range' || $dd == 'range') {
        $content .= '<tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Min: <input name="application['.$k.'][min]" type="text" style="width:3em;" value="'.$v['min'].'"> 
        Max: <input name="application['.$k.'][max]" type="text" style="width:3em;" value="'.$v['max'].'"> 
        Initial: <input name="application['.$k.'][initial]" type="text" style="width:3em;" value="'.$v['initial'].'"> 
        Step: <input name="application['.$k.'][step]" type="text" style="width:3em;" value="'.$v['step'].'"></td>
        </tr>';
    } else {
        $content .= '<tr>
        <td></td>
        <td></td>
        <td><em>Placeholder</em></td>
        <td><input name="application['.$k.'][placeholder]" type="text" style="width:40%;" value="'.$v['placeholder'].'" />&nbsp;&nbsp;&nbsp;<em>Input mask</em>&nbsp;&nbsp;<input name="application['.$k.'][mask]" type="text" style="width:40%;" value="'.$v['mask'].'" /></td>
        </tr>';
    }
    
	$content .= '</table>
    </td>
    </tr>
    </table>
    <a class="gl_close" href="javascript:void(0);" onclick="takethelead_close(this)"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
	</li>';
	
    return $content;
}

function takethelead_admin_notice($message) {if (!empty( $message)) echo '<div class="updated"><p>'.$message.'</p></div>';}

function takethelead_scripts_init() {
    wp_enqueue_style('takethelead_settings',plugins_url('settings.css', __FILE__));
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
    wp_enqueue_media();
    wp_enqueue_script('takethelead_media', plugins_url('media.js', __FILE__ ), array( 'wp-color-picker' ));
    wp_add_inline_script( 'takethelead_media', '
		var takethelead_sort;
		jQuery(function() {
			
			takethelead_enable_sort()
			
		})
	');
}