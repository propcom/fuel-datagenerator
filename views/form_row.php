<tr>
	<td>
		<input name="field[<?=$i?>][name]" type="text" />
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
		<? if (isset($subtype_options)): ?>
			<? // if subtype options are set the subtype should at least be null ?>
			<?= (new \Fieldset_Field("field[$i][subtype]", '', [
				'type' => 'select',
				'value' => $subtype,
				'options' => [ '' => '-- ( Presets ) --' ] + $subtype_options
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

