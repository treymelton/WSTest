/**
* @brief: handle all WSPlugin JS/JQuery requests
* @author: Trey Melton ( treymelton@gmailcom )
*/


/**
* @brief: given a search term, request data from the server
* @param: strElement - element to be updated after the request is complete
*/
function WS_MakeUserRequest(strElement,strRequestPurpose, objForm){
  if(typeof(window['WS_AjaxCore']) != "undefined"){
    WS_AjaxCore.arrWSPayLoad['purpose'] = strRequestPurpose;
    WS_AjaxCore.arrWSPayLoad['payload'] = JSON.stringify(jQuery("#"+objForm.name).serialize());
    WS_AjaxCore.strElementUpdateId = strElement;
    var strResponse;
    if(strResponse = WS_AjaxCore.SendAjaxRequest()){
      return WS_ShowRequestResults(strResponse);
    }
    return false;
  }
  return true;
}


/**
* @brief: given return data, display it
* @param - objResponse
* @return void
*/
function WS_ShowRequestResults(objResponse){
  var container = jQuery('#'+WS_AjaxCore.strElementUpdateId);
  container.html('');//reset the HTML
  if(objResponse.hasOwnProperty('resultmessage')){
    var strMessageSlug = '<b>'+objResponse.resultmessage+'</b>';
    container.append(strMessageSlug);
  }
  if(objResponse.hasOwnProperty('messageslug')){
    var strMessageSlug = '<i>'+objResponse.messageslug+'</i>';
    container.append(strMessageSlug);
  }
  return false;
}
