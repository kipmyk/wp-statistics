/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/dev/blocks/wp-statistics/index.js":
/*!**************************************************!*\
  !*** ./assets/dev/blocks/wp-statistics/index.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _sidebar__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./sidebar */ "./assets/dev/blocks/wp-statistics/sidebar.js");


(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__.registerPlugin)('wp-statistics', {
  render: _sidebar__WEBPACK_IMPORTED_MODULE_1__["default"]
});
toggleEditorPanelOpened('wp-statistics-block-editor-panel');

/***/ }),

/***/ "./assets/dev/blocks/wp-statistics/sidebar.js":
/*!****************************************************!*\
  !*** ./assets/dev/blocks/wp-statistics/sidebar.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ sidebar)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/editor */ "@wordpress/editor");
/* harmony import */ var _wordpress_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./assets/dev/blocks/wp-statistics/style.scss");




const wpsIcon = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  class: "wp-statistics-block-editor-panel-icon",
  width: "39",
  height: "40",
  viewBox: "0 0 39 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M36.9836 25.4319C35.6682 29.4899 32.9519 32.9475 29.3206 35.1861C25.6892 37.4246 21.3798 38.298 17.1635 37.65C12.9471 37.002 9.09886 34.8748 6.30731 31.6491C3.51577 28.4234 1.96308 24.3097 1.92705 20.044C1.89101 15.7783 3.37399 11.6389 6.11064 8.36651C8.84729 5.09413 12.659 2.90228 16.8639 2.18312C21.0687 1.46397 25.3922 2.26445 29.0608 4.44133C32.7295 6.61821 35.5038 10.0294 36.8875 14.0647H38.9135C37.5135 9.49597 34.5157 5.58385 30.4683 3.04369C26.421 0.503527 21.5946 -0.494939 16.8716 0.230871C12.1486 0.956681 7.84472 3.35827 4.74687 6.99647C1.64901 10.6347 -0.0357814 15.2664 0.000576385 20.0447C0.0369342 24.823 1.79201 29.4285 4.94487 33.0192C8.09773 36.6098 12.4377 38.9456 17.1712 39.5995C21.9046 40.2533 26.7153 39.1815 30.7235 36.5801C34.7317 33.9786 37.6696 30.0213 39 25.4319H36.9836Z",
  fill: "black"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M37.409 17.8947L36.3828 17.3633L32.3587 25.1329C32.1501 24.9274 31.9018 24.7666 31.6288 24.6606C31.3559 24.5546 31.0641 24.5056 30.7715 24.5165C30.4789 24.5274 30.1916 24.5981 29.9273 24.7241C29.663 24.8502 29.4273 25.0289 29.2347 25.2495L25.429 20.1603C25.4041 20.1222 25.3696 20.0944 25.3433 20.0585C25.4131 19.8489 25.4502 19.6299 25.4532 19.409C25.4579 19.0934 25.3936 18.7806 25.2648 18.4924C25.136 18.2043 24.9458 17.9477 24.7075 17.7407C24.4692 17.5337 24.1886 17.3812 23.8853 17.2939C23.5819 17.2067 23.2632 17.1867 22.9513 17.2354C22.6395 17.2842 22.342 17.4004 22.0798 17.5761C21.8175 17.7518 21.5968 17.9826 21.4331 18.2524C21.2693 18.5223 21.1665 18.8246 21.1317 19.1383C21.097 19.4521 21.1312 19.7696 21.2319 20.0687C21.2099 20.0995 21.1806 20.1222 21.1586 20.1545L18.4854 24.7628C18.0797 24.4027 17.5524 24.21 17.0101 24.2236C16.4679 24.2372 15.9509 24.4561 15.5637 24.8361L12.0234 18.9362C12.1102 18.7035 12.1559 18.4575 12.1583 18.2091C12.1478 17.6411 11.9149 17.0998 11.5095 16.7018C11.104 16.3038 10.5586 16.0808 9.99048 16.0808C9.42235 16.0808 8.87691 16.3038 8.47151 16.7018C8.0661 17.0998 7.83312 17.6411 7.82267 18.2091C7.82267 18.2157 7.82267 18.2216 7.82267 18.2282C7.74937 18.292 7.68047 18.3418 7.6101 18.4122L1.28516 24.8698L2.11123 25.6761L8.21408 19.4457C8.37861 19.6843 8.58973 19.8871 8.83473 20.042C9.07973 20.1969 9.35355 20.3005 9.63968 20.3468C9.92581 20.393 10.2183 20.3808 10.4996 20.311C10.781 20.2412 11.0452 20.1151 11.2765 19.9404L14.9385 26.0462C14.89 26.3415 14.9033 26.6436 14.9775 26.9335C15.0517 27.2234 15.1852 27.4948 15.3696 27.7305C15.554 27.9661 15.7852 28.161 16.0487 28.3029C16.3122 28.4447 16.6022 28.5303 16.9005 28.5543C17.1988 28.5784 17.4988 28.5403 17.7816 28.4426C18.0644 28.3448 18.3239 28.1895 18.5436 27.9863C18.7634 27.7832 18.9386 27.5368 19.0583 27.2625C19.178 26.9883 19.2395 26.6922 19.2389 26.3929C19.2374 26.2198 19.2148 26.0475 19.1715 25.8798L21.9466 21.0971C22.3311 21.411 22.8135 21.5801 23.3099 21.5751C23.8062 21.57 24.2851 21.391 24.6631 21.0692L28.7003 26.4677C28.6892 26.5406 28.6819 26.614 28.6784 26.6876C28.6888 27.2556 28.9218 27.7969 29.3272 28.1949C29.7326 28.5929 30.278 28.8159 30.8462 28.8159C31.4143 28.8159 31.9597 28.5929 32.3651 28.1949C32.7705 27.7969 33.0035 27.2556 33.014 26.6876C33.0104 26.6023 33.0016 26.5174 32.9876 26.4332L37.409 17.8947ZM9.98572 19.8928C9.65228 19.8928 9.32633 19.7939 9.04909 19.6087C8.77185 19.4234 8.55577 19.1601 8.42817 18.8521C8.30057 18.544 8.26719 18.2051 8.33224 17.878C8.39729 17.551 8.55785 17.2506 8.79362 17.0148C9.0294 16.7791 9.32979 16.6185 9.65682 16.5534C9.98384 16.4884 10.3228 16.5218 10.6309 16.6494C10.9389 16.777 11.2022 16.9931 11.3875 17.2703C11.5727 17.5475 11.6716 17.8735 11.6716 18.2069C11.6716 18.4283 11.628 18.6475 11.5433 18.8521C11.4585 19.0566 11.3344 19.2425 11.1778 19.399C11.0213 19.5556 10.8354 19.6797 10.6309 19.7645C10.4263 19.8492 10.2071 19.8928 9.98572 19.8928ZM17.0715 28.0898C16.7381 28.0898 16.4121 27.9909 16.1349 27.8057C15.8576 27.6204 15.6416 27.3571 15.514 27.0491C15.3864 26.741 15.353 26.402 15.418 26.075C15.4831 25.748 15.6436 25.4476 15.8794 25.2118C16.1152 24.9761 16.4156 24.8155 16.7426 24.7504C17.0696 24.6854 17.4086 24.7188 17.7167 24.8464C18.0247 24.974 18.288 25.1901 18.4733 25.4673C18.6585 25.7445 18.7574 26.0705 18.7574 26.4039C18.7574 26.851 18.5798 27.2798 18.2636 27.596C17.9474 27.9122 17.5186 28.0898 17.0715 28.0898V28.0898ZM23.2887 21.0663C22.9553 21.0663 22.6293 20.9674 22.3521 20.7822C22.0748 20.5969 21.8588 20.3336 21.7312 20.0256C21.6036 19.7175 21.5702 19.3786 21.6352 19.0515C21.7003 18.7245 21.8608 18.4241 22.0966 18.1883C22.3324 17.9526 22.6328 17.792 22.9598 17.727C23.2868 17.6619 23.6258 17.6953 23.9339 17.8229C24.2419 17.9505 24.5052 18.1666 24.6905 18.4438C24.8757 18.7211 24.9746 19.047 24.9746 19.3804C24.9746 19.8276 24.797 20.2564 24.4808 20.5725C24.1646 20.8887 23.7358 21.0663 23.2887 21.0663V21.0663ZM30.8473 28.3705C30.5138 28.3705 30.1879 28.2717 29.9106 28.0864C29.6334 27.9012 29.4173 27.6379 29.2897 27.3298C29.1621 27.0218 29.1287 26.6828 29.1938 26.3558C29.2588 26.0287 29.4194 25.7283 29.6552 25.4926C29.8909 25.2568 30.1913 25.0962 30.5184 25.0312C30.8454 24.9661 31.1844 24.9995 31.4924 25.1271C31.8005 25.2547 32.0638 25.4708 32.249 25.748C32.4343 26.0253 32.5331 26.3512 32.5331 26.6847C32.5331 27.1318 32.3555 27.5606 32.0394 27.8767C31.7232 28.1929 31.2944 28.3705 30.8473 28.3705V28.3705Z",
  fill: "black"
}));
function sidebar() {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_editor__WEBPACK_IMPORTED_MODULE_2__.PluginDocumentSettingPanel, {
    className: "wp-statistics-block-editor-panel",
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('WP Statistics', 'wp-statistics'),
    icon: wpsIcon
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, "Over the past week (August 03 - August 09), this post has been viewed 200 times by 150 visitors. The top referrer domain is 'example.com' with 50 visits. In total, it has been viewed 1,000 times by 700 visitors, with 'example.com' leading with 300 referrals. For more detailed insights, visit the analytics section."));
}

/***/ }),

/***/ "./assets/dev/blocks/wp-statistics/style.scss":
/*!****************************************************!*\
  !*** ./assets/dev/blocks/wp-statistics/style.scss ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/editor":
/*!********************************!*\
  !*** external ["wp","editor"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["editor"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["plugins"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"index": 0,
/******/ 			"./style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkwp_statistics"] = self["webpackChunkwp_statistics"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["./style-index"], () => (__webpack_require__("./assets/dev/blocks/wp-statistics/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map