<?php



	/* New Code */
	

if(!class_exists('qrstr')){
	include('phpqrcode.php');
}

if(!class_exists("SingleSettingsAP")){

    class SingleSettingsAP{

        public $name;
        public $type;
        public $label;
        public $value;
        public $choices;

	    /**
	     * SingleSettingsAP constructor.
	     *
	     * @param $name
	     * @param $type
	     * @param $label
	     * @param $value
	     * @param $choices
	     */
	    public function __construct( string $name, string $type, string $label, string $value, Array $choices = array() ) {

		    $this->name    = $name;
		    $this->type    = $type;
		    $this->label   = $label;
		    $this->value   = $value;
		    $this->choices = $choices;
	    }


    }
}

if(!class_exists("ChoiceAp")){

	class ChoiceAp{

		public $name;
		public $value;

		/**
		 * SingleSettingsAP constructor.
		 *
		 * @param $name
		 * @param $value
		 */
		public function __construct( $name, $value ) {
			$this->name  = $name;
			$this->value = $value;
		}

	}
}



if(!class_exists('QR_Code_Settings_AP')){
	class QR_Code_Settings_AP
	{
		private $rest_api_url;
		private $plugin_url;
		private $plugin_dir;
		public $app_slug;
		public $prefix;
		public $css_prefix;
		
	
		function __construct($plugin_dir, $plugin_url, $rest_api_url)
		{
			$this->rest_api_url = $rest_api_url;
			$this->plugin_url = $plugin_url;
			$this->plugin_dir = $plugin_dir;		
			$this->execute_settings_api();
			$this->app_slug = 'wp.alphabetic.pagination';
			$this->prefix = 'ab_ap_';
			$this->css_prefix = str_replace('_', '-', $this->prefix);
			
		}

	
		private function get_login_key_option_name(){
			$login_key_option_name = str_replace(" ", "_", get_bloginfo());
			$login_key_option_name = $login_key_option_name."_login_key";
			return $login_key_option_name;
		}
	
		private function generate_random_login_key(){
	
			$login_key_option_name = $this->get_login_key_option_name();		
			$login_key_array = get_option($login_key_option_name);
			$rand_login_key = md5(rand());
			
	
			if(empty($login_key_array)){
				$rand_key_update_status =	update_option($login_key_option_name, array($rand_login_key));
			}else{
				$login_key_array[] = $rand_login_key;
				$rand_key_update_status =	update_option($login_key_option_name, $login_key_array);
			}
	
			if($rand_key_update_status == true){
				return	$rand_login_key;
			}else{
				return false;
			}
		}	
	
		private function validate_login_key($login_key){
	
			$login_key_option_name = $this->get_login_key_option_name();
			$login_key_array = get_option($login_key_option_name, array());
			// $login_key_array = array("a7749324bf84d8e7ae248562832d24d5");
			$login_key_match_result = array_search($login_key, $login_key_array);
			if($login_key_match_result >= 0){
				return true;
			}else{
				return false;
			}
		}
		
		function qrhash_authentication_settings($param){
	
			
	
			$result_array = array(
	
				"request_status" => "rejected",
	
				"login_key" => "null",
	
				"settings_name" => "null"
	
			);
	
	
	
			if(isset($param['qr_hash'])){				
	
				
	
				$qr_hash_call = $param['qr_hash'];
	
				$qr_hash_option = get_option($this->prefix.'qrcode_hash');				
	
				$epn_qrcode_hash = $qr_hash_option[$this->prefix.'qrcode_hash'];
	
				// $epn_qrcode_hash = "a";
	
				$epn_qrcode_hash_time = $qr_hash_option[$this->prefix.'qrcode_hash_time'];
	
				
	
				if($epn_qrcode_hash == $qr_hash_call){
	
					$rand_login_key = $this->generate_random_login_key();				
	
					if($rand_login_key != false){
						$result_array["request_status"] = "active";
						$result_array["login_key"] = $rand_login_key;
						$result_array["settings_name"] = get_bloginfo();
					}
	
				}			
	
			
	
			}
	
	
	
			$res = new WP_REST_Response($result_array);			
	
			return $res;
	
		}
	
		function register_qrhash_authentication_settings() {		
	
			
	
			register_rest_route( $this->rest_api_url, '/authentication', array(
	
			  'methods' => 'POST',
	
			  'callback' => array($this,'qrhash_authentication_settings'),
	
			));
		}	
	
		function generate_qrcode_ajax() { ?>
	
			<script type="text/javascript" language="javascript">
	
	
	
				jQuery(document).ready(function($) {
	
	
	
					var qrSample = $(".<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-view .qr-sample");
	
					var modal = $(".<?php echo $this->css_prefix; ?>qrcode-body .qr-modal");
	
					var qrcode_img = $('.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-img');
	
					var modal_close = $('.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .qr-modal-close');
	
					var interval = null;
	
	
	
					var data = {
	
						'action': 'generate_qrcode'
	
						
	
					};
	
	
	
					var get_qrcode = function (){
	
	
	
						jQuery.post(ajaxurl, data, function(response, status) {							
	
							
	
							if(status == 'success'){
	
								qrcode_img.html(response);
	
							}
	
	
	
						});
	
					}
	
					
	
					var clear_interval = function (){
	
						clearInterval(interval);
	
						qrcode_img.html('<span class="qr-loading"><?php _e("Loading", "alphabetic-pagination"); ?>...</span>');
	
					}
	
					
	
					qrSample.on("click", function(){
	
						modal.css("display","block");
	
						$(get_qrcode);
	
						interval = setInterval(get_qrcode, 1000*60);
	
					})
	
	
	
					modal.on("click", function(e){
	
						
	
						if(e.target == modal[0]){
	
							modal.css("display", "none");
	
							$(clear_interval);
	
						}
	
							
	
						
	
					})
	
	
	
					modal_close.on("click", function(){
	
						modal.click();
	
					});
	
	
	
					$(document).keyup(function(e) {
	
						
	
						if (e.keyCode === 27){
	
								modal.click();
	
								$(clear_interval);
	
						}
	
					});
	
					
	
					
	
					
	
					
	
				});
	
	
	
			</script> 
	
			<?php
	
		}
	
		function generate_qrcode() {
	
						
	
			$tempDir = $this->plugin_dir."io/";
			if(!file_exists($tempDir)){
				mkdir($tempDir);
			}
			$url = $this->plugin_url."io/";		
				
			/*
			$files = glob($tempDir.'*'); // get all file names		
	
			if(!empty($files)){
	
				foreach($files as $file){ // iterate files
	
					//if(is_file($file))	
					//unlink($file); // delete file
	
				}
	
			}
			*/
			
	
			$epn_qrcode_hash_array = array();
	
			$codeContents = array();
	
			$rand_no = rand();
	
			$rand_no_qr = md5($rand_no);
	
			$codeContents['url'] = get_home_url()."/wp-json/".$this->rest_api_url."/";
			// $codeContents['url'] = "http://192.168.43.248:82/wp-json/".$this->rest_api_url."/";
	
			$codeContents['qr_hash'] = $rand_no_qr;
	
			$epn_qrcode_hash_array[$this->prefix.'qrcode_hash_time'] = time()+30;
	
			$epn_qrcode_hash_array[$this->prefix.'qrcode_hash'] = $codeContents['qr_hash'];
	
			update_option($this->prefix.'qrcode_hash', $epn_qrcode_hash_array);
	
	
	
			$qr_content = json_encode($codeContents);
	
			$fileName = 'sample.png';
	
			$pngAbsoluteFilePath = $tempDir.$fileName;		
	
			QRcode::png($qr_content, $pngAbsoluteFilePath,QR_ECLEVEL_L,10);
			
			echo '<img src="'.$url.$fileName.'?t='.time().'" />';
	
	
	
			wp_die();
	
	
	
		}
	
	
		private function execute_settings_api(){
	
			add_action( 'rest_api_init', array($this, 'register_api_read_settings'));
			add_action( 'rest_api_init', array($this, 'register_api_update_settings'));
			add_action( 'rest_api_init', array($this, 'register_qrhash_authentication_settings'));
			add_action( 'wp_ajax_generate_qrcode', array($this,'generate_qrcode') );
			add_action( 'admin_footer', array($this, 'generate_qrcode_ajax') );
		}
	
		public function ab_io_display($plugin_url){
			
		
	
			?>
			<style type="text/css">
				.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-view {
					float: left;
					width: 172px;
					height: 100px;
					clear: both;
					cursor: pointer;
					position: relative;
				}
				
				.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-view .qr-sample {
					width: auto;
					height: 38px;
					position: absolute;
					right: 0;
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-view .google-badge-img {
					width: auto;
					height: 38px;
					top: 0;
					position:absolute;
	
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal{
					display: none;
					position:fixed;
					z-index: 50000;
					top:0;
					left:0;
					width: 100%;
					height:100%;
					overflow: auto;
					background-color: rgb(0,0,0);
					background-color: rgba(0,0,0,0.6);
				}
	
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .modal-content {
	
					margin: auto;	
					width: 40%;
					text-align: center;
					padding-top: 100px;
	
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .modal-content .qr-loading {
	
					font-size: 2rem;
					color: white;
				} 
	
	
				.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-img img {
					widows: 100%;
					height: auto;
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .qr-modal-close {
					color: tomato;
					float: right;
					font-size: 50px;
					/* font-weight: bold; */
					margin-top: 50px;
					margin-right: 50px;
	
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .qr-modal-close:hover,
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .qr-modal-close:focus {
					color: #000;
					text-decoration: none;
					cursor: pointer;
				}
	
			</style>
			<div class="<?php echo $this->css_prefix; ?>qrcode-body">
	
	
	
				<div class="<?php echo $this->css_prefix; ?>qrcode-view">
	
	
	
					<img class="qr-sample" title="<?php _e("Click here to Scan QR Code", "alphabetic-pagination"); ?>" src="<?php echo $plugin_url.'io/sample.png' ?>">
	
					<div class="google-badge">
	
	
	
						<a target="_blank" href="https://play.google.com/store/apps/details?id=<?php echo $this->app_slug; ?>" title="<?php _e("Click here for Android Application", "alphabetic-pagination"); ?>">
	
							<img class="google-badge-img" alt="<?php _e("Get it on", "alphabetic-pagination"); ?> Google Play" src="<?php echo $plugin_url.'images/'; ?>googplay.png" />
	
						</a>
	
	
	
					</div>
	
				</div>
	
				
	
	
	
				<div class="qr-modal">
	
					<span class="qr-modal-close">&times;</span>
	
					<!-- Modal content -->
	
					<div class="modal-content">
	
						
	
						<div class="<?php echo $this->css_prefix; ?>qrcode-img">
	
							<span class="qr-loading"><?php _e("Loading", "alphabetic-pagination"); ?>...</span>
	
						</div>
	
					</div>
	
	
	
				</div>
	
	
	
			</div>
	
			<?php		
	
		}

		function register_api_read_settings(){

			register_rest_route( $this->rest_api_url, '/read_ap_settings', array(

				'methods' => 'POST',

				'callback' => array($this, 'api_read_settings'),

			));
		}

		function register_api_update_settings() {

			register_rest_route( $this->rest_api_url, '/update_ap_settings', array(

				'methods' => 'POST',

				'callback' => array($this,'api_update_settings'),

			));
		}

		function api_read_settings($param){

			$login_key = $param['login_key'];
			$login_key_status = $this->validate_login_key($login_key);


			$settingsList = array();

            $ap_case_choices = array(
                    new ChoiceAp("Uppercase", "U"),
                    new ChoiceAp("Lowercase", "L"),
            );
			$ap_case = new SingleSettingsAP("ap_case", "radio", "Alphabets in?", "", $ap_case_choices);

            $ap_layout_choices = array(
                    new ChoiceAp("Horizontal", "H"),
                    new ChoiceAp("Vertical", "V"),
            );

			$ap_layout = new SingleSettingsAP("ap_layout", "radio", "Layout?", "", $ap_layout_choices);

			$common_choices = array(
				new ChoiceAp("Yes", "1"),
				new ChoiceAp("No", "0"),
			);

			$ap_numeric_sign = new SingleSettingsAP("ap_numeric_sign", "radio", "Numeric sign \"#\" visibility in pagination?", "", $common_choices);


			$ap_reset_sign = new SingleSettingsAP("ap_reset_sign", "radio", "View All/Refresh icon visibility?", "", $common_choices);


			$ap_disable = new SingleSettingsAP("ap_disable", "radio", "Disable Empty Alphabets?", "", $common_choices);


			$ap_grouping = new SingleSettingsAP("ap_grouping", "radio", "Alphabets Grouping?", "", $common_choices);


			$ap_single_choices = array(
				new ChoiceAp("Show", "1"),
				new ChoiceAp("Hide", "0"),
			);

			$ap_single = new SingleSettingsAP("ap_single", "radio", "Hide/Show pagination if only one post available?", "", $ap_single_choices);

			$ap_dom = new SingleSettingsAP("ap_dom", "text", "DOM Position?", "");


			$settingsList[] = $ap_dom;
            $settingsList[] = $ap_case;
            $settingsList[] = $ap_layout;
            $settingsList[] = $ap_numeric_sign;
            $settingsList[] = $ap_reset_sign;
            $settingsList[] = $ap_disable;
            $settingsList[] = $ap_grouping;
            $settingsList[] = $ap_single;


            if(!empty($settingsList)){
                foreach ($settingsList as $index => $single_settings_AP){
                    $single_settings_AP->value = get_option($single_settings_AP->name, "");
	                $settingsList[$index] = $single_settings_AP;
                }

            }

			$settings_array = array(
                    "qrStatus" => "ok",
			        "singleSettingList" => $settingsList,
            );


			// if($login_key == base64_decode('MTIz')){
			if($login_key_status == true){

				$res = new WP_REST_Response($settings_array);
				return $res;
			}






		}

		function api_update_settings($param){

			$login_key = $param['login_key'];
			$login_key_status = $this->validate_login_key($login_key);
			$update_epn_settings = array(

				'status' => 'not_ok'

			);
			// if($login_key == base64_decode('MTIz')){
			if($login_key_status == true){


				$jsonSettings = $param['jsonSettings'];
				$jsonSettings = json_decode($jsonSettings, true);
				if(!empty($jsonSettings)){

				    foreach ($jsonSettings as $index => $settings){
				        $setting_name =  $settings['name'];
				        $setting_value =  $settings['value'];

				        if($setting_name){
					        update_option($setting_name, $setting_value);
				        }
                    }

					$update_epn_settings['status']  = 'OK';

				}



			}else{

				$update_epn_settings['login_key'] = 'invalid';
			}

			$res = new WP_REST_Response($update_epn_settings);
			return $res;

		}

	
	
	}	


}