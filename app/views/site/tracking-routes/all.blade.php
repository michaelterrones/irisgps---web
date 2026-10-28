@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-lg-12">
		    <table class="table table-striped table-bordered table-hover" id="all-elements-datatable">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Fecha de Ejecución</th>
                        <th>Vehículo</th>
                        <th>Clientes</th>
                        <th>Pasajeros</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $tracking_route)
                    <tr class="">
                        <td>{{$tracking_route->id}}</td>
                        <td>{{$tracking_route->getDate()}}</td>
                        <td>{{$tracking_route->vehicle->plate}}</td>
                        <td>{{count($tracking_route->tasks)}}</td>
                        <td>{{count($tracking_route->passengers)}}</td>
                        <td class="center">
                            <a class="iris-link" href="{{ route('getEditDevice', $tracking_route->id) }}" title="editar">
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>   
                            <a class="iris-link confirm-action" href="{{ route('delete', array(get_class($tracking_route),$tracking_route->id)) }}" title="eliminar">
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