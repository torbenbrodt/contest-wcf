<script src="{@RELATIVE_WCF_DIR}js/Calendar.class.js" type="text/javascript"></script>
<script type="text/javascript">
	//<![CDATA[
	
	var calendar = new Calendar('{$monthList}', '{$weekdayList}', {@$startOfWeek});
	/**
	 * Toggles full day view vs detailed time view
	 */
	function toggleFullDay() {
		document.getElementById('isFullDay').onclick = function() {
			toggleFullDay();
		}
			
		var elements = document.getElementsByTagName('div');
		for (var i = 0; i < elements.length; i++) {
			if (elements[i] && elements[i].className.search(/noFullDay/) != -1) {
				elements[i].style.display = document.getElementById('isFullDay').checked ? "none" : "block";
			}
		}
	}
	
	/**
	 * Adds event listener for time form elements
	 */
	function adjustTimeListener() {
	
		var fromDay = document.getElementById('fromDay');
		var fromMonth = document.getElementById('fromMonth');
		var fromYear = document.getElementById('fromYear');
		var fromHour = document.getElementById('fromHour');
		var fromMinute = document.getElementById('fromMinute');
		
		fromMinute.onchange = fromHour.onchange = fromMonth.onchange = fromDay.onchange = function() { 
			adjustTime();
		};
		
		fromMinute.onkeyup = fromHour.onkeyup = fromMonth.onkeyup = fromDay.onkeyup = function() { 
			adjustTime();
		};
		
		var untilDay = document.getElementById('untilDay');
		var untilMonth = document.getElementById('untilMonth');
		var untilYear = document.getElementById('untilYear');
		var untilHour = document.getElementById('untilHour');
		var untilMinute = document.getElementById('untilMinute');
		
		untilMinute.onchange = untilHour.onchange = untilMonth.onchange = untilDay.onchange = function() { 
			adjustTime();
		};
	}
	
	/**
	 * Adjusts the time, so invalid settings are impossible (with js)
	 */
	function adjustTime() {
		
		var fromDay = document.getElementById('fromDay');
		var fromMonth = document.getElementById('fromMonth');
		var fromYear = document.getElementById('fromYear');
		var fromHour = document.getElementById('fromHour')
		var fromMinute = document.getElementById('fromMinute');
		var fromDate = new Date(fromYear.value, fromMonth.value-1, fromDay.value, fromHour.value, fromMinute.value, 0);
		
		var untilDay = document.getElementById('untilDay');
		var untilMonth = document.getElementById('untilMonth');
		var untilYear = document.getElementById('untilYear');
		var untilHour = document.getElementById('untilHour')
		var untilMinute = document.getElementById('untilMinute');
		var untilDate = new Date(untilYear.value, untilMonth.value-1, untilDay.value, untilHour.value, untilMinute.value, 0);
		
		if (fromDate.getTime() > untilDate.getTime()) {
			untilDay.value = fromDay.value;
			untilMonth.value = fromMonth.value;
			untilYear.value = fromYear.value;
			untilHour.value = fromHour.value == '' ? '' : (parseInt(fromHour.value) + 1);
			untilMinute.value = fromMinute.value;
		}
	}
	
	onloadEvents.push(function(event) {
		toggleFullDay();
		adjustTimeListener();
	});
	//]]>
</script>

<h3 class="subHeadline">{lang}wcf.user.contest.entry.{@$action}{/lang}: {lang}wcf.user.contest.entry.settings{/lang}</h3>
<p>{lang}wcf.user.contest.entry.contest.description{/lang}</p>
<fieldset>
	<legend>{lang}wcf.user.contest.entry.settings{/lang}</legend>
	
	<div class="formElement{if $errorField == 'state'} formError{/if}">
		<div class="formFieldLabel">
			<label>{lang}wcf.user.contest.entry.state{/lang}</label>
		</div>
		<div class="formField">
			<fieldset>
				<legend>{lang}wcf.user.contest.entry.state{/lang}</legend>
				{foreach from=$states item=availableState}
					<label><input type="radio" name="state" value="{@$availableState}" {if $state == $availableState}checked="checked" {/if}/> {lang}{$availableState}{/lang}</label>
				{/foreach}
			</fieldset>
		</div>
	</div>
	
	<div class="formGroup">
		<div class="formGroupLabel">
			<label>{lang}wcf.search.period{/lang}</label>
		</div>
		<div class="formGroupField">
			<fieldset>
				<legend>{lang}wcf.calendar.event.date.from{/lang}</legend>
				<div class="formElement">
					<label><input type="checkbox" id="isFullDay" name="isFullDay"{if $eventDate->isFullDay} checked="checked"{/if} /> {lang}wcf.calendar.fullDay{/lang}</label>
				</div>
			
				<div class="floatedElement floatedElementContainer">
					<div class="floatedElement">
						<p>{lang}wcf.calendar.event.date.from{/lang}</p>
					</div>
				
					<div class="floatedElement">
						<label for="fromDay">{lang}wcf.global.date.day{/lang}</label>
						{htmlOptions options=$dayOptions selected=$eventDate->fromDay id=fromDay name=fromDay}
					</div>
				
					<div class="floatedElement">
						<label for="fromMonth">{lang}wcf.global.date.month{/lang}</label>
						{htmlOptions options=$monthOptions selected=$eventDate->fromMonth id=fromMonth name=fromMonth}
					</div>
				
					<div class="floatedElement">
						<label for="fromYear">{lang}wcf.global.date.year{/lang}</label>
						<input id="fromYear" class="inputText fourDigitInput" type="text" name="fromYear" value="{@$eventDate->fromYear}" maxlength="4" />
					</div>
				
					<div class="floatedElement noFullDay">
						<label for="fromHour">{lang}wcf.global.date.hour{/lang}</label>
						{htmlOptions options=$hourOptions selected=$eventDate->fromHour id=fromHour name=fromHour} :
					</div>
				
					<div class="floatedElement noFullDay">
						<label for="fromMinute">{lang}wcf.global.date.minutes{/lang}</label>
						{htmlOptions options=$minuteOptions selected=$eventDate->fromMinute id=fromMinute name=fromMinute}
					</div>
				
					<div class="floatedElement">
						<a id="fromButton"><img src="{icon}datePickerOptionsM.png{/icon}" alt="" /></a>
						<div id="fromCalendar" class="inlineCalendar"></div>
					</div>
				</div>

				<div class="floatedElement floatedElementContainer">
					<div class="floatedElement">
						<p>{lang}wcf.calendar.event.date.until{/lang}</p>
					</div>
				
					<div class="floatedElement">
						<label for="untilDay">{lang}wcf.global.date.day{/lang}</label>
						{htmlOptions options=$dayOptions selected=$eventDate->untilDay id=untilDay name=untilDay}
					</div>
				
					<div class="floatedElement">
						<label for="untilMonth">{lang}wcf.global.date.month{/lang}</label>
						{htmlOptions options=$monthOptions selected=$eventDate->untilMonth id=untilMonth name=untilMonth}
					</div>
				
					<div class="floatedElement">
						<label for="untilYear">{lang}wcf.global.date.year{/lang}</label>
						<input id="untilYear" class="inputText fourDigitInput" type="text" name="untilYear" value="{@$eventDate->untilYear}" maxlength="4" />
					</div>
				
					<div class="floatedElement noFullDay">
						<label for="untilHour">{lang}wcf.global.date.hour{/lang}</label>
						{htmlOptions options=$hourOptions selected=$eventDate->untilHour id=untilHour name=untilHour} :
					</div>
				
					<div class="floatedElement noFullDay">
						<label for="untilHour">{lang}wcf.global.date.minutes{/lang}</label>
						{htmlOptions options=$minuteOptions selected=$eventDate->untilMinute id=untilMinute name=untilMinute}
					</div>
				
					<div class="floatedElement">
						<a id="untilButton"><img src="{icon}datePickerOptionsM.png{/icon}" alt="" /></a>
						<div id="untilCalendar" class="inlineCalendar"></div>
						<script type="text/javascript">
							//<![CDATA[
							calendar.init('from');
							calendar.init('until');
							//]]>
						</script>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</fieldset>

{if $additionalFields1|isset}{@$additionalFields1}{/if}

<div class="formSubmit">
	<input type="submit" name="next" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" {if $action == 'add'}onclick="return steppedTabMenu.next()"{/if} />
	{@SID_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>
