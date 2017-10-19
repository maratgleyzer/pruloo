<?

	include "header.php";

?><div id="center">
           <!-- top of shit -->
           	<div id="mids">
							<div class="containerLeft" style="margin: 0pt; width: 600px;">
					<div class="container_headerA">

						<b>Please fill out the following information for the new employee</b>
					</div>
					<div class="formBox">
						<form name="new_admin" action="admin.php?action=insert" method="post" enctype="multipart/form-data">
							<p><label>Employee Full Name:</label>
								<span class="right"><input name="admin_fullname" maxlength="50" type="text"></span>* Required
							</p>
							<div class="hrline"></div>

							<p><label>Username:</label>
								<span class="right"><input name="admin_name" maxlength="30" type="text"></span>* Required
							</p>
							<div class="hrline"></div>
							<p><label>E-mail Address:</label>
								<span class="right"><input name="admin_email" maxlength="95" type="text"></span>* Required
							</p>
							<div class="hrline"></div>
							<p><label>Password:</label>

								<span class="right"><input name="password_new" maxlength="20" type="password"></span>* Required
							</p>
							<div class="hrline"></div>
							<p><label>Confirm Password:</label>
								<span class="right"><input name="password_confirmation" maxlength="20" type="password"></span>* Required
								<input name="admin_level" value="1" type="hidden">
							</p>
							<div class="hrline"></div>
							<p><label>Allowed IP Address(es):</label>

								<span class="right"><input name="allowedIpAddress" type="test"></span>Use comma to seperate
							</p>
							<div class="hrline"></div>
							<p>
								<img style="cursor: pointer;" src="images/save.png" name="Save" alt="Save" onclick="return validate_form();">
								<img style="cursor: pointer;" src="images/back.png" name="Back" alt="Back" onclick="window.location='admin.php';">
								<input name="admin_level" value="1" type="hidden">
							</p>
						</form>

					</div>
				</div>
				        <div class="clear"></div>

    		</div>
           <!-- top of shit -->    
        </div>
    </div>
</body>
</html>
