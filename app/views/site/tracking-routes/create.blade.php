@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-lg-12">		
			{{$templator->getForm()}}
		</div>
	</div>
@stop		

@section('js')
	{{$templator->getScripts()}}
@stop