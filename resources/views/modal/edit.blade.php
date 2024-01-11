<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title p-2" id="editlabel">{{$heading}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
            </div>
            <div class="modal-body" id="modal_edit_body">

            </div>
            <div class="modal-footer">
                <div class="row col-md-12">
                    <div class="col-md-4">
                        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <div class="col-md-8 text-right">
                        <button id="delete" type="button" class="btn btn-primary">Delete</button>
                        <button id="save" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>