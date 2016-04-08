<style>

	.fc-license-message { display: none; }

	.fc-resource-area .fc-cell-text { font-size: 11px; }

	.smart-form .label.col {
		margin: 0;
		padding-top: 0px;
	}

	.fc-button .fc-icon {
		font-size: 1em;
	}

</style>

<!-- widget div-->
<div>

	<div id="eventContent"></div>

	<div class="widget-bodg">
		<div id="calendar"></div>
	</div>

</div>

<script type="text/javascript">

		$(document).ready(function() {

			var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
			var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var m_text = months[ date.getMonth() ];
			var y = date.getFullYear();
			var week_day = days[ date.getDay() ];

			var hdr = {
				left: 'title',
				center: 'month,agendaWeek,agendaDay',
				right: 'prev,today,next'
			};

			var initDrag = function (e) {
				// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
				// it doesn't need to have a start or end

				var eventObject = {
					title: $.trim(e.children().text()), // use the element's text as the event title
					description: $.trim(e.children('span').attr('data-description')),
					icon: $.trim(e.children('span').attr('data-icon')),
					className: $.trim(e.children('span').attr('class')) // use the element's children as the event class
				};
				// store the Event Object in the DOM element so we can get to it later
				e.data('eventObject', eventObject);

				// make the event draggable using jQuery UI
				e.draggable({
					zIndex: 999,
					revert: true, // will cause the event to go back to its
					revertDuration: 0 //  original position after the drag
				});
			};

			var addEvent = function (title, priority, description, icon) {
				title = title.length === 0 ? "Untitled Event" : title;
				description = description.length === 0 ? "No Description" : description;
				icon = icon.length === 0 ? " " : icon;
				priority = priority.length === 0 ? "label label-default" : priority;

				var html = $('<li><span class="' + priority + '" data-description="' + description + '" data-icon="' +
						icon + '">' + title + '</span></li>').prependTo('ul#external-events').hide().fadeIn();

				$("#event-container").effect("highlight", 800);

				initDrag(html);
			};

			/* initialize the external events
			 -----------------------------------------------------------------*/

			$('#external-events > li').each(function () {
				initDrag($(this));
			});

			$('#add-event').click(function () {
				var title = $('#title').val(),
						priority = $('input:radio[name=priority]:checked').val(),
						description = $('#description').val(),
						icon = $('input:radio[name=iconselect]:checked').val();

				addEvent(title, priority, description, icon);
			});

			/* initialize the calendar
			 -----------------------------------------------------------------*/

			var myCalendar = $('#calendar');

			myCalendar.fullCalendar({

				minTime: "11:00:00",
				maxTime: "22:00:00",
				resourceAreaWidth: 130,
				editable: false,
				aspectRatio: 1,
				scrollTime: '00:00',
				height: 500,
				slotDuration: '00:30:00',
				header: {
					/*left: 'promptResource today prev,next',
					center: 'title',
					right: 'timelineDay,timelineThreeDays,agendaWeek,month'*/
				},
				defaultView: 'timelineDay',
				views: {
					timelineThreeDays: {
						type: 'timeline',
						duration: { days: 3 }
					}
				},

				resourceColumns: [
					{
						labelText: 'Name',
						field: 'name'
					}
				],
				resources: [

					<?php foreach( $venues as $venue) : ?>

						{ id: 'venue-{{ $venue['ID'] }}', name: '{{ addslashes( $venue['post_title'] ) }}' },

					<?php endforeach; ?>

				],

				events: [

					<?php $count = 0; ?>
					<?php foreach ($booked_slots as $slot) : ?>

						<?php

						if ($count > 25) $count = 0;
						$date = strtotime( $slot['date'] );
						$day = date( "d", $date );
						$month = date( "m", $date );
						$year = date( "Y", $date );

						// Calculate time values for From
						$time_from = $slot['time_from'];
						$minute_from = $time_from > floor($time_from) ? 30 : 0 ;
						$time_from_hour = $time_from;
						$time_from_minute = $minute_from;

						// Calculate time values for To
						$time_to = $slot['time_to'];
						$minute_to = $time_to > floor($time_to) ? 30 : 0 ;
						$time_to_hour = $time_to;
						$time_to_minute = $minute_to;

						$comment_icon = '';
						if ( isset( $slot['title'] ) && !empty( $slot['title'] ) ) {
							$comment_icon = ' <i class="fa fa-comment"></i>';
						}

						$title = isset( $slot['title'] ) ? $slot['title'] : NULL;
						$colours = array(
							'greenLight',
							'red',
							'blue',
							'darken',
							'yellow',
							'purple',
							'orange',
						);

						$colour = $colours[ rand( 0, sizeof( $colours ) - 1 ) ];

						?>

					{
						id: <?php echo $slot['ID']; ?>,
						title: '<?php echo addslashes(get_avatar(  $slot['timeslot_user'], 20 )); ?> {{ $slot['first_name'] }} {{ $slot['last_name']  }}{{$comment_icon}}',
						start: new Date('{{ $year }}', '{{ $month - 1 }}', '{{ $day }}' , '{{ $time_from_hour }}', '{{ $time_from_minute }}'),
						end: new Date('{{ $year }}', '{{ $month - 1 }}', '{{ $day }}', '{{ $time_to_hour }}', '{{ $time_to_minute }}'),
						allDay: false,
						className: ["event", "bg-color-{{ $colour }}", 'event-id-<?php echo $slot['ID']; ?>'],
						description: '{{ addslashes( $title ) }}',
						slotWidth: 50,
						resourceId: 'venue-{{ $slot['timeslot_venue'] }}',
						userId: <?php echo $slot['timeslot_user']; ?>
					},

						<?php $count ++; ?>

					<?php endforeach; ?>

				],

				eventRender: function (event, element, icon) {

					if (!event.description == "") {
						element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
								"</span>");
					}

					if (!event.icon == "") {
						element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
								" '></i>");
					}

					element.find('.fc-title').html(event.title);

					element.qtip({
						prerender: true,
						content: event.description,
						style: 'qtip-bootstrap',
						position: {
							at: 'bottom left',
						}
					});

					if ( <?php echo get_current_user_id(); ?> == event.userId ) {
						element.append( "<a id='delete-event-link-" + event.id + "' style='position: absolute; top: 5px; right: 5px; z-index: 10;' class='closeon delete-event-link'><i style=\"font-size: 12px; color: white;\" class=\"fa fa-times-circle\"></i></a>" );
					}

				},

				windowResize: function (event, ui) {
					$('#calendar').fullCalendar('render');
				},

				dayClick:  function(date, jsEvent, view, resourceObj) {

					$("[name=comments]").val("");
					jQuery("#launch-modal").trigger("click");

					var text_date = date.format("YYYY-MM-DD");
					var hour = parseInt(date.format("H"));
					var minute = date.format("m");

					// Add a half an hour block to the time slider if required
					minute_number = minute == 30 ? 0.5 : 0;

					jQuery("#date").val(text_date);
					jQuery("#venue-id").val(resourceObj.id.replace("venue-", ""));

					jQuery("[name=title]").val("");

					var mySlider = jQuery("#time-range");

					// Prefill the time slider with select value that ends and +3 hours
					mySlider.slider('option', 'values', [hour + minute_number , hour + 3 + minute_number]);

				},

				eventClick: function(event) {},

				eventAfterAllRender: function(a) {

					var currentDate = $("#calendar").fullCalendar('getDate');
					var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
					var week_day = days[ currentDate.day() - 1 ];

					currentDate = days[ currentDate.weekday() ] + " - " + currentDate.format('MMMM Do, YYYY') ;
					$('.fc-toolbar .fc-left h2').text(currentDate);

				},

				// Limit the Previous and Next buttons to be active for dates between 2 days in the past and
				// 2 months in the future
				viewRender: function(currentView){
					var minDate = moment().add( -2,'days'),
						maxDate = moment().add( 2,'months');
					// Past
					if (minDate >= currentView.start && minDate <= currentView.end) {
						$(".fc-prev-button").prop('disabled', true);
						$(".fc-prev-button").addClass('fc-state-disabled');
					}
					else {
						$(".fc-prev-button").removeClass('fc-state-disabled');
						$(".fc-prev-button").prop('disabled', false);
					}
					// Future
					if (maxDate >= currentView.start && maxDate <= currentView.end) {
						$(".fc-next-button").prop('disabled', true);
						$(".fc-next-button").addClass('fc-state-disabled');
					} else {
						$(".fc-next-button").removeClass('fc-state-disabled');
						$(".fc-next-button").prop('disabled', false);
					}
				}

			});

			function delete_event( id ) {

				$('.event-id-' + id).hide();
				$('#calendar').fullCalendar('removeEvents', id);
				$('.qtip').hide();

				// Delete the event via REST API
				$.ajax( {
					url: '/wp-json/wp/v2/timeslot/' + id,
					type: 'DELETE',
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', API_COURSE.nonce );
					}
				} ).done( function ( response ) {

					$('#calendar').fullCalendar('removeEvents', id);

					$.smallBox({
						title : "Booking deleted",
						content : "<i class='fa fa-clock-o'></i> <i>The booking has been deleted successfully</i>",
						color : "#659265",
						iconSmall : "fa fa-check fa-2x fadeInRight animated",
						timeout : 4000
					});

				} );

			}

			$('body').on('click', 'a.delete-event-link', function(e) {

				var t = $(this);

				$(t).closest(".event").css("opacity", "0.2");

				$.SmartMessageBox({

					title : "Delete your booking?",
					content : "Please confirm that you are would like to delete your booking...",
					buttons : '[No][Yes]'

				}, function(ButtonPressed) {

					if (ButtonPressed === "Yes") {

						var classes = $(t).closest(".event").attr("class").split(' ');
						var id = 0;

						// Process clicking on the cross
						arr = jQuery.grep(classes, function( n, i ) {
							var class_name = n.indexOf('event-id-');
							if ( class_name >=0 ) {
								id = n.replace('event-id-', '');
								delete_event( id );
							}
						});

					} else {
						$(t).parent().css("opacity", "1");
					}

				});

				e.preventDefault();

			});

			/* hide default buttons */
			$('.fc-header-right, .fc-header-center').hide();


			$('#calendar-buttons #btn-prev').click(function () {
				$('.fc-button-prev').click();
				return false;
			});

			$('#calendar-buttons #btn-next').click(function () {
				$('.fc-button-next').click();
				return false;
			});

			$('#calendar-buttons #btn-today').click(function () {
				$('.fc-button-today').click();
				return false;
			});

		});

</script>