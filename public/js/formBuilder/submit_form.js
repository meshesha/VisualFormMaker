jQuery(function () {
    //render form
    var rOptions = {
        container: false,
        dataType: 'json',
        formData: window._form_builder_content ? window._form_builder_content : '',
        render: true,
    }
    $('#fb-render').formRender(rOptions);
    //submit form
    $('.submit-form-btn').click(function (e) {
        e.preventDefault()

        var form = document.getElementById("submit-form");
        var pristine = new Pristine(form);
        // form validation check
        if (!pristine.validate()) {
            console.log("error: validate")
            return false;
        }
        //console.log($(form).serializeArray())
        //console.log("success")
        // ask for confirmation


        alertify.confirm("Confirm", "Are you sure you want to submit this form?", function () {
            alertify.success('Ok start submit ')

            $('.submit-form-btn').attr('disabled', 'disabled');
            $(".clear-form-btn").attr('disabled', 'disabled');

            $(form).submit()

        }, function () { alertify.error('Canceled!') })

        /**/

    });


    $(".clear-form-btn").click(function (e) {
        e.preventDefault()

        var form = document.getElementById("submit-form");

        alertify.confirm("Confirm", "Are you sure you want to clear the form?", function () {

            //$('.submit-form-btn').attr('disabled', 'disabled');
            //fbClearBtn.attr('disabled', 'disabled');

            $(form)[0].reset();

        }, function () { alertify.error('Canceled!') })
    });

})