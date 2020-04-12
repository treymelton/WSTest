<?php
/*/
 * @link              https://progressivecoding.net
 * @since             1.1.1
 * @package           WSTest
 * @wordpress-plugin
 * Plugin Name:       WSTest
 * Plugin URI:        https://progressivecoding.net
 * Description:       A simple Ajax handler and plugin manager
 * License:           GPL-2.0+
 * Version:           1.1.1
 * Author:            Trey Melton
 * Author URI:        https://progressivecoding.net
 * Text Domain:       progressivecoding
 * Domain Path:       /WSTest
 /*/
  // If this file is called directly, abort.
  if ( ! defined( 'WPINC' ) || ! defined( 'ABSPATH' ) )
  	die;
  define('WS_PLUGIN_NAME','WSTest');                                           
  //include our required libraries
  require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'WSRequires.php');
  register_activation_hook( __FILE__ , array('WS_PluginInstall','WS_ActivatePlugin'));
  register_deactivation_hook( __FILE__, array('WS_PluginInstall','WS_DeActivatePlugin'));
  register_uninstall_hook( __FILE__, array('WS_PluginInstall','WS_UninstallPlugin'));
  //if the plugin is activated register short codes
  if(WS_PluginInstall::Get()->CheckForInstall()){
    WS_PluginCore::Get()->WS_SpecialHookRegister();
  }
?>