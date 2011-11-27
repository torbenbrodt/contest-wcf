<div class="contentBox">
	<div class="border"> 
		<div class="containerHead">
			<h3>{lang}Coupon einlösen{/lang}</h3>
		</div>
		<div style="padding:10px">
			<form method="post">
			{if $contestCouponException|isset}
				<p class="error">{lang}{$contestCouponException->getMessage()}{/lang}</p>
			{/if}
			
			{if $contestCouponExisingCoupons|isset}
				{lang}Sie haben bereits folgende(n) Coupon(s) eingelöst:{/lang}
				<ul>
				{foreach from=$contestCouponExisingCoupons item=coupon}
					<li>{$coupon->couponCode}</li>
				{/foreach}
				</ul>
			{else}
				{if $contestCouponPossibleParticipants|count == 0}
					<input type="hidden" name="participantID" value="0" />
				{else if $contestCouponPossibleParticipants|count == 1}
					{foreach from=$contestCouponPossibleParticipants item=participant}
					<input type="hidden" name="participantID" value="{$participant->participantID}" />
					{/foreach}
				{else}
					{foreach from=$contestCouponPossibleParticipants item=participant}
						<label><input type="radio" name="participantID" value="{$participant->participantID}" /> {lang}{$participant->getOwner()->getName()}{/lang}</label>
					{/foreach}
				{/if}

				{lang}Code{/lang}: <input type="text" name="couponCode" /><br />
				<input type="submit" name="saveCoupon" value="{lang}Coupon einlösen{/lang}" />
			{/if}
			</form>
		</div>
	</div>
</div>
