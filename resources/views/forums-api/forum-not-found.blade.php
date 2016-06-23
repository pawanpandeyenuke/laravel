<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>FS - Error</title>
		<link href="{{ url('forums-data/css/bootstrap.css') }}" rel="stylesheet">
		<link href="{{ url('forums-data/css/style.css') }}" rel="stylesheet">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style>
			.alert.alert-danger {
				margin-top: 5%;
			}
		</style>
	</head>

	<body>

		<div class="container mt-10">
			<div class="row">
				<div class="col-xs-12 mt-10">
					<div class="alert alert-danger"> {{ $message }}</div>
				</div>
			</div>
		</div>

	</body>
</html>