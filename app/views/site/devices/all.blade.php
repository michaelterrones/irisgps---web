@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-lg-12">
		    <table class="table table-striped table-bordered table-hover" id="all-elements-datatable">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>IMEI</th>
                        <th>Activado</th>
                        <th>Permitido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $device)
                    <tr class="">
                        <td>{{$device->id}}</td>
                        <td>{{$device->mobile}}</td>
                        <td>{{$device->getActivated()}}</td>
                        <td>{{$device->getAllowed()}}</td>
                        <td class="center">
                            <a class="iris-link" href="{{ route('getEditDevice', $device->id) }}" title="editar">
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>   
                            <a class="iris-link confirm-action" href="{{ route('delete', array(get_class($device),$device->id)) }}" title="eliminar">
                                <i class="fa fa-trash fa-fw"></i>
                            </a>                    
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
		</div>
	</div>
@stop

@section('js')
    @include('javascript.datatable-init')
@stop