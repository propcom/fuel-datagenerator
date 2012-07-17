<form class="form-horizontal">
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
			<th>Subtype</th>
			<th>Options/values</th>
		</tr>

		<? for ($i = 0; $i < 5; $i++): ?>
			<tr>
				<td>
					<input name="field[<?=$i?>][name]" type="text" />
				</td>
				<td>
					<select name="field[<?=$i?>][type]">
					</select>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		<? endfor ?>
	</table>

	<div class="well">
		<input type="submit" class="btn btn-primary" value="Generate!" />
	</div>
</form>
