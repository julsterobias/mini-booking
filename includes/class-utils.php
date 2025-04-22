<?php 
/**
 * 
 * 
 * cuato_utils
 * @since 1.0.0
 * 
 * 
 */

namespace minibookcalc\includes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class minibookcalc_utils 
{

	public int $default_km_rate 	= 10;

	public int $default_no_rooms 	= 5;

	public int $default_base_fee	= 100;

	public int $default_room_rate	= 50;

	public string $default_currency = '$';

	public string $nonce_key		= 'minibooking';

    /**
	*
	*
	* get_view
	* render content
	* @param $file , $data
	* @return void
	*
	*
	* @since 1.0.0
	*/
	public function get_view($file, $data = [])
	{
	    if (!$file)
				return;

		$other = null;

		if (strpos($file,'.php') === false)
			$file = $file.'.php';

		if (isset($data['path'])) {
			$other = $data['path'].'/';
		}

		$plugin_folder = explode('/',MINIBOOKCAL_PLUGIN_URL);
		$plugin_folder = array_filter($plugin_folder);

		$path = get_stylesheet_directory().'/'.end($plugin_folder).'/'.$other.'views';

		if (file_exists($path.'/'.$file)) {
			include $path.'/'.$file;
		}else{
			include MINIBOOKCAL_PLUGIN_PATH.$other.'/views/'.$file;
		}
	}


}
?>