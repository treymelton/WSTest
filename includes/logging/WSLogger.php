<?php
/**
* @class:WS_Logger
* @brief: Log internal messages.
* @requires - WSUtility.php
* @author: Trey Melton ( treymelton@gmail.com )
*/

  require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'WSUtility.php');
  class WS_Logger extends WS_Utility{

    public $arrLogType = array();//hold the log types for display puposes
    public $strLogFile = '';


    public static function Get(){
	  //==== instantiate or retrieve singleton ====
	  static $inst = NULL;
	  if( $inst == NULL )
		$inst = new WS_Logger();
	  return( $inst );
    }

    public function __construct() {
      //load our log types array
      $this->arrLogType[1] = 'Info';
      $this->arrLogType[2] = 'Success';
      $this->arrLogType[3] = 'Error';
      $this->arrLogType[4] = 'Warning';
    }// end __construct()


    /**
    * given a user message deliver it to the admin console
    * @param $strMessage
    * @param $intType - type of message
    */
    public static function WS_AdminMessage($strMessage,$intType=1){
      if(trim($strMessage) != ''){
        return '<div class="notice notice-'. strtolower(WS_Logger::Get()->arrLogType[$intType]) .' is-dismissible"><p>'. $strMessage.'</p></div>';
      }
      return TRUE;
    }

    /**
     * @brief:Given an error string  attempt to open the debug log and
     * append the error string with a timestamp.
     *
     * @access public
     * @param- $strError - Error Message
     * @return bool
     */
    public function WS_LogMessage($strError, $strMethod, $intLine, $intType=1){
      if($this->WS_VerifyLogFile()){
        //write to our log file
        $strLogEntry = "\r\n----------------------[".date('r')."]:----------------------\r\n ".
                     "[Script]: " .$_SERVER['SCRIPT_NAME'].
                     "[Method]: " .  $strMethod . "\r\n".
                     "[Line]: " .  $intLine . "\r\n".
                     "[".$this->arrLogType[$intType]."]:".$strError . "\r\n";
        $strLogEntry .= $this->WS_GetLastError(). "\r\n";  
        $strLogEntry .= "\r\n###################[End Log Entry]###################\r\n ";
        return $this->WS_WriteData($this->strLogFile,$strLogEntry);
      }
      return FALSE;
    } //WS_LogMessage

    /**
    * @brief: verify our log folder exists and set the file location and name
    * @return bool
    */
    function WS_VerifyLogFile(){
      //our log folder location
      $this->strLogFile = WP_PLUGIN_DIR. DIRECTORY_SEPARATOR .WS_PLUGIN_NAME. DIRECTORY_SEPARATOR;
      if(!($strLogFileFolder = get_option('WS_LogFileLocation')) || '' === trim($this->strLogFile))
        $this->strLogFile .= 'Logs'.DIRECTORY_SEPARATOR;
      else
        $this->strLogFile .=$strLogFileFolder.DIRECTORY_SEPARATOR;
      //verify it's valid
      if(!WS_Utility::WS_VerifyFolder($this->strLogFile, TRUE)){                                            
        //can't log the message and the message may be sensitive so it cannot be printed on the screen
        return FALSE;
      }
     $this->strLogFile .= 'LOG_'.date('Y_m_d',time()).'.txt';
     return TRUE;
    }//WS_VerifyLogFile()

    /**
    * @brief: Determine if an error occured during the execution
    * @return string
    */
    function WS_GetLastError(){
      $arrLastError = error_get_last();
      $strLastError = '';
      if(is_array($arrLastError) && sizeof($arrLastError) > 0){
        return "[LastError]: ".var_export($arrLastError,TRUE). " \r\n";
      }
      return '';
    }//WS_GetLastError()

  }
?>