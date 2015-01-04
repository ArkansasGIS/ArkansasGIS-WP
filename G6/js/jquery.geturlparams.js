jQuery.fn.extend({
    getUrlParam: function(strParamName){
        strParamName = encodeURI(decodeURI(strParamName));
        var returnVal = new Array();
        this.each(
            function()
            {
                var strHref = $(this).attr("src")|| $(this).attr("href")|| this.URL||"";
                var qString = "";
                if ( strHref.search("#|\\?") > -1 ){
                    var strQueryString = strHref.substr(strHref.search("#|\\?")+1);
                    qString = strQueryString.split("&");
                }
                for (var i=0;i<qString.length; i++){
                    if (encodeURI(decodeURI(qString[i].split("=")[0])) == strParamName){
                        var val = decodeURI( qString[i].split("=")[1].replace(/\+/g,' '));
                        returnVal.push(val);
                    }
                }
            }
        );
        if (returnVal.length==0) {
            return null;
        } else { 
            return returnVal;
        }
    }
});
