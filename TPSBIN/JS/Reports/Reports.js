$(document).ready(function () {
    // Handler for .ready() called.
    $.ajax({
        url: "XML_DB_Check.php",
        beforeSend: function () {
            $("#check_db").show();
            $("#servers").hide();
        },
        success: function (data) {
            if ($(data).find("PASS").text() == 1) {
                $("#check_db").hide();
                $("#servers").show(); //.fadeIn(400);
                $("#alert_icon").fadeIn(400);
                $("#alert_icon").addClass('ui-icon ui-icon-check');
            }
            else {
                $("#check_db").hide();
                $("#servers").show(); //.fadeIn(400);
                $("#alert_icon").fadeIn(400);
                $("#alert_icon").addClass('ui-icon ui-icon-error');
                $("#dberror_notify").html($(data).find("ERROR").text());
                $("#db-error-dialog").dialog({
                    modal: true,
                    buttons: {
                        Ok: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        },
        statusCode: {
            404: function () {
                alert("DB Check Failed, Error 404 Page Not Found Returned");
            }
        }
    });

    // Load Menu
    $( "#menu" ).menu();
    //$( "#menu" ).menu( "collapseAll", null, true );

});