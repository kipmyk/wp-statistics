let WP_Statistics_CheckTime=WP_Statistics_Tracker_Object.jsCheckTime,WP_Statistics_Dnd_Active=parseInt(navigator.msDoNotTrack||window.doNotTrack||navigator.doNotTrack,10),hasTrackerInitializedOnce=!1;const referred=encodeURIComponent(document.referrer);let wpStatisticsUserOnline={hitRequestSuccessful:!0,init:function(){hasTrackerInitializedOnce||(hasTrackerInitializedOnce=!0,"undefined"==typeof WP_Statistics_Tracker_Object?console.log("Variable WP_Statistics_Tracker_Object not found on the page source. Please ensure that you have excluded the /wp-content/plugins/wp-statistics/assets/js/tracker.js file from your cache and then clear your cache."):(this.checkHitRequestConditions(),WP_Statistics_Tracker_Object.option.userOnline&&this.keepUserOnline()))},checkHitRequestConditions:function(){!WP_Statistics_Tracker_Object.option.dntEnabled||1!==WP_Statistics_Dnd_Active?this.sendHitRequest():console.log("DNT is active.")},sendHitRequest:async function(){try{var t=this.getRequestUrl("hit"),e=new URLSearchParams({...WP_Statistics_Tracker_Object.hitParams,referred:referred}).toString();const s=new XMLHttpRequest;s.open("POST",t,!0),s.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),s.send(e),s.onreadystatechange=function(){4!==s.readyState||200!==s.status||!1===JSON.parse(s.responseText).status?this.hitRequestSuccessful=!1:this.hitRequestSuccessful=!0}}catch(t){this.hitRequestSuccessful=!1}},sendOnlineUserRequest:async function(){if(this.hitRequestSuccessful)try{var t=this.getRequestUrl("online"),e=new URLSearchParams({...WP_Statistics_Tracker_Object.onlineParams,referred:referred}).toString(),s=new XMLHttpRequest;s.open("POST",t,!0),s.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),s.send(e)}catch(t){}},keepUserOnline:function(){let e;if(WP_Statistics_Tracker_Object.option.userOnline){const s=setInterval(function(){(!WP_Statistics_Tracker_Object.option.dntEnabled||WP_Statistics_Tracker_Object.option.dntEnabled&&1!==WP_Statistics_Dnd_Active)&&this.hitRequestSuccessful&&this.sendOnlineUserRequest()}.bind(this),WP_Statistics_CheckTime);["click","keypress","scroll","DOMContentLoaded"].forEach(t=>{window.addEventListener(t,()=>{clearTimeout(e),e=setTimeout(()=>{clearInterval(s)},18e5)})})}},getRequestUrl:function(t){let e=WP_Statistics_Tracker_Object.requestUrl+"/";return WP_Statistics_Tracker_Object.option.bypassAdBlockers?e+="wp-admin/admin-ajax.php":"hit"===t?e+=WP_Statistics_Tracker_Object.hitParams.endpoint:"online"===t&&(e+=WP_Statistics_Tracker_Object.onlineParams.endpoint),e}};document.addEventListener("DOMContentLoaded",function(){"disabled"!=WP_Statistics_Tracker_Object.option.consentLevel&&!WP_Statistics_Tracker_Object.option.trackAnonymously&&WP_Statistics_Tracker_Object.option.isWpConsentApiActive&&!wp_has_consent(WP_Statistics_Tracker_Object.option.consentLevel)||wpStatisticsUserOnline.init(),document.addEventListener("wp_listen_for_consent_change",function(t){var e,s=t.detail;for(e in s)s.hasOwnProperty(e)&&e===WP_Statistics_Tracker_Object.option.consentLevel&&"allow"===s[e]&&(wpStatisticsUserOnline.init(),WP_Statistics_Tracker_Object.option.trackAnonymously)&&wpStatisticsUserOnline.checkHitRequestConditions()})});