jQuery(function () {
    var visibilityObj = $("#visibility option:selected");
    onVisibility(visibilityObj);
    $('#visibility').change(function (e) {
        e.preventDefault()
        onVisibility(this);
    });

    function onVisibility(obj) {
        var ref = $(obj)
        var ref_val = ref.val();
        //1 - public
        //2 - Registered Users
        //3 - Groups
        //4 - Departments
        if (ref_val == "" || ref_val == '1' || ref_val == '2') {
            $('#group_dep_DIV').hide()
            $('#group_DIV').hide()
            $('#dep_DIV').hide()
            //remove "required" from deps_selection
            //remove "required" from group_selection
            $('#group_selection').hide().prop("required", false);
            $('#deps_selection').hide().prop("required", false);
        } else {
            $('#group_dep_DIV').show()
            var ref_txt = $("#visibility option:selected").text();
            $(".group_dep_label").html("Select " + ref_txt)
            if (ref_val == '3') {
                $('#group_DIV').show()
                $('#dep_DIV').hide()
                //add "required" to group_selection
                //remove "required" from deps_selection
                $('#group_selection').show().prop("required", true);
                $('#deps_selection').hide().prop("required", false);
            } else if (ref_val == '4') {
                $('#group_DIV').hide()
                $('#dep_DIV').show()
                //add "required" to deps_selection
                //remove "required" from group_selection
                $('#group_selection').hide().prop("required", false);
                $('#deps_selection').show().prop("required", true);
            } else {
                $('#group_DIV').hide()
                $('#dep_DIV').hide()
                //remove "required" from deps_selection
                //remove "required" from group_selection
                $('#group_selection').hide().attr("required", false);
                $('#deps_selection').hide().attr("required", false);
            }
        }

    }

    // create the form editor
    var fbEditor = $(document.getElementById('fb-editor'))
    var formBuilder
    var fbOptions = {
        dataType: 'json',
        formData: window._form_builder_content ? window._form_builder_content : '',
        controlOrder: [
            'header',
            'paragraph',
            'text',
            'textarea',
            'select',
            'number',
            'date',
            'autocomplete',
            'file',
        ],
        disableFields: [
            'button', // buttons are not needed since we are the one handling the submission
        ],  // field types that should not be shown
        disabledAttrs: [
            // 'access',
        ],
        typeUserDisabledAttrs: {
            'file': [
                'multiple',
                'subtype',
            ],
            'checkbox-group': [
                'other',
            ],
        },
        showActionButtons: false, // show the actions buttons at the bottom
        disabledActionButtons: ['data'], // get rid of the 'getData' button
        sortableControls: false, // allow users to re-order the controls to their liking
        editOnAdd: false,
        fieldRemoveWarn: false,
        roles: {},
        notify: {
            error: function (message) {
                return message;//swal('Error', message, 'error')
            },
            success: function (message) {
                return message;//swal('Success', message, 'success')
            },
            warning: function (message) {
                return message;//swal('Warning', message, 'warning');
            }
        },
        onSave: function () {
            // var formData = formBuilder.formData
            // console.log(formData)
        },
    }

    formBuilder = fbEditor.formBuilder(fbOptions)

    var fbClearBtn = $('.fb-clear-btn')
    var fbShowDataBtn = $('.fb-showdata-btn')
    var fbSaveBtn = $('.fb-save-btn')

    // setup the buttons to respond to save and clear
    fbClearBtn.click(function (e) {
        e.preventDefault()

        if (!formBuilder.actions.getData().length) return

        alertify.confirm("Confirm", "Are you sure you want to clear all fields from the form?", function () {
            formBuilder.actions.clearFields();
            alertify.success('Cleared')
        }, function () { alertify.error('Canceled!') })
    });

    fbShowDataBtn.click(function (e) {
        e.preventDefault()
        formBuilder.actions.showData()
    });

    fbSaveBtn.click(function (e) {
        e.preventDefault()

        var form = document.getElementById("createFormForm");
        var pristine = new Pristine(form);
        // form validation check
        if (!pristine.validate()) {
            console.log("error: validate")
            return false;
        }

        // make sure the form builder is not empty
        if (!formBuilder.actions.getData().length) {
            alertify.errorMsg("The form builder cannot be empty");
            return false;
        }
        //console.log("success")
        // ask for confirmation

        alertify.confirm("Confirm", "Save this form definition?", function () {
            alertify.success('Ok start saving :) ')

            fbSaveBtn.attr('disabled', 'disabled');
            fbClearBtn.attr('disabled', 'disabled');


            var formBuilderJSONData = formBuilder.actions.getData('json')
            $("#formbuilder_json").val(formBuilderJSONData)
            console.log($(form).attr('action'))
            $(form).submit()

        }, function () { alertify.error('Canceled!') })
    });

    // show the clear and save buttons
    //$('#fb-editor-footer').slideDown()
    $(".delete_form_btn").click(function (e) {
        e.preventDefault();
        alertify.confirm("Confirm", "Are you sure you want to delete this form?", function () {
            $(".delete_form").submit();
            alertify.success('Deleted')
        }, function () { alertify.error('Canceled!') })
    });

    //form status
    $(".form-status-options-btn").click(function (e) {
        var old_status = $("#form_status").val()
        var new_status = $(this).val();
        if (old_status != new_status) {
            alertify.confirm("Confirm", "Are you sure you want to change form status?", function () {
                $("#form_status").val(new_status)
                $("#form_status_frm").submit();
                alertify.success('Status changed')
            }, function () {
                $(".form-status-options-btn").parent().addClass("active");
                alertify.error('Canceled!');
                location.reload();
            })

        }
        //console.log($(this).val())
    })

    //form set user managers
    $(".form_managers_btn").click(function (e) {
        e.preventDefault();
        var original_managers = $("#original_managers").val();
        var new_managers = $(".form-managers-select").val();
        //console.log("original: " + original_managers)
        //console.log("new: " + new_managers)
        if (original_managers != new_managers) {
            alertify.confirm("Confirm", "Are you sure you want to change form managers?", function () {
                $("#form_managers_users").submit();
                alertify.success('Managers changed')
            }, function () {
                alertify.error('Canceled!');
            })
        } else {
            alertify.warning('No changes!');
        }
    })

    //form change form allow edit
    $("#form_allows_edit_opt").change(function (e) {
        var old_allows_edit = $("#form_allows_edit").val()
        var new_allows_edit = $(this).val();
        if (old_allows_edit != new_allows_edit) {
            alertify.confirm("Confirm", "Are you sure you want to change allow edit option?", function () {
                $("#form_allows_edit").val(new_allows_edit)
                $("#form_allows_edit_frm").submit();
                alertify.success('Status changed')
            }, function () {
                $("#form_allows_edit_opt").val(old_allows_edit).change();
                //$('#form_allows_edit_opt option[value="' + old_allows_edit + '"]').attr('selected', 'selected');
                alertify.error('Canceled!');
            })

        } else {
            alertify.warning('No changes!');
        }
    })

    $("#back_to_form_btn").click(function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        if (!formBuilder.actions.getData().length) {
            window.location.href = link;
            return false;
        }
        alertify.confirm("Confirm", "Are you sure you want to return to forms", function () {
            window.location.href = link;
            alertify.success('OK!')
        }, function () { alertify.error('Canceled!') })
    });
})
