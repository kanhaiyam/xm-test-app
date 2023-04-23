$( function() {
    var dateFormat = "mm/dd/yy",
    s_date = $( "#s_date" )
    .datepicker({
        defaultDate: "-1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1
    })
    .on( "change", function() {
        e_date.datepicker( "option", "minDate", getDate( this ) );
    }),
    e_date = $( "#e_date" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1
    })
    .on( "change", function() {
        s_date.datepicker( "option", "maxDate", getDate( this ) );
    });

    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }
} );

function validateForm()
{
    var status = true;
    var d = new Date;
    var oneDay = 86400000; //86400000 is the number of milliseconds in one day
    var firstStartDate = Date.now() - (oneDay * 365);
    var mailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var sDateMessage = eDateMessage = '';

    //Get all the form input
    var symbol = $('#symbol').val().length;
    var startDate = Date.parse($('#s_date').val());
    var endDate = Date.parse($('#e_date').val());
    var email = $('#email').val();

    //Symbol Validation
    if(! ((symbol >= 1) && (5 >= symbol)) ){
        status = false;
        $('#symbolError > span.error').html('Symbol should be between 1 and 5 characters.');
    }

    //Date Validation
    if (isNaN(startDate)) {
        sDateMessage += "The start date provided is not valid, please enter a valid date.\n";
        status = false;
    } else if(startDate < firstStartDate) {
        sDateMessage += "The start date cannot be less than date a year ago\n";
        status = false;
    } else if(startDate > Date.now()) {
        sDateMessage += "The start date provided cannot be more than today.\n";
        status = false;
    } 

    if (isNaN(endDate)) {
        eDateMessage += "The end date provided is not valid, please enter a valid date.\n";
        status = false;
    } else if(endDate > Date.now()) {
        eDateMessage += "The end date provided cannot be more than today.\n";
        status = false;
    }

    // validate the date range
    var difference = (endDate - startDate) / (oneDay);
    console.log(difference);
    if (difference < 0) {
        sDateMessage += "The start date must come before the end date.\n";
        status = false;
    }
    if (difference < 1) {
        eDateMessage += "The range must be at least a day apart.\n";
        status = false;
    }

    //validate email
    if(null === email.match(mailRegex)){
        status = false;
        $('#emailError > span.error').html('Please use a valid mail.');
    }

    $('#sDateError > span.error').html(sDateMessage);
    $('#eDateError > span.error').html(eDateMessage);

    return status;
}