<?php
/**
* @class: WS_PluginInstall
* @brief: plugin installation functions
* @requires - WSLogger.php
* @author: Trey Melton ( treymelton@gmail.com )
*/

  class WS_PluginInstall extends WS_Logger{

    public static function Get(){
	  //==== instantiate or retrieve singleton ====
	  static $inst = NULL;
	  if( $inst == NULL )
		$inst = new WS_PluginInstall();
	  return( $inst );
    }

    public function __construct() {
      parent::__construct();
    }// end __construct()

    /**
    * check to see if PC plugin has been installed
    * @return bool
    */
    public function CheckForInstall(){
      return get_option('WSPlugin_Activation');
    }

    /**
    * @brief: activate the plugin
    */
    public static function WS_ActivatePlugin(){
      if(!get_option('WSPlugin_Activation')){
        add_option( 'WSPlugin_Activation', time(),NULL,'yes' );
      }
      if(!get_option('WS_LogFileLocation')){
        $strLocation = WP_PLUGIN_DIR. DIRECTORY_SEPARATOR .WS_PLUGIN_NAME. DIRECTORY_SEPARATOR.'Logs';
        add_option( 'WS_LogFileLocation', $strLocation,NULL,'yes' );
      }
      //mark deactivation so we know we've been here if they change their mind
      if(get_option('WSPlugin_Deactivation')){
          delete_option('WSPlugin_Deactivation');
      }
      return WS_Logger::Get()->WS_LogMessage('Plugin activation complete....',__METHOD__,__LINE__,1);
    }

    /**
    * deactivate the plugin
    */
    function WS_DeActivatePlugin(){
      WS_Logger::Get()->WS_LogMessage('Deactivating Plugin....',__METHOD__,__LINE__,1);
      delete_option('WSPlugin_Activation');
      add_option( 'WSPlugin_Deactivation', time(),NULL,'yes' );
      return TRUE;
    }

    /**
    * deactivate the plugin
    */
    function WS_UninstallPlugin(){
      delete_option('WSPlugin_Deactivation');
      return TRUE;
    }
  }//end class
?>