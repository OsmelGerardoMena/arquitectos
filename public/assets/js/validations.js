var Validations = (function() {

	/**
	 * Class constructor
	 */
	function Validations() {}

	/**
	 * Limit number of characters
	 *
	 * @return void
	 */
	Validations.prototype.limitCharacters = function(element,size) {

		$(element).on('keyup', function() {
			var before = $(this).val();
			var size_input = $(this).val().length;
            if( size_input > size ){
            	$(element).val( before.substring(0,size_input-1) );
            }

		});
	};

    return Validations;

}());