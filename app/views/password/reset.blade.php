@extends('layouts.base')

@section('body')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
            	<div id="password-reset">
	            	<form class="form-horizontal" action="{{ action('RemindersController@postReset') }}" method="POST">
	            	<legend>Cambiar contraseña</legend>
	            	<!--ALERT ZONE -->
                        @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{Session::get('success')}}
                        </div>
                        @endif
                        @if(Session::has('info'))
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{Session::get('info')}}
                        </div>
                        @endif
                        @if(Session::has('warning'))
                        <div class="alert alert-warning alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{Session::get('warning')}}
                        </div>
                        @endif
                        @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{Session::get('error')}}
                        </div>
                        @endif
                        @if(Session::has('validation_errors'))
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Se han encontrado los siguientes errores:
                            <ul>
                                @foreach (Session::get('validation_errors') as $validation_error)
                                    @foreach ($validation_error as $error_message)
                                        <li>{{$error_message}}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                        @endif                      
                    <!--END ALERT ZONE -->
	            	<input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">E-mail</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" id="email" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Contraseña</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" id="password" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="col-sm-2 control-label">Repita Contaseña</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="">
                        </div>
                    </div>
                	<input type="submit" class="btn btn-success center-block" value="Cambiar contraseña">
                	</form>
                </div>
            </div>
        </div>
    </div>

@stop