/**
 * allows to switch between different views.
 * - datalist messages
 * - gallery mode
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
function ContestListRender(list) {
	this.list = list;
	this.current = 'messages';
	this.data = [];
	this.cached = {};

	/**
	 * style definition
	 */	
	this.renderDefinition = {
		thumbnailView: {
			className: 'class="dataList thumbnailView floatContainer">',
			style: '<li class="floatedElement smallFont"{$buttonstyle}>'+
				'<a href="{$url}" title="{$title}">'+
					'<span class="thumbnail" style="width: 40px;">'+
						'<img src="{$img}" style="width: 40px; height: 48px" alt="">'+
					'</span>'+
					'<span class="avatarCaption">{$username}</span>'+
				'</a>'+
				'{$buttons}'+
			'</li>'
		},
		messages: {
			className: 'dataList messages'
		}
	};
	
	/**
	 * returns current view as string
	 */	
	this.getCurrent = function() {
		return this.current;
	};
	
	/**
	 * fills data array from html template using current view
	 *
	 * @param	string		view
	 */
	this.read = function(view) {
		// read from dataList messages
		var elements = this.list.getElementsByTagName('li');
		for(var i=0; i<elements.length; i++) {
			var link = function(e) {
				e = e.getElementsByTagName('p');
				for(var i=0; i<e.length; i++) {
					return e[i].getElementsByTagName('a')[0];
				}
			}(elements[i]);
			var buttons = function(e) {
				var html = '', hasButtons = false, e = e.getElementsByTagName('div');
				for(var i=0; i<e.length; i++) {
					if(e[i].className == 'buttons') {
						hasButtons = true;
					}
					if(e[i].style && e[i].style.cssFloat == 'right') {
						html += e[i].innerHTML;
					}
				}
				return hasButtons ? '<div style="padding:0px 25px">' + html + '</div>' : '';
			}(elements[i]);
			var buttonstyle = function(e) {
				var html = '', hasButtons = false, e = e.getElementsByTagName('div'), x;
				for(var i=0; i<e.length; i++) {
					if(e[i].className == 'buttons') {
						hasButtons = true;
					}
					if(e[i].style && e[i].style.cssFloat == 'right') {
						x = e[i].getElementsByTagName('div');
						if(x.length > 0 && x[0].style.backgroundColor) {
							html += 'background-color:' + x[0].style.backgroundColor + ';';
						}
					}
				}
				return hasButtons ? ' style="margin:5px; padding:5px;' + html + '"' : '';
			}(elements[i]);

			this.data[i] = {
				img: elements[i].getElementsByTagName('img')[0].src,
				url: link ? link.href : null,
				username: link ? link.innerHTML : null,
				buttons: buttons,
				buttonstyle: buttonstyle,
			};
		}
	};
	
	/**
	 * adds buttons to switch views
	 */
	this.addControls = function() {
		var img, div, h4;
		
		div = document.createElement('div');
		div.style.cssFloat = 'right';
		
		img = document.createElement('img');
		img.style.cursor = 'pointer';
		img.src = RELATIVE_WCF_DIR + 'icon/contestThumbnailViewS.png';
		img.onclick = function(list) {
			return function() {
				list.change('thumbnailView');
			};
		}(this);
		div.appendChild(img);
		
		img = document.createElement('img');
		img.style.cursor = 'pointer';
		img.style.marginLeft = '3px';
		img.src = RELATIVE_WCF_DIR + 'icon/contestMessagesViewS.png';
		img.onclick = function(list) {
			return function() {
				list.change('messages');
			};
		}(this);
		div.appendChild(img);
		
		h4 = this.list.previousSibling.previousSibling.previousSibling.previousSibling;
		h4.appendChild(div);
	};
	
	/**
	 * constructir will cache current template and fill data array
	 */	
	this.init = function() {
		var view;
		
		view = this.getCurrent();
		this.read(view);

		// add buttons		
		this.addControls();	
		
		// fill cache
		this.cached[view] = this.list.innerHTML;
	};
	
	/**
	 * switch to another view
	 */
	this.change = function(view) {
		this.current = view;
		this.list.className = this.renderDefinition[view].className;
		if(!this.cached[view]) {
			this.cached[view] = '';
			for(var i=0; i<this.data.length; i++) {
				this.cached[view] += this.parseStyle(this.renderDefinition[view].style, this.data[i]);
			}
		}
		this.list.innerHTML = this.cached[view];
	};
	
	/**
	 * replace template variables in string
	 */
	this.parseStyle = function (style, data) {
		var regmatch = /\{\$([a-z_0-9]+)?\}/i.exec(style);

		while (regmatch !== null && regmatch[0] && regmatch[1]) {
			var str = data[regmatch[1]] ? data[regmatch[1]] : '';
			style = style.replace(regmatch[0], str.toString());
			regmatch = /\{\$([a-z_0-9]+)?\}/i.exec(style);
		}
		return style;
	};

	// call constructor	
	this.init();
}
