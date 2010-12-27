var ContestTabMenu = Class.create();
/**
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
ContestTabMenu.prototype = Object.extend(new TabMenu(), {
	next: function() {
		var next = false;
		$(this.activeTabMenuItem).up('.tabMenu').select('li').each(function(li, i) {
			if(next) {
				this.showSubTabMenu(li.id);
				next = false;
			} else if (li.hasClassName('activeTabMenu')) {
				next = true;
			}
		}, this);
		return false;
	},
	back: function() {
		var prev = 0;
		$(this.activeTabMenuItem).up('.tabMenu').select('li').each(function(li, i) {
			if (li.hasClassName('activeTabMenu')) {
				this.showSubTabMenu((prev || li).id);
			}
			prev = li;
		}, this);
		return false;
	},
	isActive: function() {
		return this.activeTabMenuItem != '';
	}
});
