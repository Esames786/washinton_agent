@extends('layouts.innerpages')

@section('template_title')
    Guide Videos
@endsection

@section('content')
@include('partials.mainsite_pages.return_function')
<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Guide Videos</b></h1>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title">Manage Guide Videos</div>
                <button class="btn btn-primary btn-sm" id="btnAddVideo">
                    <i class="fe fe-plus"></i> Add Video
                </button>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table id="guide-videos-table" class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add / Edit Modal --}}
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Add Guide Video</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="videoForm" method="POST" action="{{ route('guide-videos.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="videoId" name="_video_id" value="">
                <div class="modal-body">
                    <div id="modal-error" class="alert alert-danger" style="display:none"></div>
                    <div id="modal-success" class="alert alert-success" style="display:none"></div>

                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="videoTitle" class="form-control" placeholder="Enter title" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="videoDescription" class="form-control" rows="3" placeholder="Enter description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Video File <span class="text-danger" id="videoRequired">*</span></label>
                        <input type="file" name="video" id="videoFile" class="form-control-file" accept="video/*">
                        <small class="text-muted">Accepted: MP4, AVI, MOV, WMV, WEBM — Max 200MB</small>
                        <div id="currentVideo" style="display:none" class="mt-2">
                            <small class="text-info"><i class="fe fe-video"></i> Current video will be kept if no new file is selected.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="videoSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extraScript')
<script>
$(function () {
    var storeUrl = '{{ route('guide-videos.store') }}';
    var baseUrl  = '{{ url('guide-videos') }}';

    // ── DataTable ────────────────────────────────────────────────────────────
    var table = $('#guide-videos-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('guide-videos.index') }}',
        columns: [
            { data: 'id',          name: 'id',         width: '50px' },
            { data: 'title',       name: 'title' },
            { data: 'description', name: 'description', orderable: false },
            { data: 'uploaded_by', name: 'uploaded_by', orderable: false },
            { data: 'created_at',  name: 'created_at' },
            { data: 'action',      name: 'action',     orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
    });

    // ── Open add modal ───────────────────────────────────────────────────────
    $('#btnAddVideo').on('click', function () {
        $('#videoModalLabel').text('Add Guide Video');
        $('#videoForm')[0].reset();
        $('#videoId').val('');
        $('#videoForm').attr('action', storeUrl);
        $('#videoRequired').show();
        $('#currentVideo').hide();
        $('#modal-error, #modal-success').hide();
        $('#videoModal').modal('show');
    });

    // ── Open edit modal ──────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit', function () {
        var id    = $(this).data('id');
        var title = $(this).data('title');
        var desc  = $(this).data('description');

        $('#videoModalLabel').text('Edit Guide Video');
        $('#videoId').val(id);
        $('#videoTitle').val(title);
        $('#videoDescription').val(desc);
        $('#videoForm').attr('action', baseUrl + '/' + id);
        $('#videoRequired').hide();
        $('#currentVideo').show();
        $('#modal-error, #modal-success').hide();
        $('#videoModal').modal('show');
    });

    // ── Form submit (AJAX) ───────────────────────────────────────────────────
    $('#videoForm').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var url      = $(this).attr('action');

        $('#videoSubmitBtn').prop('disabled', true).text('Saving…');
        $('#modal-error, #modal-success').hide();

        $.ajax({
            url:         url,
            method:      'POST',
            data:        formData,
            processData: false,
            contentType: false,
            headers:     { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#videoSubmitBtn').prop('disabled', false).text('Save');
                if (res.success) {
                    $('#modal-success').text(res.message).show();
                    table.ajax.reload(null, false);
                    setTimeout(function () { $('#videoModal').modal('hide'); }, 1200);
                }
            },
            error: function (xhr) {
                $('#videoSubmitBtn').prop('disabled', false).text('Save');
                var msg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $('#modal-error').html(msg).show();
            }
        });
    });
});
</script>
@endsection
