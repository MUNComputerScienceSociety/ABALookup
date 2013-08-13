(function (window, undefined) {
	var document = window.document;
	var jQuery = window.jQuery;
	var hspOpts = {
		show: false, // Passwords are hidden by default
		innerToggle: true, // Create an inner toggle
		hideToggleUntil: false, // Show the toggle immediately
		touchSupport: Modernizr.touch, // Support touch events without losing focus
		toggleEvent: 'click', // Events to use when touch support is false
		toggleTouchEvent: 'touchstart mousedown', // Events to use when touch support is true
		wrapperClass: 'hsp-wrapper', // Class name for wrapper element
		toggleClass: 'hsp-toggle', // Class name for the inner toggle
		states: {
			// These settings are applied when the password text is visible (show: true)
			shown: {
				inputClass: 'hsp-shown', // Class name to apply to the input element
				eventName: 'passwordShown', // Event to trigger on the input
				toggleClass: 'hsp-toggle-hide', // Elass name to apply to the toggle
				toggleText: 'Hide', // Text of the toggle element
				// Property values to apply to the input element
				attr: {
					'type': 'text',
					'autocapitalize': 'off',
					'autocomplete': 'off',
					'autocorrect': 'off',
					'spellcheck': 'false'
				}
			},
			// Settings when text is hidden (show: false)
			hidden: {
				inputClass: 'hsp-hidden',
				eventName: 'passwordHidden',
				toggleClass: 'hsp-toggle-show',
				toggleText: 'Show',
				attr: {
					'type': 'password'
				}
			}
		},
		widthMethod: 'outerWidth',
		heightMethod: 'outerHeight'
	};
	var elements = [
		'password',
		'confirm-password',
		'old-password',
		'new-password',
		'confirm-new-password'
	];
	jQuery.each(elements, function (i, v) {
		jQuery('#' + v).hideShowPassword(hspOpts);
	});
}(window));
