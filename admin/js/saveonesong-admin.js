(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );


/**
 * The main Vue instance for our plugin settings page
 * @link https://vuejs.org/v2/guide/instance.html
 */
new Vue( {

	// DOM selector for our app's main wrapper element
	el: '#app',

	// Data that will be proxied by Vue.js to provide reactivity to our template
	data: {
		isSaving: false,
		message: '',
		vm: SOS_Data.options
	},

	// Methods that can be invoked from within our template
	methods: {

		// Save the options to the database
		saveOptions: function() {

			// set the state so that another save cannot happen while processing
			this.isSaving = true;

			// Make a POST request to the REST API route that we registered in our PHP file
			jQuery.ajax( {

				url: SOS_Data.siteUrl + '/wp-json/sos/v1/save',
				method: 'POST',
				data: this.vm,

				// set the nonce in the request header
				beforeSend: function( request ) {
					request.setRequestHeader( 'X-WP-Nonce', SOS_Data.nonce );
				},

				// callback to run upon successful completion of our request
				success: () => {

					this.message = 'Options saved';
					setTimeout( () => this.message = '', 1000 );
				},

				// callback to run if our request caused an error
				error: ( data ) => this.message = data.responseText,

				// when our request is complete (successful or not), reset the state to indicate we are no longer saving
				complete: () => this.isSaving = false,
			});
		}, // end: saveOptions
	}, // end: methods
}); // end: Vue()