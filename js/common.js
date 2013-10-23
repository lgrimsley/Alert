$(document).ready(function(){
		// Initialize the hammer plugin.      
			
			
		
			var clear = $("#clear").hammer({
				hold_timeout: 0.000001
			});
			
			
			clear.on("hold", function(ev){
				document.getElementById('password').value = "";
				document.getElementById('calc').innerHTML = "";
			});
			
			
			var submit = $("#calcsubmit").hammer({
				hold_timeout: 0.000001
			});
			
			submit.on("hold", function(ev){
				$("#calcform").submit();
				
			});
			
			var hammertime = $(".button").hammer({
				hold_timeout: 0.000001
			});

			// add multiple event listeners on the selector.
			hammertime.on("hold", function(ev) {
				login = document.getElementById('password');
				calc = document.getElementById('calc');
				login.value += $(this).attr('id');
				calc.innerHTML += $(this).attr('id');
			});
			
			var checkoption = $(".overbox").hammer({
				hold_timeout: 0.000001
			});
			
			checkoption.on("hold", function(ev) {
				selectX($(this).attr('id'), 'x'+$(this).attr('id'));
			});
			
			var checkall = $(".allbox").hammer({
				hold_timeout: 0.000001
			});
			
			checkall.on("hold", function(ev) {
				checkBox($(this).attr('id'), $(this).attr('name'));
			});	
			
			var switchscreen = $(".ibutton").hammer({
				hold_timeout: 0.000001
			});
			
			switchscreen.on("hold", function(ev) {
					if($(this).attr('value') == '^'){
						showup();
					}else if($(this).attr('value') == '?'){
						showinfo();
					}else{
						showdown();
					}
					
			});
			
			var preview = $(".preview").hammer({
				hold_timeout: 0.000001
			})
			preview.on("hold", function(ev) {
					$("#preview").attr('value','yes')
					$(this).closest('form').submit();
				
			});
			
			$("#upselect").change(function() {
				var id = $(this).children(":selected").attr("id");
				if(id == "Custom Message"){
					toggleSubject('up');
				}else{
					document.getElementById('uptext').innerText = id;
				}	
			});
			
			$("#downselect").change(function() {
				var id = $(this).children(":selected").attr("id");
				if(id == "Custom Message"){
					toggleSubject('down');
				}else{
					document.getElementById('downtext').innerText = id;
				}
			});

			
			var tab = $("#myTab a").hammer({
				hold_timeout: 0.000001
			})
			
			tab.on("hold", function(e) {
				e.preventDefault();
				$(this).tab('show');
			});
			
			
			var sendmail = $(".sendmail").hammer({
				hold_timeout: 0.000001
			})
			
			sendmail.on("hold", function(e) {
				$("#sendmail").submit();
			});
			
			var subject = $(".subject").hammer({
				hold_timeout: 0.000001
			})
			
			subject.on("hold", function(e) {
				var status = $(this).attr('value');
				toggleSubject(status);
				$("#"+status+"select").trigger("select");
			});
			
			var ltxt = $("#ltxt").hammer({
				hold_timeout: 0.000001
			})
			
			ltxt.on("hold", function(e) {
				$("#l_provider").show();
				$("#l_type").html("Text");
				$("#l_t").attr("value", "phone");
				$("#txtalert").show();
				$('.phone').attr('required','required');
				$('#l_p').attr('required','required');
				$('.email').removeAttr('required');
				$(".email").hide();
				$(".phone").show();
				$(".error:not(.email, .phone, #l_p)").remove();
			});
			
			var lemail = $("#lemail").hammer({
				hold_timeout: 0.000001
			})
			
			lemail.on("hold", function(e) {
				$("#l_provider").hide();
				$("#l_type").html("Email");
				$("#l_t").attr("value", "email");
				$("#txtalert").hide();
				$('.phone').removeAttr('required');
				$('#l_p').removeAttr('required','required');
				$('.email').attr('required','required');
				$(".phone").hide();
				$(".email").show();
				$(".error:not(.email, .phone, #l_p)").remove();
			});
			
			var lprovider = $(".lprovider").hammer({
				hold_timeout: 0.000001
			})
			
			lprovider.on("hold", function(e) {
				var id = $(this).attr("id");
				$("#l_provtxt").html(id);
				$("#l_p").attr("value", id);
				
			});
			
			var lother = $(".lother").hammer({
				hold_timeout: 0.000001
			})
			
			lother.on("hold", function(e) {
				$("#otheralert").show();
			});
			
		});
		
		
		
		function checkBox(div, type){
			div = document.getElementById(div);
			y = document.getElementById(type);
			y.checked = !y.checked;
			if(y.checked) { 
				$(div).addClass('btn-warning');
			} else $(div).removeClass('btn-warning');
			checkboxes = document.getElementsByName(type+'[]');
			for(var i=0, n=checkboxes.length;i<n;i++) {
				//checkboxes[i].checked = y.checked;
				selectX(type+i,checkboxes[i].id, y)
			}
		}
		function selectX(divid, xboxid, y){
			box = document.getElementById(xboxid);
			div = document.getElementById(divid);
			console.log(xboxid);
			box.checked = !box.checked;
			if(y){ box.checked = y.checked; }
			if(box.checked) { 
				$(div).addClass('btn-warning');
			} else $(div).removeClass('btn-warning');
		}
		function toggleSubject(status){
			$("#"+status+"select").toggle();
			$("#"+status+"subject").toggle();
		
		}
		