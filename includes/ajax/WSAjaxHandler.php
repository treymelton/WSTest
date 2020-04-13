<?php
/**
* @class: WS_AjaxHandler
* @brief: Handle ajax proxy functions
* @requires - WS_Logger.php
* @author: Trey Melton ( treymelton@gmail.com )
*/
  class WS_AjaxHandler extends WS_Logger{

    public $arrRequestResult = array();
    public $objWSPayLoad = FALSE;

    public static function Get(){
	  //==== instantiate or retrieve singleton ====
	  static $inst = NULL;
	  if( $inst == NULL )
		$inst = new WS_AjaxHandler();
	  return( $inst );
    }

    public function __construct() {
    //
    }
    // end __construct()

    /**
    * @brief: given a request from the admin side, determine the purpose and execute
    * @param - NONE
    * @return JsonResult
    */
    public static function WS_AdminAjaxHandler(){
      //load incoming payload
      //no params at this point, but that may change, so we're sanitizing
      WS_AjaxHandler::Get()->objWSPayLoad = WS_AjaxHandler::Get()->SanitizePayload();
      if(!WS_AjaxHandler::Get()->LoadRequestResults()){
        $strMessage = 'Something went wrong. Please try again later ['.__LINE__.']';
        WS_AjaxHandler::Get()->arrRequestResult['messageslug'] = WS_Logger::Get()->WS_AdminMessage($strMessage,3);
      }
      WS_AjaxHandler::Get()->PackReturnPayload();
    }

    /**
    * @brief: sanitize our payload and parse it into an array
    * @param $strPayload
    * @return array || FALSE
    */
    function SanitizePayload(){
      $arrValues = filter_var_array($_POST,FILTER_SANITIZE_STRING);
      $arrPayload = filter_var_array($arrValues['WS_payload'],FILTER_SANITIZE_STRING);
      //convert our form to an array and clean up special charsand atrifacts
      parse_str(str_replace('"','',stripslashes(html_entity_decode($arrPayload['payload']))),$arrPayload['payload']);
      return $arrPayload;
    }

    /**
    * @brief: process our request
    * @return bool
    */
    function LoadRequestResults(){
      //start switch processing
      switch(WS_AjaxHandler::Get()->objWSPayLoad['purpose']){
        case "logfilelocation":
         $strLocation = WP_PLUGIN_DIR. DIRECTORY_SEPARATOR .WS_PLUGIN_NAME. DIRECTORY_SEPARATOR;
         $strLocation .= WS_AjaxHandler::Get()->objWSPayLoad['payload']['location'];
         if('' !== trim($strLocation) && WS_Utility::WS_VerifyFolder($strLocation)){
          if(update_option('WS_LogFileLocation',WS_AjaxHandler::Get()->objWSPayLoad['payload']['location'])){
            $strMessage = 'Log file location updated!';
            WS_AjaxHandler::Get()->arrRequestResult['resultmessage'] = WS_Logger::Get()->WS_AdminMessage($strMessage,1);
            return TRUE;
          }
          else{
            $strMessage = 'Cannot update storage location for logs to '.WS_AjaxHandler::Get()->objWSPayLoad['payload']['location'];
            WS_AjaxHandler::Get()->arrRequestResult['resultmessage'] = WS_Logger::Get()->WS_AdminMessage($strMessage,3);
            return FALSE;
          }
        }
        else{
          $strMessage = 'Log file location is not valid, or is outside its permissable location.<br /> Location is limited to inside the plugin directory. <br /> '.$strLocation.' does not exist. ['.__LINE__.']';
          WS_AjaxHandler::Get()->arrRequestResult['resultmessage'] = WS_Logger::Get()->WS_AdminMessage($strMessage,3);
          return FALSE;
        }
        break;
        default:

        break;
      }
      return FALSE;
    }

    /**
    * @brief package our return into a JSON object for return to the client
    * @param $arrPayload
    * @return string ( JSON )
    */
    function PackReturnPayload(){
      echo json_encode(WS_AjaxHandler::Get()->arrRequestResult);//return our payload
      wp_die();
    }

  }//end class
?>