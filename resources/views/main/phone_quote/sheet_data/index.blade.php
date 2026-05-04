@extends('layouts.innerpages')
@include('partials.mainsite_pages.return_function')

@section('template_title')
    Data Sheet
@endsection

@section('content')
    <style>
        .jexcel > div > table {
            width: 300% !important;
            height: auto !important;
        }

        #style-1::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: #F5F5F5;
        }

        #style-1::-webkit-scrollbar {
            width: 15px !important;
            height: 15px !important;
            background-color: #F5F5F5;
        }

        #style-1::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            background-color: #555;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd26;
        }

        tr.jexcel_headers {
            font-size: 22px;
            font-family: serif;
            font-weight: 600;
        }

        .jexcel_headers > td {
            text-align: center;
        }

        .red_color {
            width: 64px;
            height: 30px;
            background-color: red;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .green_color {
            width: 64px;
            height: 30px;
            background-color: greenyellow;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .yellow_color {
            width: 64px;
            height: 30px;
            background-color: yellow;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .blue_color {
            width: 64px;
            height: 30px;
            background-color: blue;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .pink_color {
            width: 64px;
            height: 30px;
            background-color: pink;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .grey_color {
            width: 64px;
            height: 30px;
            background-color: darkgrey;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .purple_color {
            width: 64px;
            height: 30px;
            background-color: purple;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .maroon_color {
            width: 64px;
            height: 30px;
            background-color: maroon;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .lime_color {
            width: 64px;
            height: 30px;
            background-color: lime;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .teal_color {
            width: 64px;
            height: 30px;
            background-color: teal;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .olive_color {
            width: 64px;
            height: 30px;
            background-color: olive;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }

        .aqua_color {
            width: 64px;
            height: 30px;
            background-color: aqua;
            padding: 0px;
            margin-right: 6px;
            margin-top: 8px;
        }
    </style>
    <div class="alert alert-info mt-2" id="success-alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Success! </strong> Data Save successfully
    </div>
    <div class="row">
        <div class="col-md-9 ">
            <input id="myInput" type="text" placeholder="Search Data Here..."
                   class="form-control w-100 mt-5 mb-0  float-left">
            {{--<input id="table_data" type="text" style="display: none"  placeholder="" class="form-control w-50 mt-5 mb-0  float-right">--}}

        </div>
        <div class="col-md-3 ">
            <button class="btn btn-md btn-dark w-100 mt-5 mb-0  float-right" onclick="save_sheet_data();">Save Data
            </button>
        </div>
    </div>
    <div class="row">
        <span class="red_color"></span>
        <span class="blue_color"></span>
        <span class="green_color"></span>
        <span class="yellow_color"></span>
        <span class="pink_color"></span>
        <span class="grey_color"></span>
        <span class="purple_color"></span>
        <span class="maroon_color"></span>
        <span class="lime_color"></span>
        <span class="olive_color"></span>
        <span class="teal_color"></span>
        <span class="aqua_color"></span>

    </div>


    <div class="row mt-1" id="style-1" style="overflow: scroll">
        <div id="demo1">
            <input type="hidden" name="idd" id="idd" value="{{$sheet_data->id}}"/>
        </div>
    </div>


@endsection

@section('extraScript')

    <link rel="stylesheet" href="{{url('assets/ce-2.1.0/dist/css/jquery.jexcel.css')}}"/>
    <script src="{{url('assets/ce-2.1.0/dist/js/jquery.jexcel.js')}}"></script>

    <script>


        function check_check() {

            var cells = document.getElementsByTagName('td');
            for (var i = 0; i <= cells.length; i++) {
                cells[i].addEventListener('click', clickHandler);
            }
            function clickHandler() {
                document.getElementById("myInput").value = (this.textContent);
            }

        }


        setTimeout(function(){

            var idvalue = '{{$sheet_data->id}}';

            $.ajax({
                url: "/get_sheet_data",
                data: {idvalue: idvalue},
                type: 'GET',
                success: function (data) {
                    if(data.length > 0) {
                        $('#demo1').html('');
                        $('#demo1').html(data);
                        check_check();
                    }
                },

            });

        }, 500);

        $(document).ready(function () {
            $("#success-alert").hide();
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            var data = 0;
            $(document).on("click", "table td", function (e) {
                data = $(this).attr('id');
//                $('#' + data).css("background", "darksalmon");
            });
            $(document).on("dblclick", "table td", function (e) {
                data = $(this).attr('id');
                $('#' + data).css("background", "none");
            });
            $(document).on("click", ".red_color", function (e) {
                $('#' + data).css("background", "red", "color", "white");
            });
            $(document).on("click", ".blue_color", function (e) {
                $('#' + data).css("background", "blue", "color", "white");
            });
            $(document).on("click", ".green_color", function (e) {
                $('#' + data).css("background", "green", "color", "black");
            });
            $(document).on("click", ".yellow_color", function (e) {
                $('#' + data).css("background", "yellow", "color", "black");
            });
            $(document).on("click", ".pink_color", function (e) {
                $('#' + data).css("background", "pink", "color", "black");
            });
            $(document).on("click", ".grey_color", function (e) {
                $('#' + data).css("background", "grey", "color", "black");
            });
            $(document).on("click", ".purple_color", function (e) {
                $('#' + data).css("background", "purple", "color", "black");
            });
            $(document).on("click", ".maroon_color", function (e) {
                $('#' + data).css("background", "maroon", "color", "black");
            });
            $(document).on("click", ".lime_color", function (e) {
                $('#' + data).css("background", "lime", "color", "black");
            });
            $(document).on("click", ".olive_color", function (e) {
                $('#' + data).css("background", "olive", "color", "black");
            });
            $(document).on("click", ".teal_color", function (e) {
                $('#' + data).css("background", "teal", "color", "black");
            });
            $(document).on("click", ".aqua_color", function (e) {
                $('#' + data).css("background", "aqua", "color", "black");
            });
        });

        $(document).ready(function () {

            var cells = document.getElementsByTagName('td');
            for (var i = 0; i <= cells.length; i++) {
                cells[i].addEventListener('click', clickHandler);
            }

            function clickHandler() {
//                document.getElementById( 'table_data' ).style.display = 'block';

                document.getElementById("myInput").value = (this.textContent);
            }

        });


        <?php $i=0; $data = "[, , , , , , , , , , , , , , , , , , , , , , , , , ,]," ?>
        data1 = [ <?php while($i<=20){
            echo $data;
        $i++;
        } ?> ];


        $('#demo1').jexcel({

            data: data1,
            search: true,
            filters: true,
            text: {
                show: 'Show ',
                search: 'Search',
            }
        });

        function save_sheet_data() {


            var sheet_data = data1;
            var myJSONText = JSON.stringify(data1);

            var sheet_data = $('#demo1').html();

            var idvalue = $("#idd").val();

            $.ajax({
                url: "/sheet_data_save",
                data: ({'_token': '{{csrf_token()}}', idvalue: idvalue, sheet_data: sheet_data}),
                type: 'post',
                success: function (data) {
                    //alert(data);
                    $("#idd").val(data);
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function () {
                        $("#success-alert").slideUp(500);
                    });
                },

            });

        }


    </script>




@endsection

