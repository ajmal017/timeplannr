@extends('main')

@section('main')

	<h1>Plan time</h1>

	<div class="row">
		<div class="col-md-4">
			{{ Form::open('#', 'post', NULL, array( 'id' => 'calendar-filter-form', 'class' => 'smart-form')) }}
					<section>
						<div class="row">
							<label class="label col col-12">Search for venue</label>
						</div>
						<div class="row">
							<div class="col col-12">
								<section>
									<label class="textarea">
										{{ Form::text('filter', '', array('id' => 'calendar-filter')) }}
									</label>
								</section>
							</div>
						</div>
					</section>
			{{ Form::close() }}
		</div>
	</div>

	<a style="display: none;" id="launch-modal" data-toggle="modal" href="#myModal" class="btn btn-success btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-circle-arrow-up fa-lg"></i>Book</a>

	@include('venueslots')
	@include('bookingform')

	<script type="text/javascript">

		jQuery(document).ready(function() {

			jQuery("#calendar-filter").keydown(function(a) {

				if (a.keyCode == 13) {
					jQuery("#calendar-filter").trigger("change");
					return false;
				}

			});

			jQuery("#calendar-filter").focus(function() {
				this.select();
			});

			jQuery("#calendar-filter").change(function() {

				var data = {
					'action': 'get_events',
					'filter': jQuery(this).val()
				};

				// Filter the booking slots by the venue
				jQuery.post('/cms/wp-admin/admin-ajax.php', data, function(response) {

					var responseObject = JSON.parse(response);

					var myCalendar = jQuery('#calendar');

					myCalendar.fullCalendar( 'removeEvents' );

					for (i in responseObject.all_venues) {
						myCalendar.fullCalendar( 'removeResource', 'venue-' + responseObject.all_venues[i].ID );
					}

					for (i in responseObject.filtered_venues) {
						myCalendar.fullCalendar('addResource', {
							id: 'venue-' + responseObject.filtered_venues[i].ID,
							name: responseObject.filtered_venues[i].post_title
						});
					}

					myCalendar.fullCalendar( 'addEventSource', responseObject.slots );
					myCalendar.fullCalendar( 'rerenderEvents' );

				});

				return false;

			})

		});

	</script>

@stop

@section('sidebar')

@stop