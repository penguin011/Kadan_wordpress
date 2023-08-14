/*
 *	jQuery cookieTab 0.1 - jQuery plugin
 *	Plugin developed by: Takayoshi Shiraishi
 *	http://lab.komadoya.com/
 *
 *	2011/04/03 - ver 0.1
 *	 
 *	Released under the MIT license:
 *	http://www.opensource.org/licenses/mit-license.php
/* -------------------------------------------------------------------- */


(function($){

	$.fn.cookieTab = function(options){
		
		// set defaults
		var defaults = {
			activeTab: 0, //set active-tab
			tabMenuElm: '.tab-menu', //set tab-menu name(=id,class)
			tabPanelElm: '.tab-panel', //set tab-panel name(=id,class)
			cookie: { name: 'jcookieTab', expires: 7 } //set cookie option e.g. { name: 'uniqueCookieName', expires: 30, path: '/', secure:true }
		};
		var options = $.extend(true, defaults, options); //merge recursively
		
		return this.each(function(){
		
			// prepare elements
			var tabValue;
			var $unit = $(this);
			var $cookieName = options.cookie.name;
			if($unit.attr('id')){$cookieName += '-' + $unit.attr('id');} //prevent duplication of cookie, when multi-tabs are setted.
			var $tabMenu = $(options.tabMenuElm + ' > li', $unit);
			var $tabPanel = $(options.tabPanelElm + ' > div', $unit);

			// execute
			init();

			// init
			function init(){
				if(options.cookie){tabValue = getCookie($cookieName);}
				if(!tabValue || tabValue==undefined){tabValue = options.activeTab;}
				tabControl();
			}
			
			// control tab-action
			function tabControl(){
				tabDisplay();
				$tabMenu.find('a').each(function(value){ //value : array number of clicked anchor-element
					$(this).click(function(){ //action for tab-menu click
						if(options.cookie){setCookie($cookieName, value, options.cookie.expires, location.hostname, options.cookie.path, options.cookie.secure);} //define domain name explicitly for bug-fix of importing cookie
						// プランリストのタブ移動のため、こちらの条件分岐をかけないようにする	
						//if(tabValue != value){ //when clicking current tab, move to link page.
							tabValue = value;
							tabDisplay();
							return false;
						//}
					});
				});
			}
			
			// control tab-view
			function tabDisplay(){
				$tabMenu.each(function(){
					$(this).removeClass('current');
				});
				$tabPanel.each(function(){
					$(this).hide();
				});
				$tabMenu.eq(tabValue).addClass('current');
				$tabPanel.eq(tabValue).show();
			}
			
			//set cookie
			function setCookie(ckName, ckValue, ckExpires, ckDomain, ckPath, ckSecure){
				var date = new Date();
				date.setTime(date.getTime() + ckExpires*24*60*60*1000);
				var ckStr = escape(ckName) + '=' + escape(ckValue); //for garbage multibite character
				ckStr += '; expires=' + date.toGMTString(); //conversion expiry date to Greenwich mean time
				if(ckDomain){ckStr += '; domain=' + ckDomain;}
				if(ckPath)  {ckStr += '; path=' + ckPath;}
				if(ckSecure){ckStr += '; secure';}
				document.cookie = ckStr;
			}

			//get cookie
			function getCookie(ckName){
				var ckMatch = ('; ' + document.cookie + ';').match('; ' + ckName + '=(.*?);');
				var ckValue = (ckMatch) ? ckMatch[1] : '';
				ckValue = unescape(ckValue); //recover from escape string
				return ckValue;
			}
			
		});
	};
})(jQuery);
