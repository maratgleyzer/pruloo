<?

	include "header.php";

?><div id="center">
           <!-- top of shit -->
           	<div id="mids">
			Edit Blacklist

												<div class="formBoxSmall">
													<p>
														<label>First Name:</label>

														<span class="right"><input name="bl_firstname" value="david" type="text"></span>
													</p>
													<p>
														<label>Last Name:</label>
														<span class="right"><input name="bl_lastname" value="pine" type="text"></span>
													</p>
													<p>
														<label>IP:</label>

														<span class="right"><input name="bl_ip" value="58.170.181.63" type="text"></span>
													</p>
													<p>
														<label>Credit Card Number:</label>
														<span class="right"><input name="bl_ccnumber" value="XXXXXXXXXXXXXXXX" type="text"></span>
													</p>
													<p>
														<label>Zip:</label>

														<span class="right"><input name="bl_zipcode" value="6104" type="text"></span>
													</p>
													<p>
														<label>Campaign:</label>
														<span class="right">
														<select name="bl_campaign_id">
															<option value="">--Select a Campaign--</option><option value="1">4RE Instant Access (1)</option><option value="4">real estate leads (4)</option><option value="6" selected="selected">Rem Bright Free Trial (6)</option><option value="7">WhiteRembright.com (7)</option><option value="8">RemBright Discount (8)</option><option value="9">Acai-Slim (9)</option><option value="10">MundoRemBright (10)</option><option value="11">RembrightNew (11)</option><option value="12">Whitener Pen (12)</option><option value="13">RemBright Bloosky (13)</option><option value="14">RemBright Blue (14)</option><option value="16">RemBright Emerchant Pay (16)</option><option value="21">RemBright .99 (21)</option><option value="18">Debt Free Lives (18)</option><option value="19">.99 RemBright (19)</option><option value="20">ABC4 RemBright (20)</option><option value="22">ABC4 RemBright Coupon (22)</option></select>

														</span>
													</p>
												</div>
												<div class="formBoxSmall">
													<p>
														<label>Comments:</label>
													</p>
													<p>

														<span class="right"><textarea rows="5" cols="20" name="bl_comments"></textarea></span>													</p>
													<p><div style="float: left; margin-top:0px;">
												<br><br>

													<input alt="Save" src="images/save.png" onclick='javascript: UpdateBList("1"); return false;' type="image" border="0">
													<a href="#"><input src="images/back.png" alt="Back" onclick='javascript: window.location="blacklist.php"; return false;' type="image" border="0"></a>
											</div></p>
												</div>
											</div>
										</td>
										<td>
											
										</td>
    		</div>
           <!-- top of shit -->    
        </div>
    </div>
</body>
</html>
