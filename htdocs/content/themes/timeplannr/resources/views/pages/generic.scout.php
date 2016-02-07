@extends('main')

@section('main')

@loop


	<h1>{{ Loop::title() }}</h1>

	<div>
		{{ Loop::content() }}
	</div>

@endloop

@stop

@section('sidebar')

@stop
