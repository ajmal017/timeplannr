@extends('main')

@section('main')

<h1>Find a venue</h1>

<ul>
	@foreach($venues as $venue)
		<li><a href="/venue/plan?id={{ $venue['ID'] }}">{{ $venue['post_title'] }}</a></li>
	@endforeach
</ul>

<div id="bookstore-search">
	<div id="search-button"></div>
	<div id="search--form">
		<div id="search--form__icon"></div>
		<div id="search--form__form">
			{{ Form::open(home_url(), 'get', false, array('class' => 'searchform', 'role' => 'search')) }}
			{{ Form::hidden('post_type', 'venues', array()) }}
			{{ Form::text('s', '', array('id' => 's', 'placeholder' => 'Search a book...', 'autocomplete' => 'on')) }}
			{{ Form::close() }}
		</div>
	</div>
</div>

@stop

@section('sidebar')

@stop