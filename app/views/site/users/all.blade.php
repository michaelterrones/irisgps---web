@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-lg-12">
		    <table class="table table-striped table-bordered table-hover" id="all-elements-datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $user)
                    <tr class="">
                        <td>{{$user->id}}</td>
                        <td>{{$user->getFullname()}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->type->name}}</td>
                        <td class="center">
                            <a class="iris-link" href="{{ route('postEditUser', $user->id) }}" title="editar">
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>
                            @if(Auth::user()->id != $user->id)
                            <a class="iris-link confirm-action" href="{{ route('delete', array(get_class($user),$user->id)) }}" title="eliminar">
                                <i class="fa fa-trash fa-fw"></i>
                            </a>
                            @endif                         
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