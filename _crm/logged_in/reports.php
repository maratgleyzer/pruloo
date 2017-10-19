<?

	include "header.php";

?>
<style>
	table tr td{
		padding:10px;
	}
</style>
<div id="centerb">
<!-- top of shit -->
<div id="midsb">
    <div id="searches"><div class="topsa">Reports </div>
      <!-- top of shit -->
    </div>
  	<hr />
    <form>
        <h1>Generate Reports</h1><img src="images/break.jpg" /><br /><br />
        <select>
            <option>Choose Type</option>
            <option value="0">Sales Report by Products</option>
        </select><br /><br />
        <select>
            <option>Choose Campaign</option>
        </select>
        <h2>Date Range</h2><img src="images/break.jpg" /><br /><br />
         <ul>
            <li class="sortdate2"><input type="text" dojoType="dijit.form.DateTextBox" value="<?php echo date('m/d/Y'); ?>" /></li>
            <li class="sortdate2"><input type="text" dojoType="dijit.form.DateTextBox" value="<?php echo date('m/d/Y'); ?>" /></li>
        </ul>
        <div style="width:800px;height:50px;"> </div>
        <input type="submit" value="Generate" />
    </form>
    <div style="width:800px;height:50px;"> </div>
    
    <div class="bottm">
    	<!-- SALES REPORT BY PRODUCTS -->
        <?php
			$sql = "SELECT `products`.name AS `name`, COUNT(`orders`.Order_Id) AS `new_orders`, SUM(`orders`.Product_Price) AS `product_rev`, SUM(`orders`.Is_Recurring) AS `num_recurring` FROM `products` LEFT JOIN `orders` ON `products`.name =`orders`.Product_Name";
			$result = mysql_query($sql);
		?>
    	<table cellpadding="10">
        	<thead>
            	<tr>
                	<th>Product Id #</th><th>New Orders</th><th>New Order Rev</th><th>Recurring</th><th>Recurring Rev</th><th>Saved</th><th>Saved Rev</th><th>Total</th><th>Total Rev</th><th>Declines</th>
                </tr>
            </thead>
            <tbody>
            	<?php while($row = mysql_fetch_assoc($result)): ?>
                <tr>
                	<td><?php echo $row['name']; ?></td>
                    <td><?php echo number_format($row['new_orders']); ?></td>
                    <td>$<?php echo number_format($row['product_rev'],2); ?></td>
                    <td><?php echo number_format($row['num_recurring']); ?></td>
                    <td>$<?php echo number_format($row['product_rev'],2); ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($row['new_orders']); ?></td>
                    <td>$<?php echo number_format($row['product_rev'],2); ?></td>
                    <td></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- END SALES REPORT BY PRODUCTS -->
    </div>
</div>
</body>
</html>
