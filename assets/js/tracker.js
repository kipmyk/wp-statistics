let WP_Statistics_CheckTime=6e4,WP_Statistics_Dnd_Active=parseInt(navigator.msDoNotTrack||window.doNotTrack||navigator.doNotTrack,10),hasInitializedOnce=!1,wpStatisticsUserOnline={init:function(){hasInitializedOnce||(hasInitializedOnce=!0,"undefined"==typeof WP_Statistics_Tracker_Object?console.log("Variable WP_Statistics_Tracker_Object not found on the page source. Please ensure that you have excluded the /wp-content/plugins/wp-statistics/assets/js/tracker.js file from your cache and then clear your cache."):(this.checkHitRequestConditions(),this.keepUserOnline()))},checkHitRequestConditions:function(){!WP_Statistics_Tracker_Object.option.cacheCompatibility||WP_Statistics_Tracker_Object.option.dntEnabled&&1===WP_Statistics_Dnd_Active||this.sendHitRequest()},sendHitRequest:async function(){try{var e=encodeURIComponent(document.referrer),t=Date.now(),n=WP_Statistics_Tracker_Object.hitRequestUrl+`&referred=${e}&_=`+t;(await fetch(n,{method:"GET",headers:{"Content-Type":"application/json;charset=UTF-8"}})).ok||console.error("Hit request failed!")}catch(e){console.error("An error occurred on sending hit request:",e)}},sendOnlineUserRequest:function(){var e=new XMLHttpRequest;e.open("GET",WP_Statistics_Tracker_Object.keepOnlineRequestUrl),e.setRequestHeader("Content-Type","application/json;charset=UTF-8"),e.send(null)},keepUserOnline:function(){setInterval(function(){document.hidden||WP_Statistics_Tracker_Object.option.dntEnabled&&1===WP_Statistics_Dnd_Active||this.sendOnlineUserRequest()}.bind(this),WP_Statistics_CheckTime)}};document.addEventListener("DOMContentLoaded",function(){"disabled"!=WP_Statistics_Tracker_Object.option.consentLevel&&!wp_has_consent(WP_Statistics_Tracker_Object.option.consentLevel)||wpStatisticsUserOnline.init(),document.addEventListener("wp_listen_for_consent_change",function(e){var t,n=e.detail;for(t in n)n.hasOwnProperty(t)&&t===WP_Statistics_Tracker_Object.option.consentLevel&&"allow"===n[t]&&wpStatisticsUserOnline.init()})});