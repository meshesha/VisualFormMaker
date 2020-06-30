jQuery(function () {
    $('.confirm-del-recorf').click(function (e) {
        e.preventDefault()

        var recordId = $(this).data("rid");
        //console.log(recordId)
        alertify.confirm("Confirm", "Are you sure you want to delete this record?", function () {
            alertify.success('deleted!')

            $('.confirm-del-recorf').attr('disabled', 'disabled');

            $("#deleteSubmissionForm_" + recordId).submit()

        }, function () { alertify.error('Canceled!') })

    });

})