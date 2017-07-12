<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
	<title>Form Submissions</title>

	<!-- CSS  -->
	<!--  <link rel="stylesheet" href="../public/libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" /> -->
	 <link rel="stylesheet" href="../public/signature-pad/assets/jquery.signaturepad.css">
	 

	 <!-- C:\localserver\manage.connectliving.co.za\public\signature-pad\assets -->
	<link href="../public/materialize/css/materialize.css" type="text/css" rel="stylesheet" media= "screen,projection"/>
	<link href="../public/materialize/css/style.css" type="text/css" rel="stylesheet" media= "screen,projection"/>
	<!-- Rickes -->

	<!-- JSigniture -->
	<script src="../public/materialize/js/jSignature/modernizr.js"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="../public/materialize/js/jSignature/flashcanvas.js"></script>
	<![endif]-->
	

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="../public/materialize/js/star_rating/rating.css">
	<link rel="stylesheet" href="../public/materialize/js/signature_pad/signature-pad.css">

	<style type="text/css">
		div {
			margin-top:1em;
			margin-bottom:1em;
		}
		input {
			padding: .5em;
			margin: .5em;
		}
		select {
			padding: .5em;
			margin: .5em;
		}
		
		#signatureparent {
			color:darkblue;
			background-color:darkgrey;
			/*max-width:600px;*/
			padding:10px;
		}
		
		/*This is the div within which the signature canvas is fitted*/
		#signature {
			border: 2px dotted black;
			background-color:lightgrey;
		}
		/* Drawing the 'gripper' for touch-enabled devices */ 
		html.touch #content {
			float:left;
			width:98%;
		}
		html.touch #scrollgrabber {
			float:right;
			width:4%;
			margin-right:2%;
			background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAFCAAAAACh79lDAAAAAXNSR0IArs4c6QAAABJJREFUCB1jmMmQxjCT4T/DfwAPLgOXlrt3IwAAAABJRU5ErkJggg==)
		}
		html.borderradius #scrollgrabber {
			border-radius: 1em;
		}

		canvas.jSignature { height: 300px!important; }
		 
	</style>
</head>

<body class="white">
	<div class="container">
		<div class="row center-align col s12 m9 l10 ">
			<form id="survey-form" name="survey-form" class="" method="POST" action="FormSubmissions/SubmitForm">
				<input type="hidden" id="form_id" name="form_id" value="<?=$data['form_id'];?>">
				<input type="hidden" id="FormID"  name="FormID" value="<?=$data['form_id'];?>">
				<input type="hidden" id="prop_id" name="prop_id" />
				<!-- Form name -->
				<div class="row">
					<h5 id="form_name"></h5>
				</div>
				<!-- /Form name -->
				

				<!-- Complex Details -->
				<div class="row">
					<div class="input-field col s6">
						<input placeholder="Complex Name" id="prop_name" name="prop_name" type="text" class="validate" disabled="yes">
						<label for="prop_name">Complex Name</label>
					</div>
					<div class="input-field col s6">
						<input  placeholder="Unit Number" id="unit_no" name="unit_no" type="text" class="validate" value="<?=$data['unit_no'];?>">
						<label for="unit_no">Unit Number</label>
					</div>
				</div>
				<!-- /Complex Details -->

				<!-- Resident's Details -->
				<p>Resident's Details</p>
				<div class="row">
					<div class="input-field col s6">
						<input type="text" placeholder="Name"  id="full_name" name="full_name" value="<?=$data['full_name'];?>">
						<label for="full_name"> Name</label>
					</div>
					<div class="input-field col s6">
						<input id="cellphone" name="cellphone" type="text" class="validate" value="<?=$data['cellphone'];?>">
						<label for="cellphone">Cell Number</label>
					</div>
				</div>
				<!-- /Resident's Details -->


				
				<div id="survey_area" class="col m12 s12">
				</div>
				<!-- Form Instructions -->
				<div class="row">
					<div class="input-field col s12 form-instructions" id="">
						<?=$data['form_instruction'];?>
					</div>
				</div>
				<!-- /Form Instructions -->

				
				<div id="survey_err" class="col m12 s12"></div>
				
				<button class="waves-effect waves-light btn-large grey darken-3" style='width:100%' id="SubmitSurvey" type="submit">Submit</button>
			</form>
			

		</div>
	</div>
	<!--  Scripts-->
	<script src="../public/materialize/js/jquery-2.1.1.min.js"></script>
	<script src="../public/materialize/js/jquery.form.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
	<script src="../public/pickadate/lib/picker.js"></script>
	<script src="../public/pickadate/lib/picker.date.js"></script>
	<script src="../public/pickadate/lib/picker.time.js"></script>
	<script src="../public/pickadate/lib/legacy.js"></script>

	<!-- JSigniture -->
	<script src="../public/materialize/js/jSignature/jSignature.min.js"></script>

	
	
	<script src="../public/materialize/js/materialize.js"></script>
	<script src="../public/materialize/js/init.js"></script>
	<script src="../public/materialize/js/lz-string.min.js"></script>
	
	<script src="../public/materialize/js/bootstrap-filestyle.min.js"></script>
	<script src="../public/materialize/js/ajaxupload.js"></script>
	<script src="../public/materialize/js/star_rating/rating.js"></script>
	<script src="../public/materialize/js/signature_pad/signature_pad.js"></script>
	<!-- C:\localserver\manage.connectliving.co.za\public\pickadate\lib -->

	<script src="../public/bower_components/moment/min/moment.min.js" type="text/javascript"></script>
	<script src="../public/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<script src="../public/scripts/form_submision.js"  type="text/javascript"></script>

	<script src="../public/materialize/js/survey-calls.js"></script>

	<style>
		.step1-yes, .step1-no, .step2,  .step2-yes, .step2-no, .thanks {
			display: none;
		}
		#modal1 {
			padding-bottom: 140px;
		}
		.dropdown-content {
			z-index: 99;
		}
	</style>	

</body>
</html>