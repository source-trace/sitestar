/* drag-drop-ajax */
function sack(file) {
	this.xmlhttp = null;

	this.resetData = function() {
		this.method = "POST";
  		this.queryStringSeparator = "?";
		this.argumentSeparator = "&";
		this.URLString = "";
		this.encodeURIString = true;
  		this.execute = false;
  		this.element = null;
		this.elementObj = null;
		this.requestFile = file;
		this.vars = new Object();
		this.responseStatus = new Array(2);
  	};

	this.resetFunctions = function() {
  		this.onLoading = function() { };
  		this.onLoaded = function() { };
  		this.onInteractive = function() { };
  		this.onCompletion = function() { };
  		this.onError = function() { };
		this.onFail = function() { };
	};

	this.reset = function() {
		this.resetFunctions();
		this.resetData();
	};

	this.createAJAX = function() {
		try {
			this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e1) {
			try {
				this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e2) {
				this.xmlhttp = null;
			}
		}

		if (! this.xmlhttp) {
			if (typeof XMLHttpRequest != "undefined") {
				this.xmlhttp = new XMLHttpRequest();
			} else {
				this.failed = true;
			}
		}
	};

	this.setVar = function(name, value){
		this.vars[name] = Array(value, false);
	};

	this.encVar = function(name, value, returnvars) {
		if (true == returnvars) {
			return Array(encodeURIComponent(name), encodeURIComponent(value));
		} else {
			this.vars[encodeURIComponent(name)] = Array(encodeURIComponent(value), true);
		}
	}

	this.processURLString = function(string, encode) {
		encoded = encodeURIComponent(this.argumentSeparator);
		regexp = new RegExp(this.argumentSeparator + "|" + encoded);
		varArray = string.split(regexp);
		for (i = 0; i < varArray.length; i++){
			urlVars = varArray[i].split("=");
			if (true == encode){
				this.encVar(urlVars[0], urlVars[1]);
			} else {
				this.setVar(urlVars[0], urlVars[1]);
			}
		}
	}

	this.createURLString = function(urlstring) {
		if (this.encodeURIString && this.URLString.length) {
			this.processURLString(this.URLString, true);
		}

		if (urlstring) {
			if (this.URLString.length) {
				this.URLString += this.argumentSeparator + urlstring;
			} else {
				this.URLString = urlstring;
			}
		}

		// prevents caching of URLString
		this.setVar("rndval", new Date().getTime());

		urlstringtemp = new Array();
		for (key in this.vars) {
			if (false == this.vars[key][1] && true == this.encodeURIString) {
				encoded = this.encVar(key, this.vars[key][0], true);
				delete this.vars[key];
				this.vars[encoded[0]] = Array(encoded[1], true);
				key = encoded[0];
			}

			urlstringtemp[urlstringtemp.length] = key + "=" + this.vars[key][0];
		}
		if (urlstring){
			this.URLString += this.argumentSeparator + urlstringtemp.join(this.argumentSeparator);
		} else {
			this.URLString += urlstringtemp.join(this.argumentSeparator);
		}
	}

	this.runResponse = function() {
		eval(this.response);
	}

	this.runAJAX = function(urlstring) {
		if (this.failed) {
			this.onFail();
		} else {
			this.createURLString(urlstring);
			if (this.element) {
				this.elementObj = document.getElementById(this.element);
			}
			if (this.xmlhttp) {
				var self = this;
				if (this.method == "GET") {
					totalurlstring = this.requestFile + this.queryStringSeparator + this.URLString;
					this.xmlhttp.open(this.method, totalurlstring, true);
				} else {
					this.xmlhttp.open(this.method, this.requestFile, true);
					try {
						this.xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
					} catch (e) { }
				}

				this.xmlhttp.onreadystatechange = function() {
					switch (self.xmlhttp.readyState) {
						case 1:
							self.onLoading();
							break;
						case 2:
							self.onLoaded();
							break;
						case 3:
							self.onInteractive();
							break;
						case 4:
							self.response = self.xmlhttp.responseText;
							self.responseXML = self.xmlhttp.responseXML;
							self.responseStatus[0] = self.xmlhttp.status;
							self.responseStatus[1] = self.xmlhttp.statusText;

							if (self.execute) {
								self.runResponse();
							}

							if (self.elementObj) {
								elemNodeName = self.elementObj.nodeName;
								elemNodeName.toLowerCase();
								if (elemNodeName == "input"
								|| elemNodeName == "select"
								|| elemNodeName == "option"
								|| elemNodeName == "textarea") {
									self.elementObj.value = self.response;
								} else {
									self.elementObj.innerHTML = self.response;
								}
							}
							if (self.responseStatus[0] == "200") {
								self.onCompletion();
							} else {
								self.onError();
							}

							self.URLString = "";
							break;
					}
				};

				this.xmlhttp.send(this.URLString);
			}
		}
	};

	this.reset();
	this.createAJAX();
}
/* context-menu */
DHTMLGoodies_menuModel = function() {
	var menuItems;
	this.menuItems = new Array();
}

DHTMLGoodies_menuModel.prototype = {
	addItem : function(id,itemText,itemIcon,url,parentId,jsFunction) {
		this.menuItems[id] = new Array();
		this.menuItems[id]['id'] = id;
		this.menuItems[id]['itemText'] = itemText;
		this.menuItems[id]['itemIcon'] = itemIcon;
		this.menuItems[id]['url'] = url;
		this.menuItems[id]['parentId'] = parentId;
		this.menuItems[id]['separator'] = false;
		this.menuItems[id]['jsFunction'] = jsFunction;
	},
	addSeparator : function(id,parentId) {
		this.menuItems[id] = new Array();
		this.menuItems[id]['parentId'] = parentId;	
		this.menuItems[id]['separator'] = true;
	},
	init : function() {
		this.__getDepths();
	},	
	getItems : function() {
		return this.menuItems;
	},
    __getDepths : function() {    	
    	for(var no in this.menuItems) {
    		this.menuItems[no]['depth'] = 1;
    		if (this.menuItems[no]['parentId']) {
    			this.menuItems[no]['depth'] = this.menuItems[this.menuItems[no]['parentId']]['depth']+1;
    		}    		
    	}    	
    },
	__hasSubs : function(id) {
		for(var no in this.menuItems) {	// Looping through menu items
			if(this.menuItems[no]['parentId']==id)return true;		
		}
		return false;	
	}
}

var referenceToDHTMLSuiteContextMenu;

DHTMLGoodies_contextMenu = function() {
	var menuModels;
	var menuItems;	
	var menuObject;// Reference to context menu div
	var layoutCSS;
	var menuUls;// Array of <ul> elements
	var width;// Width of context menu
	var srcElement;// Reference to the element which triggered the context menu, i.e. the element which caused the context menu to be displayed.
	var indexCurrentlyDisplayedMenuModel;// Index of currently displayed menu model.
	var imagePath;

	this.menuModels = new Array();
	this.menuObject = false;
	this.menuUls = new Array();
	this.width = 100;
	this.srcElement = false;
	this.indexCurrentlyDisplayedMenuModel = false;
	this.imagePath = '../script/mtree/theme/images/';
}

DHTMLGoodies_contextMenu.prototype = {
	setWidth : function(newWidth) {
		this.width = newWidth;
	},		
	setLayoutCss : function(cssFileName) {
		this.layoutCSS = cssFileName;	
	},
	attachToElement : function(element,elementId,menuModel) {
		window.refToThisContextMenu = this;
		if (!element && elementId)element = document.getElementById(elementId);
		if (!element.id) {
			element.id = 'context_menu' + Math.random();
			element.id = element.id.replace('.','');
		}
		this.menuModels[element.id] = menuModel;
		element.oncontextmenu = this.__displayContextMenu;
		//element.onmousedown = function() { window.refToThisContextMenu.__setReference(window.refToThisContextMenu); };
		document.documentElement.onclick = this.__hideContextMenu;
	},
	__setReference : function(obj) {	
		referenceToDHTMLSuiteContextMenu = obj;	
	},	
	__displayContextMenu : function(e) {
		if (document.all) e = event;		
		var ref = referenceToDHTMLSuiteContextMenu;
		ref.srcElement = ref.getSrcElement(e);
		
		if (!ref.indexCurrentlyDisplayedMenuModel || ref.indexCurrentlyDisplayedMenuModel!=this.id) {		
			if (ref.indexCurrentlyDisplayedMenuModel) {
				ref.menuObject.innerHTML = '';				
			}else{
				ref.__createDivs();
			}
			ref.menuItems = ref.menuModels[this.id].getItems();			
			ref.__createMenuItems();	
		}
		ref.indexCurrentlyDisplayedMenuModel=this.id;
		
		ref.menuObject.style.left = (e.clientX + Math.max(document.body.scrollLeft,document.documentElement.scrollLeft)) + 'px';
		ref.menuObject.style.top = (e.clientY + Math.max(document.body.scrollTop,document.documentElement.scrollTop)) + 'px';
		ref.menuObject.style.display='block';
		return false;
	},	
	__hideContextMenu : function() {
		var ref = referenceToDHTMLSuiteContextMenu;
		if (ref.menuObject) ref.menuObject.style.display = 'none';
	},	
	__createDivs : function() {
		this.menuObject = document.createElement('DIV');
		this.menuObject.className = 'DHTMLSuite_contextMenu';
		this.menuObject.style.backgroundImage = 'url(\'' + this.imagePath + 'context_menu.gif' + '\')';
		this.menuObject.style.backgroundRepeat = 'repeat-y';
		if (this.width) this.menuObject.style.width = this.width + 'px';
		document.body.appendChild(this.menuObject);
	},
	__mouseOver : function() {
		this.className = 'DHTMLSuite_item_mouseover';	
		if (!document.all) {
			this.style.backgroundPosition = 'left center';
		}					
	},	
	__mouseOut : function() {
		this.className = '';
		if (!document.all) {
			this.style.backgroundPosition = '1px center';
		}		
	},
	__evalUrl : function() {
		var js = this.getAttribute('jsFunction');
		if (!js) js = this.jsFunction;
		if (js) eval(js);
	},	
	__createMenuItems : function() {
		window.refToContextMenu = this;	// Reference to menu strip object
		this.menuUls = new Array();
		for(var no in this.menuItems) {	// Looping through menu items		
			if (!this.menuUls[0]) {	// Create main ul element
				this.menuUls[0] = document.createElement('UL');
				this.menuObject.appendChild(this.menuUls[0]);
			}
			
			if (this.menuItems[no]['depth']==1) {
				if (this.menuItems[no]['separator']) {
					var li = document.createElement('DIV');
					li.className = 'DHTMLSuite_contextMenu_separator';
				} else {				
					var li = document.createElement('LI');
					if (this.menuItems[no]['jsFunction']) {
						this.menuItems[no]['url'] = this.menuItems[no]['jsFunction'] + '(this,referenceToDHTMLSuiteContextMenu.srcElement)';
					}
					if (this.menuItems[no]['itemIcon']) {
						li.style.backgroundImage = 'url(\'' + this.menuItems[no]['itemIcon'] + '\')';
						if (!document.all) li.style.backgroundPosition = '1px center';
					}
					if (this.menuItems[no]['url']) {
						var url = this.menuItems[no]['url'] + '';
						var tmpUrl = url + '';
						li.setAttribute('jsFunction',url);
						li.jsFunction = url;
						li.onclick = this.__evalUrl;
					}
					
					li.innerHTML = '<a href="#" onclick="return false">' + this.menuItems[no]['itemText'] + '</a>';
					li.onmouseover = this.__mouseOver;
					li.onmouseout = this.__mouseOut;
				}				
				this.menuUls[0].appendChild(li);			
			}		
		}		
	},
    getSrcElement : function(e) {
    	var el;
		// Dropped on which element
		if (e.target) el = e.target;
			else if (e.srcElement) el = e.srcElement;
			if (el.nodeType == 3) // defeat Safari bug
				el = el.parentNode;
		return el;	
    }	
}