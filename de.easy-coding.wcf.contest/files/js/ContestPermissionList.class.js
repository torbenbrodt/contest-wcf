/**
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 */
function ContestPermissionList(key, data, url) {
	this.key = key;
	this.data = data;
	this.url = url;
	this.selectedIndex = -1;
	this.ajaxRequest;
	this.inputHasFocus = false;
	
	this.onfocusEvent;
	this.onblurEvent;
	this.onkeyupEvent;
	
	/**
	 * Initialises the permission list.
	 */
	this.init = function() {
		// add button listener
		var button = document.getElementById(this.key + 'AddButton');
		if (button) {
			button.list = this;
			button.onclick = function() { this.list.add(); };
		}
		
		// add input listener
		var input = document.getElementById(this.key + 'AddInput');
		if (input) {
			input.list = this;
			
			this.onfocusEvent = input.onfocus;
			input.onfocus = function(e) { this.list.inputHasFocus = true; this.list.onfocusEvent(e); };
			this.onblurEvent = input.onblur;
			input.onblur = function(e) { this.list.inputHasFocus = false; this.list.onblurEvent(e); };
			this.onkeyupEvent = input.onkeyup;
			input.onkeyup = function(event) {
				var result = this.list.onkeyupEvent(event);
				if (!event) event = window.event;
			
				// get key code
				var keyCode = 0;
				if (event.which) keyCode = event.which;
				else if (event.keyCode) keyCode = event.keyCode;
				
				// return
				if (keyCode == 13 && result) {
					this.list.add();
				}
			};
		}
		
		// refresh data list
		this.refresh();
	}
	
	/**
	 * Refreshes the complete list.
	 */
	this.refresh = function() {
		// get data div
		var dataDiv = document.getElementById(this.key);
		if (dataDiv) {
			// remove old content
			while (dataDiv.childNodes.length > 0) {
				dataDiv.removeChild(dataDiv.childNodes[0]);
			}
			
			// create list
			if (this.data.length > 0) {
				// create ul
				var ul = document.createElement('ul');
				dataDiv.appendChild(ul);
				
				for (var i = 0; i < this.data.length; i++) {
					// create li
					var li = document.createElement('li');
					li.id = this.key + i;
					if (i == this.selectedIndex) li.className = 'selected';
					ul.appendChild(li);
					
					// create remove link
					var removeLink = document.createElement('a');
					removeLink.className = 'remove';
					removeLink.list = this;
					removeLink.name = i;
					removeLink.onclick = function() { this.list.remove(parseInt(this.name)); };
					li.appendChild(removeLink);
					
					// create remove link image
					var removeImage = document.createElement('img');
					removeImage.src = RELATIVE_WCF_DIR + 'icon/deleteS.png';
					removeLink.appendChild(removeImage);
					
					// create a span
					var a = document.createElement('a');
					a.list = this;
					a.name = i;
					li.appendChild(a);
					
					// create image
					var img = document.createElement('img');
					img.src = RELATIVE_WCF_DIR + 'icon/'+this.data[i]['type']+'S.png';
					a.appendChild(img);
					
					// create title
					var title = document.createTextNode(this.data[i]['name']);
					a.appendChild(title);
				}
			}
		}
	}
	
	/**
	 * Removes an user or a group from the list.
	 */
	this.remove = function(index) {
		this.data.splice(index, 1);
		this.refresh();
	}
	
	/**
	 * Adds a new user or a new group to the list.
	 */
	this.add = function() {
		var query = new StringUtil(document.getElementById(this.key + 'AddInput').value).trim();
		
		if (query) {
			var activePermissionList = this;
			this.ajaxRequest = new AjaxRequest();
			this.ajaxRequest.openPost(this.url+SID_ARG_2ND, 'query='+encodeURIComponent(query), function() { 
				activePermissionList.receiveResponseJson(); 
			});
		}
	}
	
	/**
	 * Receives the response of an opened ajax request.
	 */
	this.receiveResponseJson = function() {
		if (this.ajaxRequest && this.ajaxRequest.xmlHttpRequest.readyState == 4 && this.ajaxRequest.xmlHttpRequest.status == 200) {
			var objects = eval('(' + this.ajaxRequest.xmlHttpRequest.responseText + ')');
			if (objects.length > 0) {
				for (var i = 0; i < objects.length; i++) {
  					var ob = objects[i];
					
					var doBreak = false;
					for (var j = 0; j < this.data.length; j++) {
						if (this.data[j]['id'] == ob.id && this.data[j]['type'] == ob.type) doBreak = true;
					}
					
					if (doBreak) continue;
					
					var key = this.data.length;
					this.data[key] = ob;
				}
				
				document.getElementById(this.key + 'AddInput').value = '';
				this.refresh();
			}
		
			this.ajaxRequest.xmlHttpRequest.abort();
		}
	}
	
	/**
	 * Saves the selected permissions in hidden input fields.
	 */
	this.submit = function(form) {
		var typeField;

		for (var i = 0; i < this.data.length; i++) {
			for(var key in this.data[i]) {
				typeField = document.createElement('input');
				typeField.type = 'hidden';
				typeField.name = this.key + '[' + i + ']['+key+']';
				typeField.value = this.data[i][key];
				form.appendChild(typeField);
			}
		}
	}

	this.init();
}
