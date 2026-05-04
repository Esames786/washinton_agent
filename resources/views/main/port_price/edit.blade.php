@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Edit Port Price','/'))}}
@endsection
@section('content')
    <link rel="stylesheet" href="{{asset('assets/summernote/summernote-bs4.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/summernote/summernote-lite.css')}}" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    @include('partials.mainsite_pages.return_function')
    <style>
        select.form-control:not([size]):not([multiple]) {
            height: 28px;
        }

        input[type='radio']:after {
            width: 15px;
            height: 15px;
            border-radius: 15px;
            top: -4px;
            left: -1px;
            position: relative;
            background-color: #d1d3d1;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        input[type='radio']:checked:after {
            width: 20px;
            height: 20px;
            border-radius: 100px;
            top: -2px;
            left: -6px;
            position: relative;
            background-color: rgb(23 162 184);
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        .table {
            color: rgb(0 0 0);
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {
            border: 1px solid rgb(0 0 0);
        }

        .table > tbody > tr > td, .table > thead > tr > th {
            font-weight: 500;
            -webkit-transition: all .3s ease;
            font-size: 18px;
            color: rgb(0 0 0);
            text-align: center;
        }

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .tabcontent {
            animation: fadeEffect 1s; /* Fading effect takes 1 second */
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Edit Port Price</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
        <!--div-->
            <div class="card">
                <div class="card-header">
                    Edit New Port Price
                </div>
                <form action="{{ url('/port_price/update/'.$data->id) }}" method="POST">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Create Port Price...</label>
                                    <textarea class="form-control summernote" name="long_data" placeholder="Create Port Price">{{$data->long_data}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success float-right mb-4" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- end app-content-->


@endsection
@section('extraScript')

    <script src="{{asset('assets/summernote/summernote-bs4.js')}}"></script>
    <script src="{{asset('assets/summernote/summernote-lite.js')}}"></script>
    <script>
    $('.summernote').summernote({
        editing: true,
        buttons: {},
        lang: 'en-US',
        followingToolbar: false,
        toolbarPosition: 'top',
        otherStaticBar: '',
        // toolbar
        toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], ['fontname', ['fontname']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], ['table', ['table']], ['insert', ['link', 'picture', 'video']], ['view', ['fullscreen', 'codeview', 'help']]],
        // popover
        popatmouse: true,
        popover: {
          image: [['resize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']], ['float', ['floatLeft', 'floatRight', 'floatNone']], ['remove', ['removeMedia']]],
          link: [['link', ['linkDialogShow', 'unlink']]],
          table: [['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']], ['delete', ['deleteRow', 'deleteCol', 'deleteTable']]],
          air: [['color', ['color']], ['font', ['bold', 'underline', 'clear']], ['para', ['ul', 'paragraph']], ['table', ['table']], ['insert', ['link', 'picture']], ['view', ['fullscreen', 'codeview']]]
        },
        // air mode: inline editor
        airMode: false,
        width: null,
        height: 500,
        linkTargetBlank: true,
        useProtocol: true,
        defaultProtocol: 'http://',
        focus: false,
        tabDisabled: false,
        tabSize: 4,
        styleWithSpan: true,
        shortcuts: true,
        textareaAutoSync: true,
        tooltip: 'auto',
        container: null,
        maxTextLength: 0,
        blockquoteBreakingLevel: 2, // 0 - No break, the new paragraph remains inside the quote; 1 - Break the first blockquote in the ancestors list; 2 - Break all blockquotes, so that the new paragraph is not quoted. (default)
        spellCheck: true,
        disableGrammar: false,
        placeholder: "Create Port Price",
        inheritPlaceholder: true,
        hintMode: 'word',
        hintSelect: 'after',
        hintDirection: 'bottom',
        styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana'],
        fontNamesIgnoreCheck: [],
        addDefaultFonts: true,
        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36'],
        fontSizeUnits: ['px', 'pt'],
        lineHeights: ['1.0', '1.2', '1.4', '1.5', '1.6', '1.8', '2.0', '3.0'],
        tableClassName: 'table table-bordered',
        insertTableMaxSize: {
          col: 10,
          row: 10
        },
        dialogsInBody: false, // By default, dialogs are attached in container.
        dialogsFade: false,
        maximumImageFileSize: null,
        codemirror: {
          mode: 'text/html',
          htmlMode: true,
          lineNumbers: true
        },
        codeviewFilter: false,
        codeviewFilterRegex: /<\/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|ilayer|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|t(?:itle|extarea)|xml)[^>]*?>/gi,
        codeviewIframeFilter: true,
        codeviewIframeWhitelistSrc: [],
        codeviewIframeWhitelistSrcBase: ['www.youtube.com', 'www.youtube-nocookie.com', 'www.facebook.com', 'vine.co', 'instagram.com', 'player.vimeo.com', 'www.dailymotion.com', 'player.youku.com', 'v.qq.com'],
        keyMap: {
          pc: {
            'ENTER': 'insertParagraph',
            'CTRL+Z': 'undo',
            'CTRL+Y': 'redo',
            'TAB': 'tab',
            'SHIFT+TAB': 'untab',
            'CTRL+B': 'bold',
            'CTRL+I': 'italic',
            'CTRL+U': 'underline',
            'CTRL+SHIFT+S': 'strikethrough',
            'CTRL+BACKSLASH': 'removeFormat',
            'CTRL+SHIFT+L': 'justifyLeft',
            'CTRL+SHIFT+E': 'justifyCenter',
            'CTRL+SHIFT+R': 'justifyRight',
            'CTRL+SHIFT+J': 'justifyFull',
            'CTRL+SHIFT+NUM7': 'insertUnorderedList',
            'CTRL+SHIFT+NUM8': 'insertOrderedList',
            'CTRL+LEFTBRACKET': 'outdent',
            'CTRL+RIGHTBRACKET': 'indent',
            'CTRL+NUM0': 'formatPara',
            'CTRL+NUM1': 'formatH1',
            'CTRL+NUM2': 'formatH2',
            'CTRL+NUM3': 'formatH3',
            'CTRL+NUM4': 'formatH4',
            'CTRL+NUM5': 'formatH5',
            'CTRL+NUM6': 'formatH6',
            'CTRL+ENTER': 'insertHorizontalRule',
            'CTRL+K': 'linkDialog.show'
          },
          mac: {
            'ENTER': 'insertParagraph',
            'CMD+Z': 'undo',
            'CMD+SHIFT+Z': 'redo',
            'TAB': 'tab',
            'SHIFT+TAB': 'untab',
            'CMD+B': 'bold',
            'CMD+I': 'italic',
            'CMD+U': 'underline',
            'CMD+SHIFT+S': 'strikethrough',
            'CMD+BACKSLASH': 'removeFormat',
            'CMD+SHIFT+L': 'justifyLeft',
            'CMD+SHIFT+E': 'justifyCenter',
            'CMD+SHIFT+R': 'justifyRight',
            'CMD+SHIFT+J': 'justifyFull',
            'CMD+SHIFT+NUM7': 'insertUnorderedList',
            'CMD+SHIFT+NUM8': 'insertOrderedList',
            'CMD+LEFTBRACKET': 'outdent',
            'CMD+RIGHTBRACKET': 'indent',
            'CMD+NUM0': 'formatPara',
            'CMD+NUM1': 'formatH1',
            'CMD+NUM2': 'formatH2',
            'CMD+NUM3': 'formatH3',
            'CMD+NUM4': 'formatH4',
            'CMD+NUM5': 'formatH5',
            'CMD+NUM6': 'formatH6',
            'CMD+ENTER': 'insertHorizontalRule',
            'CMD+K': 'linkDialog.show'
          }
        },
        icons: {
          'align': 'note-icon-align',
          'alignCenter': 'note-icon-align-center',
          'alignJustify': 'note-icon-align-justify',
          'alignLeft': 'note-icon-align-left',
          'alignRight': 'note-icon-align-right',
          'rowBelow': 'note-icon-row-below',
          'colBefore': 'note-icon-col-before',
          'colAfter': 'note-icon-col-after',
          'rowAbove': 'note-icon-row-above',
          'rowRemove': 'note-icon-row-remove',
          'colRemove': 'note-icon-col-remove',
          'indent': 'note-icon-align-indent',
          'outdent': 'note-icon-align-outdent',
          'arrowsAlt': 'note-icon-arrows-alt',
          'bold': 'note-icon-bold',
          'caret': 'note-icon-caret',
          'circle': 'note-icon-circle',
          'close': 'note-icon-close',
          'code': 'note-icon-code',
          'eraser': 'note-icon-eraser',
          'floatLeft': 'note-icon-float-left',
          'floatRight': 'note-icon-float-right',
          'font': 'note-icon-font',
          'frame': 'note-icon-frame',
          'italic': 'note-icon-italic',
          'link': 'note-icon-link',
          'unlink': 'note-icon-chain-broken',
          'magic': 'note-icon-magic',
          'menuCheck': 'note-icon-menu-check',
          'minus': 'note-icon-minus',
          'orderedlist': 'note-icon-orderedlist',
          'pencil': 'note-icon-pencil',
          'picture': 'note-icon-picture',
          'question': 'note-icon-question',
          'redo': 'note-icon-redo',
          'rollback': 'note-icon-rollback',
          'square': 'note-icon-square',
          'strikethrough': 'note-icon-strikethrough',
          'subscript': 'note-icon-subscript',
          'superscript': 'note-icon-superscript',
          'table': 'note-icon-table',
          'textHeight': 'note-icon-text-height',
          'trash': 'note-icon-trash',
          'underline': 'note-icon-underline',
          'undo': 'note-icon-undo',
          'unorderedlist': 'note-icon-unorderedlist',
          'video': 'note-icon-video'
        }
    });
    </script>
    
@endsection


