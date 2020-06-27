var org_dep_dialog,
    org_tree_chart;


org_dep_dialog = $("#org_dep_win").dialog({
    modal: true,
    autoOpen: false,
    /*dialogClass: "formbuilder_dialg_win",*/
    width: 0.5 * $(window).width(),
    height: 0.7 * $(window).height(),
    buttons: [
        {
            text: "Cancel",
            class: "btn btn-primary btn-lg",
            click: function () {
                $(this).dialog("close");
            }
        },
        {
            text: "Save",
            class: "btn btn-primary btn-lg",
            click: function () {
                let new_node_name = $("#dep_name").val();
                let depId = $("#orgtree_dep_id").val();
                let actionType = $("#org_tree_action_type").val();
                let parentId;
                if ($("#parent_dep_id").val() != "") {
                    parentId = $("#parent_dep_id").val();
                } else {
                    parentId = $("#parent_dep_list").val();
                }
                //let slctedUsrs = $("#selected-users").val();
                let selectedUsers = [];
                $('#selected-users option').each(function () {
                    //selected[$(this).val()]=$(this).text();
                    selectedUsers.push($(this).val());
                });

                let availableUsers = [];
                $('#available-users option').each(function () {
                    //selected[$(this).val()]=$(this).text();
                    availableUsers.push($(this).val());
                });

                let depMngr = $("#dep_manager").val();

                //console.log(availableUsers);

                var dep_data = {
                    record_id: depId,
                    dep_name: new_node_name,
                    dep_prnt_id: parentId,
                    dep_manager: depMngr,
                    selected_users: JSON.stringify(selectedUsers),
                    available_users: JSON.stringify(availableUsers)
                };
                var tbl = "org_tree";
                //console.log(usr_data)
                ajaxAction(actionType, tbl, dep_data, org_dep_dialog);

            }
        }

    ]/*,
        close: function( event, ui ) {
            isFullMode = false;
        }*/
});


function loadUsersGroupsTable(tbl) {
    var tbl_columns_names;
    var colDefs = [{
        "targets": [0, -1],
        "searchable": false,
        "orderable": false
    }];
    var btns = [];
    if (tbl == "org") {
        $('#org_tree_content').show();
        $('#users_groups_data_table').hide();
        var org_tree_data = getOrgTree();
        //console.log(org_tree_data)
        if (org_tree_data != "") {
            var org_obj = JSON.parse(org_tree_data);
            org_tree_chart = $('#org_tree_content').orgChart({
                data: org_obj,
                showControls: true,
                allowEdit: true,
                newNodeText: '',
                nameFontSize: "10px",
                onAddNode: function (node) {
                    newOrUpdateDep("new", node.data.id, "", "", "");
                },
                onDeleteNode: function (node) {
                    //log('Deleted node ' + node.data.id);
                    let beforeDel = org_tree_chart.getData();
                    org_tree_chart.deleteNode(node.data.id);
                    let afterDel = org_tree_chart.getData();
                    let deletedNodes = $(beforeDel).not(afterDel).get();
                    let checkAry = ["1"];
                    $.each(afterDel, function (i, inode) {
                        if (inode.parent != "0") {
                            if (checkAry.indexOf(inode.parent) != -1) {
                                checkAry.push(inode.id)
                            } else {
                                let isFound = false;
                                $.each(deletedNodes, function (y, ynode) {
                                    if (inode.id == ynode.id) {
                                        isFound = true;
                                    }
                                });
                                if (!isFound) {
                                    deletedNodes.push(inode)
                                }
                            }
                        }
                    })
                    //console.log(beforeDel,afterDel,JSON.stringify(deletedNodes))

                    alertify.confirm("Are you sure you want to delete departments?",
                        function () {
                            //OK
                            //console.log("OK");
                            var org_data = {
                                data: JSON.stringify(deletedNodes)
                            }

                            ajaxAction("delete", "org_tree", org_data);
                        },
                        function () {
                            //Cancel
                            //console.log("Cancel");
                            alertify.error('Cancel');
                            $('#users_groups_tree_content').jstree(true).refresh();
                            $('#users_groups_tree_content').jstree('select_node', "#org", true);
                            /*
                            $.each(deletedNodes,function(k,node){
                                org_tree_chart.addNode(node)
                            });
                            */

                        }
                    );
                },
                onClickNode: function (node) {
                    //org_tree_chart.getData()
                    //console.log(node.data)
                    newOrUpdateDep("update", node.data.parent, node.data.id, node.data.name, node.data.mngr);
                }

            });
        }
    } else {
        return false;
    }

}
function newOrUpdateDep(action, parentId, depId, depName, managerId) {
    $("#org_tree_action_type").val(action);
    let title = "";
    if (action == "update") {
        title = "Update Organization Department";
    } else if (action == "new") {
        title = "New Organization Department";
    }
    if (parentId != "") {
        $(".parent-row").hide();
        $("#parent_dep_id").val(parentId);
    } else {
        $(".parent-row").show();
        $("#parent_dep_id").val("");
        //get dep list -> #parent_dep_list
        setDepParentOptionList("", "", false);
    }
    $("#orgtree_dep_id").val(depId);
    $("#dep_name").val(depName);

    //$("#dep_manager").val("");
    setDepAdminOptionList([managerId], depId, false); //selectedAry,readonly
    //get users
    getUserOptinLists(depId);
    org_dep_dialog.dialog("option", "title", title);
    org_dep_dialog.dialog("open");
}
function getOrgTree() {
    data = "";
    $.ajax({
        type: "POST",
        url: "get_org_data.php",
        data: {
            data_type: "all"
        },
        async: false,
        success: function (response) {
            data = response;
        },
        error: function (response) {
            console.log("Error:", JSON.stringify(response));
            alert(response.responseText)
        }
    });
    return data;
}
function delSingleDepFromOrg(dep_id) {
    alertify.confirm("Are you sure you want to delete departments?",
        function () {
            //OK
            $.ajax({
                type: "POST",
                url: "del_single_dep_from_org.php",
                data: {
                    depToDel: dep_id
                },
                success: function (response) {
                    console.log(response);
                    if (response != "") {
                        if (response.indexOf("|") != -1) {
                            var dataAry = response.split("|");
                            if (dataAry[0] == "1") {
                                alertify.success('success');
                                loadUsersGroupsTable("org_table");
                            } else if (dataAry[0] == "2") {
                                alertify.warning(dataAry[1]);
                                loadUsersGroupsTable("org_table");
                            } else {
                                alertify.error('error: ' + dataAry[1]);
                            }
                        } else {
                            alertify.error('error: unknown error: ' + response);
                        }
                    } else {
                        alertify.error('error: empty response!');
                    }
                },
                error: function (response) {
                    console.log("Error:", JSON.stringify(response));
                    alert(response.responseText)
                }
            });
        },
        function () {
            //Cancel
            //console.log("Cancel");
            alertify.error('Cancel');


        }
    );
}
function getUserOptinLists(dep_id) {
    data = "";
    $.ajax({
        type: "POST",
        url: "get_all_users.php",
        data: {
            selectType: "users",
            selecDepId: dep_id
        },
        /*async:false,*/
        success: function (response) {
            if (response != "") {
                $("#available-users").html("");
                $("#selected-users").html("");
                var dataObj = JSON.parse(response);
                //console.log(dataObj)
                $("#available-users").html(dataObj.available);
                $("#selected-users").html(dataObj.selected);

            }

        },
        error: function (response) {
            console.log("Error:", JSON.stringify(response));
            alert(response.responseText)
        }
    });
    //return data;
}

function setDepAdminOptionList(selectedAry, depId, readonly) {
    var isDisabled = false;
    if (readonly !== undefined && readonly == "true") {
        isDisabled = true;
    }
    $('#dep_manager').empty();
    $('#dep_manager').select2({
        disabled: isDisabled,
        ajax: {
            url: 'get_all_users.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term, // search term
                    selectType: "admins",
                    selecDepId: depId
                };
            },
            processResults: function (response) {
                return {
                    results: response.results
                };
            },
            cache: false
        }
    });
    if (selectedAry === undefined || selectedAry == "") {
        return;
    }
    $multiSelectDepMngrs = $('#dep_manager');
    $multiSelectDepMngrs.val(null).trigger('change');
    $.ajax({
        type: 'POST',
        url: 'get_all_users.php',
        data: {
            selectType: "admins",
            selecDepId: depId
        }
    }).then(function (data) {
        //console.log(data)
        var selectObj = JSON.parse(data);
        var selectObjAry = selectObj.results;
        $.each(selectObjAry, function (i, val) {
            if (selectedAry.indexOf(val.id) != -1) {
                var option = new Option(val.text, val.id, true, true);
                $multiSelectDepMngrs.append(option).trigger('change');
            }
        });
        // manually trigger the 'select2:select' event
        $multiSelectDepMngrs.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
}
////////////////////Dep parent list ///////////////////

function setDepParentOptionList(selectedAry, depId, readonly) {
    var isDisabled = false;
    if (readonly !== undefined && readonly == "true") {
        isDisabled = true;
    }
    $('#parent_dep_list').empty();
    $('#parent_dep_list').select2({
        disabled: isDisabled,
        ajax: {
            url: 'get_all_deps.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term, // search term
                    /*selectType: "admins",
                    selecDepId: depId*/
                };
            },
            processResults: function (response) {
                return {
                    results: response.results
                };
            },
            cache: false
        }
    });
    if (selectedAry === undefined || selectedAry == "") {
        return;
    }
    $multiSelectDepPrnt = $('#parent_dep_list');
    $multiSelectDepPrnt.val(null).trigger('change');
    $.ajax({
        type: 'POST',
        url: 'get_all_deps.php',
        data: {
            selectType: "admins",
            selecDepId: depId
        }
    }).then(function (data) {
        //console.log(data)
        var selectObj = JSON.parse(data);
        var selectObjAry = selectObj.results;
        $.each(selectObjAry, function (i, val) {
            if (selectedAry.indexOf(val.id) != -1) {
                var option = new Option(val.text, val.id, true, true);
                $multiSelectDepPrnt.append(option).trigger('change');
            }
        });
        // manually trigger the 'select2:select' event
        $multiSelectDepPrnt.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
}
///////////////////Users///////////////////////////



