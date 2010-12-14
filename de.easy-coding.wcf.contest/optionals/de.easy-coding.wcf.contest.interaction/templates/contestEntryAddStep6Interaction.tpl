<fieldset>
	<legend>{lang}wcf.contest.interaction.settings{/lang}</legend>
	
	<div class="formElement{if $errorField == 'state'} formError{/if}">
		<div class="formFieldLabel">
			<label>{lang}wcf.contest.options{/lang}</label>
		</div>
		<div class="formField">
			<fieldset>
				<legend>{lang}wcf.contest.state{/lang}</legend>
				<label>
					<input type="checkbox" name="enableSolution" value="1" {if $enableSolution}checked="checked" {/if}/>
					{lang}wcf.contest.enableSolution{/lang}
				</label>
				<label>
					<input type="checkbox" name="enableOpenSolution" value="1" {if $enableOpenSolution}checked="checked" {/if}/>
					{lang}wcf.contest.enableOpenSolution{/lang}
				</label>
				<label>
					<input type="checkbox" name="enableParticipantCheck" value="1" {if $enableParticipantCheck}checked="checked" {/if}/>
					{lang}wcf.contest.enableParticipantCheck{/lang}
				</label>
				<label>
					<input type="checkbox" name="enableSponsorCheck" value="1" {if $enableSponsorCheck}checked="checked" {/if}/>
					{lang}wcf.contest.enableSponsorCheck{/lang}
				</label>
			</fieldset>
		</div>
	</div>
	
	<div class="formElement{if $errorField == 'state'} formError{/if}">
		<div class="formFieldLabel">
			<label>{lang}wcf.contest.state{/lang}</label>
		</div>
		<div class="formField">
			<fieldset>
				<legend>{lang}wcf.contest.state{/lang}</legend>
				{foreach from=$states item=availableState key=key}
					<label><input type="radio" name="state" value="{@$key}" {if $state == $key}checked="checked" {/if}/> {lang}{$availableState}{/lang}</label>
				{/foreach}
			</fieldset>
		</div>
	</div>
	
	<div class="formGroup" id="stateScheduled">
		<div class="formGroupLabel">
			<label>{lang}wcf.search.period{/lang}</label>
		</div>
		<div class="formGroupField">
			<fieldset>
				<legend>{lang}wcf.contest.calendar.event.date.from{/lang}</legend>
				<div class="formElement">
					<label><input type="checkbox" id="isFullDay" name="isFullDay"{if $eventDate->isFullDay} checked="checked"{/if} /> {lang}wcf.contest.calendar.fullDay{/lang}</label>
				</div>
			
				<div class="floatedElement floatedElementContainer">
					<div class="floatedElement">
						<p>{lang}wcf.contest.calendar.event.date.from{/lang}</p>
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
						<p>{lang}wcf.contest.calendar.event.date.until{/lang}</p>
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
