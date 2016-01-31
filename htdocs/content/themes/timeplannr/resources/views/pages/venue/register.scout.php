@extends('main')

@section('main')

@loop

<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6 centered"><h1>{{ Loop::title() }}</h1></div>
	<div class="col-md-3"></div>
</div>

{{ Loop::content() }}

@endloop


@stop

