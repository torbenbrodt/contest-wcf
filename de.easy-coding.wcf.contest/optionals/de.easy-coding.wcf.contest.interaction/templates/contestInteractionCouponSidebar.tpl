<div class="contentBox">
	<div class="border"> 
		<div class="containerHead">
			<h3>{lang}Coupon einlösen{/lang}</h3>
		</div>
		<div style="padding:10px">
			<form method="post">
				{if $contestCouponPossibleParticipants|count == 0}
					<input type="hidden" name="participantID" value="" />
				{else}
					<input type="radio" name="participantID" value="" /> foreach mit radio
				{/if}

				{lang}Code{/lang}: <input type="text" name="couponCode" /><br />
				<input type="submit" name="saveCoupon" value="{lang}Coupon einlösen{/lang}" />
			</form>
		</div>
	</div>
</div>
