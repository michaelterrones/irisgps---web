@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-lg-12">
		    <table class="table table-striped table-bordered table-hover" id="all-elements-datatable">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Puesto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $employee)
                    <tr class="">
                        <td>{{$employee->id}}</td>
                        <td>{{$employee->dni}}</td>
                        <td>{{$employee->getFullname()}}</td>
                        <td>{{$employee->job->name}}</td>
                        <td class="center">
                            <a class="iris-link" href="{{ route('getEditEmployee', $employee->id) }}" title="editar">
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>   
                            <a class="iris-link confirm-action" href="{{ route('delete', array(get_class($employee),$employee->id)) }}" title="eliminar">
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