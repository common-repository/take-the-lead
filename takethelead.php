<?php
/*
Plugin Name: Take the Lead
Plugin URI: http://take-the-lead.com/
Description: Mulitstop lead generation form.
Version: 1.0
Author: aerin
Author URI: http://quick-plugins.com/
Text Domain: takethelead
Domain Path: /languages
*/

require_once( plugin_dir_path( __FILE__ ) . '/options.php' );

add_shortcode('takethelead', 'takethelead_page');
add_shortcode('taketheleadhomepage', 'takethelead_homepage');

add_action( 'wp_enqueue_scripts', 'takethelead_scripts' );
add_action( 'wp_ajax_ajax_submit', 'takethelead_ajax_submit' );
add_action( 'wp_ajax_nopriv_ajax_submit', 'takethelead_ajax_submit' );

if (is_admin()) require_once( plugin_dir_path( __FILE__ ) . '/settings.php' );

function takethelead_do_replace($subject, $array) {

	$keys = array_keys($array);
	
	foreach ($keys as $key) {

		$subject = str_replace('['.$key.']', $array[$key], $subject);
	}
	
	return esc_html($subject);
	
}
function takethelead_ajax_submit() {
    
    if ($_POST['validator']) die();
    if(!takethelead_spawnSecure($_POST['anything'])) die();

	$settings  = takethelead_get_stored_settings();
	$log       = [];
	$return    = ['success' => false,'title' => '', 'message' => ''];
    
    $sort       = explode(",", $settings['sort']);
    
    foreach ($sort as $key) {
        $log[$key] = filter_var($_POST[$key],FILTER_SANITIZE_STRING);
    }
    
    $log[$key] = filter_var($_POST['timestamp'],FILTER_SANITIZE_STRING);
    
    //$yourname = explode(' ', $log['yourname'], 2);
    //$log['yourname'] = $yourname[0];
	
    $log['sentdate'] = date_i18n('d M Y');
    $log['timestamp'] = time();
			
    $content = takethelead_build_complete_message($log);
			
    takethelead_send_notification ($log,$content);
    takethelead_send_confirmation ($log,$content);
    
    $yourname = explode(' ', $log['yourname'], 2);
    $log['yourname'] = $yourname[0];
    $return['success'] = true;
    $return['title']	= takethelead_do_replace($settings['thankyoutitle'],$log);
    $return['message']	=  takethelead_do_replace($settings['thankyoublurb'],$log);
    
	echo json_encode($return);
	
	die(0);
	
}

function takethelead_block_init() {
    
    if ( !function_exists( 'register_block_type' ) ) {
        return;
    }
	
    // Register our block editor script.
	wp_register_script(
		'block',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
	);

	// Register our block, and explicitly define the attributes we accept.
	register_block_type(
        'takethelead/homepage', array(
		'editor_script'   => 'block', // The script name we gave in the wp_register_script() call.
		'render_callback' => 'takethelead_homepage'
        )
	);
    
    register_block_type(
        'takethelead/standalone', array(
		'editor_script'   => 'block', // The script name we gave in the wp_register_script() call.
		'render_callback' => 'takethelead_page'
        )
	);
}

add_action( 'init', 'takethelead_block_init' );

function takethelead_homepage() {
    
    $settings = takethelead_get_stored_settings();
    
    $homepagealign = str_replace('homepage', '', $settings['homepagealign']);
    
    $content = '<style>';
    if ($settings['background']) $content .= '.takethelead_homepage {background: url('.$settings['background'].') no-repeat;background-position: right top;background-size: cover;}';
    $content .= '.takethelead_homepage #takethelead p {color: '.$settings['homepagecolour'].'}
    .takethelead_homepage #takethelead p.warning {color:red;}
    .takethelead_homepage #takethelead p.warning > label { color: red;}
    #takethelead .action-button {background: '.$settings['primarycolour'].';color:'.$settings['buttonlabel'].'}
    #takethelead input[type=text], #takethelead input[type=tel], #takethelead input[type=email], #takethelead select, #takethelead textarea {border-color:'.$settings['secondarycolour'].';}
    #takethelead input[type="checkbox"] + label::before {border-color:'.$settings['secondarycolour'].';}
    #takethelead input[type=range]::-webkit-slider-runnable-track {border-color:'.$settings['secondarycolour'].';}
    #takethelead .required {border: 1px solid '.$settings['primarycolour'].';}
    #progressbar li:before {color:'.$settings['primarycolour'].';background:'.$settings['secondarycolour'].';}
    #progressbar li:after {background:'.$settings['secondarycolour'].';}
    #progressbar li.active:before,  #progressbar li.active:after{background:'.$settings['primarycolour'].';}
    #takethelead input[type=range]::-webkit-slider-thumb {background:'.$settings['primarycolour'].';}
    #takethelead input[type=range]::-moz-range-thumb {background:'.$settings['primarycolour'].';}
    #takethelead input[type=range]::-ms-thumb {background:'.$settings['primarycolour'].';}
    #takethelead input[type="checkbox"].required+ label::before {border-color:'.$settings['primarycolour'].';}
    #takethelead input[type="checkbox"]:checked+label::before {background:'.$settings['primarycolour'].';}
    .takethelead_homepage h2{color: '.$settings['headingcolour'].';font-size: '.$settings['headingsize'].';text-align:'.$homepagealign.';}
    .takethelead_homepage h5{color: '.$settings['headingblurbcolour'].';font-size:'.$settings['headingblurbsize'].';text-align:'.$homepagealign.';}
    h2.section_success_title {color:'.$settings['headingcolour'].';}
    </style>
    <div class="takethelead_homepage">';
    if ($settings['contentposition'] == 'ontheleft') {
        $content .= '<div class="gridcontent"><h2>'.$settings['heading'].'</h2>
        <h5>'.$settings['headingblurb'].'</h5>
        '.takethelead_display_application ().'
        </div>
        <div></div>';
    } else {
        $content .= '<div></div>
        <div class="gridcontent"><h2>'.$settings['heading'].'</h2>
        <h5>'.$settings['headingblurb'].'</h5>
        '.takethelead_display_application ().'
        </div>';
    }
    $content .= '</div>';
    
    return $content;
}

function takethelead_page() {
    
    $settings = takethelead_get_stored_settings();
    
    $content = '<style>
    #takethelead p {color: '.$settings['primarycolour'].'}
    #takethelead p.warning {color:red;}
    #takethelead p.warning > label { color: red;}
    #takethelead .action-button {background: '.$settings['primarycolour'].';color:'.$settings['buttonlabel'].'}
    #takethelead input[type=text], #takethelead input[type=tel], #takethelead input[type=email], #takethelead select, #takethelead textarea {border-color:'.$settings['secondarycolour'].';}
    #takethelead input[type="checkbox"] + label::before {border-color:'.$settings['secondarycolour'].';}
    #takethelead input[type=range]::-webkit-slider-runnable-track {border-color:'.$settings['secondarycolour'].';}
    #takethelead .required {border: 1px solid '.$settings['primarycolour'].';}
    #progressbar li:before {color:'.$settings['primarycolour'].';background:'.$settings['secondarycolour'].';}
    #progressbar li:after {background:'.$settings['secondarycolour'].';}
    #progressbar li.active:before,  #progressbar li.active:after{background:'.$settings['primarycolour'].';}
    #takethelead input[type=range]::-webkit-slider-thumb {background:'.$settings['primarycolour'].';}
    #takethelead input[type=range]::-moz-range-thumb {background:'.$settings['primarycolour'].';}
    #takethelead input[type=range]::-ms-thumb {background:'.$settings['primarycolour'].';}
    #takethelead input[type="checkbox"].required+ label::before {border-color:'.$settings['primarycolour'].';}
    #takethelead input[type="checkbox"]:checked+label::before {background:'.$settings['primarycolour'].';}
    h2.section_success_title {color:'.$settings['thankyoutitlecolour'].';}
    </style>
    <div class="takethelead_page">';
    $content .= takethelead_display_application ();
    $content .= '</div>';
    
    return $content;
}

// Application form
function takethelead_display_application () {

	$ajaxurl = admin_url( 'admin-ajax.php' );
	
    $settings = takethelead_get_stored_settings();
    $application = takethelead_get_stored_application();
    $arr = array_map('array_shift', $application);
    
    $maxsection = 1;
    
	$functions = [];
	foreach ($application as $name => $field) {
        $js = $field['js'] ? $field['js'] : "function(obj){ return false; }";
		
		$functions[] = '"'.(($name == 'captcha')? 'youranswer':$name).'":{"required":'.(($field['required'] == 'checked')? 'true':'false').',"callback":'.$js.'}';
        if ($field['section'] > $maxsection) {
            $maxsection = $field['section'];
        }
	}
	
	$functions = implode(",\n			",$functions);
	$autocomplete = (($settings['autocomplete'] == 'checked')? 1:0);
    $content = '<!-- takethelead form -->
	<script type="text/javascript">
		var takethelead_fields = {'.$functions.'};
		var takethelead_ajax_url = "'.$ajaxurl.'";
		var takethelead_auto_complete = '.$autocomplete.';
	</script>
    <form id="takethelead" form action="'.admin_url('admin-ajax.php').'" method="POST">';
    
    // Progress Bar
    if ($maxsection > 1 && !$settings['hideprogressbar']) {
    
        $content .= '<ul id="progressbar">
        <li class="active" style="width:'.(100/$maxsection).'%"></li>';
        for ($i = 1; $i < ($maxsection); $i++) {
            $content .= '<li style="width:'.(100/$maxsection).'%"></li>';
        }
        $content .= '</ul>';
    }
    
    // Fieldsets
    $content .= '<div class="fieldsets">';
    
    $sort = explode(",", $settings['sort']);
    
    for($i = 1; $i <= $maxsection; $i++) {
        
        $content .= '<fieldset class="section'.$i.'">';
        foreach ($sort as $key) {
			
            if ($application[$key]['section'] == $i) {
				
                $class = '';
                if (isset($application[$key]['class'])) $class = $application[$key]['class'];
                
                if ($application[$key]['type'] == 'text') {
				    $required = ($application[$key]['required'] ? ' class = "required" ' : null );
				    $content .= '<p class="inputfield '.$class.'">'.$application[$key]['label'].'<br>
				    <input id="'.$key.'" name="'.$key.'" type="text" '.$required.' value="" /></p>'."\n";
                }
                
                if ($application[$key]['type'] == 'plain') {
				    $content .= '<p>'.$application[$key]['label'].'</p>'."\n";
                }
                
                if ($application[$key]['type'] == 'textarea') {
				    $required = ($application[$key]['required'] ? ' class = "required" ' : null );
				    $content .= '<p class="inputfield '.$class.'">'.$application[$key]['label'].'<br>
                    <textarea rows="4" label="message" id="'.$key.'" name="'.$key.'" '.$required.'></textarea></p>'."\n";
                }

                if ($application[$key]['type'] == 'dollars') {
				    $required = ($application[$key]['required'] ? ' class = "required" ' : null );
				    $content .= '<p class="inputfield '.$class.'">'.$application[$key]['label'].'<br>
				    <input id="'.$key.'" type="text" '.$required.' name="'.$key.'" value="" placeholder="'.$application[$key]['placeholder'].'" /><script type="text/javascript">jQuery(document).ready(function() {jQuery("input[name=\"'.$key.'\"]").mask("'.$application[$key]['mask'].'");});</script></p>';  
                }
                
                if ($application[$key]['type'] == 'number') {
				    $required = ($application[$key]['required'] ? ' class = "required" ' : null );
				    $content .= '<p class="inputfield '.$class.'">'.$application[$key]['label'].'<br>
				    <input id="'.$key.'" name="'.$key.'" type="number" '.$required.' value="" min="0" max="10"/></p>'."\n";
                }
                    
                if ($application[$key]['type'] == 'date') {
                    $required = ($application[$key]['required'] ? ' class = "required" ' : null );
                    $content .= '<p class="inputfield '.$class.'">'.$application[$key]['label'].'<br>
                    <input id="'.$key.'" type="text" '.$required.' name="'.$key.'" value="" placeholder="'.$application[$key]['placeholder'].'" /><script type="text/javascript">jQuery(document).ready(function() {jQuery("input[name=\"'.$key.'\"]").mask("'.$application[$key]['mask'].'");});</script></p>';    
                }
                    
                if ($application[$key]['type'] == 'telephone') {
                    $required = ($application[$key]['required'] ? ' class = "required" ' : null );
                    $content .= '<p class="inputfield '.$class.'">'.$application[$key]['label'].'<br>
                    <input id="'.$key.'" type="tel" '.$required.' name="'.$key.'" value="" placeholder="'.$application[$key]['placeholder'].'" /><script type="text/javascript">jQuery(document).ready(function() {jQuery("input[name=\"'.$key.'\"]").mask("'.$application[$key]['mask'].'");});</script></p>';    
                }
                
                if ($application[$key]['type'] == 'range') {
                    $label = str_replace('[value]', '<span class="rangeoutput"></span>', $application[$key]['label']);
                    $content .= '<p class="inputfield '.$class.'">'.$label.'<br>
                    <input id="'.$key.'" type="range" name="'.$key.'" min="'.$application[$key]['min'].'" max="'.$application[$key]['max'].'" step="'.$application[$key]['step'].'" value="'.$application[$key]['initial'].'" /></p>';    
                }
                    
                if ($application[$key]['type'] == 'dropdown') {
				    $required = ($application[$key]['required'] ? ' required' : null );
                    $content .= '<p class="inputfield '.$class.'">'.$application[$key]['label'].'<br>
				    <select class="minimal'.$required.'" name="'.$key.'">'."\r\t";
                    $d = explode(",",$application[$key]['options']);
				    foreach ($d as $item) {
					   $content .= '<option value="' .  $item . '">' .  $item . '</option>'."\r\t";
				    }
				    $content .= '</select></p>'."\r\t";
                }
                
                if ($application[$key]['type'] == 'conditional') {
				    $required = ($application[$key]['required'] ? ' required' : null );
                    $content .= '<p class="inputfield '.$class.'">'.$application[$key]['label'].'<br>
				    <select class="minimal'.$required.'" name="'.$key.'" '.$required.'>'."\r\t";
                    $d = explode(",",$application[$key]['options']);
				    foreach ($d as $item) {
					   $content .= '<option value="' .  $item . '">' .  $item . '</option>'."\r\t";
				    }
				    $content .= '</select></p>'."\r\t";
                    $content .= '<p class="inputfield conditional_hidden">'.$application[$key]['question'].'<br>
				    <input id="'.$key.'" name="'.$key.'" type="text" '.$required.' value="" /></p>'."\n";
                }
                    
                if ($application[$key]['type'] == 'checkbox') {
				    $required = ($application[$key]['required'] ? ' class="required"' : null );
                    $content .= '
					<p style="text-align:left;"><input type="checkbox"'.$required.' name="'.$key.'" id="'.$key.'" value="checked"><label for="'.$key.'">'.$application[$key]['label'].'</label></p>';   
                }
                    
                if ($application[$key]['type'] == 'link') {
				    $required = ($application[$key]['required'] ? ' style = "color:' . $settings['primarycolour'] . '" ' : null );
				    if ($errors[$key]) $required = ' style = "color:red;"';
				    $msg = $application[$key]['label'];
				    if ($settings['termstarget']) $target = ' target="blank" ';
				    $msg = str_replace('[a]', '<a href= "'.$application[$key]['termsurl'].'"'.$target.'>', $msg);
				    $msg = str_replace('[/a]', '</a>', $msg);
				    $content .= '<p'.$required.'  class="'.$class.'"><input type="checkbox" name="'.$key.'" value="checked" /> '.$msg.'</p>';
                }
                    
                if ($application[$key]['type'] == 'captcha') {
                    $digit1 = mt_rand(1,10);
                    $digit2 = mt_rand(1,10);
                    if( $digit2 >= $digit1 ) {
                        $thesum = "$digit1 + $digit2";
                        $answer = $digit1 + $digit2;
                    } else {
                        $thesum = "$digit1 - $digit2";
                        $answer = $digit1 - $digit2;
                    }
                    $content .= '<p class="inputfield">Answer the sum: '.$thesum.' = </span><input class="required" id="youranswer" name="youranswer" type="text" style="width:3em;"  value="" /></p>
                        <input type="hidden" name="answer" value="' . strip_tags($thesum) . '" />
                        <input type="hidden" name="thesum" value="' . strip_tags($answer) . '" />';
                }
            }
        }
        
        // Spam
        if ($i == $maxsection ) {
            $content .= '<input type="hidden" name="anything" value="'. date('Y-m-d H:i:s').'">
            <div class="validator">Enter the word YES in the box: <input type="text" style="width:3em" name="validator" value=""></div>';
        }
        
        // Buttons
		$content .= '<div class="buttons">';
        if ($i == 1 && $maxsection > 1) $content .= '<input type="button" name="next" class="next action-button" value="'.$settings['nextbutton'].'" />';
        elseif ($i == $settings['sections'] && $maxsection == 1) $content .= '<input type="submit" name="submit" id="takethelead_submit" class="submit action-button" value="'.$settings['submitbutton'].'" />';
        elseif ($i == $settings['sections']) $content .= '<input type="button" name="previous" class="previous action-button" value="'.$settings['prevbutton'].'" /><input type="submit" name="submit" id="takethelead_submit" class="submit action-button" value="'.$settings['submitbutton'].'" />';
        else $content .= '<input type="button" name="previous" class="previous action-button" value="'.$settings['prevbutton'].'" /><input type="button" name="next" class="next action-button" value="'.$settings['nextbutton'].'" />';
		$content .= '</div>';
		$content .= '<div class="buttons_working">';
		$content .= '	<p class="working_loading"></p>';
		$content .= '</div>';
        $content .= '</fieldset>';
    }
	
    // Thank You
    $content .= '<fieldset class="section_success"><h2 class="section_success_title">Success</h2><p class="section_success_blurb">You successfully submitted the form</p></fieldset>';
	
    // Failure
    $content .= '<fieldset class="section_failure"><h2>Oops</h2><p>The form didn\'t submit</p></fieldset>';
	
    // Loanding
    $content .= '<fieldset class="section_loading"><p class="loading"></p></fieldset>';
	
    $content .= '</div></form>';
    return $content;
}

// Check speed of completion
function takethelead_spawnSecure($var) { 
	$spawn = trim(stripslashes($var)); $now = date('Y-m-d H:i:s'); $diff = strtotime($now) - strtotime($spawn);
	if($diff<=1.5) { return false; } else { return true; }
}

function takethelead_send_notification ($values,$content) {
    
    $settings = takethelead_get_stored_settings();
    if (!$settings['sendto']) $settings['sendto'] = get_bloginfo('admin_email');
    
    $subject = takethelead_do_replace($settings['notificationsubject'],$values);
    
    $headers = "From: ".$values['yourname']." <".$values['youremail'].">\r\n"
    . "Content-Type: text/html; charset=\"utf-8\"\r\n";	
    
    $message = '<html>'.$content.'</html>';
    
    wp_mail($settings['sendto'], $subject, $message, $headers);
}

function takethelead_send_confirmation ($values,$content) {
    
    $settings = takethelead_get_stored_settings();
    
    $subject = $settings['confirmationsubject'] ? $settings['confirmationsubject'] : 'Loan Application';
    
    if (!$settings['fromemail']) $settings['fromemail'] = get_bloginfo('admin_email');
    if (!$settings['fromname']) $settings['fromname'] = get_bloginfo('name');

    $msg = takethelead_do_replace($settings['confirmationmessage'],$values);
    
    $message = '<html>' . $msg . '<h2>'.$settings['registrationdetailsblurb'].'</h2>' . $content.'</html>';
    
    $headers = "From: ".$settings['fromname']." <{$settings['fromemail']}>\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
    
    wp_mail($values['youremail'], $subject, $message, $headers);
}

function takethelead_build_complete_message($values) {
    
    $application = takethelead_get_stored_application();
    $arr = array_map('array_shift', $application);
    
    $content = '<table>';

    foreach ($arr as $key => $value) {
        if ($values[$key]) $content .= '<tr><td><b>'.$application[$key]['label'].'</b></td><td>' . strip_tags(stripslashes($values[$key])) . '</td></tr>';
    }
    
    $content .= '<tr>
    <td><b>Application Date</b></td>
    <td>' . strip_tags(stripslashes($values['sentdate'])) . '</td>
    </tr>
    </table>';

    return $content;
}

function takethelead_display_result($values) {
    
    $messages = takethelead_get_stored_settings();
    
    $messages['thankyoutitle'] = str_replace('[yourname]', $values['yourname'],$messages['thankyoutitle']);
    
    $msg = $messages['thankyoublurb'];
    $msg = str_replace('[yourname]', $values['yourname'],$msg);
    $msg = str_replace('[amountrequired]', $values['amountrequired'], $msg);
    $msg = str_replace('[youremail]', $values['youremail'], $msg);
    
    $content = '<fieldset><h2>'.$messages['thankyoutitle'].'</h2>'.$msg.'</fieldset>';
    
    return $content;
}

// Enqueue Scripts and Styles

function takethelead_scripts() {
    wp_enqueue_style( 'takethelead_style',plugins_url('takethelead.css', __FILE__));
    wp_enqueue_script("jquery-effects-core");
    // wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
    wp_enqueue_script('takethelead_mask_script',plugins_url('jquery.mask.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_enqueue_script('takethelead_script',plugins_url('takethelead.js', __FILE__ ), array( 'jquery' ), false, true );
}