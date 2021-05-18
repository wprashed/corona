<?php
/**
* Plugin Name: Corona
* Description: Live statistics tracking the number of confirmed, dead, recover & active cases by country or global due to the Corona Virus. 
* Plugin URI: 
* Version: 3.4.0
* Author: Md Rashed Hossain, shails
* Author URI: http://wprashed.com/
* Requires at least: 4.4
* Tested up to: 5.7
* License URL: http://www.gnu.org/licenses/gpl-2.0.txt
* License: GPL-2.0+
* Text Domain: corona
* Domain Path: /languages/
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'corona' ) ) {
	class corona {

		public $continents = array(
			'asia' => 'AFAMAZBHBDBTBNKHCNCXCCIOGEHKINIDIRIQILJPJOKZKWKGLALBMOMYMVMNMMNPKPOMPKPSPHQASASGKRLKSYTWTJTHTRTMAEUZVNYE',
			'africa' => 'DZAOSHBJBWBFBICMCVCFTDKMCGCDDJEGGQERSZETGAGMGHGNGWCIKELSLRLYMGMWMLMRMUYTMAMZNANENGSTRERWSTSNSCSLSOZASSSHSDSZTZTGTNUGCDZMTZZW',
			'oceania' => 'ASAUNZCKTLFMFJPFGUKIMPMHUMNRNCNZNUNFPWPGMPWSSBTKTOTVVUUMWF',
			'europe' => 'ALADATBYBEBABGHRCYCZDKEEFOFIFRDEGIGRHUISIEIMITRSLVLILTLUMKMTMDMCMENLNOPLPTRORUSMRSSKSIESSECHUAGBVARS',
			'americas' => 'AIAGAWBSBBBZBMBQVGCAKYCRCUCWDMDOSVGLGDGPGTHTHNJMMQMXPMMSCWKNNIPAPRBQBQSXKNLCPMVCTTTCUSVIARBOBRCLCOECFKGFGYGYPYPESRUYVE'
		);

		function __construct() {

			// Backend
			define( 'corona_VER', '2.1.2' );
			if ( ! defined( 'corona_URL' ) ) {
				define( 'corona_URL', plugin_dir_url( __FILE__ ) );
			}
			if ( ! defined( 'corona_PATH' ) ) {
				define( 'corona_PATH', plugin_dir_path( __FILE__ ) );
			}
			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );
			add_action( 'admin_menu', array( $this, 'register_custom_menu_page' ) );
			$this->createJob();
			$this->make_sure_data_loaded();

			// Frontend
			add_action( 'init', array( $this, 'register_assets' ) );
			add_shortcode( 'corona', array($this, 'shortcode') );
			add_shortcode( 'corona-table', array($this, 'shortcode_table') );
			add_shortcode( 'corona-map', array($this, 'shortcode_map') );
			add_shortcode( 'corona-line', array($this, 'shortcode_line') );
			add_shortcode( 'corona-chart', array($this, 'shortcode_chart') );
		}

		/**
		 * Register a custom menu page.
		 */
		function register_custom_menu_page(){
			add_menu_page( 
				esc_attr__( 'Corona Live Stats', 'corona' ),
				esc_attr__( 'Corona', 'corona' ),
				'manage_options',
				'corona',
				array($this, 'custom_menu_page'),
				'dashicons-shield-alt',
				81
			); 
		}
		
		/**
		 * Display a custom menu page
		 */
		function custom_menu_page(){
			include_once( corona_PATH .'templates/admin.php');
		}

		function register_assets() {
			$getOptionAll = get_option('corona_all');
			$getOptionCountries = get_option('corona_countries');
			$getOptionHistory = get_option('corona_history');
			wp_register_style( 'corona', corona_URL . 'assets/css/style.css', array(), corona_VER );
			wp_register_script( 'jquery.datatables', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js', array( 'jquery' ), corona_VER, true );
			wp_register_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@2.8.0', array( 'jquery' ), corona_VER, true );
			wp_register_script( 'corona', corona_URL . 'assets/js/frontend.js', array( 'jquery' ), corona_VER, true );
			$translation_array = array(
				'all' => $getOptionAll,
				'countries' => $getOptionCountries,
				'history' => $getOptionHistory
			);
			wp_localize_script( 'corona', 'corona', $translation_array );
		}

		public function admin_enqueue_assets() {
			wp_enqueue_script( 'corona-admin', corona_URL . 'assets/js/admin.js', array( 'jquery' ), corona_VER, true );
		}

		function createJob(){
			add_filter( 'cron_schedules', array( $this, 'add_wp_cron_schedule' ) );
			if ( ! wp_next_scheduled( 'corona_jobs' ) ) {
				$next_timestamp = wp_next_scheduled( 'corona_jobs' );
				if ( $next_timestamp ) {
					wp_unschedule_event( $next_timestamp, 'corona_jobs' );
				}
				wp_schedule_event( time(), 'every_15minute', 'corona_jobs' );
			}
			add_action( 'corona_jobs', array($this,'getDatafromAPI') );
		}

		function add_wp_cron_schedule( $schedules ) {
			$schedules['every_15minute'] = array(
				'interval' => 15*60,
				'display'  => esc_attr__( 'Every 15 minutes', 'corona' ),
			);
			return $schedules;
		}
		
		function getDatafromAPI() {
			$all = $this->getData(false);
			$countries = $this->getData(true);
			$history = $this->getData(false, true);
			$getOptionAll = get_option('corona_all');
			$getOptionCountries = get_option('corona_countries');
			$getOptionHistory = get_option('corona_history');

			if ($getOptionAll) {
				update_option( 'corona_all', $all );
			} else {
				add_option('corona_all', $all);
			}
			if ($getOptionCountries) {
				update_option( 'corona_countries', $countries );
			} else {
				add_option('corona_countries', $countries);
			}
			if ($getOptionHistory) {
				update_option( 'corona_history', $history );
			} else {
				add_option('corona_history', $history);
			}
		}

		function make_sure_data_loaded(){
			$getOptionAll = get_option('corona_all');
			$getOptionCountries = get_option('corona_countries');
			$getOptionHistory = get_option('corona_history');
			if (!$getOptionCountries) {
				$countries = $this->getData(true);
				update_option( 'corona_countries', $countries );
			}
			if (!$getOptionAll) {
				$all = $this->getData(false);
				update_option( 'corona_all', $all );
			}
			if (!$getOptionHistory) {
				$history = $this->getData(false, true);
				update_option( 'corona_history', $history );
			}
		}

		/**
		 * Load text domain
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'corona', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		function getData($countryCode = false, $history = false) {
			$endPoint 	= 'https://corona.lmao.ninja/';
			$methodPath = 'v2/all';

			if ($history) {
				$methodPath = 'v2/historical/all';
			}

			if ($countryCode && !$history) {
				$methodPath = 'v2/countries/?sort=cases';
			} else if ($history && $countryCode) {
				$methodPath = 'v2/historical/'.$countryCode.'?lastdays=all';
			}

			$endPoint = $endPoint.$methodPath;
			
			$json_decode = $this->curl($endPoint);
			
			$current_time = current_time('timestamp');
			if (get_option('corona_last_updated')) {
				update_option( 'corona_last_updated', $current_time);
			} else {
				add_option( 'corona_last_updated', $current_time );
			}

			return $json_decode;
		}


		function curl($endPoint){
			$ch = curl_init();
			$options = array(
				CURLOPT_URL            => $endPoint,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => false,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_USERAGENT 	   => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html',
				CURLOPT_ENCODING       => "utf-8",
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_CONNECTTIMEOUT => 180,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_TIMEOUT        => 180,
				CURLOPT_MAXREDIRS      => 10,
			);
			curl_setopt_array( $ch, $options );
			if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
				curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			}
			$data = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			$finalData = strval($data);

			return json_decode( $finalData );
		}


		function curl_bing($endPoint){
			$ch = curl_init();
			$options = array(
				CURLOPT_URL            => $endPoint,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => false,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_USERAGENT 	   => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html',
				CURLOPT_ENCODING       => "utf-8",
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_CONNECTTIMEOUT => 180,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_TIMEOUT        => 180,
				CURLOPT_MAXREDIRS      => 10,
			);
			curl_setopt_array( $ch, $options );
			if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
				curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			}
			$data = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			$rawHtml = strval($data);

			$htmlData = explode("<script type=\"text/javascript\">", $rawHtml);
			$htmlData = str_replace("var data=", "", $htmlData[2]);
			$htmlData = trim(str_replace(";</script></div></body></html>", "", $htmlData));

			return json_decode($htmlData);
		}

		function getDataProvinceState($countryName = false){
			$endPoint = 'https://bing.com/corona/';
			$data = $this->curl_bing($endPoint);

			if ($data) {
				$new_array = array_filter($data->areas, function($obj) use($countryName) {
					if ($obj->id == $countryName) {
						return true;
					}
					return false;
				});

				if ($new_array) {
					$data = reset($new_array);
				}
				return $data->areas;
			} else {
				return false;
			}
		}

		function shortcode( $atts ){
			$params = shortcode_atts( array(
				'title' => esc_attr__( 'Global', 'corona' ),
				'country' => null,
				'continent' => '',
				'label_confirmed' => esc_attr__( 'Confirmed', 'corona' ),
				'label_deaths' => esc_attr__( 'Deaths', 'corona' ),
				'label_recovered' => esc_attr__( 'Recovered', 'corona' ),
				'label_active' => esc_attr__( 'Active', 'corona' ),
				'label_total' => esc_attr__( 'Total', 'corona' ),
				'label_updated' => esc_attr__( 'Last updated: ', 'corona' ),
				'label_confirmedtoday' => esc_attr__( 'Today cases', 'corona' ),
				'label_deathstoday' => esc_attr__( 'Today deaths', 'corona' ),
				'label_country' => esc_attr__( 'Country and City', 'corona' ),
				'label_critical' => esc_attr__( 'Critical', 'corona' ),
				'label_new' => esc_attr__( '', 'corona' ),
				'style' => 'default',
				'show_detail' => 'yes',
				'dark_mode' => false,
				'lang_url' => "",
				"showing" => 10
			), $atts );

			if ($params['dark_mode'] === 'yes') {
				$params['dark_mode'] = true;
			}

			if ($params['show_detail'] === 'yes') {
				$params['show_detail'] = true;
			}

			$data = get_option('corona_all');

			if ($params['country'] || $params['style'] == 'list' ) {
				$data = get_option('corona_countries');
				if ($params['country'] && $params['style'] !== 'list' ) {
					$new_array = array_filter($data, function($obj) use($params) {
						if ($obj->country === $params['country']) {
							return true;
						}
						return false;
					});
					if ($new_array) {
						$data = reset($new_array);
					}
				}
			}

			if ($params['continent']) {
				$data = get_option('corona_countries');
				$countries = $this->continents[$params['continent']];
				$countries = str_split($countries, 2);
				$new_array = array_filter($data, function($obj) use($countries) {
					if (in_array($obj->countryInfo->iso2, $countries)) {
						return true;
					}
					return false;
				});

				if ($new_array) {
					$data = $new_array;
				}
			}

			ob_start();
			if ($params['style'] == 'list') {
				echo $this->render_list($params, $data);
			} else {
				echo $this->render_item($params, $data);
			}
			
			return ob_get_clean();
		}

		function shortcode_table( $atts ){
			$params = shortcode_atts( array(
				'label_confirmed' => esc_attr__( 'Total Cases', 'corona' ),
				'label_deaths' => esc_attr__( 'Deaths', 'corona' ),
				'label_recovered' => esc_attr__( 'Recovered', 'corona' ),
				'label_country' => esc_attr__( 'Country and City', 'corona' ),
				'label_confirmedtoday' => esc_attr__( 'Today cases', 'corona' ),
				'label_deathstoday' => esc_attr__( 'Today deaths', 'corona' ),
				'label_updated' => esc_attr__( 'Last updated: ', 'corona' ),
				'show_detail' => 'yes',
				'lang_url' => "",
				"searching" => true,
				"showing" => 10,
				'continent' => '',
				'country' => false
			), $atts );
			$data = get_option('corona_countries');
			if ($params['show_detail'] === 'yes') {
				$params['show_detail'] = true;
			} else {
				$params['show_detail'] = false;
			}

			if ($params['continent']) {
				$countries = $this->continents[$params['continent']];
				$countries = str_split($countries, 2);
				$new_array = array_filter($data, function($obj) use($countries) {
					if (in_array($obj->countryInfo->iso2, $countries)) {
						return true;
					}
					return false;
				});

				if ($new_array) {
					$data = $new_array;
				}
			}

			if ($params['country']) {
				$data = $this->getDataProvinceState($params['country']);
			}

			

			ob_start();
			if ($params['country']) {
				echo $this->render_table_bing($params, $data);
			} else {
				echo $this->render_table($params, $data);
			}
			return ob_get_clean();
		}

		function shortcode_map( $atts ){
			$params = shortcode_atts( array(
				'label_confirmed' => esc_attr__( 'Confirmed', 'corona' ),
				'label_deaths' => esc_attr__( 'Deaths', 'corona' ),
				'label_recovered' => esc_attr__( 'Recovered', 'corona' ),
				'label_updated' => esc_attr__( 'Last updated: ', 'corona' ),
				'style' => 'blue'
			), $atts );
			$data = [];

			ob_start();
			echo $this->render_map($params, $data);
			return ob_get_clean();
		}

		function shortcode_line( $atts ){
			$params = shortcode_atts( array(
				'title' => esc_attr__( 'Global', 'corona' ),
				'country' => null,
				'label_confirmed' => esc_attr__( 'Total confirmed: ', 'corona' ),
				'label_deaths' => esc_attr__( 'Deaths: ', 'corona' ),
				'label_recovered' => esc_attr__( 'Recovered: ', 'corona' ),
				'label_active' => esc_attr__( 'Active: ', 'corona' ),
				'label_updated' => esc_attr__( 'Last updated: ', 'corona' ),
				'label_critical' => esc_attr__( 'Critical: ', 'corona' ),
				'label_new' => esc_attr__( ' new today', 'corona' ),
				'style' => 'default'
			), $atts );

			$data = get_option('corona_all');

			if ($params['country']) {
				$data = get_option('corona_countries');
				$new_array = array_filter($data, function($obj) use($params) {
					if ($obj->country === $params['country']) {
						return true;
					}
					return false;
				});
				if ($new_array) {
					$data = reset($new_array);
				}
			}

			ob_start();
			echo $this->render_line($params, $data);
			return ob_get_clean();
		}

		function shortcode_chart( $atts ){
			$params = shortcode_atts( array(
				'title' => esc_attr__( 'Global', 'corona' ),
				'country' => null,
				'label_confirmed' => esc_attr__( 'Cases', 'corona' ),
				'label_deaths' => esc_attr__( 'Deaths', 'corona' ),
				'label_recovered' => esc_attr__( 'Recovered', 'corona' ),
				'label_updated' => esc_attr__( 'Updated on: ', 'corona' ),
				'style' => 'default'
			), $atts );

			$data = get_option('corona_all');

			if ($params['country']) {
				$data = $this->getData($params['country'], true);
			}

			ob_start();
			echo $this->render_chart($params, $data);
			return ob_get_clean();
		}

		function render_chart($params, $data){
			ob_start();
			include( corona_PATH .'templates/corona-chart.php');
			return ob_get_clean();
		}

		function render_line($params, $data){
			ob_start();
			include( corona_PATH .'templates/corona-line.php');
			return ob_get_clean();
		}

		function render_item($params, $data){
			ob_start();
			include( corona_PATH .'templates/corona.php');
			return ob_get_clean();
		}

		function render_list($params, $data){
			ob_start();
			include( corona_PATH .'templates/corona-list.php');
			return ob_get_clean();
		}

		function render_table($params, $data){
			ob_start();
			include( corona_PATH .'templates/corona-table.php');
			return ob_get_clean();
		}

		function render_table_bing($params, $data){
			ob_start();
			include( corona_PATH .'templates/corona-table-bing.php');
			return ob_get_clean();
		}

		function render_map($params, $data){
			ob_start();
			include( corona_PATH .'templates/corona-map.php');
			return ob_get_clean();
		}

	}

	new corona();
}