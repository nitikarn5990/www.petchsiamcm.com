<?php
/* Removed old autoload function and migrated to spl_autoload stack
if (!function_exists('__autoload')){
function __autoload($className){
	if (is_file(FS_SIMPL . strtolower($className) . '.php'))
		include_once(FS_SIMPL . strtolower($className) . '.php');
	else if (defined('DIR_CLASSES') && is_file(DIR_CLASSES . strtolower($className) . '.php'))
		include_once(DIR_CLASSES . strtolower($className) . '.php');
}
}
*/

/**
 * Autoload classes (no need to include them one by one)
 *
 * @param $className string
 */
if (!function_exists('simplAutoload')){
function simplAutoload($className){
	if (is_file(FS_SIMPL . strtolower($className) . '.php'))
		include_once(FS_SIMPL . strtolower($className) . '.php');
	else if (defined('DIR_CLASSES') && is_file(DIR_CLASSES . strtolower($className) . '.php'))
		include_once(DIR_CLASSES . strtolower($className) . '.php');
}
}

spl_autoload_register('simplAutoload');

 
/**
 * Display an array of alerts with a div class
 *
 * @param $alerts An Array with the alerts
 * @param $type A string with the type of alert, usually ("error","success")
 * @return NULL
 */
if (!function_exists('Alert')){
	function Alert($alerts, $type=''){
		// Decide what class to display
		$class = ($type == '')?'Error':$type;
		
		//Display all errors to user
		if ( is_array($alerts) && count($alerts) > 0){
			while ( list($key,$data) = each($alerts) ){
				//echo '<div class="form' . ucfirst($class) . '" id="form' . ucfirst($class) . '"><p>' . $data . '</p></div>'. "\n";
				if(ucfirst($class) == "Success"){
					echo '<div class="da-message success" style="margin-bottom:10px">' . $data. '</div>';
				}else{
					echo '<div class="da-message error" style="margin-bottom:10px">' . $data. '</div>';
				}
			}
		}else if ( is_string($alerts) ){
			//echo '<div class="form' . ucfirst($class) . '" id="form' . ucfirst($class) . '"><p>' . $alerts . '</p></div>'. "\n";
			if(ucfirst($class) == "Success"){
				echo '<div class="da-message success" style="margin-bottom:10px">' . $data. '</div>';
			}else{
				echo '<div class="da-message error" style="margin-bottom:10px">' . $data. '</div>';
			}
		}
	}
	
	function AlertFront($alerts, $type=''){
		// Decide what class to display
		$class = ($type == '')?'Error':$type;
		
		//Display all errors to user
		if ( is_array($alerts) && count($alerts) > 0){
			while ( list($key,$data) = each($alerts) ){
				//echo '<div class="form' . ucfirst($class) . '" id="form' . ucfirst($class) . '"><p>' . $data . '</p></div>'. "\n";
				if(ucfirst($class) == "Success"){
					echo '<div class="alert alert-success">' . $data. '</div>';
				}else{
					echo '<div class="alert alert-danger">' . $data. '</div>';
				}
			}
		}else if ( is_string($alerts) ){
			//echo '<div class="form' . ucfirst($class) . '" id="form' . ucfirst($class) . '"><p>' . $alerts . '</p></div>'. "\n";
			if(ucfirst($class) == "Success"){
				echo '<div class="alert alert-success">' . $data. '</div>';
			}else{
				echo '<div class="alert alert-danger">' . $data. '</div>';
			}
		}
	}
}

/**
 * Set a string as an alert
 * 
 * @param $alert A string with the Alert text in it
 * @param $type A string with the type of alert, usually ("error","success")
 * @return bool
 */
if (!function_exists('SetAlert')){
	function SetAlert($alert,$type='error'){
		// Set the Alert into the correct session type
		if (is_array($alert))
			foreach($alert as $value)
				$_SESSION[$type][] = $value;
		else
			$_SESSION[$type][] = $alert;
		
		return true;
	}
}

/**
 * Is there a certain type of alerts waiting
 * 
 * @param $type A string containing the type of alert to return
 * @return array
 */
if (!function_exists('IsAlert')){
	function IsAlert($type){
		// Return if there are strings waiting the the session type array
		return (is_array($_SESSION[$type]) && count($_SESSION[$type]) > 0);
	}
}

/**
 * Get the Alert from the session
 * This will clear the session alerts when done.
 * 
 * @param $type A string containing the type of alert to return
 * @return array
 */
if (!function_exists('GetAlert')){
	function GetAlert($type){
		// Get the array
		$return = $_SESSION[$type];
		// Reset the array
		$_SESSION[$type] = array();
		// Return the array
		return $return;
	}
}

/**
 * Display text or an array in HTML <pre> tags
 *
 * @param $text A mixed set, anything with a predefined format
 * @return null
 */
if (!function_exists('Pre')){
	function Pre($text, $ip=''){
		$ready = true;
		
		if (is_string($ip) && $ip != '' && $_SERVER['REMOTE_ADDR'] != $ip)
			$ready = false;
		else if (is_array($ip) && !in_array($_SERVER['REMOTE_ADDR'], $ip))
			$ready = false;
		
		if ($ready == true){
			echo '<pre>';
			print_r($text);
			echo '</pre>';
		}
	}
}

/**
 * Display text from DB in HTML safe format
 *
 * @param $text String
 * @return string
 */
if (!function_exists('h')){
	function h($text){
		return htmlspecialchars(stripslashes($text));
	}
}

/**
 * Display Debug Information if set
 *
 * @param $output A mixed variable that needs to be outputted with predefined formatting
 * @return NULL
 */
if (!function_exists('Debug')){
	function Debug($output, $class=''){
		if (DEBUG === true){
			$backtrace = debug_backtrace();
			$debug = array();
			$stack = (isset($backtrace[1]['class']) ? "{$backtrace[1]['class']}::" : '') . (isset($backtrace[1]['function']) ? "{$backtrace[1]['function']}" : '');
			
			if ($stack)
				$debug[] = $stack;
				
			$debug[] = "Line {$backtrace[0]['line']} of {$backtrace[0]['file']}";
			
			$debug = implode('<br />', $debug);
			
			print '<pre class="debug' . (($class != '')?' ' . $class:'') . '">' . "{$label}: {$debug}:<br />" . print_r($output, 1) . "\n";
		}
		
		/*
		if (DEBUG === true){
			echo '<pre class="debug' . (($class != '')?' ' . $class:'') . '">DEBUG:' . "\n";
			print_r($output);
			echo '</pre>';
		}
		*/
		
		if (DEBUG_LOG === true){
			 if (!$fp = fopen(FS_CACHE . 'debug.log', "a"))
			 	return;
			 
			 if (fwrite($fp, date("Y-m-d H:i:s") . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . print_r($output, true) . "\n") === FALSE)
			 	return;
			 	
			 fclose($fp);
			 chmod (FS_CACHE . 'debug.log', 0777);
		}
	}
}

/**
 * Displays time difference in readable way
 *
 * @param $data_ref date to compare against in 0000:00:00 00:00:00 format
 * @return string
 */
if (!function_exists('DateTimeDiff')){
function DateTimeDiff($time, $opt = array('parts' => 3)) {
	$time = strtotime($time);
    
    // The default values
    $defOptions = array(
        'to' => 0,
        'parts' => 1,
        'precision' => 'second',
        'distance' => TRUE,
        'separator' => ', '
    );
    $opt = array_merge($defOptions, $opt);
    // Default to current time if no to point is given
    (!$opt['to']) && ($opt['to'] = time());
    // Init an empty string
    $str = '';
    // To or From computation
    $diff = ($opt['to'] > $time) ? $opt['to']-$time : $time-$opt['to'];
    // An array of label => periods of seconds;
    $periods = array(
        'decade' => 315569260,
        'year' => 31556926,
        'month' => 2629744,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1
    );
    // Round to precision
    if ($opt['precision'] != 'second') 
        $diff = round(($diff/$periods[$opt['precision']])) * $periods[$opt['precision']];
    // Report the value is 'less than 1 ' precision period away
    (0 == $diff) && ($str = 'less than 1 '.$opt['precision']);
    // Loop over each period
    foreach ($periods as $label => $value) {
        // Stitch together the time difference string
        (($x=floor($diff/$value))&&$opt['parts']--) && $str.=($str?$opt['separator']:'').($x.' '.$label.($x>1?'s':''));
        // Stop processing if no more parts are going to be reported.
        if ($opt['parts'] == 0 || $label == $opt['precision']) break;
        // Get ready for the next pass
        $diff -= $x*$value;
    }
    $opt['distance'] && $str.=($str&&$opt['to']>$time)?' ago':' away';
    return $str;
}
}

function search_split_terms($terms){
	$terms = preg_replace("/\"(.*?)\"/e", "search_transform_term('\$1')", $terms);
	$terms = preg_split("/\s+|,/", $terms);

	$out = array();
	foreach($terms as $term){
		$term = preg_replace("/\{WHITESPACE-([0-9]+)\}/e", "chr(\$1)", $term);
		$term = preg_replace("/\{COMMA\}/", ",", $term);
		$out[] = $term;
	}

	return $out;
}

function search_transform_term($term){
	$term = preg_replace("/(\s)/e", "'{WHITESPACE-'.ord('\$1').'}'", $term);
	$term = preg_replace("/,/", "{COMMA}", $term);
	return $term;
}

function search_escape_rlike($string){
	return preg_replace("/([.\[\]*^\$])/", '\\\$1', $string);
}

function search_db_escape_terms($terms){
	$out = array();
	foreach($terms as $term){
		$out[] = '[[:<:]]'.AddSlashes(search_escape_rlike($term)).'[[:>:]]';
	}
	return $out;
}

function search_rx_escape_terms($terms){
	$out = array();
	foreach($terms as $term){
		$out[] = '\b'.preg_quote($term, '/').'\b';
	}
	return $out;
}

function search_sort_results($a, $b){
	$ax = $a[score];
	$bx = $b[score];

	if ($ax == $bx){ return 0; }
	return ($ax > $bx) ? -1 : 1;
}

function search_html_escape_terms($terms){
	$out = array();

	foreach($terms as $term){
		if (preg_match("/\s|,/", $term)){
			$out[] = '"'.HtmlSpecialChars($term).'"';
		}else{
			$out[] = HtmlSpecialChars($term);
		}
	}

	return $out;	
}

function search_pretty_terms($terms_html){
	if (count($terms_html) == 1){
		return array_pop($terms_html);
	}

	$last = array_pop($terms_html);
	return implode(', ', $terms_html)." and $last";
}

/**
 * Checks for multiarray (2 or more levels deep)
 * 
 * @param $multiarray Array
 * @return bool
 */
function isMultiArray($multiarray) {
  if (is_array($multiarray))
   foreach ($multiarray as $array)
     if (is_array($array))
       return true;
  return false;
}
?>