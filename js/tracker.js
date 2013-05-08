
var _mt_scriptVersion = "1.5";


function registerFaceBookEvents() {
    window.setTimeout(
		function () {
		    try {
		        if (FB && FB.Event && FB.Event.subscribe) {
		            FB.Event.subscribe('edge.create', function (resp) { SocialEventHandler('Facebook', 'like'); });
		            FB.Event.subscribe('edge.remove', function (resp) { SocialEventHandler('Facebook', 'unlike') });
		        }
		    }
		    catch (e) {
		        registerFaceBookEvents();
		    }
		}, 1000);
}

function registerTwitterEvents() {
    window.setTimeout(
		function () {
		    try {
		        if (twttr != null) {
		            twttr.events.bind('tweet',      function (event) { if (event) { SocialEventHandler('Twitter', 'Tweet'); } });
		            twttr.events.bind('follow',     function (event) { if (event) { SocialEventHandler('Twitter', 'Follow'); } });
		            twttr.events.bind('favorite',   function (event) { if (event) { SocialEventHandler('Twitter', 'Favorite'); } });
		        }
		    }
		    catch (e) {
		        registerTwitterEvents();
		    }
		}, 1000);
}

function SocialEventHandler(socialName, eventName) {
    var eventAmount = "";

    var str;
    var res;
    str = (document.location.href.indexOf("https") == 0) ? "https" : "http";
    img = str + ":\/\/test-tracker.roitesting.com\/EventTracker.aspx?uid=" + UserID
        + "&etype=" + escape(eventName)
        + "&socialType=" + escape(socialName)
        + "&url=" + escape(document.location.href);
    (new Image()).src = img;
	alert(socialName+' ' + eventName + ' tracked');
}

registerFaceBookEvents();
registerTwitterEvents();

var _mtA = 22695477, _mtC = 1013904223, _mtM = Math.pow(2, 32), _mtfc = 1, _mt2 = 2000000000;
_mtrnd.seed = 1;
_mtrnd.today = new Date();
_mtrnd.sd = _mtrnd.today.getTime();
var mmtpromocode="";
if (typeof (_mt_newHitGuid) == "undefined")
    var _mt_newHitGuid = _mt_newGuid();
if (typeof (_mt_sessionID) == "undefined")
    var _mt_sessionID = _mt_newGuid();
if (typeof (_mt_visitorID) == "undefined")
    var _mt_visitorID = _mt_newGuid();
if (typeof (mmtpromocode) != "undefined" && typeof (_mt_newHitGuid) != "undefined")
    mmtpromocode = _promocode();
function _mtrnd() { _mtrnd.seed = (_mtrnd.seed * _mtA + _mtC) % _mtM; return _mtrnd.seed / (_mtM * 1.0); };
function _mtHS(s) { s = "" + s; var h = 1; for (i = 0; i < s.length; i++) { var x = s.charCodeAt(i); h = (h + x * x * h + x) % _mt2; }; return h; };
function _mtins() { _mtrnd.seed = (_mtHS(document.URL) + _mtHS(document.referrer) + _mtHS(navigator.userAgent)) % _mt2; };
function _mtgetP(N, p) { N = N % 31536000000; N = N.toString(0x10); if (p < N.length) return N.substring(p, p + 1); else return "0"; };
function _mt_newGuid() {
    var g = "";
    for (var i = 0; i < 9; i++) g += _mtgetP(_mtrnd.sd, i) + (i == 7 || i == 11 || i == 15 || i == 19 ? "-" : "");
    if (_mtfc == 1) { _mtins(); _mtfc = 0; };
    for (var i = 9; i < 25; i++) g += Math.round(_mtrnd() * 0xf).toString(0x10) + (i == 7 || i == 11 || i == 15 || i == 19 ? "-" : "");
    for (var i = 25; i < 32; i++) g += Math.round(Math.random() * 0xf).toString(0x10) + (i == 7 || i == 11 || i == 15 || i == 19 ? "-" : "");
    return g;
};

function _promocode() {
    var g = "";
    if (typeof (_mt_newHitGuid) == "undefined") {
        return g;
    }
    else {
        g = _mt_newHitGuid.substring(7, 8) + _mt_newHitGuid.substring(9, 10) + _mt_newHitGuid.substring(27, 29) + _mt_newHitGuid.substring(34, 36);
        return g;
     }
 }

/*	query string parsing */
function _mt_parseParameter(QueryString, Key) {
    var substringQuery;
    var nLength = Key.length + 1;
    if (QueryString.toLowerCase().lastIndexOf(Key + "=") >= 0) {
        substringQuery = QueryString.substring(QueryString.toLowerCase().lastIndexOf(Key + "=") + nLength);
        if (substringQuery.indexOf("&") >= 0)
            return escape(substringQuery.substring(0, substringQuery.toLowerCase().indexOf("&")));
        else
            return escape(substringQuery);
    }
    return "";
}

if (typeof (_mt_isTrack) == "undefined")
    _mt_isTrack = "";
/*	Don't save repeated hit - but save new Event	!!!	*/
var _mt_saveHit = 1;
var _mt_isTrackPart = "";
var _mt_separatorPosition = _mt_isTrack.indexOf(";" + UserID + ";");
if (_mt_separatorPosition >= 0) {
    _mt_saveHit = 0;
    _mt_isTrackPart = _mt_isTrack.substring(_mt_separatorPosition);

    _mt_separatorPosition = _mt_isTrackPart.indexOf(";", 1)
    if (_mt_separatorPosition > 0) {
        _mt_isTrackPart = _mt_isTrackPart.substring(_mt_separatorPosition + 1);
        var _mt_subs = _mt_isTrackPart.split(";");
        if (_mt_subs.length > 0)
            _mt_newHitGuid = _mt_subs[0];
    }
}
else {
    if (_mt_isTrack.length > 0)
        _mt_newHitGuid = _mt_newGuid();

    _mt_isTrack = _mt_isTrack + ";" + UserID + ";" + _mt_newHitGuid;
}

if (UserID.toString().indexOf('hitGuid') <= 0) {
    UserID = "" + UserID + "&hitGuid=" + _mt_newHitGuid + "&version=" + escape(_mt_scriptVersion);
    UserID = UserID + "&sessionID=" + _mt_sessionID + "&visitorID=" + _mt_visitorID;
}

_mt_referrer = escape(document.referrer);
_mt_url = escape(document.location.href);

_mt_SH = screen.height;
_mt_SW = screen.width;
_mt_CD = screen.colorDepth;

_mt_dt = new Date();
_mt_UserDate = escape(_mt_dt.getFullYear() + "-" + (_mt_dt.getMonth() + 1) + "-" + _mt_dt.getDate() + " " + _mt_dt.getHours() + ":" + _mt_dt.getMinutes() + ":" + _mt_dt.getSeconds());
_mt_TimeOffset = escape(_mt_dt.getTimezoneOffset());
_rrnd = Math.random();

_mt_trurl = document.location.protocol + "//[include:TrackerHost]";
if (document.location.port != null && document.location.port.length > 0)
    _mt_trurl = _mt_trurl + ":" + document.location.port;
_mt_trurl = _mt_trurl + "[include:TrackerPath]";
_mt_img = _mt_trurl + "/SiteTracker.aspx?r=" + _rrnd + "&uid=" + UserID + "&ref=" + _mt_referrer + "&SH=" + _mt_SH + "&SW=" + _mt_SW + "&CD=" + _mt_CD + "&url=" + _mt_url + "&udt=" + _mt_UserDate + "&toffset=" + _mt_TimeOffset;

if (typeof (mmtLOB) != "undefined")
    _mt_img = _mt_img + "&xlob=" + mmtLOB;
if (typeof (mmtCountry) != "undefined")
    _mt_img = _mt_img + "&xcntry=" + mmtCountry;

_mt_qs = document.location.href;
if (typeof (_mt_pc) != "undefined") {
    _mt_ppcseid = _mt_pc;
    _mt_ppcsekw = _mt_pk;
    _mt_ppcseprice = "0";
    _mt_img = _mt_img + "&ppcseid=" + _mt_ppcseid + "&ppcsekw=" + _mt_ppcsekw + "&ppcseprice=" + _mt_ppcseprice;
}
/*	PPCSE Parameters */
if (_mt_qs.toLowerCase().indexOf("ppcseid=") > 0) {
    _mt_ppcseid = _mt_parseParameter(_mt_qs, "ppcseid");
    _mt_ppcsekw = _mt_parseParameter(_mt_qs, "ppcsekeyword");
    mmtctg = _mt_parseParameter(_mt_qs, "mmtctg");
    mmtcmp = _mt_parseParameter(_mt_qs, "mmtcmp");
    mmtmt = _mt_parseParameter(_mt_qs, "mmtmt");
    mmtmtname = _mt_parseParameter(_mt_qs, "mmtmtname");
    mmtadid = _mt_parseParameter(_mt_qs, "mmtadid");
    mmtgglcnt = _mt_parseParameter(_mt_qs, "mmtgglcnt");
    mmtplmt = _mt_parseParameter(_mt_qs, "mmtplmt");
    _mt_img = _mt_img + "&ppcseid=" + _mt_ppcseid + "&ppcsekw=" + _mt_ppcsekw + "&mmtctg=" + mmtctg + "&mmtcmp=" + mmtcmp + "&mmtmt=" + mmtmt + "&mmtmtname=" + mmtmtname + "&mmtadid=" + mmtadid + "&mmtgglcnt=" + mmtgglcnt + "&mmtplmt=" + mmtplmt;
}

_mt_img = _mt_img + "&save=" + (_mt_saveHit == 1 ? "true" : "false");

//Before...
//if (typeof (EventName) != "undefined" && typeof (EventAmount) != "undefined" && typeof (EventProfit) != "undefined" && typeof (EventOrderNumber) != "undefined" && typeof (EventProductName) != "undefined" && typeof (EventCurrency) != "undefined" && typeof (EventParametersString) != "undefined") {
//    _mt_img = _mt_img + "&etype=" + escape(EventName) + "&ROICost=" + escape(EventAmount) + "&profit=" + escape(EventProfit) + "&order=" + escape(EventOrderNumber) + "&product=" + escape(EventProductName) + "&cur=" + escape(EventCurrency) + "&params=" + escape(EventParametersString);
//}
//after...
if (typeof (EventName) != "undefined") {
    _mt_img = _mt_img + "&etype=" + escape(EventName);

    if (typeof (EventAmount) != "undefined") {
        _mt_img = _mt_img + "&ROICost=" + escape(EventAmount);
    }
    if (typeof (EventProfit) != "undefined") {
        _mt_img = _mt_img + "&profit=" + escape(EventProfit);
    }
    if (typeof (EventOrderNumber) != "undefined") {
        _mt_img = _mt_img + "&order=" + escape(EventOrderNumber);
    }
    if (typeof (EventProductName) != "undefined") {
        _mt_img = _mt_img + "&product=" + escape(EventProductName);
    }
    if (typeof (EventCurrency) != "undefined") {
        _mt_img = _mt_img + "&cur=" + escape(EventCurrency);
    }
    if (typeof (EventParametersString) != "undefined") {
        _mt_img = _mt_img + "&params=" + escape(EventParametersString);
    }
}



var _mt_image = new Image();
_mt_image.src = _mt_img;