/**
 * hover effects to change rating
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
function ContestRating(elementName, optionID, currentRating) {
	this.spanelement = this.inputelement = null;
	this.elementName = elementName;
	this.optionID = optionID;
	this.currentRating = currentRating;

	/**
	 * Initialises a new rating option.
	 * replaces img with fancy hovered script
	 */
	this.init = function() {
		var img = document.getElementById(this.elementName);
		var span = this.spanelement = document.createElement('span');
		var input = this.inputelement = document.createElement('input');
		input.name = 'optionIDs[' + this.optionID + ']';
		input.type = 'hidden';
		input.value = this.currentRating;
		
		// add stars
		for (var i = 1; i <= 5; i++) {
			var star = document.createElement('img');
			star.alt = i;
			star.onmouseover = function(x) {
				return function() {
					this.style.cursor = 'pointer';
					x.showRating(parseInt(this.alt));
				};
			}(this);
			star.onclick = function(x) {
				return function() {
					x.submitRating(parseInt(this.alt));
				};
			}(this);
			span.appendChild(star);
		}
		
		// add hidden input field
		span.appendChild(input);
		
		// replace img through span
		img.parentNode.replaceChild(span, img);
		
		// add listener
		span.onmouseout = function(x) {
			return function() {
				x.showCurrentRating();
			};
		}(this);
		
		this.showCurrentRating();
	}
	
	/**
	 * Shows the current user rating.
	 */
	this.showCurrentRating = function() {
		this.showRating(this.currentRating);
	}
	
	/**
	 * Shows a selected rating.
	 */
	this.showRating = function(rating) {
		for (var i = 1; i <= 5; i++) {
			this.spanelement.childNodes[i - 1].src = RELATIVE_WCF_DIR + 'icon/contestRating' + (rating >= i ? 'S.png' : 'NoS.png');
		}
	}
	
	/**
	 * Submits a selected rating.
	 */
	this.submitRating = function(rating) {
		this.currentRating = this.inputelement.value = rating;
	}
	
	this.init();
}
