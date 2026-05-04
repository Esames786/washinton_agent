@extends('layouts.innerpages')
@include('partials.mainsite_pages.return_function')
@section('template_title')
    {{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection

@section('content')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Template View</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.html" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Template View</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header">
            <h5 class="card-title mb-0">Template View</h5>
        </div>
        <div class="card-body">
            <!-- Add Template Button -->
            <button id="addTemplateBtn" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
                Add Template
            </button>


            <!-- Add Template Modal -->
            <!-- Add Template Modal -->

            <div style="overflow-x: auto; width: 100%;">
                <table class="table bordered-table mb-0" id="templateTable" data-page-length='10'>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th style="min-width: 300px;">Description</th> <!-- Optional fixed width -->
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="modal fade" id="addTemplateModal" tabindex="-1" aria-labelledby="addTemplateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="templateForm" action="{{ route('view_template_store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="addTemplateModalLabel">Add Template</h5>
                        </div>
                        <div class="modal-body">
                            <input id="templateId" type="hidden" name="id">
                            <div class="mb-3">
                                <label for="templateName" class="form-label">Template Name</label>
                                <input type="text" class="form-control" name="name" id="templateName" required>
                            </div>
                            <div class="mb-3">
                                <label for="templateStatus" class="form-label">Type</label>
                                <select class="form-control" name="notification_type" id="notification_type" required>
                                    <option value="">Select</option>
                                    <option value="1">Email Marketing</option>
                                </select>
                            </div>
                            <div class="mb-3"  id="status_fields">
                                <label class="form-label">Shipper/Broker Dynamic Fields</label>
                                <div class="d-flex flex-wrap gap-2" style="overflow-y: scroll">
                                    @foreach($data as $column)
                                        <span class="badge bg-secondary insert-tag" data-tag="{{ '{' . $column . '}' }}" style="cursor: pointer;">
                                                {{ $column }}
                                            </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="templateStatus" class="form-label">Message Type</label>
                                <select class="form-control" name="status" id="templateStatus" required>
                                    <option value="">Type</option>
                                    <option value="1">Email</option>
                                    <option value="2">SMS</option>
                                </select>
                            </div>

                            <div class="mb-3" id="template_image_div">
                                <label class="form-label">Template Image</label>
                                <input type="file" class="form-control" name="template_image" id="template_image" />

                                {{-- Image Preview --}}
                                <img id="templateImagePreview" src="" alt="Template Preview" style="display:none; max-width: 100%; height: auto; margin-top: 10px;" />
                            </div>



                            <div class="mb-3">
                                <label for="templateDescription" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="templateDescription" rows="4" required></textarea>
                            </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Template</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@section('extraScript')
    <script>
        $(document).ready(function () {

            $('#addTemplateBtn').on('click', function () {
                // Reset form fields
                $('#templateForm')[0].reset();

                // Clear hidden ID (in case user edited previously)
                $('#templateId').val('');

                // Reset select dropdowns
                $('#notification_type').val('').trigger('change');
                $('#templateStatus').val('').trigger('change');

                // Hide image preview and remove src
                $('#templateImagePreview').hide().attr('src', '');

                // Ensure image input is required for new entry
                $('#template_image').prop('required', true);

                // Reset modal title
                $('#addTemplateModalLabel').text('Add Template');
                $('#addTemplateModal').modal('show');
            });


            $('#notification_type').on('change', function () {
                $('#templateDescription').val('');
                const selected = $(this).val();
                if (selected === '1') {
                    $('#template_image_div').show();
                    $('#template_image').prop('required',true);
                }
            });

            // Trigger initial change if needed
            $('#templateStatus').trigger('change');
        });

        $(document).ready(function () {
            $('#templateTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('template_datatable') }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Handle Edit Button Click
            $(document).on('click', '.edit-btn', function () {
                var templateId = $(this).data('id');
                var templateName = $(this).data('name');
                var notification_type = $(this).data('notification_type');
                var templateDescription = $(this).data('description');
                var templateStatus = $(this).data('status');
                var templateImage = $(this).data('template_image');

                $('#templateId').val(templateId);
                $('#templateName').val(templateName);
                $('#notification_type').val(notification_type).trigger('change');
                $('#templateDescription').val(templateDescription);
                $('#templateStatus').val(templateStatus).trigger('change');

                // Image preview
                if (templateImage) {
                    $('#templateImagePreview').attr('src', templateImage).show();
                    $('#template_image').prop('required', false); // Remove required when editing
                } else {
                    $('#templateImagePreview').hide();
                    $('#template_image').prop('required', true); // Required when creating
                }

                // Update modal title
                $('#addTemplateModalLabel').text('Edit Template');

                // Show the modal
                $('#addTemplateModal').modal('show');
            });

        });
    </script>

    <script>
        $(document).ready(function () {
            $('#templateStatus').on('change', function () {
                const selected = $(this).val();

                // Hide all type fields first
                $('.type-fields').hide();

                // Show based on selection
                if (selected === '1') {
                    $('.email-fields').show();
                } else if (selected === '2') {
                    $('.sms-fields').show();
                }
            });

            // Trigger initial change if needed
            $('#templateStatus').trigger('change');
        });

        $(document).ready(function () {
            $('.insert-tag').on('click', function () {
                var tag = $(this).data('tag');
                var $textarea = $('#templateDescription');

                // Get current cursor position
                var cursorPos = $textarea.prop('selectionStart');
                var v = $textarea.val();
                var textBefore = v.substring(0, cursorPos);
                var textAfter = v.substring(cursorPos, v.length);

                // Insert the tag
                $textarea.val(textBefore + tag + textAfter);

                // Move cursor after inserted tag
                $textarea[0].selectionStart = $textarea[0].selectionEnd = cursorPos + tag.length;
                $textarea.focus();
            });
        });
    </script>
@endsection
