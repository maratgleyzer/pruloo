<?

	include "header.php";

?><div id="center">
           <!-- top of shit -->
           	<div id="mids">
            <div class="content">
		<h1>Place An Order</h1>
			<div class="horizontal_rule"></div>
			<div class="containerLeft" style="width: 660px;">
				<form action="placeorder.php" method="post" name="order_form" id="order_form" onsubmit="return validate_form()">
					<input name="campaign_id" id="campaign_id" value="11" type="hidden">
					<input name="product_id" id="product_id" value="5" type="hidden">
					
					<input name="actionToTake" id="actionToTake" value="submitOrder" type="hidden">

											
					<input name="internal" value="order" type="hidden">
					<input name="step" value="third" type="hidden">
					<input name="ret_order_id" value="" type="hidden">
					<input id="custom_product" name="custom_product" value="5/" type="hidden"><input id="http_server" name="http_server" value="http://sales.prolero.com" type="hidden">					<div class="container_headerA">
						<ul class="form">
							<li class="wider">Place New Customer Order</li>
						</ul>
					</div>

					<div class="formBox">
						<p><label>Select Campaign:</label>
							<span class="right">
								<select name="campaign_id_sel" id="campaign_id_sel" onchange="change_prod()">
									<option value="0">--Select a Campaign--</option>
																			<option value="1">4RE Instant Access (1)</option>
																			<option value="4">real estate leads (4)</option>

																			<option value="6">Rem Bright Free Trial (6)</option>
																			<option value="7">WhiteRembright.com (7)</option>
																			<option value="8">RemBright Discount (8)</option>
																			<option value="9">Acai-Slim (9)</option>
																			<option value="10">MundoRemBright (10)</option>
																			<option value="11" selected="selected">RembrightNew (11)</option>

																			<option value="12">Whitener Pen (12)</option>
																			<option value="13">RemBright Bloosky (13)</option>
																			<option value="14">RemBright Blue (14)</option>
																			<option value="16">RemBright Emerchant Pay (16)</option>
																			<option value="21">RemBright .99 (21)</option>
																			<option value="18">Debt Free Lives (18)</option>

																			<option value="19">.99 RemBright (19)</option>
																			<option value="20">ABC4 RemBright (20)</option>
																			<option value="22">ABC4 RemBright Coupon (22)</option>
																	</select>
							</span>
						</p>
						<div class="hrline"></div>

												<p><label>Select Product:</label>
							<span class="checkbox">
								RemBright Free Trial							</span>
						</p>
						<input id="prod_price" name="prod_price" value="0.00" type="hidden">
						<div class="hrline"></div>
						<p><label>Product Description:</label>

							<span class="text">
								<div id="prod_dsc">RemBright Free Trial</div>
							</span>
						</p>
						<div class="hrline"></div>
						<p><label>Shipping Method:</label>
							<span class="right">
								<select name="shipping" id="shipping" onchange="javascript:SetShippingValue()">

																		<option value="1">Standard Shipping ($4.87) (1)</option>
																	</select>
							</span>
						</p>
						<div class="clear"><br></div>
						<input name="is_upsell" value="0" type="hidden">								<div class="clear"></div>
									
<br>
						<div class="container_headerA">

							<ul class="form">
								<li>Shipping Price: <div style="display: inline;" id="shipping_price">$1</div></li>
								<li>Product Price: <div style="display: inline;" id="p_price">Free Trial</div></li>
								<li>Total Amount: <div style="display: inline;" id="total_amount">$1.00</div></li>
							</ul>
						</div>

						<script type="text/javascript">load_shipping_price();prod_desc_load("RemBright Free Trial");//change_campaign_value(); // if you enable this line, there is an infinite loop :(</script>						<br>
						<p><label>First Name:</label>
							<span class="right"><input id="fields_fname" name="fields_fname" value="Eric" type="text"></span>
						</p>
						<div class="hrline"></div>
						<p><label>Last Name:</label>
							<span class="right"><input id="fields_lname" name="fields_lname" value="Esron" type="text"></span>

						</p>
						<div class="hrline"></div>
						<p><label>Shipping Street Address:</label>
							<span class="right"><input id="fields_address1" name="fields_address1" value="4709 N 66th St Apt 61" type="text"></span>
						</p>
						<div class="hrline"></div>
						<p><label>City:</label>
							<span class="right"><input id="fields_city" name="fields_city" value="Omaha" type="text"></span>

						</p>
						<div class="hrline"></div>
							
						<p><label>State:</label>
							<span class="right">
								<div id="state_cus1">
									<select id="fields_state" name="fields_state">
										<option value="" selected="selected">Select State</option>
										<option value="AL">Alabama</option>

										<option value="AK">Alaska</option>
										<option value="AZ">Arizona</option>
										<option value="AR">Arkansas</option>
										<option value="CA">California</option>
										<option value="CO">Colorado</option>
										<option value="CT">Connecticut</option>

										<option value="DE">Delaware</option>
										<option value="DC">District of Columbia</option>
										<option value="FL">Florida</option>
										<option value="GA">Georgia</option>
										<option value="HI">Hawaii</option>
										<option value="ID">Idaho</option>

										<option value="IL">Illinois</option>
										<option value="IN">Indiana</option>
										<option value="IA">Iowa</option>
										<option value="KS">Kansas</option>
										<option value="KY">Kentucky</option>
										<option value="LA">Louisiana</option>

										<option value="ME">Maine</option>
										<option value="MD">Maryland</option>
										<option value="MA">Massachusetts</option>
										<option value="MI">Michigan</option>
										<option value="MN">Minnesota</option>
										<option value="MS">Mississippi</option>

										<option value="MO">Missouri</option>
										<option value="MT">Montana</option>
										<option value="NE" selected="selected">Nebraska</option>
										<option value="NV">Nevada</option>
										<option value="NH">New Hampshire</option>
										<option value="NJ">New Jersey</option>

										<option value="NM">New Mexico</option>
										<option value="NY">New York</option>
										<option value="NC">North Carolina</option>
										<option value="ND">North Dakota</option>
										<option value="OH">Ohio</option>
										<option value="OK">Oklahoma</option>

										<option value="OR">Oregon</option>
										<option value="PA">Pennsylvania</option>
										<option value="RI">Rhode Island</option>
										<option value="SC">South Carolina</option>
										<option value="SD">South Dakota</option>
										<option value="TN">Tennessee</option>

										<option value="TX">Texas</option>
										<option value="UT">Utah</option>
										<option value="VT">Vermont</option>
										<option value="VA">Virginia</option>
										<option value="WA">Washington</option>
										<option value="WV">West Virginia</option>

										<option value="WI">Wisconsin</option>
										<option value="WY">Wyoming</option>
									</select>
								</div>
								<div id="state_cus2" style="display: none;">
									<input id="fields_state2" name="fields_state2" value="NE" onblur="SetCountryValue()" type="text">
								</div>
							</span>

						</p>
						<div class="hrline"></div>
						<p><label>Zip Code:</label>
							<span class="right"><input id="fields_zip" name="fields_zip" onkeydown="return onlyNumbers(event,'phone')" value="68104" type="text"></span>
						</p>
						<div class="hrline"></div>
														
						<p><label>Phone:</label>
							<span class="right">

								<input name="phone1" id="fields_phone_phone1" onchange="update_phone_field('fields_phone')" size="2" maxlength="3" value="" type="hidden">
								<input name="phone2" id="fields_phone_phone2" onchange="update_phone_field('fields_phone')" size="2" maxlength="3" value="" type="hidden">
								<input name="phone3" id="fields_phone_phone3" onchange="update_phone_field('fields_phone')" size="3" maxlength="4" value="" type="hidden">
								<input id="fields_phone" name="fields_phone" onkeydown="return onlyNumbers(event,'phone')" value="4025025904" type="text">
							</span>
						</p>
						<div class="hrline"></div>
						<p><label>Email:</label>

							<span class="right"><input id="fields_email" name="fields_email" value="erryesron@yahoo.co.uk" type="text"></span>
						</p>
					<div class="hrline"></div> <p>Is your billing address the same as this shipping address?</p>
								<div class="question1"><input id="radioOne" onclick="toggleBillingAddress(this)" name="billingSameAsShipping" value="yes" checked="checked" type="radio">Yes
								<input id="radioTwo" onclick="toggleBillingAddress(this)" name="billingSameAsShipping" value="no" type="radio">No </div>
								<div style="display: none;" id="billingDiv"> <div class="hrline"></div><p><label for="billing_street_address'">Billing Address:</label>
<span class="right"><input id="billing_street_address" name="billing_street_address" type="text"></span></p>

 <div class="hrline"></div> <p><label for="billing_city'">Billing City:</label>
<span class="right"><input id="billing_city" name="billing_city" type="text"></span></p>
 <div class="hrline"></div> <p><label for="billing_state'">Billing State:</label>
 <span class="right"><select id="billing_state" name="billing_state"><option value="" selected="selected">
						Select State</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option>

						<option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option>
						<option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District of Columbia</option>
						<option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option>
						<option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option>

						<option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option>
						<option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option>
						<option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option>
						<option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option>

						<option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option>
						<option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option>
						<option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option>
						<option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option>

						<option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option>
						<option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option>
						<option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option>
						<option value="WV">West Virginia</option><option value="WI">Wisconsin</option>

						<option value="WY">Wyoming</option></select></span>
						</p>  <div class="hrline"></div><p><label for="billing_postcode'">Billing Zip:</label>
<span class="right"><input id="billing_postcode" name="billing_postcode" type="text"></span></p>
</div>						<div class="hrline"></div>
						<p><label>Credit Card Type:</label>
							<span class="right">
								<select id="cc_type" name="cc_type">

									<option value="amex">American Express</option>
									<option value="visa">Visa</option>
									<option value="master">Master Card</option>
									<option value="discover">Discover</option>
								</select>
							</span>
						</p>

						<div class="hrline"></div>
						<p><label>Credit Card Number:</label>
							<span class="right"><input id="cc_number" maxlength="16" onkeydown="return onlyNumbers(event,'cc')" name="cc_number" value="" type="text"></span>
						</p>
						<div class="hrline"></div>
						<p><label>Expiration Date:</label>
							<span class="right">
								<select name="fields_expmonth" onchange="javascript:update_expire()" id="fields_expmonth">

									<option selected="selected" value="">Month</option>
	                                <option value="01">January</option>
	                                <option value="02">February</option>
	                                <option value="03">March</option>
	                                <option value="04">April</option>
	                                <option value="05">May</option>

	                                <option value="06">June</option>
	                                <option value="07">July</option>
	                                <option value="08">August</option>
	                                <option value="09">September</option>
	                                <option value="10">October</option>
	                                <option value="11">November</option>

	                                <option value="12">December</option>
								</select>
							</span>
							<span class="right">
								<select name="fields_expyear" onchange="javascript:update_expire()" id="fields_expyear">
									<option value="09" selected="selected">2009</option><option value="10">2010</option><option value="11">2011</option><option value="12">2012</option><option value="13">2013</option><option value="14">2014</option><option value="15">2015</option><option value="16">2016</option><option value="17">2017</option><option value="18">2018</option><option value="19">2019</option><option value="20">2020</option><option value="21">2021</option><option value="22">2022</option>								</select>

								<input value="" id="cc_expires" name="cc_expires" type="hidden">
							</span>
						</p>
						<div class="hrline"></div>
						<p><label>Security Code:</label>
							<span class="right"><input id="cc_cvv" name="cc_cvv" value="" maxlength="4" type="text"></span>
						</p>
						<div style="display: none;"><input id="terms" name="terms" checked="checked" type="checkbox"></div>

						<div class="clear"><br></div>
					</div>
					
					<script type="text/javascript">
										
						update_expire();
					</script>
					
					<div class="container_headerA">
						<ul class="form">
							<li>&nbsp;</li>
							<li>&nbsp;</li>
							<li class="right"><input class="process-order" alt="Process Order" style="cursor: pointer;" value="" type="submit"></li>

						</ul>
					</div>
				</form>
			</div>
			
		    <div class="clear"></div>

			</div>
			
    		</div>
           <!-- top of shit -->    
        </div>
    </div>
</body>
</html>
