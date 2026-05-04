@extends('layouts.email')

@section('template_title')
    Send Code Mail
@endsection

@section('content')

    <?php
    $searchString = ',';
    ?>
    <tr>
        <td valign="top" align="center">
            <table width="600" bgcolor="#ffffff" align="center" cellspacing="0" cellpadding="0" border="0"
                   class="mobile-width">
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
                                          <!--                                           
										   <td height="30" valign="middle" align="left" class="center-stack"
                                                style="font-family: Open Sans, sans-serif;font-size:8px;font-weight:bold;">
                                                <a href="{{ $link1 }}" style="color: #1E9FF2;"><span
                                                        style="font-size: 35px;font-weight:bold;font-family: 'Lato', sans-serif;">ORDER LINK</span></a>
                                            </td>
											-->
											<td>
                                                <a href="{{ $link1 }}" style="color: #1E9FF2;">ORDER LINK</a>
                                            </td>
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
                        <table width="600" align="center" cellspacing="0" cellpadding="0" border="0"
                               class="mobile-width">
                            <tbody>
                            <tr>
                                <td align="center">

                                    <!-- Section 2 Start / Start Block Content -->
                                    <table  width="100%" cellspacing="0" cellpadding="0" border="0"
                                           class="full-width">
                                        <tbody>
                                        <!--<tr style="background: #00000085;">-->
                                        <!--<td class="front" style="font-family: Open Sans, sans-serif; font-size: 60px;mso-line-height-rule:exactly; line-height:60px; font-weight: bold; color: #ffffff;" align="center">HEAVY EQUIPMENT</td>-->
                                        <!--</tr>-->
                                        <!--<tr>-->
                                        <!--<td class="front" height="150" align="center"><img src="img/star.png" width="131" height="14" alt=""/></td>-->
                                        <!--</tr>-->
                                        <tr height="25"></tr>
                                        <tr>
										<!--
                                            <td class="front" height="200" align="center">&nbsp;
                                                <span style="font-size:100px; color:#fff; font-weight:bold;">
                                                    {{ $mainTxt }}
                                                </span>
                                            </td>
										-->	
										<td class="front"  align="center">&nbsp;
                                                
                                                    {{ $mainTxt }}
                                                
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
                                                <table width="600" align="center" cellspacing="0" cellpadding="0"
                                                       border="0" class="mobile-width">
                                                    <tbody>
                                                    <tr>
                                                        <td align="center">

                                                            <!-- Start Block Content -->
                                                            <table width="550" align="center" cellspacing="0"
                                                                   cellpadding="0" border="0" class="content-width">
                                                                <tbody>
                                                                <tr>
                                                                    <td valign="top" align="center">

                                                                        <!-- Start Column 1 -->
                                                                        <table align="center" cellspacing="0"
                                                                               cellpadding="0" border="0"
                                                                               class="midaling" style="width:100%">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td style=" mso-line-height-rule:exactly; line-height:50px; height:50px">
                                                                                    &nbsp;
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"
                                                                                    style="font-size:15px; mso-line-height-rule:exactly; line-height:15px; color:#2c3e50; font-weight:bold; font-family: Open Sans, sans-serif;">
                                                                                    HELLO, ADMIN!
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="30" align="left"><img
                                                                                        src="{{ url('images/email/green_line.jpg') }}"/>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="15">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="font-size:13px; line-height: 20px; mso-line-height-rule:exactly; color:#414848; font-weight:normal; font-family: Open Sans, sans-serif;"
                                                                                    align="left">
                                                                                    <strong style="color: #000;">
                                                                                        
                                                                                    </strong>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="32">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="30" align="center"><img
                                                                                        src="{{ url('images/email/green_line.jpg') }}"/>
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
                                    <table width="600" bgcolor="#1E9FF2" align="center" cellspacing="0" cellpadding="0"
                                           border="0" class="mobile-width">
                                        <tbody>
                                        <tr>
                                            <td align="center">
                                                <!-- Start Block Content -->
                                                <table width="550" align="center" cellspacing="0" cellpadding="0"
                                                       border="0" class="content-width">
                                                    <tbody>
                                                    <tr>
                                                        <td valign="top" td align="center">
                                                            <!-- Start Column 1 -->
                                                            <table align="center" cellspacing="0" cellpadding="0"
                                                                   border="0" class="full-width" width="100%">
                                                                <tbody>
                                                                <tr>
                                                                    <td height="50">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size:14px; height:20px; color:#ffffff; font-weight:normal; font-family: Open Sans, sans-serif;"
                                                                        align="center">Have questions? We’re here to
                                                                        help!
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="15">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size:14px; height:20px; color:#ffffff; font-weight:normal; font-family: Open Sans, sans-serif;"
                                                                        align="center">Do not hesitate call
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="15">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-family: Open Sans, sans-serif; font-size:28px;mso-line-height-rule:exactly; line-height:28px; font-weight:bold; color:#ffffff"
                                                                        align="center">+1 (800) 550-1515
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="18">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size:14px; height:20px; color:#ffffff; font-weight:normal; font-family: Open Sans, sans-serif;"
                                                                        align="center">OR Email:
                                                                        <a href="mailto:support@allstatetostateautotransport.com">support@allstatetostateautotransport.com</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="18">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size:12px; font-family: Open Sans, sans-serif; line-height:12px; color:#ffffff; font-weight:bold;"
                                                                        align="center">6700 Alexander Bell Dr Suite200,
                                                                        Columbia, MD 21046, USA
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="18">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="48">&nbsp;</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <!-- End Column 1 -->
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!-- End Block Content -->

                                                <!-- Start Space -->
                                                <table width="100%" bgcolor="#2c3e50" cellspacing="0" cellpadding="0"
                                                       border="0" class="full-width">
                                                    <tbody>
                                                    <tr>
                                                        <td height="28" align="center"
                                                            style="font-family: Open Sans, sans-serif;font-size:11px; font-weight:normal; color:#7f8c8d">
                                                            ALL STATE TO STATE AUTO TRANSPORT All Rights Reserved.
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!-- End Space -->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
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
