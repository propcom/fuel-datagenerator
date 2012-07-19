$(function() {
	$('select').live('change', function() {
		var $this = $(this),
			row = $this.parents('tr');

		var extra_data = {};
		row.find('[name*="[extra]"]').each(function() {
			var name = $(this).attr('name');
			var val = $(this).val();

			name = name.match(/\[([^\]]+)\]/).pop();

			extra_data[name] = val;
		});

		var data = {
			name: row.find('[name$="[name]"]').val(),
			type: row.find('[name$="[type]"]').val(),
			preset: row.find('[name$="[preset]"]').val(),
			value: row.find('[name$="[value]"]').val(),
			extra: extra_data,
			i: row.find('[name=i]').val()
		};

		$.ajax('/datagenerator/home/tablerow', {
				dataType: 'html',
				data: data,
				type: 'post'
			})
		.success(function(data) {
				row.replaceWith($(data));
			});
	});
});
