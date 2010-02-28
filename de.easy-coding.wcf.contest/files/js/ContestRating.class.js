/**
 * hover effects to change rating
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 */
function ContestRating(elementName, currentRating) {
	this.element = null;
	this.elementName = elementName;
	this.currentRating = currentRating;

	/**
	 * Initialises a new rating option.
	 * replaces img with fancy hovered script
	 */
	this.init = function() {
		var img = document.getElementById(this.elementName);
		var span = this.element = document.createElement('span');
		
		// add stars
		for (var i = 1; i <= 5; i++) {
			var star = document.createElement('img');
			star.src = RELATIVE_WCF_DIR+'icon/contestRatingS.png';
			star.alt = '';
			star.name = i;
			star.onmouseover = function(x) {
				return function() {
					this.style.cursor = 'pointer';
					x.showRating(parseInt(this.name));
				};
			}(this);
			star.onclick = function(x) {
				return function() {
					x.submitRating(parseInt(this.name));
				};
			}(this);
			span.appendChild(star);
		}
		
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
			this.element.childNodes[i - 1].src = RELATIVE_WCF_DIR + 'icon/contestRating' + (rating >= i ? 'S.png' : 'NoS.png');
		}
	}
	
	/**
	 * Submits a selected rating.
	 */
	this.submitRating = function(rating) {
		this.currentRating = rating;
		// TODO: save using ajax
	}
	
	this.init();
}
