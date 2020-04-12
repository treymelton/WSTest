/**
* @brief: handle all WSPlugin Ajax requests
* @author: Trey Melton ( treymelton@gmailcom )
*/

/**
* @brief handle ajax calls from the front end
* @param strSearchValue
* @return bool
*/
var WS_AjaxCore = {
    strElementUpdateId:'',
    arrWSPayLoad:{"purpose":"",
                  "payload":""},
    /**
    *   make an ajax call
    *   @param arrWSPayLoad - Simple package of variables to search for
    *       -string curl formed string
    *       -form data direct POST/GET
    *   @return bool
    */
    SendAjaxRequest:function(){
      //can't make empty requests
      if( this.arrWSPayLoad.length < 1 ){
        console.log( 'Ajax request failed.');
        return false;
      }
      //send our content
    	var objResult = jQuery.ajax({
    		type: "post",
            url: "/wp-admin/admin-ajax.php",
            data: { action: 'WS_AjaxHandler', WS_payload: this.arrWSPayLoad},
            async: false
    	}); //close jQuery.ajax(
      return jQuery.parseJSON(objResult.responseText);
    }
};