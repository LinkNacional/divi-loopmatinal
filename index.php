<?php
/*
Plugin Name: Divi WP Loop Matinal Player
Plugin URI: https://www.linknacional.com.br/
Author URI: https://www.linknacional.com.br/
Author: Link Nacional
Description: Inserir o loop matinal no WordPress por shortcode
Text-Domain: 'link-nacional'
Version: 1.0.0
*/

//Check for direct access
defined( 'ABSPATH' ) or die( 'Please Keep Silence' );

// Constants
define('LKN_DPLLMP_VERSION', '1.0.0');
define('LKN_DPLLMP_OPTIONS_VERSION', '1');
define('LKN_DPLLMP_SUPPORT_FORUM', 'https://www.linknacional.com.br/suporte');
define('LKN_DPLLMP_WP_VERSION', '4.0');
define('LKN_DPLLMP_WC_VERSION', '3.0');


if( !class_exists('LKN_Divi_Wp_Loop_Matinal_Player') ){
    /**
    * @package LKN_Divi_Wp_Loop_Matinal_Player
    * @author Davi Souza
    */
    class LKN_Divi_Wp_Loop_Matinal_Player{
        //$this->plugin_name = $plugin_name;
        //$this->version = $version;
        public function __construct(){
            
            add_shortcode('loopmatinal', array($this, 'lkn_dpllmp_shortcode'));
        }

        /// CRIAR O SHORTCODE DO 
        public function lkn_dpllmp_shortcode($atts = [], $content = null, $tag = ''){

            $a = shortcode_atts( array(
                'title'=>'',
                'hidefds' => '',
                'class'=>'',					
                ), $atts );

            $feed_url = ("https://loopmatinal.libsyn.com/rss");
            
            if($this->isTodayWeekend() && $a['hidefds'] == 'true'){
                
            }else{
                $data = wp_remote_retrieve_body(wp_remote_get($feed_url, array( 'timeout' => 30 ) ));
                $dom = new DOMDocument;
                $dom->loadXML($data);
                if (!$dom) {
                  echo 'Error while parsing the document';
                  exit;
                }
                $xml = simplexml_import_dom($dom);
                $xml_data = $xml->channel->item;
                
                //foreach($xml_data as $key => $val) {
                 //   echo $val->title;
                //}
                //echo $xml->channel->item[0]->title;
                
                if($a['title'] == 'false'){
                    $title =  "";
                }else{
                    $title =  "<h3>".$content."</h3>";
                }
                //foreach($x->channel->item as $entry) {//}
                   $player = "<audio controls><source src='".$xml->channel->item[0]->link."' type='audio/mpeg'>Seu navegador n達o permiti a tag audio.</audio>";
                   
                if($a['title'] == 'false'){
                    $title =  "";
                }else{
                    $player .=  "<h4>EP:".$xml->channel->item[0]->title."</h4>";
                }
                
    
                return "<div class='".$a['class']."'>".$title.$player."</div>";
            };
        }

        public function isTodayWeekend() {
            return in_array(date("l"), ["Saturday", "Sunday"]);
        }


    }
    $matinalInit = new LKN_Divi_Wp_Loop_Matinal_Player;
}