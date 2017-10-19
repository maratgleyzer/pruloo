<?

	include "header.php";

?><div id="center">
           <!-- top of shit -->
           	<div id="mids">
			<h1>Employee's Permissions</h1>
        <div class="horizontal_rule"></div>
		<form method="post" action="permissions.php?actions=update&amp;userid=9">
			<div class="containerLeft" style="width: 460px;">
				<div class="container_headerD"></div>
				<table class="tableA" id="tableA_left" cellspacing="1">
				<tbody><tr>

								<th width="230">Offers</th>
								<th>Enable</th>
								<th>Disable</th>
							  </tr><tr class="even"><td>Categories / Products</td>
											<td><input value="enabled" name="2" type="radio"></td>
											<td><input value="disabled" checked="checked" name="2" type="radio"></td>
										</tr><tr class="even"><td>Shipping</td>

											<td><input value="enabled" name="3" type="radio"></td>
											<td><input value="disabled" checked="checked" name="3" type="radio"></td>
										</tr><tr>
								<th width="230">Clients &amp; Fulfillment</th>
								<th>Enable</th>
								<th>Disable</th>

							  </tr><tr class="even"><td>Orders</td>
											<td><input value="enabled" checked="checked" name="5" type="radio"></td>
											<td><input value="disabled" name="5" type="radio"></td>
										</tr><tr class="even"><td>Prospects</td>
											<td><input value="enabled" checked="checked" name="6" type="radio"></td>
											<td><input value="disabled" name="6" type="radio"></td>
										</tr><tr class="even"><td>Customers</td>

											<td><input value="enabled" checked="checked" name="7" type="radio"></td>
											<td><input value="disabled" name="7" type="radio"></td>
										</tr><tr class="even"><td>Black List</td>
											<td><input value="enabled" checked="checked" name="8" type="radio"></td>
											<td><input value="disabled" name="8" type="radio"></td>
										</tr><tr>
								<th width="230">Call Center</th>
								<th>Enable</th>

								<th>Disable</th>
							  </tr><tr class="even"><td>Place Order</td>
											<td><input value="enabled" checked="checked" name="10" type="radio"></td>
											<td><input value="disabled" name="10" type="radio"></td>
										</tr><tr>
								<th width="230">Campaigns</th>
								<th>Enable</th>

								<th>Disable</th>
							  </tr><tr class="even"><td>New Campaigns</td>
											<td><input value="enabled" name="12" type="radio"></td>
											<td><input value="disabled" checked="checked" name="12" type="radio"></td>
										</tr><tr class="even"><td>Manage Campaigns</td>
											<td><input value="enabled" name="13" type="radio"></td>
											<td><input value="disabled" checked="checked" name="13" type="radio"></td>

										</tr><tr>
								<th width="230">Auto Responders</th>
								<th>Enable</th>
								<th>Disable</th>
							  </tr><tr class="even"><td>iContact</td>
											<td><input value="enabled" name="15" type="radio"></td>
											<td><input value="disabled" checked="checked" name="15" type="radio"></td>

										</tr><tr>
								<th width="230">Payment Systems</th>
								<th>Enable</th>
								<th>Disable</th>
							  </tr><tr class="even"><td>Gateway Providers</td>
											<td><input value="enabled" name="17" type="radio"></td>
											<td><input value="disabled" checked="checked" name="17" type="radio"></td>

										</tr><tr>
								<th width="230">Admin Settings</th>
								<th>Enable</th>
								<th>Disable</th>
							  </tr><tr class="even"><td>Employee Settings</td>
											<td><input value="enabled" name="20" type="radio"></td>
											<td><input value="disabled" checked="checked" name="20" type="radio"></td>

										</tr><tr class="even"><td>Import/Export</td>
											<td><input value="enabled" name="19" type="radio"></td>
											<td><input value="disabled" checked="checked" name="19" type="radio"></td>
										</tr><tr>
								<th width="230">Reports</th>
								<th>Enable</th>
								<th>Disable</th>

							  </tr><tr class="even"><td>Sales Report by Products</td>
											<td><input value="enabled" name="22" type="radio"></td>
											<td><input value="disabled" checked="checked" name="22" type="radio"></td>
										</tr><tr class="even"><td>Sales Report By Date</td>
											<td><input value="enabled" name="23" type="radio"></td>
											<td><input value="disabled" checked="checked" name="23" type="radio"></td>
										</tr><tr class="even"><td>Sales Report By Campaign</td>

											<td><input value="enabled" name="25" type="radio"></td>
											<td><input value="disabled" checked="checked" name="25" type="radio"></td>
										</tr><tr class="even"><td>Prospects and Customers by Campaign</td>
											<td><input value="enabled" name="26" type="radio"></td>
											<td><input value="disabled" checked="checked" name="26" type="radio"></td>
										</tr><tr class="even"><td>Customer Retention Report</td>
											<td><input value="enabled" name="27" type="radio"></td>
											<td><input value="disabled" checked="checked" name="27" type="radio"></td>

										</tr>				</tbody></table>
			</div>
			<div class="clear"><br></div>
			<div id="save_permissions" style="display: inline;">
				<input src="images/save.png" type="image">
				<img style="cursor: pointer;" src="images/back.png" onclick="window.location='admin.php';">
			</div>
		</form>

        <div class="clear"></div>
        <br><br>
    		</div>
           <!-- top of shit -->    
        </div>
    </div>
</body>
</html>
