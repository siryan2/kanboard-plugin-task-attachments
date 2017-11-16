/**
 * Handle add/remove attachments
 *
 * @package Kanboard\Plugins\TaskAttachments
 * @author Yannick Herzog <info@hit-services.net>
 */

(function($) {
	var settings = {
		'filename': '.js-filename',
		'filenameContainer': '.js-filename-container',
		'fileInput': '.js-input-file',
		'fileInputId': '#inputFile',
		'removeFile': '.js-btn-remove-file',
		'toggleClass': 'is-hidden'
	};

	function init() {
		registerEvents();
	}

	function changeFilenameContainer(filename) {
		$(settings.filename).text(filename);
	}

	function getFilename(file) {
		return file.name;
	}

	function hideFilenameContainer() {
		$(settings.filenameContainer).addClass(settings.toggleClass);
	}

	function registerEvents() {
		$(document).on('change', settings.fileInput, function(e) {
			var $self = $(e.currentTarget);
			var selectedFile = $self[0].files[0];
			var filename = getFilename(selectedFile);

			changeFilenameContainer(filename);
			showFilenameContainer();
		});

		$(document).on('click', settings.removeFile, function(e) {
			var $self = $(e.currentTarget);
			var $fileInput = $(settings.fileInputId);

			removeFile($fileInput);
			changeFilenameContainer('');
			hideFilenameContainer();
		});
	}

	function removeFile($fileInput) {
		$fileInput.val('');
	}

	function showFilenameContainer() {
		$(settings.filenameContainer).removeClass(settings.toggleClass);
	}

	init();

})(jQuery);
