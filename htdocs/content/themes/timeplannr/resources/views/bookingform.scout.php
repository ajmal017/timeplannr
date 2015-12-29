<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-hidden="false" style="display: none; padding-left: 0px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					×
				</button>
				<h4 class="modal-title">
					<span id="logo"> <img src="http://timeplannr.amber/content/themes/timeplannr/resources/assets/test/img/logo.png" alt="SmartAdmin"> </span>
				</h4>
			</div>
			<div class="modal-body">

				{{ Form::open(NULL, 'post', NULL, array( 'class' => 'smart-form')) }}

					{{ Form::hidden('id', '', array('id' => 'venue-id')) }}
					{{ Form::hidden('date', '', array('id' => 'date')) }}
					{{ Form::hidden('time_from', '', array('id' => 'time_from')) }}
					{{ Form::hidden('time_to', '', array('id' => 'time_to')) }}

					<fieldset>

						<section>
							<div class="row">
								<label class="label col col-3">Time range</label>
								<div class="col col-6">
									<div id="time-range"></div>
								</div>

								<div class="col col-3">
									<span id="from">12</span> - <span id="to">15</span>
								</div>
							</div>
						</section>

						<section>
							<div class="row">
								<label class="label col col-3">Add comments if you like</label>
								<div class="col col-9">
									<section>
										<label class="textarea">
											{{ Form::textarea('title', '', array('class' => 'custom-scroll', 'rows' => 3)) }}
										</label>
										<div class="note">
											<strong>Note:</strong> height of the textarea depends on the rows attribute.
										</div>
									</section>
								</div>
							</div>
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

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

	jQuery(document).ready(function() {

		var mySlider = jQuery("#time-range").slider({
			min: 11,
			max: 22,
			range: true,
			values: [11, 22],
			tooltip: 'show',
			tooltip_position: 'top',
			animate: true,

			change: function( event,ui ){

				jQuery( "#time_from" ).val( ui.values[0] );
				jQuery( "#time_to" ).val( ui.values[1] );

				var from_12h = '0';
				var to_12h = '0';

				if (ui.values[0] > 12) {
					from_12h = (ui.values[0] - 12) + 'pm';
				} else {
					from_12h = (ui.values[0]) + 'am';
				}

				if (ui.values[1] > 12) {
					to_12h = (ui.values[1] - 12) + 'pm';
				} else {
					to_12h = (ui.values[1]) + 'am';
				}

				jQuery( "#from" ).html( from_12h );
				jQuery( "#to" ).html( to_12h );

			},

			slide: function( event,ui ){

				jQuery( "#time_from" ).val( ui.values[0] );
				jQuery( "#time_to" ).val( ui.values[1] );

				var from_12h = '0';
				var to_12h = '0';

				if (ui.values[0] > 12) {
					from_12h = (ui.values[0] - 12) + 'pm';
				} else {
					from_12h = (ui.values[0]) + 'am';
				}

				if (ui.values[1] > 12) {
					to_12h = (ui.values[1] - 12) + 'pm';
				} else {
					to_12h = (ui.values[1]) + 'am';
				}

				jQuery( "#from" ).html( from_12h );
				jQuery( "#to" ).html( to_12h );

			}

		});

		jQuery(".smart-form").submit(function(e) {

			var url = "/book"; // the script where you handle the form input.

			jQuery.ajax({
				type: "POST",
				url: url,
				data: jQuery(".smart-form").serialize(), // serializes the form's elements.
				complete: function(data, data2, data3) {

					var myCalendar = $('#calendar');

					var myjson = {};
					jQuery.each(jQuery(".smart-form input"), function() { myjson[this.name] = this.value; });

					var date = new Date();
					var d = date.getDate();
					var m = date.getMonth();
					var y = date.getFullYear();

					var new_event = {

						title: '<?php echo addslashes(get_avatar( get_current_user_id(), 20 )); ?> <?php echo get_user_name(); ?>',
						start: new Date(y, m, d , myjson.time_from, 0),
						end: new Date(y, m, d, myjson.time_to, 0),
						allDay: false,
						className: ["event", "bg-color-red"],
						description: 'sdfojsdofijosdijf osidjf osijdf oisj df',
						slotWidth: 50,
						resourceId: 'venue-' + myjson.id,

					};

					myCalendar.fullCalendar( 'renderEvent', new_event );

					$('#myModal').modal('hide');

				},
			});

			e.preventDefault(); // avoid to execute the actual submit of the form.
			return false;

		});

	});

</script>