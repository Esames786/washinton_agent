@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Questions','/'))}}
@endsection
@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
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
        .ms-drop ul > li.ms-select-all{
            width: 100%;
        }
        .ms-drop ul > li.text-capitalize{
            width: 50%;
            text-align: left;
        }
        .ms-drop ul{
            display: flex;
            flex-wrap: wrap;
        }
    </style>
    <style>
        body {
            background: #fff;
        }


        .ChatView {
            padding: 40px;
        }

        .ChatAddBtn button {
            display: flex;
            justify-content: flex-end;
            margin-left: auto;
            width: fit-content;
        }

        .ChatViewMain {
            margin: 2rem auto;
        }

        .ChatViewHeading {
            background: #1e61a1;
        }

        .ChatViewMain .row {
            margin: 0;
        }

        .ChatViewMain .box {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .ChatViewHeading .box {
            font-weight: 700;
            color: #fff;
            font-size: 16px;
            border: 1px solid #002b5440;
        }

        .boxeditbtn {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .boxeditbtn .btn {
            border-radius: 5px;
            padding: 5px 10px;
        }

        .ChatViewDecription {
            color: #000;
        }

        .DecriptionQuestion {
            display: flex;
            gap: 10px;
            font-size: 15px;
            align-items: center;
            color: #000;
        }

        .ChatViewMain_d .ChatViewDecription:nth-child(2n) {
            background: #f1f1f1;
        }

        .DecriptionQuestion input {
            width: 50px;
            border: 1px solid #ddd;
            outline: none;
        }

        #addQuestionModal .modal-dialog,
        #editQuestionModal .modal-dialog {
            width: 50%;
            max-width: 50%;
        }

        .addQuestionBodyBox_ h2,
        .inputFormRow h2,
        #editinputFormRow h2 {
            font-size: 20px;
            font-weight: 600;
            margin: 10px 0 5px 0;
        }

        .addQuestionBodyBox_a_box_ {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }

        .addQuestionBodyBox_ textarea,
        .addQuestionBodyBox_a_box_ textarea {
            width: 100%;
            border: 1px solid #ddd;
            outline: none;
            padding-left: 10px;
            border-radius: 5px;
        }

        .showbodybottom,
        .list {
            list-style: none;
            padding: 0;
            counter-reset: li;
        }

        .showbodybottom .showbodybottombox {
            text-align: justify;
            position: relative;
            display: flex;
        }

        .showbodybottombox::before {
            position: relative;
            content: counter(li);
            counter-increment: li;
            display: block;
            font-size: 16px;
            color: #fff;
            padding-bottom: 3px;
            margin-right: 5px;
            width: 2rem;
            height: 2rem;
            border-radius: 5px;
            background: #0f3151;
            display: grid;
            place-items: center;
        }

        .showbodyTop h6 {
            font-size: 20px;
        }

        .addwriteQues,
        .addwriteAnswer {
            border: 1px solid #ddd;
            padding: 1rem;
            background: #f7f7f7;
        }

        .addwriteQues input,
        .addwriteAnswer input,
        .showccc {
            padding-left: 1rem;
            height: 2.5rem;
            border-radius: .5rem;
            border: 1px solid #ccc;
            margin: .5rem;
            outline: none;
        }

        .showccc {
            height: 2rem;
            margin: 0 .5rem;
        }

        div.inputFormRow {
            margin-bottom: 1rem;
        }

        .inputFormRow .input-group-append {
            flex-wrap: wrap;
            margin: 0px 1rem;
            gap: 0.2rem;
            margin-right: 0;
        width: 18%;}

        .input-group-append button {
            width: 100%;
            border-radius: 0;
        }
    </style>
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Questions</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Questions/Answers</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="msg">
            </div>
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="container-fluid">
                    </div>
                </div>
                <div class="card-body">

                    <!-- Tab content -->
                    <div class="">
                        <div class="d-flex justify-content-between">                        
                            <h3>Questions/Answers</h3>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Add New</button>
                        </div>
                        <br>
                        <div id="table_data">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="width:100%" id="" role="grid">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">Questions</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Created At</th>
                                            <th class="border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->
    
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Add Question</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="addQuestionBodyBox">
                        <div class="addQuestionBodyBox_">
                            <div class="d-flex justify-content-between">
                                <h2>Type Question</h2>
                                <div class="btn-group">
                                    <!-- <button class="btn btn-primary addsaveques">Save</button> -->
                                    <button class="btn btn-info addInput">Add Input</button>
                                </div>
                            </div>
                            <textarea id="addques" class="form-control editques" placeholder="Kindly type the Question"></textarea>
                            <br>
                            <div class="addwriteQues addQues"></div>
                        </div>
                        <hr>
                        <div class="inputFormRow">
                            <div class="d-flex justify-content-between">
                                <h2>Type Answer</h2>
                                <div class="btn-group">
                                    <!-- <button class="btn btn-primary addsaveques">Save</button> -->
                                    <button class="btn btn-info addInput">Add Input</button>
                                </div>
                            </div>
                            <textarea type="text" name="mainaccountname[]" class="editques addanswer form-control m-input"
                                placeholder="Enter name" required></textarea>
                            <br>
                            <div class="addwriteQues addAns"></div>
                        </div>
                        <button id="addRow" type="button" class="btn btn-info">Add Question</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveQues" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Save Question</button>
                </div>
            </div>
        </div>
    </div>
    <div class="show"></div>
    
    <div id="test"></div>
  
@endsection

@section('extraScript')
<script src="https://www.shipa1.com/assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="https://www.shipa1.com/assets/js/bootstrap.min.js"></script>
<script src="https://www.shipa1.com/assets/js/popper.min.js"></script>
<script src="https://www.shipa1.com/assets/js/jquery.easing.min.js"></script>
<!-- <script src="/js/slim.js"></script> -->





<script type="text/javascript">

    function clickAppend() 
    {        
        $('.editques').keypress(function (e) {
            if (e.which == 13) {
                var text = $(this).val();
                $(this).siblings('.addwriteQues').append(text);
                $(this).val('');
            }
        });
                
        $('.removeRow').click(function () {
            $(this).parents('.inputFormRow').remove();
        }); 
        
        $('.addInput').click(function () {
            var input = '<div><span></span><input type="text" disabled placeholder="input" /></div>';
            $(this).parents('.btn-group').parents('.d-flex').siblings('.addwriteQues').append(input);
            $(this).parents('.btn-group').parents('.d-flex').siblings('.editques').val('');
        })
    }
        
    $("#addRow").click(function () {
        $(`<div class="inputFormRow">
                <div class="d-flex justify-content-between">
                    <h2>Type Answer</h2>
                    <div class="btn-group">
                        <button type="button" class="btn removeRow btn-danger">Remove</button>
                        <button class="btn btn-info addInput">Add Input</button>
                    </div>
                </div>
                <textarea id="editques" placeholder="Kindly type the Question" class="form-control editques m-input"></textarea>
                <br>
                <div class="addwriteQues addAns"></div>
            </div>`
        ).insertBefore(this);
        clickAppend();
    });
    clickAppend();

    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function editAnswers(data)
        {
            var answer = '';
            if(data)
            {
                $.each(data, function(){
                    answer += 
                        '<div class="inputFormRow">'+
                            '<div class="d-flex justify-content-between">'+
                                '<h2>Type Answer</h2>'+
                                '<div class="btn-group">'+
                                    `${(data[0].id != this.id) ? '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>' : ''}`+
                                    '<button class="btn btn-info addInput">Add Input</button>'+
                                '</div>'+
                            '</div>'+
                            '<textarea class="editques form-control m-input" placeholder="Enter name" required></textarea>'+
                            '<br>'+
                            '<div class="addwriteQues updateAns">'+this.anwser+'</div>'+
                            '<div class="input-group-append">'+
                            '</div>'+
                        '</div>';
                })
                return answer;
            }
        }
        function editQuesAns(res)
        {
            var question = '';
            if(res)
            {
                question = `
                    <div class="modal fade" id="editQuestionModal${res.ques.id}" tabindex="-1" role="dialog" aria-labelledby="editQuestionModal${res.ques.id}"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><b>Edit Question</b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <input type="hidden" class="updatedId" value="${res.ques.id}" />
                                <div class="modal-body">
                                    <div class="addQuestionBodyBox">
                                        <div class="addQuestionBodyBox_">
                                            <div class="d-flex justify-content-between">
                                                <h2>Type Question</h2>
                                                <div class="btn-group">
                                                    <button class="btn btn-info addInput">Add Input</button>
                                                </div>  
                                            </div>
                                            <textarea id="editques" placeholder="Kindly type the Question" class="editques form-control"></textarea>
                                            <br>
                                            <div class="addwriteQues updateQues">${res.ques.question}</div>
                                        </div>
                                        <hr>
                                        ${editAnswers(res.ques.ans)}
                                        <button id="editRow" type="button" class="btn btn-info">Add Question</button>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="updated" data-dismiss="modal" aria-label="Close">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            return question;
        }

        function index() 
        {
            $.ajax({
                url:"{{url('/questions/get')}}",
                type: "get",
                dataType:"json",
                success: function(res){
                    if(res.ques)
                    {
                        $("tbody").children().remove();
                        $.each(res.ques, function(key){
                            $("tbody").append(`
                                <tr>
                                    <td>${key + 1}</td>
                                    <td>${this.question}</td>
                                    <td>${(this.status == 1) ? '<span class="badge bg-success text-white">Active</span>' : '<span class="badge bg-danger text-white">Disabled</span>'}</td>
                                    <td>${this.date_time}</td>
                                    <td>
                                        <div class="btn-group">
                                            <input type="hidden" class="id" value="${this.id}">
                                            
                                            <a title="View" class="btn btn-info showQues" href="#" data-toggle="modal" data-target="#showQuestionModal${this.id}"><i class="fa fa-eye"></i></a>
                                            
                                            <a title="Edit" class="btn btn-success editQues" href="#" data-toggle="modal" data-target="#editQuestionModal${this.id}" ><i class="fa fa-edit"></i></a>
                                            
                                            ${(this.status == 1) ? '<a title="Disable" class="btn btn-danger disableQues" href="#"><i class="fa fa-trash"></i></a>' : '<a title="Active" class="btn btn-warning disableQues" href="#"><i class="fa fa-plus"></i></a>'}
                                        </div>
                                    </td>
                                </tr>
                            `);
                        })
                    }

                    $('.editQues').click(function() {
                        var id = $(this).siblings('input').val();
                        var editModal = '';
                        $.ajax({
                            url:"{{url('/questions/edit')}}",
                            type:"get",
                            dataType:"json",
                            data:{id:id},
                            success: function(res)
                            {
                                editModal = editQuesAns(res);
                                // console.log(res);
                                $('.show').append(editModal);                       
                                $(`#editQuestionModal${res.ques.id}`).modal('show');
                        
                                $("#editRow").click(function () {
                                    $(`<div class="inputFormRow">
                                            <div class="d-flex justify-content-between">
                                                <h2>Type Answer</h2>
                                                <div class="btn-group">
                                                    <button type="button" class="btn removeRow btn-danger">Remove</button>
                                                    <button class="btn btn-info addInput">Add Input</button>
                                                </div>
                                            </div>
                                            <textarea id="editques" placeholder="Kindly type the Question" class="form-control editques m-input"></textarea>
                                            <br>
                                            <div class="addwriteQues updateAns"></div>
                                        </div>`
                                    ).insertBefore(this);
                                    clickAppend();
                                });
                                clickAppend();
                                
                                $('#updated').click(function(){
                                    var id = $('.updatedId').val();
                                    var addques = $('.updateQues').html();
                                    var addanswer = [];
                                    var addans = $('.updateAns');
                                    $.each(addans, function(){
                                        addanswer.push(this.innerHTML);
                                    });
                                    // console.log(addanswer);
                                    $.ajax({
                                        url:"{{url('/questions/update')}}",
                                        type:"post",
                                        dataType:"json",
                                        data:{question:addques,anwser:addanswer,id:id},
                                        success:function(res){
                                            // console.log(res);
                                            $('.msg').children().remove();
                                            if(res.status === true)
                                            {
                                                $('.msg').append(`
                                                    <div class="alert alert-success">
                                                        ${res.message}
                                                    </div>
                                                `);
                                                index();
                                            }
                                            else
                                            {
                                                $.each(res.message,function(key,val){
                                                    $('.msg').append(`
                                                        <div class="alert alert-danger">
                                                            ${this}
                                                        </div>`
                                                    );
                                                })
                                            }
                                        }
                                    });
                                });
                            }
                        });
                    })

                    $('.disableQues').click(function(){
                        var id = $(this).siblings('input').val();
                        $.ajax({
                            url:"{{url('/questions/destroy')}}",
                            type:"post",
                            dataType:"json",
                            data:{id:id},
                            success: function(res)
                            {
                                $('.msg').children().remove();
                                $('.msg').append(`
                                    <div class="alert alert-success">
                                        ${res.message}
                                    </div>
                                `);
                                index();
                            }
                        });
                    })

                    function answers(data)
                    {
                        var answer = '';
                        // console.log(data);
                        if(data){
                            $.each(data, function(){ 
                                answer += '<li class="showbodybottombox"><p class="my-auto">'+this.anwser+'</p></li><br>';
                            })
                        }
                        return answer;
                    }
                    
                    $(".showQues").click(function () {
                        var id = $(this).siblings('input').val();
                        // $('.show').html("");
                        var showModal = '';
                        $.ajax({
                            url:"{{url('/questions/show')}}",
                            type: "post",
                            dataType:"json",
                            data:{id:id},
                            success: function(res){
                                // console.log(res);
                                showModal = `
                                    <div class="modal fade" id="showQuestionModal${res.ques.id}" tabindex="-1" role="dialog" aria-labelledby="showQuestionModal${res.ques.id}"
                                    aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"><b>Question</b></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="showbodyTop">
                                                        <h6>${res.ques.question}</h6>
                                                    </div>
                                                    <hr>
                                                    <ul class="showbodybottom">
                                                        ${answers(res.ques.ans)}
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary close" data-dismiss="modal" aria-label="Close">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('.show').append(showModal);                       
                                $(`#showQuestionModal${res.ques.id}`).modal('show');
                                $('.showbodybottombox').children('p').children('input').attr('disabled',false);
                                $('.showbodybottombox').children('p').children('input').attr('class','mx-1');
                            }
                        })
                    })
                    // console.log(res);
                }
            });
        }
        index();

        $('#saveQues').click(function(){
            var addques = $('.addQues').html();
            var addanswer = [];
            var addans = $('.addAns');
            $.each(addans, function(){
                addanswer.push(this.innerHTML);
            });
            // console.log(addanswer);
            $.ajax({
                url:"{{url('/questions/store')}}",
                type:"post",
                dataType:"json",
                data:{question:addques,anwser:addanswer},
                success:function(res){
                    // console.log(res);
                    $('.msg').children().remove();
                    if(res.status === true)
                    {
                        $('.msg').append(`
                            <div class="alert alert-success">
                                ${res.message}
                            </div>
                        `);
                        index();
                    }
                    else
                    {
                        $.each(res.message,function(key,val){
                            $('.msg').append(`
                                <div class="alert alert-danger">
                                    ${this}
                                </div>`
                            );
                        })
                    }
                }
            });
        });

    });



    
</script>
{{--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


@endsection