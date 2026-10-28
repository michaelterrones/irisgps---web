@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-lg-12">
		    <table class="table table-striped table-bordered table-hover" id="all-elements-datatable">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Color</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $vehicle)
                    <tr class="">
                        <td>{{$vehicle->plate}}</td>
                        <td>{{$vehicle->brand}}</td>
                        <td>{{$vehicle->model}}</td>
                        <td>{{$vehicle->color}}</td>
                        <td class="center">
                            <a class="iris-link" href="{{ route('getEditVehicle', $vehicle->id) }}" title="editar">
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>   
                            <a class="iris-link confirm-action" href="{{ route('delete', array(get_class($vehicle),$vehicle->id)) }}" title="eliminar">
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