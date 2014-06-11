<form class="form-horizontal" action="/datagenerator/home/generate" method="get">
	<div class="well">
		<div class="control-group">
			<label class="control-label">Output format</label>
			<div class="controls">
				<label class="radio">
					<input type="radio" name="format" value="json" checked="checked"/> JSON
				</label>
				<label class="radio">
					<input type="radio" name="format" value="sql"/> SQL
				</label>
				<label class="radio">
					<input type="radio" name="format" value="csv"/> CSV
				</label>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="num_records">Generate</label>
			<div class="controls">
				<input name="num_records" id="num_records" type="number" value="100" step="1" min="1"/> records
			</div>
		</div>
	</div>

	<table class="table table-striped table-bordered">
		<tr>
			<th>Field name</th>
			<th>Type</th>
			<th>Preset</th>
			<th>Options/values</th>
		</tr>

		<? for ($i = 0; $i < 5; $i++): ?>
			<?= \View::forge('form_row')->set('i', $i) ?>
		<? endfor ?>
	</table>

	<div class="well">
		<input type="submit" class="btn btn-primary" value="Generate!" />
	</div>
</form>
