<div class="col-md-6 col-sm-12">
    <div id="drag-and-drop-zone" class="dm-uploader p-5">
        <h3 class="mb-5 mt-5 text-muted">Drag &amp; drop files here</h3>

        <div class="btn btn-primary btn-block mb-5">
            <span>上傳檔案</span>
            <input type="file" name="upload_files"/>
        </div>
    </div><!-- /uploader -->
</div>
<div class="col-md-6 col-sm-12">
    <div class="card h-100">
        <div class="card-header">檔案</div>
        <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
            @if(isset($file_datas))
                @foreach($file_datas as $key => $val)
                <li class="media" id="uploaderFile{{ @$val["file_id"] }}">
                    <div class="media-body mb-1">
                        <p class="mb-2">
                            <strong>{{ @$val["name"] }}</strong>
                            <input type="hidden" id="file_id_{{ @$val["file_id"] }}" name="file_id[]" value="{{ @$val["file_id"] }}" />
                            <i class="fas fa-trash-alt tm-trash-icon" onclick="deleteFile('{{ @$val["file_id"] }}','edit');"></i>
                        </p>
                        <hr class="mt-1 mb-1" />
                    </div>
                </li>
                @endforeach
            @endif
        </ul>
    </div>
</div><!-- /file list -->
<div class="col-12" style="display:none;">
    <div class="card h-100">
        <div class="card-header">
            Debug Messages
        </div>

        <ul class="list-group list-group-flush" id="debug">
            <li class="list-group-item text-muted empty">Loading plugin....</li>
        </ul>
    </div>
</div> <!-- /debug -->

<!-- File item template -->
<script type="text/html" id="files-template">
    <li class="media">
        <div class="media-body mb-1">
            <p class="mb-2">
                <strong>%%file_name%%</strong> - 狀態: <span class="text-muted">等待</span>
                <input type="hidden" id="file_id_%%file_id%%" name="file_id[]" value="%%file_id%%" />
                <i class="fas fa-trash-alt tm-trash-icon" onclick="deleteFile('%%file_id%%','add');"></i>
            </p>
            <div class="progress mb-2" style="display:none;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                    role="progressbar"
                    style="width: 0%" 
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <hr class="mt-1 mb-1" />
        </div>
    </li>
</script>

<!-- Debug item template -->
<script type="text/html" id="debug-template">
    <li class="list-group-item text-%%color%%"><strong>%%date%%</strong>: %%message%%</li>
</script>