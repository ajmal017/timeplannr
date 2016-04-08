@extends('main')

@section('main')

@loop

<h1>{{ Loop::title() }}</h1>

<div class="row">

	<div class="col-md-6">{{ Loop::content() }}</div>

	<div class="col-md-6">

		<div class="jarviswidget jarviswidget-sortable">

			<header role="heading">
				<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
				<h2>New venue details</h2>
			</header>

			<div role="content">

				{{ Form::open(NULL, 'post', NULL, array( 'id' => 'suggestion-form', 'class' => 'smart-form')) }}

				{{ Form::hidden('id', '', array('id' => 'venue-id')) }}
				{{ Form::hidden('date', '', array('id' => 'date')) }}
				{{ Form::hidden('time_from', '', array('id' => 'time_from')) }}
				{{ Form::hidden('time_to', '', array('id' => 'time_to')) }}

				<fieldset>

					<section>
						<label class="input">
							<i class="icon-append fa fa-anchor"></i>
							{{ Form::text('title_a', '', array('placeholder' => 'Venue name')) }}
						</label>
					</section>

					<section>
						<label class="input">
							<i class="icon-append fa fa-home"></i>
							{{ Form::text('address', '', array('placeholder' => 'Address')) }}
						</label>
					</section>

					<section>
						<label class="input">
							<i class="icon-append fa fa-home"></i>
							{{ Form::text('city', '', array('placeholder' => 'City')) }}
						</label>
					</section>

					<section>
						<label class="input">
							<i class="icon-append fa fa-home"></i>
							{{ Form::text('state', '', array('placeholder' => 'State')) }}
						</label>
					</section>

					<section>
						<label class="input">
							<i class="icon-append fa fa-archive"></i>
							{{ Form::text('postcode', '', array('placeholder' => 'Postcode')) }}
						</label>
					</section>

					<section>
						<label class="input">
							<i class="icon-append fa fa-flag"></i>
							{{ Form::text('country', '', array('placeholder' => 'Country')) }}
						</label>
					</section>

				</fieldset>

				<footer>

					<button type="submit" class="btn btn-primary">
						Save
					</button>

					<button type="button" class="btn btn-default" data-dismiss="modal">
						Cancel
					</button>

				</footer>

				{{ Form::close() }}

			</div>

		</div>

	</div>

</div>

@endloop

<?php if ( $submitted ) : ?>

	<script type="text/javascript">

		$(document).ready(function() {

			setTimeout(show_confirmation_popup, 300);

			function show_confirmation_popup() {
				$.smallBox({
					title: "New venue suggession submitted",
					content: "<i class='fa fa-clock-o'></i> <i>You have successfully submitted a new venue suggested. You will be notified once it is approved</i>",
					color: "#659265",
					iconSmall: "fa fa-check fa-2x fadeInRight animated",
					timeout: 4000
				});
			}

		});

	</script>

<?php endif; ?>

<script type="text/javascript">

	$(document).ready(function() {

		var $checkoutForm = $('#suggestion-form').validate({
			// Rules for form validation
			rules : {
				title_a : {
					required : true
				},
				country : {
					required : true
				},
				city : {
					required : true
				},
				state : {
					required : true,
				},
				address : {
					required : true
				},
				postcode : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				title_a : {
					required : 'Please enter venue name'
				},
				country : {
					required : 'Please select country'
				},
				city : {
					required : 'Please enter city'
				},
				state : {
					required : 'Please enter state',
				},
				address : {
					required : 'Please enter full address'
				},
				postcode : {
					required : 'Please enter postcode'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

	});

</script>

@stop

@section('sidebar')

@stop