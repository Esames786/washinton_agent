@extends('layouts.email')

@section('template_title')
    Change Price
@endsection

@section('content')

    <?php
    $searchString = ',';
    ?>
    <tr>
        <td valign="top" align="center">
            <table width="600" bgcolor="#ffffff" align="center" cellspacing="0" cellpadding="0" border="0" class="mobile-width">
                <tbody>
                <tr>
                    <td valign="top" bgcolor="#ffffff" align="center">

                        <!-- Start Space -->
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="full-width">
                            <tbody>
                            <tr>
                                <td height="26">&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- End Space -->

                        <!-- Section 1 Starts / Logo & Menu -->
                        <table align="center" cellspacing="0" cellpadding="0" border="0" class="content-width-menu">
                            <tbody>
                            <tr>
                                <td height="25" valign="middle">

                                    <!-- Start Logo -->
                                    <table align="center" cellspacing="0" cellpadding="0" border="0" class="full-width">
                                        <tbody>
                                        <tr>
                                            <td height="30" valign="middle" align="left" class="center-stack"  style="font-family: Open Sans, sans-serif;font-size: 18px;font-weight:bold;" ><a href="#" style="color: #1E9FF2;"><span style="font-size: 35px;font-weight:bold;font-family: 'Lato', sans-serif;">All State To State Auto Transport</span></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!-- End Logo -->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Section 1 Ends / Logo & Menu -->

                        <!-- Start Space -->
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="full-width">
                            <tbody>
                            <tr>
                                <td height="25">&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- End Space -->

                        <!-- After Menu Start -->
                        <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="mobile-width">
                            <tbody>
                            <tr>
                                <td align="center">

                                    <!-- Section 2 Start / Start Block Content -->
                                    <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" class="full-width">
                                        <tbody>
                                        <!--<tr style="background: #00000085;">-->
                                        <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                        <!--</tr>-->
                                        <!--<tr>-->
                                        <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                        <!--</tr>-->
                                        <tr height="25"></tr>
                                        <tr>
                                            <td class="front" height="200" align="center">&nbsp;
                                                <span style="font-size:100px; color:#fff; font-weight:bold;">
                                                                ${{ $order->payment }}
                                                        </span>
                                                <span style="font-size:50px; color:#fff; font-weight:bold;">
                                                            @if($order->changeprice_count == 1)
                                                        <del>${{ $order->changeprice_1st }}</del>
                                                    @elseif($order->changeprice_count == 2)
                                                        <del>${{ $order->changeprice_2nd }}</del>
                                                    @elseif($order->changeprice_count >= 3)
                                                        <del>${{ $order->changeprice_3rd }}</del>
                                                    @endif
                                                        </span>
                                            </td>
                                        </tr>
                                        <tr height="25"></tr>
                                        </tbody>
                                    </table>
                                    <!-- Section 3 Start -->

                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="full-width">
                                        <tbody>
                                        <tr>
                                            <td align="center">
                                                <!-- Left Part Start -->
                                                <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="mobile-width">
                                                    <tbody>
                                                    <tr>
                                                        <td align="center">

                                                            <!-- Start Block Content -->
                                                            <table width="550" align="center" cellspacing="0" cellpadding="0" border="0" class="content-width">
                                                                <tbody>
                                                                <tr>
                                                                    <td valign="top" align="center">

                                                                        <!-- Start Column 1 -->
                                                                        <table align="center" cellspacing="0" cellpadding="0" border="0" class="midaling" style="width:100%">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td style=" mso-line-height-rule:exactly; line-height:50px; height:50px">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" style="font-size:15px; mso-line-height-rule:exactly; line-height:15px; color:#2c3e50; font-weight:bold; font-family: Open Sans, sans-serif;">HELLO {{ strtoupper($order->cname) }}!</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="30" align="left"><img src="{{ url('images/email/green_line.jpg') }}" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                                @if($id == 1)
                                                                                    @if($order->changeprice_count == 1)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">We have Discounted price for your shipping quote <strong style="color: #000">#{{ $order->id }}</strong>
                                                                                            to transport your
                                                                                            <strong style="color: #000">
                                                                                                @if(strpos($order->vmake, $searchString) !== false)
                                                                                                    <?php
                                                                                                    $make = explode(',',$order->vmake);
                                                                                                    $model = explode(',',$order->vmodel);
                                                                                                    $year = explode(',',$order->vyear);
                                                                                                    $numItems = count($make);
                                                                                                    $i = 0;
                                                                                                    ?>
                                                                                                    @if(count($make) >= 1)
                                                                                                        @for($a = 0; $a < count($make); $a++)
                                                                                                            @if(isset($year[$a]))
                                                                                                                {{ ucwords($year[$a]) }}
                                                                                                            @endif
                                                                                                            @if(isset($make[$a]))
                                                                                                                {{ ucwords($make[$a]) }}
                                                                                                            @endif
                                                                                                            @if(isset($model[$a]))
                                                                                                                {{ ucwords($model[$a]) }}
                                                                                                            @endif
                                                                                                            @if(++$i !== $numItems) | @endif
                                                                                                        @endfor
                                                                                                    @endif
                                                                                                @else
                                                                                                    @if($order->vtype == 1 || $order->vtype == '')
                                                                                                        @if($order->vyear > 0)
                                                                                                            {{ $order->vyear }} {{ $order->vmake }} {{ $order->vmodel }}
                                                                                                        @else
                                                                                                            {{ $order->vmake }} {{ $order->vmodel }}
                                                                                                        @endif
                                                                                                    @else
                                                                                                        {{ $order->heading }}
                                                                                                    @endif
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            of
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @elseif($order->changeprice_count == 2)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">As you can see we’re now offering
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}
                                                                                            </strong>
                                                                                            which is the lowest market rate for your
                                                                                            <strong style="color: #000">
                                                                                                @if(strpos($order->vmake, $searchString) !== false)
                                                                                                    <?php
                                                                                                    $make = explode(',',$order->vmake);
                                                                                                    $model = explode(',',$order->vmodel);
                                                                                                    $year = explode(',',$order->vyear);
                                                                                                    $numItems = count($make);
                                                                                                    $i = 0;
                                                                                                    ?>
                                                                                                    @if(count($make) >= 1)
                                                                                                        @for($a = 0; $a < count($make); $a++)
                                                                                                            @if(isset($year[$a]))
                                                                                                                {{ ucwords($year[$a]) }}
                                                                                                            @endif
                                                                                                            @if(isset($make[$a]))
                                                                                                                {{ ucwords($make[$a]) }}
                                                                                                            @endif
                                                                                                            @if(isset($model[$a]))
                                                                                                                {{ ucwords($model[$a]) }}
                                                                                                            @endif
                                                                                                            @if(++$i !== $numItems) | @endif
                                                                                                        @endfor
                                                                                                    @endif
                                                                                                @else
                                                                                                    @if($order->vtype == 1 || $order->vtype == '')
                                                                                                        @if($order->vyear > 0)
                                                                                                            {{ $order->vyear }} {{ $order->vmake }} {{ $order->vmodel }}
                                                                                                        @else
                                                                                                            {{ $order->vmake }} {{ $order->vmodel }}
                                                                                                        @endif
                                                                                                    @else
                                                                                                        {{ $order->heading }}
                                                                                                    @endif
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            including
                                                                                            <strong style="color: #000">
                                                                                                all taxes, insurance and all shipping cost applied.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @elseif($order->changeprice_count >= 3)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">As you can see we’re the offering
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}
                                                                                            </strong>
                                                                                            lowest market rates for your
                                                                                            <strong style="color: #000">
                                                                                                @if(strpos($order->vmake, $searchString) !== false)
                                                                                                    <?php
                                                                                                    $make = explode(',',$order->vmake);
                                                                                                    $model = explode(',',$order->vmodel);
                                                                                                    $year = explode(',',$order->vyear);
                                                                                                    $numItems = count($make);
                                                                                                    $i = 0;
                                                                                                    ?>
                                                                                                    @if(count($make) >= 1)
                                                                                                        @for($a = 0; $a < count($make); $a++)
                                                                                                            @if(isset($year[$a]))
                                                                                                                {{ ucwords($year[$a]) }}
                                                                                                            @endif
                                                                                                            @if(isset($make[$a]))
                                                                                                                {{ ucwords($make[$a]) }}
                                                                                                            @endif
                                                                                                            @if(isset($model[$a]))
                                                                                                                {{ ucwords($model[$a]) }}
                                                                                                            @endif
                                                                                                            @if(++$i !== $numItems) | @endif
                                                                                                        @endfor
                                                                                                    @endif
                                                                                                @else
                                                                                                    @if($order->vtype == 1 || $order->vtype == '')
                                                                                                        @if($order->vyear > 0)
                                                                                                            {{ $order->vyear }} {{ $order->vmake }} {{ $order->vmodel }}
                                                                                                        @else
                                                                                                            {{ $order->vmake }} {{ $order->vmodel }}
                                                                                                        @endif
                                                                                                    @else
                                                                                                        {{ $order->heading }}
                                                                                                    @endif
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            including
                                                                                            <strong style="color: #000">
                                                                                                all taxes, insurance and all shipping cost applied.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @endif
                                                                                @elseif($id == 2)
                                                                                    @if($order->changeprice_count == 1)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">We have Discounted price for your shipping quote <strong style="color: #000">#{{ $order->id }}</strong>
                                                                                            to transport your
                                                                                            <strong style="color: #000">
                                                                                                @if($order->stype == 1 || $order->stype == "")
                                                                                                    @if(strpos($order->vmake, '^*-') !== false)
                                                                                                        <?php
                                                                                                        $make = explode('^*-',$order->vmake);
                                                                                                        $model = explode('^*-',$order->vmodel);
                                                                                                        $i = 0;
                                                                                                        $len = count($make);
                                                                                                        $heading = "";
                                                                                                        foreach($make as $key => $m) {
                                                                                                            if (++$i == $len) {
                                                                                                                $heading .= $m .' '. $model[$key];
                                                                                                            } else {
                                                                                                                $heading .= $m .' '. $model[$key] . '^*-';
                                                                                                            }

                                                                                                        }
                                                                                                        ?>
                                                                                                    @else
                                                                                                        <?php
                                                                                                        $heading = $order->vmake . ' ' . $order->vmodel;
                                                                                                        ?>
                                                                                                    @endif
                                                                                                    {{ Common::getMultiDataWOTooltipMail($heading) }}
                                                                                                @else
                                                                                                    {{ Common::getMultiDataWOTooltipMail($order->heading) }}
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            of
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @elseif($order->changeprice_count == 2)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">As you can see we’re now offering
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}
                                                                                            </strong>
                                                                                            which is the lowest market rate for your
                                                                                            <strong style="color: #000">
                                                                                                @if($order->stype == 1 || $order->stype == "")
                                                                                                    @if(strpos($order->vmake, '^*-') !== false)
                                                                                                        <?php
                                                                                                        $make = explode('^*-',$order->vmake);
                                                                                                        $model = explode('^*-',$order->vmodel);
                                                                                                        $i = 0;
                                                                                                        $len = count($make);
                                                                                                        $heading = "";
                                                                                                        foreach($make as $key => $m) {
                                                                                                            if (++$i == $len) {
                                                                                                                $heading .= $m .' '. $model[$key];
                                                                                                            } else {
                                                                                                                $heading .= $m .' '. $model[$key] . '^*-';
                                                                                                            }

                                                                                                        }
                                                                                                        ?>
                                                                                                    @else
                                                                                                        <?php
                                                                                                        $heading = $order->vmake . ' ' . $order->vmodel;
                                                                                                        ?>
                                                                                                    @endif
                                                                                                    {{ Common::getMultiDataWOTooltipMail($heading) }}
                                                                                                @else
                                                                                                    {{ Common::getMultiDataWOTooltipMail($order->heading) }}
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            including
                                                                                            <strong style="color: #000">
                                                                                                all taxes, insurance and all shipping cost applied.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @elseif($order->changeprice_count >= 3)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">As you can see we’re the offering
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}
                                                                                            </strong>
                                                                                            lowest market rates for your
                                                                                            <strong style="color: #000">
                                                                                                @if($order->stype == 1 || $order->stype == "")
                                                                                                    @if(strpos($order->vmake, '^*-') !== false)
                                                                                                        <?php
                                                                                                        $make = explode('^*-',$order->vmake);
                                                                                                        $model = explode('^*-',$order->vmodel);
                                                                                                        $i = 0;
                                                                                                        $len = count($make);
                                                                                                        $heading = "";
                                                                                                        foreach($make as $key => $m) {
                                                                                                            if (++$i == $len) {
                                                                                                                $heading .= $m .' '. $model[$key];
                                                                                                            } else {
                                                                                                                $heading .= $m .' '. $model[$key] . '^*-';
                                                                                                            }

                                                                                                        }
                                                                                                        ?>
                                                                                                    @else
                                                                                                        <?php
                                                                                                        $heading = $order->vmake . ' ' . $order->vmodel;
                                                                                                        ?>
                                                                                                    @endif
                                                                                                    {{ Common::getMultiDataWOTooltipMail($heading) }}
                                                                                                @else
                                                                                                    {{ Common::getMultiDataWOTooltipMail($order->heading) }}
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            including
                                                                                            <strong style="color: #000">
                                                                                                all taxes, insurance and all shipping cost applied.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @endif
                                                                                @elseif($id == 3)
                                                                                    @if($order->changeprice_count == 1)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">We have Discounted price for your shipping quote <strong style="color: #000">#{{ $order->id }}</strong>
                                                                                            to transport your
                                                                                            <strong style="color: #000">
                                                                                                @if($order->htype == 1 || $order->htype == "")
                                                                                                    @if(strpos($order->vmake, '^*-') !== false)
                                                                                                        <?php
                                                                                                        $make = explode('^*-',$order->vmake);
                                                                                                        $model = explode('^*-',$order->vmodel);
                                                                                                        $i = 0;
                                                                                                        $len = count($make);
                                                                                                        $heading = "";
                                                                                                        foreach($make as $key => $m) {
                                                                                                            if (++$i == $len) {
                                                                                                                $heading .= $m .' '. $model[$key];
                                                                                                            } else {
                                                                                                                $heading .= $m .' '. $model[$key] . '^*-';
                                                                                                            }

                                                                                                        }
                                                                                                        ?>
                                                                                                    @else
                                                                                                        <?php
                                                                                                        $heading = $order->vmake . ' ' . $order->vmodel;
                                                                                                        ?>
                                                                                                    @endif
                                                                                                    {{ Common::getMultiDataWOTooltipMail($heading) }}
                                                                                                @else
                                                                                                    {{ Common::getMultiDataWOTooltipMail($order->heading) }}
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            of
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @elseif($order->changeprice_count == 2)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">As you can see we’re now offering
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}
                                                                                            </strong>
                                                                                            which is the lowest market rate for your
                                                                                            <strong style="color: #000">
                                                                                                @if($order->htype == 1 || $order->htype == "")
                                                                                                    @if(strpos($order->vmake, '^*-') !== false)
                                                                                                        <?php
                                                                                                        $make = explode('^*-',$order->vmake);
                                                                                                        $model = explode('^*-',$order->vmodel);
                                                                                                        $i = 0;
                                                                                                        $len = count($make);
                                                                                                        $heading = "";
                                                                                                        foreach($make as $key => $m) {
                                                                                                            if (++$i == $len) {
                                                                                                                $heading .= $m .' '. $model[$key];
                                                                                                            } else {
                                                                                                                $heading .= $m .' '. $model[$key] . '^*-';
                                                                                                            }

                                                                                                        }
                                                                                                        ?>
                                                                                                    @else
                                                                                                        <?php
                                                                                                        $heading = $order->vmake . ' ' . $order->vmodel;
                                                                                                        ?>
                                                                                                    @endif
                                                                                                    {{ Common::getMultiDataWOTooltipMail($heading) }}
                                                                                                @else
                                                                                                    {{ Common::getMultiDataWOTooltipMail($order->heading) }}
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            including
                                                                                            <strong style="color: #000">
                                                                                                all taxes, insurance and all shipping cost applied.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @elseif($order->changeprice_count >= 3)
                                                                                        <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">As you can see we’re the offering
                                                                                            <strong style="color: #000">
                                                                                                ${{ $order->payment }}
                                                                                            </strong>
                                                                                            lowest market rates for your
                                                                                            <strong style="color: #000">
                                                                                                @if($order->htype == 1 || $order->htype == "")
                                                                                                    @if(strpos($order->vmake, '^*-') !== false)
                                                                                                        <?php
                                                                                                        $make = explode('^*-',$order->vmake);
                                                                                                        $model = explode('^*-',$order->vmodel);
                                                                                                        $i = 0;
                                                                                                        $len = count($make);
                                                                                                        $heading = "";
                                                                                                        foreach($make as $key => $m) {
                                                                                                            if (++$i == $len) {
                                                                                                                $heading .= $m .' '. $model[$key];
                                                                                                            } else {
                                                                                                                $heading .= $m .' '. $model[$key] . '^*-';
                                                                                                            }

                                                                                                        }
                                                                                                        ?>
                                                                                                    @else
                                                                                                        <?php
                                                                                                        $heading = $order->vmake . ' ' . $order->vmodel;
                                                                                                        ?>
                                                                                                    @endif
                                                                                                    {{ Common::getMultiDataWOTooltipMail($heading) }}
                                                                                                @else
                                                                                                    {{ Common::getMultiDataWOTooltipMail($order->heading) }}
                                                                                                @endif
                                                                                            </strong>
                                                                                            from
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->ocity }}, {{ $order->ostate }} {{ $order->ozip }}
                                                                                            </strong>
                                                                                            to
                                                                                            <strong style="color: #000">
                                                                                                {{ $order->dcity }}, {{ $order->dstate }} {{ $order->dzip }}
                                                                                            </strong>
                                                                                            including
                                                                                            <strong style="color: #000">
                                                                                                all taxes, insurance and all shipping cost applied.
                                                                                            </strong>
                                                                                        </td>
                                                                                    @endif
                                                                                @endif
                                                                            </tr>
                                                                            @if($order->changeprice_count == 1)
                                                                                <tr>
                                                                                    <td height="15">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">
                                                                                        <strong style="color: #000;">
                                                                                            Please keep in mind our price is with all taxes, insurance and all shipping cost applied and with door to door service.
                                                                                        </strong>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            <tr>
                                                                                <td height="32">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="30" align="center"><img src="{{ url('images/email/green_line.jpg') }}" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <table align="center" cellspacing="0" cellpadding="0" border="0">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            @if($id == 1)
                                                                                                <td style="border:#1E9FF2 solid 1px; border-radius:4px; color:#1E9FF2; display:block; font-family: Open Sans, sans-serif; font-size:12px; font-weight:bold; mso-line-height-rule:exactly; line-height:12px; text-align:center; text-decoration:none; padding-top: 10px; padding-bottom: 10px; width:200px; -webkit-text-size-adjust:none;"><a style="color:#1E9FF2;font-family: Open Sans, sans-serif; font-size:13px; font-weight:800;" href="{{ url('order/'.base64_encode($order->id)) }}">COMPLETE BOOKING</a></td>
                                                                                            @elseif($id == 2)
                                                                                                <td style="border:#1E9FF2 solid 1px; border-radius:4px; color:#1E9FF2; display:block; font-family: Open Sans, sans-serif; font-size:12px; font-weight:bold; mso-line-height-rule:exactly; line-height:12px; text-align:center; text-decoration:none; padding-top: 10px; padding-bottom: 10px; width:200px; -webkit-text-size-adjust:none;"><a style="color:#1E9FF2;font-family: Open Sans, sans-serif; font-size:13px; font-weight:800;" href="{{ url('ship/order/'.base64_encode($order->id)) }}">COMPLETE BOOKING</a></td>
                                                                                            @elseif($id == 3)
                                                                                                <td style="border:#1E9FF2 solid 1px; border-radius:4px; color:#1E9FF2; display:block; font-family: Open Sans, sans-serif; font-size:12px; font-weight:bold; mso-line-height-rule:exactly; line-height:12px; text-align:center; text-decoration:none; padding-top: 10px; padding-bottom: 10px; width:200px; -webkit-text-size-adjust:none;"><a style="color:#1E9FF2;font-family: Open Sans, sans-serif; font-size:13px; font-weight:800;" href="{{ url('heavy/order/'.base64_encode($order->id)) }}">COMPLETE BOOKING</a></td>
                                                                                            @endif
                                                                                        </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="50">&nbsp;</td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <!-- End Column 1 -->

                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!-- Section 3 End -->

                                    <!-- Section 4 Start -->

                                    <!-- Section 6 Start -->
                                @include('partials.email.footer')
                                <!-- Section 6 End -->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- End Space -->
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

@endsection