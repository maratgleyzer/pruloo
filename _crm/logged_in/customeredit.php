<?

	include "header.php";
  
	if(isset($_REQUEST['customers_id'])) $customers_id = $_REQUEST['customers_id'];
  else $customers_id = 0;
  $msg = '';
  $customer_db = '';
  
  if(!empty($_REQUEST['delete'])) {
  	
    $sql = "DELETE FROM orders WHERE Orders_Id='$customers_id'";
    $qry = mysql_query($sql);
    
    ?>
    <script language="JavaScript" type="text/javascript">
    <!--
    	history.back(1);
    //-->
    </script>
    <?
  }
  
  if($_POST) {
  
    $customer_db = $_POST['user'];
    $customer_db['Order_Id'] = $customers_id;
    $customers_id = save_record($customer_db, 'orders');
    
      if($customers_id > 0 && !empty($_POST['purge'])) $msg = 'customer editted succesfully';
      if(!empty($_POST['username'])) $msg = 'customer Deleted';
      if($customers_id == 0) $msg = 'customer Created Successfully!';
  }
  
  
	$sql = "SELECT * FROM orders WHERE Order_Id='$customers_id'";
  $qry = mysql_query($sql);
  $customer = mysql_fetch_assoc($qry);
  
?>

<style type="text/css">
<!--
label {
	font-weight:bold;
	font-size:11px;
}

legend {
	font-weight:bold;
	font-style:italic;
  font-size:13px;
}

fieldset {
	border:solid 1px grey;
  overflow-x:hidden;
  overflow-y:auto;
}
-->
</style>

		<div id="center">
    <!-- top of shit -->
      <div id="mids">
      	<div class="containerFloat" style="margin: 0pt; width: 100%;">
      		<div class="container_headerC"><h1>Edit Customer</h1></div>
      		<form name="new_customers" id="new_customers" method="post" enctype="multipart/form-data" action="customeredit.php?customers_id=<?=$customers_id?>">
      			
      			<div>
              <fieldset style="width:49.9%; float:right;">
              	<legend>Shipping</legend>
        				<p>
        					<label>Shipping First Name:</label>
        					<span class="right"><input name="user[Ship_First]" class="filter_field" value="<?=$customer['Ship_First']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Shipping Last Name:</label>
        					<span class="right"><input name="user[Ship_Last]" class="filter_field" value="<?=$customer['Ship_Last']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Shipping Email Address:</label>
        					<span class="right"><input name="user[Ship_Email]" class="filter_field" value="<?=$customer['Ship_Email']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Shipping Phone:</label>
        					<span class="right"><input name="user[Ship_Phone]" class="filter_field" value="<?=formatPhone($customer['Ship_Phone'])?>" type="text"></span>
        				</p>
        				<p>
        					<label>Shipping Address:</label>
        					<span class="right"><input name="user[Ship_Address1]" class="filter_field" value="<?=$customer['Ship_Address1']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Shipping City:</label>
        					<span class="right"><input name="user[Ship_City]" class="filter_field" value="<?=$customer['Ship_City']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Shipping State:</label>
        					<span class="right"><input name="user[Ship_State]" class="filter_field" value="<?=$customer['Ship_State']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Shipping Zip:</label>
        					<span class="right"><input name="user[Ship_Zip]" class="filter_field" value="<?=$customer['Ship_Zip']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Shipping Country:</label>
        					<span class="right"><input name="user[Ship_Country]" class="filter_field" value="<?=$customer['Ship_Country']?>" type="text"></span>
        				</p>
              </fieldset>
            	<fieldset style="width:49.9%;">
              	<legend>Billing</legend>
        				<p>
        					<label>Billing First Name:</label>
        					<span class="right"><input name="user[Bill_First]" class="filter_field" value="<?=$customer['Bill_First']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Billing Last Name:</label>
        					<span class="right"><input name="user[Bill_Last]" class="filter_field" value="<?=$customer['Bill_Last']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Billing Email Address:</label>
        					<span class="right"><input name="user[Bill_Email]" class="filter_field" value="<?=$customer['Bill_Email']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Billing Phone:</label>
        					<span class="right"><input name="user[Bill_Phone]" class="filter_field" value="<?=formatPhone($customer['Bill_Phone'])?>" type="text"></span>
        				</p>
        				<p>
        					<label>Billing Address:</label>
        					<span class="right"><input name="user[Bill_Address1]" class="filter_field" value="<?=$customer['Bill_Address1']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Billing City:</label>
        					<span class="right"><input name="user[Bill_City]" class="filter_field" value="<?=$customer['Bill_City']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Billing State:</label>
        					<span class="right"><input name="user[Bill_State]" class="filter_field" value="<?=$customer['Bill_State']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Billing Zip:</label>
        					<span class="right"><input name="user[Bill_Zip]" class="filter_field" value="<?=$customer['Bill_Zip']?>" type="text"></span>
        				</p>
        				<p>
        					<label>Billing Country:</label>
        					<span class="right"><input name="user[Bill_Country]" class="filter_field" value="<?=$customer['Bill_Country']?>" type="text"></span>
        				</p>
              </fieldset>
              
              <fieldset style="width:33%; height:125px; float:right;">
              	<legend>Order Info</legend>
              	<label>Credit Card Number:</label><?="XXXX-XXXX-XXXX-" . substr($customer['Credit Card Number'],-4,4)?> <br />
              	<label>Credit Card Expiration:</label><?=$customer['Credit Card Expiration']?> <br />
              	<label>Decline Reason:</label><?=$customer['Decline Reason']?> <br />
              	<label>Authorization Number:</label><?=$customer['Auth_Number']?> <br />
                <?
                	$checked = ($customer['Is_Recurring'] == 'Yes') ? 'CHECKED' : '';
                ?>
                Automatic Rebill?: <input type="checkbox" name="user[Is_Recurring]" value="Yes" onclick="return confirm('Are you sure?')" <?=$checked?>>
              </fieldset>
              
              <fieldset style="width:33%; height:125px; float:right;">
              	<legend>Product Info</legend>
              	<label>Product Name:</label><?=$customer['Product_Name']?> <br />
              	<label>Product Sku #:</label><?=$customer['Product_Sku #']?> <br />
              	<label>Product Price:</label><?=$customer['Product_Price']?> <br />
              	<label>Quantity:</label><?=$customer['Quantity']?> <br />
              	<label>Is this Product still Active?:</label>
                <?  
                	$sql = "SELECT * FROM products WHERE 'sku'='".$customer['Product_Sku #']."' ";
                  $qry = mysql_query($sql);
                  $num = mysql_num_rows($qry);
                  
                  if($num > 0) echo "&nbsp; Yes";
                  else echo "&nbsp; No";
                ?> 
                <br />
              	<label>Description:</label><?=$customer['Description']?>
              </fieldset>
              
              <fieldset style="width:33%; height:125px; float:right;">
              	<legend>Other Info</legend>
              	<label>Total Sale:</label><?=$customer['Total_Sale']?> <br />
              	<label>Date of Sale:</label><?=$customer['Date_of_Sale']?> <br />
              	<label>Time of Sale:</label><?=$customer['Time_of_Sale']?> <br />
              	<label>Tracking Number:</label><?=$customer['Tracking_Number']?> <br />
              </fieldset>
      				<p style="clear:right;">
      					<label>Blacklist?:</label>
                <?
                	$checked = ($customer['blacklist'] == 1) ? 'CHECKED' : '';
                ?>
      					<span class="right"><input name="user[Blacklist]" onclick="return confirm('Do you really want to change this customers blacklist status?');" value="1" type="checkbox" <?=$checked?>></span>
      				</p>
      
      					<img src="images/back.png" value="Cancel" alt="Cancel" onclick="history.back();" style="cursor: pointer;">
      					<img src="images/save.png" value="Edit Customer" alt="Save" style="cursor: pointer;" onclick="document.new_customers.submit();">
      				</p>
      			</div>
      		</form>
      	</div>
    	</div>
           <!-- top of shit -->    
  	</div>
  </div>
</body>
</html>
