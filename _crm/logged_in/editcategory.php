<?

	include "header.php";

?><div id="center">
           <!-- top of shit -->
           	<div id="mids">
			<h1>Edit A Category</h1>
	        <div class="horizontal_rule"></div>
				<form method="post" action="categories.php?action=save_category">
					<div class="containerLeft" style="margin: 0pt; width: 490px;">
						<div class="container_headerA">
							<b>Edit Category</b>
						</div>

						<div class="formBox">
							<p><label>Edit Category Name:</label>
								<span class="right"><input name="category_name" value="4RealEstateLeads" type="text" class="inputbgs"></span>
							</p>
							<div class="hrline"></div>
							<p><label>Category Description:</label><br><br>
								<textarea cols="54" rows="8" name="category_desc" style="background-color:#2b2b2b;border:0;color:#fff;"></textarea>

								<input name="cat_id" value="1" type="hidden">
							</p>
							<div class="clear"><br></div>
						</div>
						<div class="container_headerA">
							<ul class="form">
								<li>
									<input alt="Save" src="images/save.png" type="image">
									<img style="cursor: pointer;" alt="Back" src="images/back.png" onclick='window.location="categories.php";' border="0">

								</li>
							</ul>
						</div>
					</div>
				</form>
					<div class="clear"></div>

    		</div>
           <!-- top of shit -->    
        </div>
    </div>
</body>
</html>
