/**
 * compare to Suggestion from WCF Package, problem there is that it requires a global variable name
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 */
var ContestSuggestion = function() {
};

ContestSuggestion.prototype = new Suggestion();

/**
 * Initialises a new suggestion popup.
 */
ContestSuggestion.prototype.init = function(inputFieldID) {
	if (this.inputFields[inputFieldID]) {
		return;
	}
	this.inputFields[inputFieldID] = inputFieldID;
	
	// get input selement
	var element = document.getElementById(inputFieldID);
	
	// set display=block for ie and safari
	if (IS_IE || IS_SAFARI) {
		element.style.display = 'block';
	}
	
	// set autocomplete off
	// TODO: does not work in safari
	element.form.setAttribute('autocomplete', 'off');
	
	// disable submit on return
	element.form.onsubmit = function(suggestion) {
		return function() {
			if (suggestion.selectedIndex != -1) return false; 
		};
	}(this);
	
	// create suggestion list div
	var newDiv = document.createElement('div');
	newDiv.id = 'option' + inputFieldID;
	newDiv.className = 'hidden';
	
	// insert new div
	if (element.nextSibling) {
		element.parentNode.insertBefore(newDiv, element.nextSibling);
	}
	else {
		element.parentNode.appendChild(newDiv);
	}
	
	// add event listeners
	element.onkeyup = function (suggestion) {
		return function(e) {
			return suggestion.handleInput(e);
		}
	}(this);
	
	element.onkeydown = function (suggestion) {
		return function(e) {
			return suggestion.handleBeforeInput(e);
		}
	}(this);
	
	element.onfocus = element.onclick = function (suggestion) {
		return function(e) {
			return suggestion.handleClick(e);
		}
	}(this);
	
	element.onblur = function (suggestion) {
		return function(e) {
			return suggestion.closeList();
		}
	}(this);
};
	
/**
 * Opens a new ajax request to get a new suggestion list.
 */
ContestSuggestion.prototype.getSuggestList = function(target) {
	// get active string
	var string = this.getActiveString(target);
	
	// send request
	if (string != '') {
		this.ajaxRequest = new AjaxRequest();
		this.ajaxRequest.openPost(this.source, 'query='+encodeURIComponent(string), function(suggestion) {
			return function() {
				suggestion.receiveResponse();
			}
		}(this));
	}
	else {
		this.closeList();
	}
}

/**
 * Shows the suggestion list.
 */
ContestSuggestion.prototype.showList = function() {
	this.closeList();
	
	if (this.suggestions.length > 0 && this.activeTarget) {
		if (this.suggestions.length == 1 && this.insertAutomatically) {
			this.setSelectedIndex(0);
			this.insertSelectedOption();
		}
		else {
			// get option div
			var optionDiv = document.getElementById('option'+this.activeTarget.id);
			if (optionDiv) {
				optionDiv.className = 'pageMenu popupMenu';
				
				// create option list
				var optionList = document.createElement('ul');
				optionList.id = 'optionList'+this.activeTarget.id
				optionDiv.appendChild(optionList);
			
				optionList = document.getElementById('optionList'+this.activeTarget.id);
			
				// add list elements
				for (var i = 0; i < this.suggestions.length; ++i) {
					// create li element
					var optionListElement = document.createElement('li');
					optionListElement.id = 'optionList'+this.activeTarget.id+'Element'+i;
					optionList.appendChild(optionListElement);
					
					// create a element
					var optionListLink = document.createElement('a');
					optionListLink.name = i;
					optionListLink.onmousedown = function(suggestion) {
						return function() {
							suggestion.insertSelectedOption(this.name);
						}; 
					}(this);
					document.getElementById('optionList'+this.activeTarget.id+'Element'+i).appendChild(optionListLink);
					
					// create icon
					if (this.showIcon) {
						var icon = document.createElement('img');
						icon.src = RELATIVE_WCF_DIR + 'icon/' + this.suggestions[i]['type'] + 'S.png';
						optionListLink.appendChild(icon);
					}
					
					// create text node
					var name = document.createTextNode((this.showIcon ? ' ' : '') + this.suggestions[i]['name']);
					optionListLink.appendChild(name);
				}
			}
			
			this.setSelectedIndex(0);
		}
	}
}
