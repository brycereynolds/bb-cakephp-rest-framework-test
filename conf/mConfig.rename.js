/* Mobile App Configuration */

var app = {
    config: {

        //Specify http rule 
        redirect_https  :true,
        //Mobile app Root (Please don't change the _WEBROOT, it will work as a relative path for all environments)
        _WEBROOT        :'/',	
        //Desktop App Root
        APPS_WEBROOT    :'https://yourserver.bloomboard.com/',
        //Where to log
        BB_TRACKER      :'https://banalytics.bloomboard.com/events/logEvent',
        //Google Analytics (do not define during tests)
        GA_TOKEN_PROD   :'UA-XXXXX' 
    }
};


//These two are global variables
_webroot 		    = 	app.config._WEBROOT;
_staticPath         =   app.config.APPS_WEBROOT;
_tracking           =   {
    url: app.config.BB_TRACKER, 
    session_id: Math.floor(Math.random()*10000000)
    };

