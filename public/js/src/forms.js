(function (window, document, undefined) {
	var hspOpts = {
		show: false, // password is hidden by default
		innerToggle: true, // create an inner toggle
		hideToggleUntil: false, // toggle is immediately visible
		touchSupport: Modernizr.touch, // support touch events without losing focus
		toggleEvent: 'click', // when touch support is false
		toggleTouchEvent: 'touchstart mousedown', // when touch support is true
		wrapperClass: 'hsp-wrapper', // class name for wrapper element
		toggleClass: 'hsp-toggle', // class name for the inner toggle
		states: {
			// these settings are applied when the password text is visible (show: true)
			shown: {
				inputClass: 'hsp-shown', // class name to apply to the input element
				eventName: 'passwordShown', // event to trigger on the input
				toggleClass: 'hsp-toggle-hide', // class name to apply to the toggle
				toggleText: 'Hide', // text of the toggle element
				// property values to apply to the input element
				attr: {
					'type': 'text',
					'autocapitalize': 'off',
					'autocomplete': 'off',
					'autocorrect': 'off',
					'spellcheck': 'false'
				}
			},
			// settings when text is hidden (show: false)
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
	$('#password').hideShowPassword(hspOpts);
	$('#confirm-password').hideShowPassword(hspOpts);
}(window, window.document));
