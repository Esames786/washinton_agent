<style>
    select.form-control:not([size]):not([multiple]) {
        height: 40px;
        padding: 0px 10px;
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

    .fixedQandA ul::-webkit-scrollbar-thumb {
        background: #1e61a1;
        border-radius: 10px 0px 10px 0px;
    }

    .fixedQandA ul::-webkit-scrollbar {
        width: .6rem;
        background-color: #f1f1f1;
    }

    .ChatView {
        overflow: hidden;
        padding: 20px;
    }

    .ChatViewMain {
        overflow: hidden;
    }

    .ChatViewMain_d {
        padding: 5px;
    }

    .ChatViewMain {
        margin: 2rem auto;
    }

    .ChatViewHeading {
        background: #1e61a1;
    }

    .ChatViewMain .row {
        position: relative;
        margin: 0;
        z-index: 9;
        width: 100%;
        align-items: center;
    }

    .ChatViewMain .box {
        padding: 7px 10px;
        border: 1px solid #ddd;
        align-self: stretch;
    }

    .ChatViewHeading .box {
        font-weight: 700;
        color: #fff;
        font-size: 16px;
        border: 1px solid #002b5440;
    }

    .ChatViewMain_d .box span.price {
        font-weight: 600;
        color: #1e61a1;
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

    .swiper-slide:nth-child(2n) .ChatViewDecription {
        background: #f1f1f1;
    }


    .showbodybottom .showbodybottombox {
        text-align: justify;
    }

    .showbodyTop h6 {
        font-size: 20px;
    }

    .showbodybottom .showbodybottombox:nth-child(2n) {
        background: #f1f1f1;
        padding: 10px;
        margin: 10px auto;

    }

    .showmsgleft {
        padding: 10px;
        background: #f1f1f1;
    }

    .showmsgleft p {
        margin: 0;
        font-weight: 600;
    }

    .showtypeData {
        display: flex;
    }

    .showtypeData input {
        width: 100%;
        border-radius: 5px;
        height: 40px;
        padding-left: 10px;
        border: 1px solid #ddd;
    }

    .showtypeData button {
        width: fit-content;

    }

    .Chat__Body {
        max-height: 20rem;
        overflow: auto;
        position: relative;
        scroll-behavior: smooth;
        margin-bottom: 3rem;
        border: 1px solid #ddd;
        padding: 1rem;
    }

    .Chat__Body--box {
        /* display: flex; */
        gap: 1rem;


    }

    .Chat__Body--box+.Chat__Body--box {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #ddd;
    }

    .Chat__Body--box .Chat__Body--box--img img {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        object-fit: cover;
    }

    .Chat__Body--box--txt img {
        width: 100%;
        border-radius: 1rem;
    }

    .Chat__Body--box--txt p {
        border-radius: 1rem;
        padding: 1rem 2rem;
        margin-bottom: 0;
    }

    .leftChat .Chat__Body--box--txt p {
        background: #f1f1f1;

    }

    .fixedQandA {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        border: 1px solid #ddd;
        margin: 20px auto;
    }

    .fixedQandAbox {
        padding: 10px;
    }

    .fixedQandAbox h3 {
        font-size: 20px;
        font-weight: 600;
    }

    .fixedQandAbox select {
        height: 40px;
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 5px;
        outline: none;
    }

    .fixedQandA ul {
        max-height: 150px;
        overflow: auto;
        padding: 0;
        margin-top: 40px;
    }

    .fixedQandA ul li {
        display: flex;
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .fixedQandA input[type="radio"] {
        margin-right: 10px;
        top: 11px;
        position: relative;
    }

    .fixedQandA input[type="number"] {
        width: 80px;
        border-radius: 5px;
        height: 30px;
        border: 1px solid #ddd;
        margin: 0 10px;
        outline: none;
        padding-left: 10px;
    }

    .fixedQandA label {
        flex-wrap: wrap;
    }

    .rightChat {
        justify-content: flex-end;
        text-align: right;
    }

    .fixedleftnohidden {
        max-width: 0;
        display: inline-block;
        overflow: hidden;
        max-height: 0;
        position: relative;
    }

    .boxmain {
        background-color: #ebebeb;
        text-align: center;
        font-weight: 600;
        color: #1e61a1;
        font-size: 1.3rem;
    }

    .box .btn {
        padding: 4px 10px;
        font-size: 12px;
        position: relative;
        overflow: hidden;
    }

    .tabMain .tabMainbtn {
        display: flex;
            gap: 2rem 1rem;
    flex-wrap: wrap;
    }

    .tabMain .tabMainbtn .btn {
        background: #222;
        padding: .4rem 1rem;
        border: 1px solid #222;
        text-transform: uppercase;
    }


    .tabMain .tabMainbtn .btn.active {
        background: #1e61a1;
        font-weight: 600;
    }

    .tabMainbody .ChatViewMain#listed {
        display: block;
    }
/* 
    .tabMainbody .ChatViewMain {
        display: none;
    } */

    .ChatViewMain a {
        display: block;
    }

    dd {
        margin-bottom: 0;
    }

    .ChatViewMain table td,
    .ChatViewMain table th {
        border: 1px solid #ddd;
        padding: 10px;

    }

    table b {
        font-weight: 600;
    }

    .ChatViewMain table th {
        background: #1e61a1;
        color: #fff;
        font-weight: 600;
    }

    .ChatViewMain table {
        width: 100%;
    }

    .paginationmain {
        display: flex;
        justify-content: flex-end;
        margin: 1rem 1rem 0;
    }

    .searchFilter {
        color: #000;
        border: 1px solid #ddd;
        height: 40px;
        outline: none;
        width: fit-content;
        border-radius: 10px;
        padding-left: 6px;
    }

    .searchmain,
    .searchmainright {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.6rem;
    }

    .searchmain {
        margin-bottom: 1rem;
        border-bottom: 1px solid #ddd;
        padding: 1rem;
    }

    .searchmain span {
        min-width: 5.1rem;
        font-weight: 600;
    }

    .globalSearch {
        display: flex;
        justify-content: center;
        padding: 1rem 0;
        background: #1e61a1;
    }

    .globalSearch_ input {
        border: 1px solid #ddd;
        height: 2.4rem;
        border-radius: 0.6rem 0 0 0.6rem;
        width: 30rem;
        padding: 0 1rem;
    }

    .globalSearch_ button {
        border-radius: 0 0.6rem 0.6rem 0;
    }

    .globalSearch_ {
        display: flex;
        align-items: center;
        gap: 0rem;
    }

    .globalSearch_ span {
        color: #fff;
        font-weight: 600;
        position: relative;
        margin-right: 1rem;
    }

    .modal-content .ChatViewMain {
        padding: 0 1rem;
        width: 100%;
        margin: 1rem auto 0;
    }

    .Chat__Body::-webkit-scrollbar-thumb {
        background: #1e61a1;
        border-radius: 10px 0px 10px 0px;
    }

    .Chat__Body::-webkit-scrollbar {
        width: .6rem;
        background-color: #f1f1f1;
    }

    .searchmainright input {
        min-width: 22rem;
    }

    .vquest {
        position: relative;
        width: fit-content;
    }

    .vquest span {
        position: absolute;
        right: -10px;
        top: -13px;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: #1e61a1;
        display: grid;
        place-items: center;
        color: #fff;
        font-weight: 600;
    }
    td b {
        display: block;
    }
    tr.active {
        background: #ccc;
    }
    .side-app{
        padding: 45px 0 !important;
    }
    ul#fixedQandAulright li div,
    ul#fixedQandAulleft li div {
    padding: 0 1rem;
    display:flex;
}

ul#fixedQandAulright li div span,
ul#fixedQandAulleft li div span {
    max-width: 0;
    opacity: 0;
}
ul#fixedQandAulright li label,
ul#fixedQandAulleft li label  
{
    display:flex;
    align-items: center;
}


</style>