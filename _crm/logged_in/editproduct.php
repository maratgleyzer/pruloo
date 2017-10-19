<?

	include "header.php";

  if(isset($_REQUEST['products_id'])) $products_id = $_REQUEST['products_id'];
  else $products_id = 0;
  $msg = '';
  
  if(!empty($_REQUEST['delete'])) {
  	
    $product['id'] = $products_id;
    $product['purge'] = 1;
    $products_id = save_record($product, 'products');
    
    ?>
    <script language="JavaScript" type="text/javascript">
    <!--
    	history.back(1);
    //-->
    </script>
    <?
  }
  
  if($_POST) {
  
  	$product['id'] = $products_id;
    $product['name'] = mysql_escape_string($_POST['name']);
    $product['sku'] = mysql_escape_string($_POST['sku']);
    $product['has_trial'] = mysql_escape_string($_POST['has_trial']);
    $product['trial_length'] = mysql_escape_string($_POST['recurring_days']);
    $product['membership_length'] = mysql_escape_string($_POST['recurring_days']);
    $product['campaign_id'] = mysql_escape_string($_POST['next_recurring_product']);
    $product['type'] = mysql_escape_string($_POST['is_shippable']);
    $product['price'] = mysql_escape_string($_POST['price']);
    $product['description'] = mysql_escape_string($_POST['description']);
    if(!empty($_POST['purge'])) $product['purge'] = 1;
    
    if($products_id > 0 && !empty($_POST['purge'])) $msg = 'Product editted succesfully';
    if(!empty($_POST['purge'])) $msg = 'Product Deleted';
    if($products_id = 0) $msg = 'Product Created Successfully!';
    
    $products_id = save_record($product, 'products');
  	
    if($products_id) print $msg;
  }
  
  
	$sql = "SELECT * FROM products WHERE id='$products_id'";
  $qry = mysql_query($sql);
  $product = mysql_fetch_assoc($qry);
  
?>
  			<div id="center">
             <!-- top of shit -->
             	<div id="mids">
              <form action="editproduct.php?products_id=<?=$products_id?>" method="post">
              <h1>
              <? 
              if($products_id != 0) print "Edit ";
              else print "Create ";
              ?>
              Product</h1>
              <table>
              <tr><td>Product Name:</td><td><span class="right">
                <input name="name" id="prod_name2" type="text" value="<?=$product['name']?>" class="inputbgs" />
              </span></td>
              </tr>
              <tr><td>Product Sku #:</td><td><span class="right">
                <input name="sku" id="prod_name3" type="text" value="<?=$product['sku']?>" class="inputbgs" />
              </span></td>
              </tr>
              <tr><td>Free Trial Product:</td><td><span class="right">
                <select name="has_trial" class="inputdrop">
                  <option value="">Select</option>
                  <option value="0" <? if($product['has_trial'] == 0) print 'selected="SELECTED"' ?>>No</option>
                  <option value="1" <? if($product['has_trial'] == 1) print 'selected="SELECTED"' ?>>Yes</option>
                </select>
              </span></td>
              </tr>
              <tr><td>Start Recurring (Days):   </td><td><span class="right">
                <input name="recurring_days" dir="rtl" value="<?=$product['trial_length']?>" type="text" class="inputbgs" />
              </span></td>
              </tr>
              <tr><td>Next Recurring Product:  </td>
              <td>
              <span class="right">
                <select name="next_recurring_product" class="inputdrop">
                  <option value="0">None</option>
                </select>
              </span></td>
              </tr>
              <tr><td>Shippable Product:  </td><td><span class="right">
                <select name="is_shippable" class="inputdrop">
                  <option value="">Select</option>
                  <option value="0" <? if($product['type'] != 1) print 'selected="SELECTED"' ?>>No</option>
                  <option value="1" <? if($product['type'] == 1) print 'selected="SELECTED"' ?>>Yes</option>
                </select>
              </span></td>
              </tr>
              <tr><td>Products Price:   </td><td><span class="right">
                <input name="price" value="<?=$product['price']?>" type="text" class="inputbgs" />
              </span></td>
              </tr>
              <?
              if($products_id > 0) {
              ?>
              <tr>
                <td>Delete:   </td>
                <td>
                	<span class="right">
                  <input name="purge" value="1" type="checkbox" onclick="return confirm('Permanently Delete?');" />
                </span>
                </td>
              </tr>
              <?
              }
              ?>
              <tr><td>Product Description:    </td><td><span class="right">
                <textarea name="description" class="texts"><?=$product['description']?></textarea>
              </span></td>
              </tr>
              </table>
              <div class="container_headerA">
  					<ul class="form">
  						<li>&nbsp;</li>
  						<li>&nbsp;</li>
  						<li class="right">
              <a href="products.php" style="display: inline;">
              <img src="images/back.png" alt="Back" border="0"></a> 
              <input alt="Create Product" src="images/create-new-product-btn.png" type="image"></li>
  					</ul>
            </form>
  					</p>
  					<div class="clear"><br></div>
  					</div>
  				</div>
  			</div>
  			<div class="clear"></div>
  		</div>
             <!-- top of shit -->    
    </div>
  </div>
</body>
</html>
