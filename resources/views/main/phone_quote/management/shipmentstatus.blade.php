@extends('layouts.innerpages')
@include('partials.mainsite_pages.return_function')
@section('template_title')
    Shipment Status
@endsection

@section('content')
        <style>
            .pageTable .firstsection{
                padding: 6px;
                background: #007fb8;
            }
            .pageTable .firstsection select,input{
                font-size: 14px;
                height: 29px;
                background-color: #f7f7f7;
                border: 1px solid #efefef;
                border-right: none;
                border-radius: 0;
                display: flex;
                /* flex-direction: column; */
                width: 96%;
                outline: 0;
                padding: 0px 6px;
                border-radius: 4px;
            }
            .delpik{
                color: white;
                padding: 3px 12px;
                margin-right: 10px;
                border-radius: 7px; 
            }
            .green{
                background: green;
            }
            .red{
                background: red;
            }
            .pageTable .firstsection button{
                text-transform: uppercase;
                color: #000;
                /* padding: 0.75rem; */
                text-align: center;
                font-weight: 600;
                border: 0;
                min-width: 102px;
                height: 29px;
            }
            .customers::-webkit-scrollbar {
                width: 4px;
                background-color: transparent;
            }
            /* .customers::-webkit-scrollbar-track {
                background-color: darkgrey;
                } */
                .customers::-webkit-scrollbar-thumb {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #a6afffd5;
                border-radius: 50px;
            }
            .table>:not(caption)>*>*{
                padding: 3px;
            }
            .late{
                /* background-color: #e70000e6 !important; */
                /* font-weight: 700;
                color: white; */
            }
            .pageTable .firstsection .sreachdiv{
                display: flex;
            }
            .pageTable .firstsection .icon{
                background: #f7f7f7;
                padding: 8px;
                border-left: 1px solid #c8c8c8;
            }
            .pageTable .secondsection .mainheading{
                background: #179dd9;
                padding: 2px;
                text-align: center;
                font-size: 14px;
                border-bottom: 1px solid #ffffff70;
                color: white;
                text-transform: uppercase;
            }
            .pageTable .secondsection .smheading .columns{
                background: #179dd9;
                padding: 3px;
                border-right: 1px solid #ffffff70;
                color: white;
                text-align: center;
                font-size: 12px;
            }
            body{
                font-size: 12px;
            }
            .customers .row:nth-child(2n) {
                background-color: #179dd970;
                color: black;
                font-weight: 600;
                margin: 0;
            }
            .pageTable .secondsection .customers{
                max-height: 450px;
                overflow-y: scroll;
                overflow-x: hidden;
            }
            .customers .row{
                transition: 0.9s;
            }
            .customers .row:hover{
                background-color: #179dd970;
            }
            .pageTable .secondsection select{
                border: 1px solid #dedede;
                padding: 4px 0px;
                outline: 0;
                width: 95%;
                display: block;
                margin: auto;
                font-family: math;
                font-size: 12px;
            }
            .pageTable .secondsection .row .col-md-4{
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .pageTable .secondsection .maincol{
                border: 1px solid #dadada;
                border-top: none;
            }
        </style>
    </head>
    <body>
        <div class="pageTable">

        <div class="container-fluid">
            <div class="row firstsection">
                <h3 class="m-auto text-light">Shipment Status</h3>
                <center class="m-auto">
                    <span class="badge badge-danger">
                        Refresh In: <span class="countdown"></span>
                    </span>
                </center>
                <!--<div class="col-md-2">-->
                <!--    <button>-->
                <!--        Home-->
                <!--    </button>-->
                <!--</div>-->
                <!--<div class="col-md-2">-->
                <!--    <select>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--    </select>-->
                <!--</div>-->
                <!--<div class="col-md-2">-->
                <!--    <select>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--    </select>-->
                <!--</div>-->
                <!--<div class="col-md-2">-->
                <!--    <select>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--        <option value="hello">Abdul-sami</option>-->
                <!--    </select>-->
                <!--</div>-->
                <!--<div class="col-md-4">-->
                <!--    <div class="sreachdiv">-->
                <!--        <input style="padding: 12px;" type="text" placeholder="Sreach...">-->
                <!--        <div class="icon">-->
                <!--            <i class="fa-solid fa-magnifying-glass"></i>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
            <div class="row secondsection" id="table_data">
                <div class="col-md-3 maincol">
                    <div class="table">
                        <div class="row">
                            <div class="col-md-12 mainheading">Listed</div>
                        </div>
                        <div class="row smheading">
                            <div class="col-md-4 col-4 columns">
                                Order ID
                            </div>
                            <div class="col-md-4 col-4 columns">
                                OT/DIS
                            </div>
                            <div class="col-md-4 col-4 columns">DELAY</div>
                        </div>
                        <div class="customers">
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 pedding">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 late">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 late">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-3 maincol">
                    <div class="table">
                        <div class="row">
                            <div class="col-md-12 mainheading">Schedule</div>
                        </div>
                        <div class="row smheading">
                            <div class="col-md-4 col-4 columns">
                                Order ID
                            </div>
                            <div class="col-md-4 col-4 columns">
                                OT/DIS
                            </div>
                            <div class="col-md-4 col-4 columns">
                                Date
                            </div>
                        </div>
                        <div class="customers">
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 pendding">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6  col-4">
                        <span   class="green delpik">Pick up</span>   : 2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div><div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 succes">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>

                    </div>
                    </div>
                </div>
                <div class="col-md-3 maincol">
                    <div class="table">
                        <div class="row">
                            <div class="col-md-12 mainheading">Pick Up</div>
                        </div>
                        <div class="row smheading">
                            <div class="col-md-4 col-4 columns">
                                Order ID
                            </div>
                            <div class="col-md-4 col-4 columns">
                                OT/DIS
                            </div>
                            <div class="col-md-4 col-4 columns">
                                Date
                            </div>
                        </div>
                        <div class="customers">
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 pendding">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6  col-4">
                        <span class="green delpik">Pick up</span>   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div><div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 succes">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>

                    </div>
                    </div>
                </div>
                <div class="col-md-3 maincol">
                    <div class="table">
                        <div class="row">
                            <div class="col-md-12 mainheading">Delivery</div>
                        </div>
                        <div class="row smheading">
                            <div class="col-md-4 col-4 columns">
                                Order ID
                            </div>
                            <div class="col-md-4 col-4 columns">
                                OT/DIS
                            </div>
                            <div class="col-md-4 col-4 columns">
                                Date
                            </div>
                        </div>
                        <div class="customers">
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 pendding">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6  col-4">
                        <span class="green delpik">Pick up</span>   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div><div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4 succes">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 col-4">
                        <span  class="green delpik">Pick up</span>:   2/2/20220 
                            </div>
                            <div class="col-md-6 col-4">
    <spon class="red delpik">Delivery</spon>
  2/2/2020</div>
                        </div><div class="row">
                            <div class="col-md-4 col-4">
                                39292
                            </div>
                            <div class="col-md-4 col-4">
                                <select name="" id="">
                                    <option value="sami">Abdul-Sami</option>
                                    <option value="Wassi">Abdul-Wassi</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-4">
                                11/4
                            </div>
                        </div>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection


@section('extraScript')

    <script>

        $(document).ready(function () {
            var data_get = 0;
            day_count(data_get);
            countdonwn();

            function day_count(data_get) {
                const stauss = [2, 3, 4, 5, 6, 7, 8, 9, 10];
                if (data_get <= 8) {
                    setTimeout(function () {
                        $.ajax({
                            url: "/fetch_day22",
                            type: "get",
                            data: {pstatus: stauss[data_get]},
                            success: function (data) {
                                data_get++;
                                $('#table_data').append(data);
                                day_count(data_get);
                            }
                        });
                    }), 2000
                }
            }


            setInterval(function () {
                $('#table_data').html('');
                data_get = 0;
                day_count(data_get);
                countdonwn();

            }, 30000*2);


            function countdonwn() {
                var timer2 = "1:00";
                var interval = setInterval(function() {


                    var timer = timer2.split(':');
                    //by parsing integer, I avoid all extra string processing
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);
                    --seconds;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    if (minutes < 0) clearInterval(interval);
                    seconds = (seconds < 0) ? 59 : seconds;
                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                    //minutes = (minutes < 10) ?  minutes : minutes;
                    $('.countdown').html(minutes + ':' + seconds);
                    timer2 = minutes + ':' + seconds;
                }, 1000);
            }


        });

    </script>



@endsection
