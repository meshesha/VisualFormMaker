@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div id="org_tree_editor"></div>
        </div>
        <script type="text/javascript">
            var org_tree_data = {!! $tree_array !!};
            //console.log(org_tree_data)
            var orgChart = $('#org_tree_editor').orgChart({
                data: org_tree_data,
                showControls: true,
                allowEdit: true,
                newNodeText: 'new',
                nameFontSize: "10px",
                onAddNode: function (node) {
                    //newOrUpdateDep("new", node.data.id, "", "", "");
                    var url = '{{ route("org.edit", ":id") }}';
                    url = url.replace(':id', node.data.id);
                    location.href = url;
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
                            console.log("OK");
                        },
                        function () {
                            //Cancel
                            console.log("Cancel");

                        }
                    );
                },
                onClickNode: function (node) {
                    //org_tree_chart.getData()
                    console.log(node.data)
                    var url = '{{ route("org.edit", ":id") }}';
                    url = url.replace(':id', node.data.id);
                    location.href = url;
                }
            });
        </script>
    </div>
</div>
@endsection

@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
    
        <a href="{{ route('org.index') }}" class="btn btn-sm btn-primary ">
            <i class="fas fa-table"></i> Table View
        </a>
    @endif
@endsection

@section('scripts_head')
	<script src="{{ asset('js/jquery.orgChart/jquery.orgchart.js') }}" ></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('js/jquery.orgChart/jquery.orgchart.css') }}" />
    
@endsection
