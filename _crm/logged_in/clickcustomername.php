<?

	include "header.php";

?><div id="center">
           <!-- top of shit -->
           	<div id="mids">
			<h1>Customers</h1>
		<form name="import_customers" id="import_customers" method="get" enctype="multipart/form-data" action="customers.php">
			<div class="horizontal_rule"></div>			
		        <input id="search_req" name="search_req" value="" type="hidden">
		        <input id="act" name="act" value="" type="hidden">
				<input name="ci_id" value="" type="hidden">
				<div class="containerFloat" style="margin: 0pt; width: 540px;">
					<div class="container_headerC">

						<table width="100%"><tbody><tr><td width="60%">Search Customers</td></tr></tbody></table>
					</div>
					<div class="formBoxSmall" id="searchBox" style="display: inline;">
					<p><label>First Name:</label>
						<span class="right"><input name="customer_first_filter" value="" type="text"></span>
					</p>
					<p><label>Last Name:</label>

						<span class="right"><input name="customer_last_filter" value="" type="text"></span>
					</p>
					<p><label>Email Address:</label>
						<span class="right"><input name="email_filter" value="" type="text"></span>
					</p>
				</div>
				<div class="formBoxSmall" id="searchBox2" style="display: inline;">
					<p><label>Phone:</label>

						<span class="right"><input name="phone_filter" value="" type="text"></span>
					</p>
					<p>&nbsp;</p>
				</div>
				<div class="buttons" id="searchBox3" style="display: inline;">
					<a href="#"><img src="images/clear.png" alt="Clear" class="clearbutton" value="Clear Search" name="clear" onclick="javascript: return ClearFilter();" border="0">
					<img src="images/showresults.png" alt="Show Results" class="submit" value="Search Customers" onclick="javascript: return ShowSearchDetails();" border="0"></a>
				</div>
			</div>

	        <div class="clear"></div>
			<p>
				</p><ul class="form">
					<li>Customer Features:</li>
					<li style="width: 350px;">
						<img style="cursor: pointer;" src="images/show-details.gif" alt="Show Details" value="Order Details" onclick="javascript: return ShowDetails();" border="0">
						<img style="cursor: pointer;" src="images/delete-customers.gif" alt="Delete Customers" value="Delete Customers" onclick="javascript: return MassCustomerDelete();" border="0">
						<img style="cursor: pointer;" src="images/export-csv.gif" alt="Export CSV" value="Export to CSV" onclick="javascript: return DownloadCSV();" border="0">

					</li>
				</ul>
			
			<div class="clear"><br></div>
	        <div class="containerLeft" style="width: 835px;">		  
	            <div class="container_headerD"></div>
					<table class="tableA" cellspacing="1">
						<tbody><tr>
							<th></th>
							<th>Customer ID</th>

							<th>First Name</th>
							<th>Last Name</th>
							<th>Phone</th>
							<th>Email Address</th>
							<th>Account Created</th>
							<th>Features</th>

						</tr>
<tr>						<td><input id="id_302" name="id_302" value="302" type="checkbox"></td>
						<td><a href="customers.php?act=show_details&amp;show_by_id=302&amp;id_302=302&amp;page=0">302</a></td>
						<td>Keysha </td>
						<td>Marshall</td>
						<td>(310) 916-3784</td>
						<td><a href="mailto:tesha_marshall@tmail.com">tesha_marshall@tmail.com</a></td>

												<td>07/10/2009 6:00pm</td>
						<td>
							<a href="#" onclick="MM_openBrWindow('edit_customer.php?ci_id=302','','width=340, height=340, left=200,top=50');" style="display: inline;"><img src="images/edit.png" alt="Add Customers" border="0"></a> 
							<a href="#" onclick="javascript: DeleteCustomer(302); return false;" style="display: inline;"><img src="images/delete.png" alt="Add Customers" border="0"></a>
						</td>
					</tr>
<tr><td colspan="8">
							<table class="tableA" cellspacing="1">
								<tbody><tr>

									<th>Serial</th>
									<th>Order Date/Time</th>
									<th>Order ID</th>
									<th>Product Name</th>
									<th>Order Amount</th>
									<th>Campaign ID</th>

									<th>Campaign Name</th>
									<th>Transaction Status</th>
								</tr><tr><td>1</td><td>07/10/2009 6:00pm</td><td><a href="orders.php?show_details=show_details&amp;show_by_id=799&amp;act=&amp;id_799">799</a></td><td>RemBright Exlusive Offer</td><td>$4.87&nbsp;&nbsp;</td><td>13</td><td>RemBright Bloosky</td><td>Approved</td></tr><tr class="even"><td>2</td><td>07/20/2009 1:00am</td><td><a href="orders.php?show_details=show_details&amp;show_by_id=1169&amp;act=&amp;id_1169">1169</a></td><td>RemBright Exlusive Offer</td><td>$74.86&nbsp;&nbsp;</td><td>13</td><td>RemBright Bloosky</td><td>Approved</td></tr></tbody></table></td></tr>				</tbody></table>

			</div>
					<div class="clear"></div>
			
    		</div>
           <!-- top of shit -->    
        </div>
    </div>
</body>
</html>
