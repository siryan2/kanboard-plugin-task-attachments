<?php if(empty($task_id)): ?>
	<ul class="add-attachements-list">
		<li class="add-attachements-list__item">
			<input type="file" name="files[]" id="inputFile" class="js-input-file" style="display:none;">

			<label for="inputFile" class="btn btn-blue">
				<i class="fa fa-file m-r"></i>
				<?= t('Attach a document') ?>
			</label>

			<div class="filename-container js-filename-container is-hidden">
				<button type="button" class="btn btn-remove js-btn-remove-file"><i class="fa fa-close"></i></button>

				<span class="js-filename"></span>
			</div>

		</li>
	</ul>
<?php endif; ?>
