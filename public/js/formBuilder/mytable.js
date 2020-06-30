function edit_form_file() {
    $("#builder-editor").html("");
    var frm_id = $("#form_id").val();
    $("#form_content_id").val(frm_id);
    var form_content = get_form_content(frm_id);

    if (form_content != undefined && form_content != null && form_content != "new" && form_content != "") {
        var form_content_obj = JSON.parse(form_content);
        if (form_content_obj.length > 0) {
            form_content_obj.forEach(function (item, index) {
                if (item.label !== undefined) { //item.type == "paragraph" || item.type == "header" //
                    item.label = item.label.replace(/&quot;/g, "\"");
                    item.label = item.label.replace(/&apos;/g, "'");
                }
                if (item.description !== undefined) {
                    item.description = item.description.replace(/&quot;/g, "\"");
                    item.description = item.description.replace(/&apos;/g, "'");
                }
                if (item.type != "table" && item.placeholder !== undefined) {
                    item.placeholder = item.placeholder.replace(/&quot;/g, "\"");
                    item.placeholder = item.placeholder.replace(/&apos;/g, "'");
                }
                if (item.type != "hidden" && item.value !== undefined) {
                    item.value = item.value.replace(/&quot;/g, "\"");
                    item.value = item.value.replace(/&apos;/g, "'");
                }

                if (item.type == "select" || item.type == "checkbox-group" || item.type == "radio-group") {
                    if (item.values !== undefined && item.values.length > 0) {
                        item.values.forEach(function (item2, index) {
                            item2.label = item2.label.replace(/&quot;/g, "\"");
                            item2.label = item2.label.replace(/&apos;/g, "'");
                        });
                    }
                }

            });
            form_content = JSON.stringify(form_content_obj);
        }
    }
    var $fbEditor = $(document.getElementById('builder-editor'));
    var formBuilder;
    var positionOptions = {
        class: {
            label: 'Position',
            class: "frm-position",
            multiple: false, // optional, omitting generates normal <select>
            options: {
                '': '',
                'form-control-element-right': 'Right',
                'form-control-element-left': 'Left',
                'form-control-element-center': 'Center'
            }/*,
                    onchange: 'console.log(this)'*/
        }
    };
    var maxFileSize = {
        fileSize: {
            label: 'File max size',
            type: 'number',
            min: '0',
            value: '1024'
        },
        sizeUnits: {
            label: 'File size units',
            type: 'select',
            options: {
                'bytes': 'Bytes',
                'kB': 'Kilobyte (kB)',
                'MB': 'Megabyte (MB)'
            }
        }
    };
    var tableBtn = {
        title: {
            label: 'Table Columns',
            type: "button",
            value: "Edit",
            style: "width:80px",
            onclick: "setTableSettings(this)"
        }
    };

    var options = {
        controlPosition: 'left',
        disabledActionButtons: ['data'],
        formData: (form_content == "new") ? "" : form_content,
        dataType: 'json',
        typeUserAttrs: {
            header: positionOptions,
            file: maxFileSize,
            table: tableBtn,
            Buttons: {
                label: {
                    label: 'Container label',
                    type: 'text',
                    value: ''
                },
                submitBtnColor: {
                    label: 'Submit button color',
                    multiple: false, // optional, omitting generates normal <select>
                    options: {
                        '': '',
                        'btn btn-primary': 'blue',
                        'btn btn-secondary': 'gray',
                        'btn btn-success': 'green',
                        'btn btn-danger': 'red',
                        'btn btn-warning': 'yellow',
                        'btn btn-info': 'light blue',
                        'btn btn-light': 'white',
                        'btn btn-dark': 'dark',
                        'btn btn-link': 'link'
                    }/*,
                            onchange: 'console.log(this)'*/
                },
                clearBtnColor: {
                    label: 'Clear button color',
                    multiple: false, // optional, omitting generates normal <select>
                    options: {
                        '': '',
                        'btn btn-primary': 'blue',
                        'btn btn-secondary': 'gray',
                        'btn btn-success': 'green',
                        'btn btn-danger': 'red',
                        'btn btn-warning': 'yellow',
                        'btn btn-info': 'lightblue',
                        'btn btn-light': 'white',
                        'btn btn-dark': 'dark',
                        'btn btn-link': 'link'
                    }/*,
                            onchange: 'console.log(this)'*/
                },
                btnsPos: {
                    label: 'Buttons position',
                    multiple: false, // optional, omitting generates normal <select>
                    options: {
                        '': '',
                        'form-control-buttons-right': 'Right',
                        'form-control-buttons-left': 'Left',
                        'form-control-buttons-center': 'Center'
                    }/*,
                            onchange: 'console.log(this)'*/
                },
                submitLabel: {
                    label: 'Submit button label',
                    type: 'text',
                    value: ''
                },
                cancelLabel: {
                    label: 'Cancel button label',
                    type: 'text',
                    value: ''
                }
            }
        },
        replaceFields: [
            {
                type: "table",
                label: 'Table',
                placeholder: "[{&quot;name&quot;:&quot;Column1&quot;,&quot;type&quot;:&quot;txt&quot;,&quot;attr&quot;:&quot;&quot;},{&quot;name&quot;:&quot;Column2&quot;,&quot;type&quot;:&quot;txt&quot;,&quot;attr&quot;:&quot;&quot;},{&quot;name&quot;:&quot;Column3&quot;,&quot;type&quot;:&quot;txt&quot;,&quot;attr&quot;:&quot;&quot;}]"
            }
        ],
        disableFields: ['autocomplete', 'hidden', 'button', 'Buttons'],
        controlOrder: [
            'header',
            'text',
            'textarea'
        ],
        disabledAttrs: [
            'access'
        ],
        disabledSubtypes: {
            file: ['fineuploader'],
            textarea: ['quill']
        },
        disabledFieldButtons: {
            table: ['copy'], // disables the copy button for table fields
            Buttons: ['copy', 'remove'],
            hidden: ['copy', 'remove']
        },
        stickyControls: {
            enable: true
        },
        scrollToFieldOnAdd: true,
        typeUserEvents: {
            header: {
                onadd: function (fld) {
                    var orginVal;
                    $('.frm-position', fld).on('focus', function () {
                        orginVal = this.value;
                    }).change(function (e) {
                        var calssVal = $(".fld-className", fld).val();
                        if (calssVal.indexOf(" ") > -1) {
                            var calssAry = calssVal.split(" ");
                            if (calssAry.indexOf(orginVal) > -1) {
                                calssAry[calssAry.indexOf(orginVal)] = e.target.value;
                                var newclass = calssAry.join(" ");
                                $(".fld-className", fld).val(newclass)
                            } else {
                                calssAry.push(e.target.value);
                                var newclass = calssAry.join(" ");
                                $(".fld-className", fld).val(newclass)
                            }
                        } else {
                            $(".fld-className", fld).val(e.target.value)
                        }
                    });
                }
            },
            table: {
                onadd: function (fld) {
                    var $patternField = $(".fld-placeholder", fld);
                    var $patternWrap = $patternField.parents(".placeholder-wrap:eq(0)");
                    $patternWrap.hide();
                }
            },
            Buttons: {
                onadd: function (fld) {
                    var $patternField = $(".fld-value", fld);
                    var $patternWrap = $patternField.parents(".value-wrap:eq(0)");
                    $patternWrap.hide();
                    var $patternField = $(".fld-required", fld);
                    var $patternWrap = $patternField.parents(".required-wrap:eq(0)");
                    $patternWrap.hide();
                    var $patternField = $(".fld-placeholder", fld);
                    var $patternWrap = $patternField.parents(".placeholder-wrap:eq(0)");
                    $patternWrap.hide();
                    /*
                    var $patternField = $(".fld-name", fld);
                    var $patternWrap = $patternField.parents(".name-wrap:eq(0)");
                    $patternWrap.hide();
                    */
                }
            },
            hidden: {
                onadd: function (fld) {
                    var $valueField = $(".fld-value", fld);
                    $valueField.attr("readonly", true);
                    var $nameField = $(".fld-name", fld);
                    $nameField.attr("readonly", true);
                }
            }

        },
        actionButtons: [{
            id: 'preview_form',
            className: 'btn btn-success',
            label: 'Preview',
            type: 'button',
            events: {
                click: function () {
                    var data = formBuilder.actions.getData('json', true);
                    formBuilder.actions.removeField("button-submit-form");
                    previewForm(frm_id, data);
                }
            }
        }, {
            id: 'custom_form_stle_btn',
            className: 'btn btn-warning',
            label: 'Style',
            type: 'button',
            events: {
                click: function () {
                    edit_custom_style();
                }
            }
        }, {
            id: 'close_form',
            className: 'btn btn-danger',
            label: 'Close',
            type: 'button',
            events: {
                click: function () {
                    if (confirm("Are you sure you want to close form editor?")) {
                        formbuilder_content_dialog.dialog("close");
                    }
                }
            }
        }],
        onSave: function (e, formData) {
            try {
                var formDataObj = JSON.parse(formData);
                if (formDataObj.length > 0) {
                    formDataObj.forEach(function (item, index) {
                        if (item.label !== undefined) { //item.type == "paragraph" || item.type == "header" //
                            item.label = item.label.replace(/"/g, "&quot;");
                            item.label = item.label.replace(/'/g, "&apos;");
                        }
                        if (item.description !== undefined) {
                            item.description = item.description.replace(/"/g, "&quot;");
                            item.description = item.description.replace(/'/g, "&apos;");
                        }
                        if (item.type != "table" && item.placeholder !== undefined) {
                            item.placeholder = item.placeholder.replace(/"/g, "&quot;");
                            item.placeholder = item.placeholder.replace(/'/g, "&apos;");
                        }
                        if (item.type != "hidden" && item.value !== undefined) {
                            item.value = "";
                        }

                        if (item.type == "select" || item.type == "checkbox-group" || item.type == "radio-group") {
                            if (item.values !== undefined && item.values.length > 0) {
                                item.values.forEach(function (item2, index) {
                                    item2.label = item2.label.replace(/"/g, "&quot;");
                                    item2.label = item2.label.replace(/'/g, "&apos;");
                                });
                            }
                        }

                    });
                    formData = JSON.stringify(formDataObj);
                }
                //console.log(formData)
                formContentJsonObj = formData;
                setFormJsonObj(formbuilder_content_dialog);
            } catch (err) {
                console.log(err.message);
            }
        },
        onOpenFieldEdit: function (editPanel) {
            //console.log()
            //console.log($(editPanel).parent()[0].type)
            if ($(editPanel).parent()[0].type == "table") {
                $($($($(editPanel)[0].children[0]).find(".title-wrap")[0]).find(".input-wrap")[0].children[0]).click();
            }
        },
    };
    if (form_content == "new") {
        $("#form_content_status").val("new");
        formBuilder = $fbEditor.formBuilder(options);
    } else {
        $("#form_content_status").val("");
        formBuilder = $fbEditor.formBuilder(options);
    }
    //formContentJsonObj = "";

    formbuilder_content_dialog.dialog("open")
}

function previewForm(frm_id, data) {
    console.log(data)
    // Store
    var dataName = "formpreview-" + frm_id;
    localStorage.setItem(dataName, data);
    var previewLink = "formpreview.php?id=" + frm_id;
    window.open(previewLink, 'formPreview', 'height=480,width=640,toolbar=no,scrollbars=yes');
}
