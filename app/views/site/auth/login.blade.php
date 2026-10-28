@extends('layouts.base')

@section('body')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default login-box-shadow">
                    <div class="iris-background2 text-center">
                        <h3 class="panel-title"><img src="{{asset('assets/images/irisgps-logo-largo_inv.png')}}" alt="" style="height:55px"></h3>
                    </div>
                    <div class="panel-body">

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
                        
                        <form role="form" action="{{ route('postLogin') }}" method="POST">
                            <fieldset>
                                <div class="form-group">
                                    <input class="login form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="login form-control" placeholder="Contraseña" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Recordarme
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-login btn-block">Iniciar Sesión</button>
                            </fieldset>
                        </form>
                    </div>
                    <div class="panel-footer text-center">
                        <p><img src="{{asset('assets/images/logoSGTEL_color-H40.png')}}" alt=""></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop