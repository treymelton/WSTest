<?php
/**
* @class: WS_PluginCore
* @brief: Central codebehind plugin functionality
* @requires - WSLogger.php
* @author: Trey Melton ( treymelton@gmail.com )
*/

  class WS_PluginCore extends WS_Logger{


    public static function Get(){
	  //==== instantiate or retrieve singleton ====
	  static $inst = NULL;
	  if( $inst == NULL )
		$inst = new WS_PluginCore();
	  return( $inst );
    }

    public function __construct() {
      //Do nothing
    }
    // end __construct()


    /**
    * since we have a bevy of initial hook wordpress loves to use, we need to
    * initiate and execute first run actions now. functions.php CANNOT be trusted.
    */
    public function WS_SpecialHookRegister(){
      //============ Add Actions ===============//
      add_action( 'admin_menu', array(&$this,'WS_MakeAdminMenuOption'),1 );
      add_action( 'admin_enqueue_scripts', array(&$this,'WS_EnqueueScripts'),99 );  //
      add_action('clear_auth_cookie',array(&$this,'WS_LogUserExit'));
      add_action('wp_login',array(&$this,'WS_LogUserEntry'),10,2);    
      //add ajax hooks
      if ( is_admin() ) {
        add_action( 'wp_ajax_WS_AjaxHandler', array('WS_AjaxHandler','WS_AdminAjaxHandler') );
      }
      return TRUE;
    }

    /**
    * @brief: To access the options we'll ad a menu option
    * @return bool
    */
    function WS_MakeAdminMenuOption(){
      //load our Admin menu option
      add_menu_page( 'WSTest', 'WSTest', 'manage_options', 'WSPluginAdmin', array(&$this, 'WS_MakeAdminOptions'), 'dashicons-carrot', 20 );
      return TRUE;
    }

    /**
    * enqueue our styles and scripts
    * @return bool
    */
    function WS_EnqueueScripts(){
      //enque styles
      wp_enqueue_style( 'WS_css', plugin_dir_url( __FILE__ ).'../assets/css/WScss.css');
      wp_enqueue_style( 'bootstrap4.0.0', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',array(),'4.0.0');

      //enque JS
      wp_enqueue_script('popper1.12.9','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js',array('jquery'),'1.12.9');
      wp_enqueue_script('bootstrap4.0.0','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js',array('jquery'),'4.0.0');
      wp_enqueue_script('WS_jscore',plugin_dir_url( __FILE__ ).'../assets/js/WSJSCore.js',array(),'');
      wp_enqueue_script('WS_ajaxcore',plugin_dir_url( __FILE__ ).'../assets/js/WSAjaxCore.js',array(),'');
      return TRUE;
    }

    /**
    * @brief: log a user exiting the site via logout
    * @return bool
    */
    function WS_LogUserExit(){
      $objCurrentUser = wp_get_current_user();
      $strUserAction = 'User Logged OUT'."\r\n";
      $strUserAction .= $this->AssembleLogMetrics($objCurrentUser);
      return WS_Logger::Get()->WS_LogMessage($strUserAction,__METHOD__,__LINE__,2);
    }

    /**
    * @brief: log a user entering the site via login
    * @return bool
    */
    function WS_LogUserEntry($strUsername,$objUser){
      $strUserAction = 'User Logged IN'."\r\n";
      $strUserAction .= $this->AssembleLogMetrics($objUser);
      return WS_Logger::Get()->WS_LogMessage($strUserAction,__METHOD__,__LINE__,2);
    }

    /**
    * @brief: assemble logging metrics
    * @return string
    */
    function AssembleLogMetrics($objCurrentUser){
      $ObjUserMeta = get_userdata($objCurrentUser->ID);
      $strMetrics = 'Username:'.$objCurrentUser->user_login."\r\n";
      $strMetrics .= 'ID:'.$objCurrentUser->ID."\r\n";
      $strMetrics .= 'Roles:'."\r\n";
      if(is_object($ObjUserMeta) && is_array($ObjUserMeta->roles) && sizeof($ObjUserMeta->roles) > 0){
        foreach($ObjUserMeta->roles as $strRole)
          $strMetrics .= $strRole."\r\n";
      }
      else
         $strMetrics .= 'NONE'."\r\n";
      $strMetrics .= 'IP:'.$_SERVER['REMOTE_ADDR']."\r\n";
      return $strMetrics;
    }



    /**
    * @brief make the admin page
    * @return string - options
    */
    public function WS_MakeAdminOptions(){
      $strDiv = '<div class="wrap col-md-12">';
      $strDiv .= '<h1>WS Test Admin Page</h1>';
      $strDiv .= '<hr />';
      $strDiv .= '<div id="WS_updateresults"></div>';
      $strDiv .= '<form action="" method="post" name="ws_update_log_location" id="ws_update_log_location">';
      $strDiv .= '<h3>The log file folder is relative and confined to the root of this plugin, or nested within.</h3>';
      $strDiv .= '  <input type="text" class="form-control col-md-6" placeholder="Log file location" name="location" value="'.get_option('WS_LogFileLocation').'" />';
      $strDiv .= '  <button onclick="WS_MakeUserRequest(\'WS_updateresults\',\'logfilelocation\', this.form);" class="btn btn-success" type="button">Update</button>';
      $strDiv .= '</form>';
      $strDiv .= '</div>';
      echo $strDiv;
    }

  }//end class
?>