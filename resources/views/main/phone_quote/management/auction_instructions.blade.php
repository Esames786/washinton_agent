@extends('layouts.innerpages')

@section('template_title')
    Auction Instructions
@endsection
@include('partials.mainsite_pages.return_function')

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

@section('content')

    <div class="slim-mainpanel" style="padding-left: 0px !important;">
        <div class="container">
            <div class="slim-pageheader pl-0">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Auction Instructions</li>
                </ol>
                <h6 class="slim-pagetitle">Auction Instructions</h6>
            </div><!-- slim-pageheader -->

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- ===== READ-ONLY VIEW (everyone sees this) ===== --}}
            <div id="instruction-view" class="section-wrapper">
                @if($canEdit)
                    <div class="text-right mb-3">
                        <button type="button" id="btn-edit-instructions" class="btn btn-primary">
                            <i class="fa fa-pencil"></i> Edit Instructions
                        </button>
                    </div>
                @endif
                <div class="instruction-content">
                    <?php echo $instruction->guide_content; ?>
                </div>
            </div>

            {{-- ===== EDIT FORM (admin / manager only) ===== --}}
            @if($canEdit)
                <div id="instruction-edit" class="section-wrapper" style="display:none;">
                    <form id="instructionsForm" method="POST" action="{{ route('auction_instructions.update') }}">
                        @csrf
                        <div class="form-group">
                            <label class="section-title">Auction Instructions</label>
                            <textarea id="guide_content" name="guide_content" style="display:none;">{!! $instruction->guide_content !!}</textarea>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Save
                            </button>
                            <button type="button" id="btn-cancel-instructions" class="btn btn-light">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div><!-- container -->
    </div>

    <style>
        a { color: #007bff; }
        .section-wrapper {
            border: 1px solid #ced4da;
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #343a40;
            text-transform: uppercase;
            margin-top: 20px;
            display: block;
            letter-spacing: 1px;
        }
        .instruction-content table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .instruction-content table th, .instruction-content table td {
            padding: 0.75rem;
            border: 1px solid #ced4da;
            vertical-align: top;
        }
    </style>

@endsection

@section('extraScript')
    <script src="{{ url('assets/summer_note/js/summernote.min.js')}}"></script>
    <script>
        $(function () {
            @if($canEdit)
            var editorReady = false;

            function initEditor() {
                if (editorReady) return;
                $('#guide_content').summernote({
                    placeholder: 'Enter the auction instructions...',
                    tabsize: 2,
                    height: 500
                });
                editorReady = true;
            }

            $('#btn-edit-instructions').on('click', function () {
                initEditor();
                $('#instruction-view').hide();
                $('#instruction-edit').show();
            });

            $('#btn-cancel-instructions').on('click', function () {
                $('#instruction-edit').hide();
                $('#instruction-view').show();
            });

            // Make sure the textarea holds the latest editor HTML before submit.
            $('#instructionsForm').on('submit', function () {
                if (editorReady) {
                    $('#guide_content').val($('#guide_content').summernote('code'));
                }
            });
            @endif
        });
    </script>
@endsection
