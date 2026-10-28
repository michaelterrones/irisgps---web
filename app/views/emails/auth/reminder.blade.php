<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Cambio de Contraseña</h2>

		<div>
			{{--*/ $reset_url = URL::route('getReset', array($token)); /*--}}
			Para cambiar tu contraseña, completa el siguiente formulario: <a href="{{$reset_url}}">{{$reset_url}}</a><br/>
			El enlace expirará en {{ Config::get('auth.reminder.expire', 60) }} minutos.<br>
			<strong>IrisGPS</strong>
		</div>
	</body>
</html>
