@extends('layouts.innerpages')

@section('template_title')
    JD REPORT
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')
    <style>
        select.form-control:not([size]):not([multiple]) {
            height: calc(1.5005rem + 5px);
        }
        .form-control, .dataTables_filter input {
            padding: 0.1rem 0.75rem;
        }
        h5{
            text-decoration:underline;
        }
        .jd ul{
            list-style-type: unset;
            padding: revert;  
        }
        .jd h5,.jd li,.jd span{
            user-select:none;
            color: #000 !important;
        }
        .jd li{
            margin-top:10px;
        }
    </style>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif
            <div class="card mt-2">
                <h2 class="card-header d-flex justify-content-center">JD REPORT</h2>
                <div class="card-body">
                    <div class="jd">
                        <h5>SPECIAL INSTRUCTION:</h5>
                        <span>Dispatchers must have to call the driver at least twice before sending a text message, and if they fail to follow this guideline, they will be fined Rs.200.</span>
                        <br>
                        <br>
                        <h5>Payment Method to Drivers:</h5>
                        <nav>
                            <ul>
                                <li>QUICK PAY</li>
                                <span>QuickPay (We pay the carrier with an e-cheque in two working days after the driver delivers us the signed bill of lading/stamp dock receipt.)</span>
                                <li>COD</li>
                                <span>When the driver delivers the car to the address, he will be paid.</span>
                                <li>CASHAPP</li>
                                <span>(Certified in an emergency only)</span>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>RELISTING:</h5>
                        <nav>
                            <ul>
                                <li>We relist the vehicle daily until we find a carrier or the order is canceled.</li>
                                <li>We proceed forward for the look-alike body shape and relist the vehicle in reverse ID if the vehicle post becomes alphabetically old and does not receive any carrier changes.  </li>
                                <li>To receive the carrier, we update daily pricing offered by the Order Takers.</li>
                                <li>We relist the vehicle with the reverse IDs when we schedule a car to someone for a later date.</li>
                                <li>If we schedule a car for a specific day at a higher price and require someone at a lower price, we execute a reverse relist on a lower price.</li>
                                <li>If a vehicle is listed on a port in any specific state, we have to relist it to a nearby port as soon as possible.</li>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>AUCTION UPDATE:</h5>
                        <span>In the auction update, we phone the auction and verify the buyer number and stock/lot number. After that, we ask them a few questions about the car.</span>
                        <nav>
                            <ul>
                                <li>Is the automobile fully paid for or does it have any remaining balance?</li>
                                <li>When is the storage going to begin?</li>
                                <li>Address confirmation</li>
                                <li>Do you have the title and keys? Will it be provided to the driver or mailed?</li>
                                <li>If the car is in a subplot, we inquire about the subplot's address as well as the title/keys. Is the car in the main lot or the subplot?</li>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>CARRIER UPDATE:</h5>
                        <span>When a driver contacts us for a job, we ask and perform the following.</span>
                        <nav>
                            <ul>
                                <li>First, we inspect the vehicle's condition.</li>
                                <li>Tell the driver the collection and delivery locations, as well as the car and price, if he accepts.</li>
                                <li>Inquire about the carrier's identity and confirm that the carrier's insurance is active; it will not be a desi driver because we haven't given him the negative or blocked him.</li>
                                <li>Once everything with the carrier is in order, we question him about the timetable; if it is picked up from the auction, it will only be picked up Monday through Friday from 8 a.m. to 4 p.m.; if it is picked up from the business, we schedule with our customer’s timeframe and provide the information to the driver as we always give a general piece of timeframe that is 9 am to 4 pm.</li>
                                <li>If an automobile is inoperable, we inform the driver and advise him to place the vehicle at the very bottom of his trailer (if he asks about the forklift at the time of delivery we will tell him that help will be provided but you need to put the car at the last bottom of the trailer)</li>
                                <li>If there is any storage on the car, we tell him that there is $xyz in the storage on this car and that if he pays it in cash, we will reimburse you that amount when you send us the st receipt at the time of delivery (if he doesn't agree to pay, we tell other departments to clear the st from the customer side).</li>
                                <li>If Owes is mentioned in the listings, we have to be aware of the driver about it to collect our pending balance from clients. </li>
                                <li>The vehicle going to the port in the Grimaldi needs Twic Card. We have to confirm with the driver he has it. </li>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>PICKUP SHEET:</h5>
                        <span>We call the driver and inform him or her that we have scheduled the load (car name) for you to pick up. IF THE ANSWER IS YES, THEN</span>
                        <nav>
                            <ul>
                                <li>Did you pay any storage fees while you were there? If that's the case, could you please send me the storage receipt?</li>
                                <li>Do you have the title and keys?</li>
                                <li>What is the present condition of the car, and is it in a driveable or rollable condition?</li>
                                <li>When are you ready to deliver this?</li>
                                <li>Where did you manage to keep the car in your trailer, was it on an upper deck or lower deck?</li>
                                <br><br>
                                <span>IF NO THEN,</span>
                                <li>When will you be able to pick this up, any estimated time?</li>
                                <li>Once you've picked it up, must notify us, and must pick it up before 4 p.m.</li>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>Scenario-Based (If the driver does not attend the call)</h5>
                        <nav>
                            <ul>
                                <li>Same-day delivery pickup should be prioritized early morning.</li>
                                <li>CH will be checked for late-night & non-paid vehicles schedules.</li>
                                <li>After the CH, ask the driver for the pickup Timeframe. If the vehicle is not ready, ask the order taker i.e (when the vehicle will be ready, any timeframe? If the vehicle is not ready on the given timeframe of the order taker then we will have to stop the driver)</li>
                                <li>Call drivers for their estimated pickup timeframe regularly in the first hours.</li>
                                <li>The working must be started on the pickup Sheet and must be maintained properly from 9 pm onwards in Pakistan</li>
                                <li>If any driver is delaying a vehicle’s pickup then it should be relisted in reverse. </li>
                                <li>If the driver does not pick up the vehicle on the same day, he will have to bear the storage and if he doesn’t pick it up on the same day or the next day without any reason he will be reviewed negatively on Central.</li>
                                <li>If any driver is continuously not responding then the ID should be relisted.</li>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>AUCTION TO BUSINESS SCHEDULING: </h5>
                        <span>If the auction update is complete, we proceed to schedule the vehicles. Then we schedule it by assigning and mentioning all of the details, such as pickup and delivery times and days, scheduling nonrunner vehicles in nonrunning mode, and running and driving in running mode. </span>
                        <span>In Quick Pay Job:</span>
                        <nav>
                            <ul>
                                <li>we mention payment to the carrier then 0 in COD/COP amount then we click on company check.</li>
                                <li>On the next step, it will be in 2 business days (quick pay)</li>
                                <li>We specify whether it is going to a business or a port there. We enter the VIN, and the car will automatically pull up instructions and other paperwork, as well as assign a schedule.</li>
                                <li>If the driver does not sign the job by a certain deadline, we will contact him to inform him that the load has already been scheduled</li>
                                <li>After schedule, we must enter the ID into the ShipA1 Portal's pickup and schedule sheet.</li>
                                <li>A vehicle going to the port is always a Quick Pay job.</li>
                                <li>Unless it is specified in the COD listings, a vehicle going to a shipping company will be a Quick Pay job only.</li>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>BUSINESS TO BUSINESS SCHEDULING:</h5>
                        <span>Before schedule, we have to schedule with the customer to give them the timeframe that the driver will pick up & deliver someday around any estimated time.</span>
                        <nav>
                            <ul>
                                <li>Must check with the customer regarding modification and ground clearance on private vehicles. </li>
                                <li>Make sure about the condition of the vehicle</li>
                                <li>Customer available timeframe</li>
                                <li>Will he give the title/keys to the driver?</li>
                                <li>Is there any extra weight on the vehicle?</li>
                                <li>Who will be there at the pickup & the delivery location?</li>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>APPROACHING: </h5>
                        <span>If we don't have a carrier on any car and storage has already begun, or if we require an urgent carrier, we will contact you.</span>
                        <span>We'll start by checking the car's pickup location, then look for a carrier in the same state, call him, and ask about the load (Hi Sir, this is Stephen from all state to state auto transport.) Is this MR someone sir? I'm calling you about my car, a Toyota, and I'm looking for a carrier who can pick it up from somewhere and deliver it to somewhere. Can you do it, my brother?)</span>
                        <br>
                        <br>
                        <h5>Scheduling From Other DayDispatch:</h5>
                        <span>We schedule the car from the DayDispatch account when the central account is suspended.</span>
                        <span>This account's driver is checked, as is the order id is in the ShipA1 portal.</span>
                        <span>We ask the driver to register on DayDispatch so that we can assign the load to them.</span>
                        <br>
                        <br>
                        <h5>IMPORTANT NOTES: </h5>
                        <nav>
                            <ul>
                                <li>Need driver’s COI on luxury vehicles</li>
                                <li>Need driver’s COI on delayed delivery vehicles</li>
                                <li>Need driver’s COI on late-night deliveries</li>
                                <li>Need driver’s COI on 2016 onwards vehicles.</li>
                                <li>Need driver’s COI on heavy vehicles.</li>
                                <li>Need driver’s  COI on ground clearance vehicle </li>
                                <li>For a business job, a driver must have cargo insurance.</li>
                                <li>For a vehicle coming from Manheim, ACV auctions, or any other dealership, a gate pass will be needed. </li>
                                <li>We have to call the Manheim or any other auction or dealership to confirm vehicle condition, address and forklift timings. </li>
                            </ul>
                        </nav>
                        <br>
                        <br>
                        <h5>INSURANCE REQUIREMENT:</h5>
                        <nav>
                            <ul>
                                <li>All schedulers must check the footer in the section on the Fmcsa Website.</li>
                                <li>The scheduler must check the Active/Pending Insurance if the posted</li>
                                <li>The date is less than 6 months which will be fine otherwise scheduler has to take the COI of the carrier.</li>
                                <li>All schedulers must check the authority history in Fmcsa it must be a MOTOR PROPERTY COMMON CARRIER and must be granted. </li>
                                <li>The scheduler must check the more above mention things before schedule</li>
                                <li>Active/Pending Insurance</li>
                                <li>Rejected Insurance</li>
                                <li>Insurance History </li>
                                <li>Authority History</li>
                                <li>Pending Application</li>
                                <li>Revocation</li>
                            </ul>
                        </nav>
                        <span>It is the responsibility of the room manager to get the sign of every dispatcher either old or new otherwise in case of consequences room manager will be responsible for it. </span>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->
@endsection