@extends('layouts.email')
<!--<div>-->
    <!--<p style=" background: white; margin: auto; font-family: sans-serif;">-->
        
@section('content')
<style>
    /*.title-shipa1 {*/
    /*        text-align: center;*/
    /*        background: white;*/
    /*        color: #19a4d7;*/
    /*}*/
    /*.container-title {*/
    /*   display: flex;background: #fefefe; column-gap: 17rem; justify-content: end;align-items: center;*/
    /*}*/
</style>

  
   <div style="display: flex; justify-content: space-between;background: white; padding-bottom: 14px;">
    <div style="flex: 0 0 16.666%; max-width: 16.666%;   width: 16.666%;"></div>
    <div style="flex: 0 0 66.666%; max-width: 66.666%;     width: 100%; text-align: center;">
        <h1 style="margin: 0; color: #19a4d7;" >{{ $content }} </h1>
    </div>
    <div style="flex: 0 0 16.666%;  width: 16.666%; max-width: 16.666%; text-align: right;">
      <a href="https://www.shipa1.com/" style="cursor: pointer;">  <button style="  
    padding: 10px 7px;
    background: #ededed;
    color: #19a4d7;
    border: none;
    border-radius: 10px;
    font-weight: 900;
    margin: 0px 14px;
    cursor: pointer;
    box-shadow: 0px 1px 3px grey;">VISIT OUR WEBSITE</button>
    </a>
    </div>
</div>
  

  
<!--        </p>-->
<!--</div>-->
    <div style="width: 50vw; margin: auto;">
        <div class="d-flex">
            <img src="{{ asset($banner) }}" alt="banner" height="50%" width="100%" />
        </div>
    </div>
@endsection
