<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>IrisGPS</title>
	<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}"/>

	<style type="text/css" media="screen">

		.vertical-center {
			min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
			min-height: 100vh; /* These two lines are counted as one :-)       */

			display: flex;
			align-items: center;
		}

		.img-responsive {
			margin: 0 auto;
		}
		
	</style>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

</head>
<body>
	<div class="vertical-center">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<p class="text-center"><img class="img img-responsive" src="{{ asset('assets/images/irisgps.png') }}" alt="IrisGPS"></p>
				</div>
			</div>
		</div>
	</div>


	<!-- Google hosted jquery minified JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	
</body>
</html>