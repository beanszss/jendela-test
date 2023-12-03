$(document).ready(function() {
	var dataTable = $('.table').DataTable({
		processing: true,
		serverSide: true,
		pageLength: 10,
		lengthChange: false,
		searching: false,
		info: true,
		ordering: false,
		deferRender: true,
		ajax: {
			'url': "author/ajax",
		},
		responsive: {
			details: {
				type: 'column'
			}
		},
		columnDefs: [{
			className: 'text-center',
			orderable: false,
			targets: [0, 1]
		}],
		drawCallback: function( settings ) {
			$('html, body').animate({
				scrollTop: 0,
			}, 'slow');

			$('[name=page]').val(settings.json.page);
		}
	});

	$('.btn-filter').on('click', function() {
		dataTable.draw();
	});
});
