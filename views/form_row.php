<tr>
	<td>
		<input name="field[<?=$i?>][name]" type="text" value="<?= isset($name) ? $name : '' ?>"/>
		<input name="i" value="<?=$i?>" type="hidden" />
	</td>
	<td>
		<?= (new \Fieldset_Field("field[$i][type]", '', [
			'type' => 'select',
			'value' => isset($type) ? $type : null,
			'options' => $type_options
		], [], $fs))
		->set_template('{field}')?>
	</td>
	<td>
		<? if (isset($preset_options)): ?>
			<? // if preset options are set the preset should at least be null ?>
			<?= (new \Fieldset_Field("field[$i][preset]", '', [
				'type' => 'select',
				'value' => $preset,
				'options' => [ '' => '-- ( Presets ) --' ] + $preset_options
			], [], $fs))
			->set_template('{field}')?>
		<? else: ?>
			&nbsp;
		<? endif ?>
	</td>
	<td>
		<input type="text" name="field[<?= $i ?>][value]" value="<?= $value ?>"/>
	</td>
</tr>

