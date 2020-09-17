// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Custom decimal currency max-length validator
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'currencyMaxlength', {
        requirementType: [ 'string', 'integer', 'string' ],
        validateString: function( string, requirement, part ){
            // --------------------------------------------------------------
            var error = null; var str = null;
            part = part || 'number';
            var exploded = string.split('.');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // If validating decimal part
            // --------------------------------------------------------------
            if( 'decimal' == part && exploded.length > 1 ){
                str = exploded[1];
                error = '整数は' +requirement+ '以下で入力してください。';
            }
            // --------------------------------------------------------------
            // Else if validation the whole-number part
            // --------------------------------------------------------------
            else {
                str = exploded[0];
                error = '小数は' +requirement+ '以下で入力してください。';
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Clean the string and validate the length 
            // based on the requirement passed
            // --------------------------------------------------------------
            var stripped = str.replace(/[.,\s]/g, ''); // Remove space and comma dot
            if( stripped.length > requirement ) return $.Deferred().reject( error );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return true; // Otherwise return true
            // --------------------------------------------------------------
        },
        messages: {}
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Custom decimal currency max number/amount validator
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'currencyMax', {
        requirementType: [ 'string', 'integer', 'string' ],
        validateString: function( string, requirement, part ){
            // --------------------------------------------------------------
            var error = null; var str = null;
            part = part || 'number';
            var exploded = string.split('.');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // If validating decimal part
            // --------------------------------------------------------------
            if( 'decimal' == part && exploded.length > 1 ){
                str = exploded[1];
                error = '最大許容小数は' +requirement+ 'です';
            }
            // --------------------------------------------------------------
            // Else if validation the whole-number part
            // --------------------------------------------------------------
            else {
                str = exploded[0];
                error = '最大許容数は' +requirement+ 'です';
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Clean the string and validate the number 
            // based on the requirement passed
            // --------------------------------------------------------------
            var number = io.parseInt( str.replace( /[.,\s]/g, '' )); // Remove space and comma dot
            if( number && number > requirement ) return $.Deferred().reject( error );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return true; // Otherwise return true
            // --------------------------------------------------------------
        },
        messages: {}
    });
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Custom not equal validation
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'notequalto', {
        requirementType: 'string',
        validateString: function( value, element ){
            // --------------------------------------------------------------
            var valid = true;
            var count = 0;
            // --------------------------------------------------------------
            $(element).each(function (index, input) {
                if (value == input.value) count++;
            });
            // --------------------------------------------------------------
            valid = count > 1 ? false : true;
            // --------------------------------------------------------------
            return valid;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------
        messages: {
            en: '* A unique value (no duplication)',
            ja: '※ユニークな値とする(重複不可)',
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Custom both decimal and integer length validator
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'decimalMaxlength', {
        requirementType: [ 'string', 'integer', 'integer' ],
        validateString: function( string, maxNumber, maxDecimal ){
            // --------------------------------------------------------------
            var error = null; var exploded = string.split('.');
            if( _.isUndefined( maxNumber )) maxNumber = 12; 
            if( _.isUndefined( maxDecimal )) maxDecimal = 0;
            // --------------------------------------------------------------
            if( !maxDecimal && maxNumber ) error = '整数は' +maxNumber+ '桁で入力してください。';
            else if( maxDecimal && !maxNumber ) error = '少数は' +maxDecimal+ '桁以下で入力してください。'
            else error = '整数は' +maxNumber+ '桁、少数は' +maxDecimal+ '桁以下で入力してください。';
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            // Validate the integer
            // --------------------------------------------------------------
            if( typeof exploded[0] !== 'undefined' ){
                var number = io.toString( exploded[0].replace( /[.,\s]/g, '' ));
                if( number.length > maxNumber ) return $.Deferred().reject( error );
            } 
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Validate the decimal
            // --------------------------------------------------------------
            if( typeof exploded[1] !== 'undefined' ){
                var decimal = io.toString( exploded[1].replace( /[.,\s]/g, '' ));
                if( decimal.length > maxDecimal ) return $.Deferred().reject( error );
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return true; // Otherwise return true
            // --------------------------------------------------------------
        },
        messages: {}
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Custom trimmed string length validator
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'phoneNumber', {
        requirementType: [ 'string', 'integer' ],
        validateString: function( string, maxLength ){
            // --------------------------------------------------------------
            maxLength = maxLength || 14; 
            var error = '正しい電話番号の形式で入力して下さい。';
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            // Validate the string
            // --------------------------------------------------------------
            var trimmed = io.trim( string );
            if( trimmed.length > maxLength ) return $.Deferred().reject( error );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return true; // Otherwise return true
            // --------------------------------------------------------------
        },
        messages: {}
    });
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Custom date nengau validation
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'dateNengou', {
        requirementType: 'string',
        validateString: function( value, element ){
            // --------------------------------------------------------------
            var valid = false;
            var $form = $('form[data-parsley]');
            var range = {
                1: { name: '昭和', from: 1, to: 64, fromYear: 1926, toYear: 1989 },
                2: { name: '平成', from: 1, to: 31, fromYear: 1989, toYear: 2019 },
                3: { name: '令和', from: 1, to: 50, fromYear: 2019, toYear: 2050 },
            }
            // --------------------------------------------------------------
            // array of nengou and nengou year selector
            element = element.split(',');
            // get value of element ( nengou | nengou year | nengou month | nengou day )
            var nengou = $(element[0]).val();
            var year = $(element[1]).val();
            var month = $(element[2]).val();
            var day = $(element[3]).val();

            // get selected range of nengou
            var selected = range[nengou];
            if(!selected) return valid;
            // --------------------------------------------------------------
            var valid_number = _.inRange(year, selected.from, selected.to+1);
            var valid_year = _.inRange(year, selected.fromYear, selected.toYear+1);
            // check for nengou year validation
            valid = (valid_number || valid_year) ? true : false;
            // --------------------------------------------------------------
            var year_range = _.range(selected.fromYear, selected.toYear);
            let western_year = year_range[year-1];
            if (western_year && month && day) {
                // check date is exist date
                var date = moment(`${day}.${month}.${western_year}`,'DD.MM.YYYY');
                valid = date.isValid();
            }
            console.log(valid);
            if (valid) {
                $form.parsley().reset();
            }
            // --------------------------------------------------------------
            return valid;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------
        messages: {
            en: 'The construction date is wrong.',
            ja: '建築時期 は誤った日付です。',
        }
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Custom min number/amount validator
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'numberMin', {
        requirementType: [ 'string', 'string' ],
        validateString: function( number, minimum ){
            // --------------------------------------------------------------
            // Turn the string into real number/float
            // --------------------------------------------------------------
            var number = numeral( number ).value();
            var error = '最小許容数は' +minimum+ 'です';
            if( number < minimum ) return $.Deferred().reject( error );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return true; // Otherwise return true
            // --------------------------------------------------------------
        },
        messages: {}
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Custom max number/amount validator
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'numberMax', {
        requirementType: [ 'string', 'string' ],
        validateString: function( number, maximum ){
            // --------------------------------------------------------------
            // Turn the string into real number/float
            // --------------------------------------------------------------
            var number = numeral( number ).value();
            var error = '最大許容数は' +maximum+ 'です';
            if( number > maximum ) return $.Deferred().reject( error );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return true; // Otherwise return true
            // --------------------------------------------------------------
        },
        messages: {}
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Custom min percentage amount validator
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'percentageMin', {
        requirementType: [ 'string', 'string' ],
        validateString: function( number, minimum ){
            // --------------------------------------------------------------
            // Turn the string into real number/float
            // --------------------------------------------------------------
            var number = numeral( number ).value();
            var error = '最小値は' +minimum+ '%です';
            if( number < minimum ) return $.Deferred().reject( error );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return true; // Otherwise return true
            // --------------------------------------------------------------
        },
        messages: {}
    });
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Custom max percentage amount validator
    // ----------------------------------------------------------------------
    Parsley.addValidator( 'percentageMax', {
        requirementType: [ 'string', 'string' ],
        validateString: function( number, maximum ){
            // --------------------------------------------------------------
            // Turn the string into real number/float
            // --------------------------------------------------------------
            var number = numeral( number ).value();
            var error = '最大値は' +maximum+ '%です';
            if( number > maximum ) return $.Deferred().reject( error );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return true; // Otherwise return true
            // --------------------------------------------------------------
        },
        messages: {}
    });
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
}(jQuery, _, document, window));
// --------------------------------------------------------------------------