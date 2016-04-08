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

				document.cookie="timeplannr_filter=" + jQuery(this).val() + "; expires=Thu, 18 Dec 2017 12:00:00 UTC; path=/";

				return false;

			})

			jQuery("#calendar-filter").val( getCookie( 'timeplannr_filter' )).trigger("change");

		});

		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i=0; i<ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1);
				if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
			}
			return "";
		}

	</script>

@stop

@section('sidebar')

@stop