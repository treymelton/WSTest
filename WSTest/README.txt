Usage:
1. Copy entire contents to plugin directory.
2. Navigate to plugin section in the backend.
3. Activate "WSTest" plugin.
~Fin


Debugging is available for any specific unit testing required. Debugging usage is simply a call to a singleton.

$this->arrLogType[1] = 'Info';
$this->arrLogType[2] = 'Success';
$this->arrLogType[3] = 'Error';
$this->arrLogType[4] = 'Warning';

WS_Logger::Get()->WS_LogMessage('Message for log goes here',__METHOD__,__LINE__,LogType ( int ));  