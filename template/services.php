<div style='float:right;  position:absolute; margin-top:0px; margin-left:0px;  '>
							<center>
								<a href='index.php' >
									<span class='label label-warning' >
										<? 
									
											echo $email;
										
										?>
										<br>
										<small>Not you? Click here</small>
									</span>
								</a>
							</center>
</div>
<form action='index.php?a=add' method='POST'>
				<div class='row-fluid container-fluid' style='padding-top:3%; padding-left:0;'>
					<div class='span12 '><center>
						<div class='page-header' style='width:70%;text-align:center;'> 
						<h1 style='text-align:left; padding-top:3%; margin-left:-30px;'>Manage Your Subscriptions
							<p >
									<small >
										Choose services, then click Subscribe.
										<br>
										Subscribed services are highlighted in <font color='orange' style='font-weight:bold'>orange</font>
									</small>
									
									 
							</p>
						</h1>
						
						</div> 
						
					</div> 
				</div>
			
				<div class='row-fluid' >
					
					
					
					<div class='row-fluid' >
						<? echo showSubscription($email, ""); ?>	
					</div>
			
			<br>
					<input type='hidden' value='<? echo $email ?>' name='email'>

				<div class='row-fluid'>
					<a href='index.php?a=unsubscribe&email=<? echo $email ?>'>
						<div class='btn button goback btn-danger vmiddle box' style="line-height:250%;height:2.8em; width:6em; margin:3%; font-size:1.3em;">
							Unsubscribe
						</div>
					</a>
					<input type='submit' class='btn btn-primary button' style="height:3.3em; width:9em;margin:3%; font-size:1.3em;" value='Subscribe Now'>
				</div>
			</div>

</form>