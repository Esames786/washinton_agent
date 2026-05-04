@extends('layouts.email')

@section('template_title')
    Order Status Change to Delivered
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

                                @if($id == 1)
                                    @if($order->vtype == 1 || $order->vtype == '')
                                        <!-- Section 2 Start / Start Block Content -->
                                            <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" background="{{ url('images/email/veh.jpg') }}" style="background-repeat: no-repeat; !important; background-position: center center;background-size: cover;" class="full-width">
                                                <tbody>
                                                <!--<tr style="background: #00000085;">-->
                                                <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                                <!--</tr>-->
                                                <!--<tr>-->
                                                <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td class="front" height="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="front" height="50">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Section 2 End / End Block Content -->
                                    @elseif($order->vtype == 2)
                                        <!-- Section 2 Start / Start Block Content -->
                                            <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" background="{{ url('images/email/moto.jpg') }}" style="background-repeat: no-repeat; !important; background-position: center center;background-size: cover;" class="full-width">
                                                <tbody>
                                                <!--<tr style="background: #00000085;">-->
                                                <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                                <!--</tr>-->
                                                <!--<tr>-->
                                                <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td class="front" height="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="front" height="50">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Section 2 End / End Block Content -->
                                    @elseif($order->vtype == 3)
                                        <!-- Section 2 Start / Start Block Content -->
                                            <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" background="{{ url('images/email/heavy-equipment.jpg') }}" style="background-repeat: no-repeat; !important; background-position: center center;background-size: cover;" class="full-width">
                                                <tbody>
                                                <!--<tr style="background: #00000085;">-->
                                                <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                                <!--</tr>-->
                                                <!--<tr>-->
                                                <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td class="front" height="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="front" height="50">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Section 2 End / End Block Content -->
                                    @endif
                                @elseif($id == 2)
                                    @if($order->stype == 1 || $order->stype == '')
                                        <!-- Section 2 Start / Start Block Content -->
                                            <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" background="{{ url('images/email/veh.jpg') }}" style="background-repeat: no-repeat; !important; background-position: center center;background-size: cover;" class="full-width">
                                                <tbody>
                                                <!--<tr style="background: #00000085;">-->
                                                <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                                <!--</tr>-->
                                                <!--<tr>-->
                                                <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td class="front" height="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="front" height="50">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Section 2 End / End Block Content -->
                                    @elseif($order->stype == 2)
                                        <!-- Section 2 Start / Start Block Content -->
                                            <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" background="{{ url('images/email/moto.jpg') }}" style="background-repeat: no-repeat; !important; background-position: center center;background-size: cover;" class="full-width">
                                                <tbody>
                                                <!--<tr style="background: #00000085;">-->
                                                <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                                <!--</tr>-->
                                                <!--<tr>-->
                                                <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td class="front" height="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="front" height="50">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Section 2 End / End Block Content -->
                                    @elseif($order->stype == 3)
                                        <!-- Section 2 Start / Start Block Content -->
                                            <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" background="{{ url('images/email/heavy-equipment.jpg') }}" style="background-repeat: no-repeat; !important; background-position: center center;background-size: cover;" class="full-width">
                                                <tbody>
                                                <!--<tr style="background: #00000085;">-->
                                                <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                                <!--</tr>-->
                                                <!--<tr>-->
                                                <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td class="front" height="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="front" height="50">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Section 2 End / End Block Content -->
                                    @endif
                                @elseif($id == 3)
                                    @if($order->htype == 1 || $order->htype == '')
                                        <!-- Section 2 Start / Start Block Content -->
                                            <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" background="{{ url('images/email/veh.jpg') }}" style="background-repeat: no-repeat; !important; background-position: center center;background-size: cover;" class="full-width">
                                                <tbody>
                                                <!--<tr style="background: #00000085;">-->
                                                <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                                <!--</tr>-->
                                                <!--<tr>-->
                                                <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td class="front" height="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="front" height="50">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Section 2 End / End Block Content -->
                                    @else
                                        <!-- Section 2 Start / Start Block Content -->
                                            <table bgcolor="#666666" width="100%" cellspacing="0" cellpadding="0" border="0" background="{{ url('images/email/heavy-equipment.jpg') }}" style="background-repeat: no-repeat; !important; background-position: center center;background-size: cover;" class="full-width">
                                                <tbody>
                                                <!--<tr style="background: #00000085;">-->
                                                <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                                <!--</tr>-->
                                                <!--<tr>-->
                                                <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td class="front" height="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="front" height="50">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Section 2 End / End Block Content -->
                                    @endif
                                @endif

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
                                                                        <table align="left" cellspacing="0" cellpadding="0" border="0" class="midaling">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td style=" mso-line-height-rule:exactly; line-height:50px; height:50px">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" style="font-size:15px; mso-line-height-rule:exactly; line-height:15px; color:#2c3e50; font-weight:bold; font-family: Open Sans, sans-serif;">Dear {{ strtoupper($order->cname) }}!</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="30" align="left"><img src="{{ url('images/email/green_line.jpg') }}" /></td>
                                                                            </tr>

                                                                            @if($id == 1)
                                                                                <tr>
                                                                                    <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">
                                                                                        Your
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
                                                                                                    {{ $order->vyear }} {{ $order->vmake }} {{ $order->vmodel }}
                                                                                                @else
                                                                                                    {{ $order->heading }}
                                                                                                @endif
                                                                                            @endif
                                                                                        </strong> marked as Delivered.
                                                                                    </td>
                                                                                </tr>
                                                                            @elseif($id == 2)
                                                                                <tr>
                                                                                    <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="left">
                                                                                        Your
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
                                                                                                    {{ $order->vyear }} {{ $order->vmake }} {{ $order->vmodel }}
                                                                                                @else
                                                                                                    {{ $order->heading }}
                                                                                                @endif
                                                                                            @endif
                                                                                        </strong> marked as Delivered.
                                                                                    </td>
                                                                                </tr>
                                                                            @elseif($id == 3)

                                                                            @endif
                                                                            <tr>
                                                                                <td height="20" align="center"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <table align="center" cellspacing="0" cellpadding="0" border="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;" align="center">
                                                                                                    <strong style="color: #000">
                                                                                                        Thank you for using our service, we pride our selves in providing the best possible service for our customer, and so we’d like to hear your feedback  hope you love’d our service
                                                                                                    </strong>                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <table align="center" cellspacing="0" cellpadding="0" border="0">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td style="border:#1E9FF2 solid 1px; border-radius:4px; color:#1E9FF2; display:block; font-family: Open Sans, sans-serif; font-size:12px; font-weight:bold; mso-line-height-rule:exactly; line-height:12px; text-align:center; text-decoration:none; padding-top: 10px; padding-bottom: 10px; width:200px; -webkit-text-size-adjust:none;"><a style="color:#1E9FF2;font-family: Open Sans, sans-serif; font-size:13px; font-weight:800;" href="{{ url('feedback/'.$id.'/'.$order->id) }}">LEAVE FEEDBACK</a></td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
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
                                @include('partials.email.footer2')
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