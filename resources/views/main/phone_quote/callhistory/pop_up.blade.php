<?php
foreach ($data as $val) {
    $pstatus = $val->pstatus;
    $order_id = $val->id;
    $approve_pickup = $val->approve_pickup;
    $approve_deliver = $val->approve_deliver;
}
?>

@if ($pstatus == '0')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">New HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="1">Interested</option>
                        <option value="2">FollowMore</option>
                        <option value="3">AskingLow</option>
                        <option value="4">NotInterested</option>
                        <option value="5">NoResponse</option>
                        <option value="6">TimeQuote</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" id='expected_date' class="form-control">
                </div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>
            <div class="col-sm-6 col-md-12" id="ask_low">

            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif
@if ($pstatus == '2')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">FollowUp HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="1">Interested</option>
                        <option value="2">FollowMore</option>
                        <option value="3">AskingLow</option>
                        <option value="4">NotInterested</option>
                        <option value="5">NoResponse</option>
                        <option value="6">TimeQuote</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" id='expected_date' class="form-control">
                </div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>
            <div class="col-sm-6 col-md-12" id="ask_low">

            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif
@if ($pstatus == '1')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">INTERESTED HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>>
                        <option value="3">AskingLow</option>
                        <option value="5">NoResponse</option>
                        <option value="6">TimeQuote</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" id='expected_date' class="form-control">
                </div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>
            <div class="col-sm-6 col-md-12" id="ask_low">

            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>

        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif
@if ($pstatus == '3')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">ASKING LOW HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="1">Interested</option>
                        <option value="2">FollowMore</option>
                        <option value="4">NotInterested</option>
                        <option value="5">NoResponse</option>
                        <option value="6">TimeQuote</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" id='expected_date' class="form-control">
                </div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>
            <div class="col-sm-6 col-md-12" id="ask_low">

            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '4')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">NOT INTERESTED HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="1">Interested</option>
                        <option value="2">FollowMore</option>
                        <option value="3">AskingLow</option>
                        <option value="4">NotInterested</option>
                        <option value="5">NoResponse</option>
                        <option value="6">TimeQuote</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" id='expected_date' class="form-control">
                </div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>
            <div class="col-sm-6 col-md-12" id="ask_low">

            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '5')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">NOT RESPONDING HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="1">Interested</option>
                        <option value="2">FollowMore</option>
                        <option value="3">AskingLow</option>
                        <option value="4">NotInterested</option>
                        <option value="5">NoResponse</option>
                        <option value="6">TimeQuote</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" id='expected_date' class="form-control">
                </div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>
            <div class="col-sm-6 col-md-12" id="ask_low">

            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '6')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">TIME QUOTE HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="1">Interested</option>
                        <option value="2">FollowMore</option>
                        <option value="3">AskingLow</option>
                        <option value="4">NotInterested</option>
                        <option value="5">NoResponse</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" id='expected_date' class="form-control">
                </div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>
            <div class="col-sm-6 col-md-12" id="ask_low">

            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif
@if ($pstatus == '7')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">PAYMENT MISSING HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="7">PAYMENT MISSING</option>
                        <option value="9">LISTED</option>
                        <option value="19">ONAPPROVAL CANCEL</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" disabled id='expected_date'
                        class="form-control select_cancel">
                </div>
            </div>
            <div class="col-sm-5 col-md-5">
                <div class="form-group">
                    <label class="form-label">LISTED PRICE</label>
                    <input type="number" required name="listed_price" id='listed_price' disabled
                        class="form-control select_cancel">
                </div>
            </div>
            @php
                $ptype = 1;
                $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                if (!empty($query)) {
                    $ptype = $query['penal_type'];
                }

                if ($ptype == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } elseif ($ptype == 2) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                } elseif ($ptype == 3) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_test);
                } elseif ($ptype == 4) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_4);
                } elseif ($ptype == 5) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_5);
                } elseif ($ptype == 6) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_6);
                } else {
                    $phoneaccess = [];
                }
            @endphp
            @if (in_array('76', $phoneaccess))
                <?php
                $dis = \App\User::with('daily_ass')
                    ->whereHas('userRole', function ($q) {
                        $q->where('name', 'Dispatcher');
                    })
                    ->where('deleted', 0)
                    ->get();
                ?>
                <div class="col-sm-3 col-md-3 my-auto">
                    <button class="btn btn-primary" type="button" id="showingDispatchers" disabled
                        onclick="$('#showDispatchers').toggle();">Assign Dispatcher</button>
                </div>
                <div class="col-sm-4 col-md-4" id="showDispatchers" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Dispatchers <span class="text-muted">(Optional)</span></label>
                        <select name="dis_id" id='dis_id' class="form-control">
                            <option value="" selected disabled>Select</option>
                            @foreach ($dis as $key => $dispa)
                                <option value="{{ $dispa->id }}">
                                    {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                    ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control "></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row auctionupdate mb-2"></div>
        <script>
            $("#pstatus").on('change', function() {
                if ($(this).val() == 9) {
                    $(".auctionupdate").html(`
                        <input type="hidden" value="9" name="pstatus222">
                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                        <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                        <div class="col-md-4">
                            <label>Paid</label>
                            <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Storage</label>
                            <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Listed Price</label>
                            <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Auction Update</label>
                            <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                   value="">
                        </div>
                        <div class="col-md-4">
                            <label>Title</label>
                            <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Key</label>
                            <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Listed Count</label>
                            <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Old/New Price</label>
                            <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                   value="">
                        </div>
                        <div class="col-md-4">
                            <label>Vehicle Position</label>
                            <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                        </div>
                        <div class="col-md-12">
                            <label>Additional</label>
                            <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                        </div>
                    `);
                    $("#expected_date").attr('disabled', false);
                    $("#listed_price").attr('disabled', false);
                    $("#showingDispatchers").attr('disabled', false);
                } else if ($(this).val() == 7) {
                    $("#expected_date").attr('disabled', false);
                    $("#listed_price").attr('disabled', true);
                    $("#showingDispatchers").attr('disabled', true);
                    $("#showDispatchers").hide();
                    $("#dis_id").children('option').removeAttr('selected');
                    $("#dis_id").children('option').eq(0).attr('selected', true);
                } else {
                    $("#expected_date").attr('disabled', true);
                    $("#listed_price").attr('disabled', true);
                    $("#showingDispatchers").attr('disabled', true);
                    $("#showDispatchers").hide();
                    $("#dis_id").children('option').removeAttr('selected');
                    $("#dis_id").children('option').eq(0).attr('selected', true);
                    $(".auctionupdate").html('');
                }
            })
        </script>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif
@if ($pstatus == '8')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">BOOKED HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="8">BOOKED</option>
                        <option value="9">LISTED</option>
                        <option value="19">ONAPPROVAL CANCEL</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" disabled id='expected_date'
                        class="form-control">
                </div>
            </div>
            <div class="col-sm-5 col-md-5">
                <div class="form-group">
                    <label class="form-label">LISTED PRICE</label>
                    <input type="number" required name="listed_price" id='listed_price' disabled
                        class="form-control">
                </div>
            </div>
            @php
                $ptype = 1;
                $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                if (!empty($query)) {
                    $ptype = $query['penal_type'];
                }

                if ($ptype == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } elseif ($ptype == 2) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                } elseif ($ptype == 3) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_test);
                } elseif ($ptype == 4) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_4);
                } elseif ($ptype == 5) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_5);
                } elseif ($ptype == 6) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_6);
                } else {
                    $phoneaccess = [];
                }
            @endphp
            @if (in_array('76', $phoneaccess))
                <?php
                
                $dis = \App\User::with('daily_ass')
                    ->whereHas('userRole', function ($q) {
                        $q->where('name', 'Dispatcher');
                    })
                    ->where('deleted', 0)
                    ->get();
                ?>
                <div class="col-sm-3 col-md-3 my-auto">
                    <button class="btn btn-primary" type="button" id="showingDispatchers" disabled
                        onclick="$('#showDispatchers').toggle();">Assign Dispatcher</button>
                </div>
                <div class="col-sm-4 col-md-4" id="showDispatchers" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Dispatchers <span class="text-muted">(Optional)</span></label>
                        <select name="dis_id" id='dis_id' class="form-control">
                            <option value="" selected disabled>Select</option>
                            @foreach ($dis as $key => $dispa)
                                <option value="{{ $dispa->id }}">
                                    {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                    ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row auctionupdate mb-2"></div>
        <script>
            $("#pstatus").on('change', function() {
                if ($(this).val() == 9) {
                    $(".auctionupdate").html(`
                        <input type="hidden" value="9" name="pstatus222">
                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                        <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                        <div class="col-md-4">
                            <label>Paid</label>
                            <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Storage</label>
                            <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Listed Price</label>
                            <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Auction Update</label>
                            <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                   value="">
                        </div>
                        <div class="col-md-4">
                            <label>Title</label>
                            <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Key</label>
                            <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Listed Count</label>
                            <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Old/New Price</label>
                            <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                   value="">
                        </div>
                        <div class="col-md-4">
                            <label>Vehicle Position</label>
                            <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                        </div>
                        <div class="col-md-12">
                            <label>Additional</label>
                            <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                        </div>
                    `);
                    $("#expected_date").attr('disabled', false);
                    $("#listed_price").attr('disabled', false);
                    $("#showingDispatchers").attr('disabled', false);
                } else if ($(this).val() == 8) {
                    $("#expected_date").attr('disabled', false);
                    $("#listed_price").attr('disabled', true);
                    $("#showingDispatchers").attr('disabled', true);
                    $("#showDispatchers").hide();
                    $("#dis_id").children('option').removeAttr('selected');
                    $("#dis_id").children('option').eq(0).attr('selected', true);
                } else {
                    $("#expected_date").attr('disabled', true);
                    $("#listed_price").attr('disabled', true);
                    $("#showingDispatchers").attr('disabled', true);
                    $("#showDispatchers").hide();
                    $("#dis_id").children('option').removeAttr('selected');
                    $("#dis_id").children('option').eq(0).attr('selected', true);
                    $(".auctionupdate").html('');
                }
            })
        </script>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>

@endif
@if ($pstatus == '9')
    <form id="listedform" method="post" id="saveChangesForm" action="{{ route('call_history_post_relist') }}">
        @csrf
        <div class="card-title font-weight-bold">LISTED HISTORY/CHANGE
            STATUS:
        </div>

        <div class="row" id="row1">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}" id='order_id1'
                placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select required name="pstatus" id='pstatus' class="form-control  getcarrier">
                        <option value="" selected disabled>Select</option>
                        <option value="9">Listed</option>
                        <option value="10">Schedule</option>
                        <option value="19">ONAPPROVAL CANCEL</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">Select Carrier
                        <a href="javascript:;"
                            onclick="this.href='/carrier_add/'+ document.getElementById('order_id1').value"
                            type="button" target="_blank" class="btn btn-info btn-sm">UPDATE CARRIER</a>

                    </label>
                    <select id="current_carrier" class="form-control select_cancel" name="current_carrier" required
                        style=" height: auto; " disabled data-validation-required-message="This field is required">
                        <option value="">Please Add Carrier</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-5 col-md-5">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" disabled id='expected_date'
                        class="form-control select_cancel">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4 my-auto">
                                <div class="form-group d-flex m-auto">
                                    <input type="checkbox" disabled class="mr-2 already_late" name="already_late1"
                                        value="1">
                                    <label class="form-label my-auto">Already Storage Price</label>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 auction_already_late" style="display:none;">
                                <div class="form-group">
                                    <input type="text" name="already_storage" id='already_storage'
                                        class="form-control" placeholder="Enter Already Storage Price">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4 my-auto">
                                <div class="form-group d-flex m-auto">
                                    <input type="checkbox" disabled class="mr-2 already_late" name="already_late2"
                                        value="1">
                                    <label class="form-label my-auto">Late Pickup Storage Price</label>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 auction_already_late" style="display:none;">
                                <div class="form-group">
                                    <input type="text" name="late_pickup_storage" id='late_pickup_storage'
                                        class="form-control" placeholder="Enter Late Pickup Storage Price">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(".already_late").on('change', function() {
                    if ($(this).is(":checked")) {
                        $(this).parent('div').parent('div').siblings('.auction_already_late').show();
                    } else {
                        $(this).parent('div').parent('div').siblings('.auction_already_late').hide();
                    }
                })
            </script>
            <div class="col-sm-12">
                <div class="row auctionupdate mb-2"></div>
                <script>
                    $("#pstatus").on('change', function() {
                        if ($(this).val() == 10) {
                            $(".auctionupdate").html(`
                                <input type="hidden" value="10" name="pstatus222">
                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                <div class="col-md-12 text-center"><h4>Scheduling Sheet</h4></div>
                                <div class="col-md-4">
                                    <label>Pickedup Time</label>
                                    <input class="form-control" type="datetime-local" id="auc_pickedup" name="auc_pickedup" placeholder="=PickedUp time" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Delivery Time</label>
                                    <input class="form-control" type="datetime-local" id="auc_delivery_date" name="auc_delivery_date" placeholder="=Delivery time" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Dispatch Price</label>
                                    <input class="form-control" type="text" id="auc_price" name="auc_price" placeholder="Dispatch Price" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Vehicle Condition</label>
                                    <input class="form-control" type="text" id="auc_condition" name="auc_condition" placeholder="Vehicle Condition" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Storage</label>
                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Driver FMCSA (Active)?</label>
                                    <select id="auc_driver_fmcsa" name="auc_driver_fmcsa" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Carrier Rating</label>
                                    <input class="form-control" id="auc_carrier_rating" name="auc_carrier_rating" placeholder="Carrier Rating" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Verify FMCSA?</label>
                                    <input class="form-control" id="auc_fmcsa" name="auc_fmcsa" placeholder="FMCSA" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Date Of Insurance (FMCSA)</label>
                                    <input class="form-control" type="date" id="auc_insurance_date" name="auc_insurance_date" placeholder="Date Of Insurance (FMCSA)" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>COI Holder</label>
                                    <select id="auc_coi_holder" name="auc_coi_holder" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="Waiting">Waiting</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Is Vehicle Luxury?</label>
                                    <select id="auc_vehicle_luxury" name="auc_vehicle_luxury" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Aware Driver Delivery</label>
                                    <input class="form-control" type="text" id="auc_aware_driver_delivery_date" name="auc_aware_driver_delivery_date" placeholder="Aware Driver Delivery" required>
                                </div>
                                <div class="col-md-4">
                                    <label>New/Old Driver</label>
                                    <select id="auc_new_old_driver" name="auc_new_old_driver" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Old Driver">Old Driver</option>
                                        <option value="New Driver">New Driver</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Is Local?</label>
                                    <select id="auc_is_local" name="auc_is_local" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Job Accept</label>
                                    <input class="form-control" id="auc_job_accept" name="auc_job_accept" placeholder="Job Accept" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Title</label>
                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Key</label>
                                    <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Auction Update</label>
                                    <input id="auc_auction_update" name="auc_auction_update" class="form-control" placeholder="Auction Update" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Storage Pay</label>
                                    <select id="auc_who_pay_storage" name="auc_who_pay_storage" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Vehicle Position</label>
                                    <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Payment Method</label>
                                    <input class="form-control" id="auc_payment_method" name="auc_payment_method" placeholder="Payment Method" value="" required>
                                </div>
                                <div class="col-md-12">
                                    <label>Additional</label>
                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                </div>
                            `);
                            $("#expected_date").attr('disabled', false);
                            $("#current_carrier").attr('disabled', false);
                            $("input[name='already_late1']").attr('disabled', false);
                            $("input[name='already_late2']").attr('disabled', false);
                        } else if ($(this).val() == 9) {
                            $("#expected_date").attr('disabled', false);
                            $("#current_carrier").attr('disabled', true);
                            $("input[name='already_late1']").attr('disabled', true);
                            $("input[name='already_late1']").removeAttr('checked');
                            $("#already_storage").hide();
                            $("input[name='already_late2']").attr('disabled', true);
                            $("input[name='already_late2']").removeAttr('checked');
                            $("#late_pickup_storage").hide();
                        } else {
                            $("#expected_date").attr('disabled', true);
                            $("#current_carrier").attr('disabled', true);
                            $(".auctionupdate").html('');
                            $("input[name='already_late1']").attr('disabled', true);
                            $("input[name='already_late1']").removeAttr('checked');
                            $("#already_storage").hide();
                            $("input[name='already_late2']").attr('disabled', true);
                            $("input[name='already_late2']").removeAttr('checked');
                            $("#late_pickup_storage").hide();
                        }
                    })
                </script>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="row2" style="display: none">
            <div class="col-sm-1 col-md-1">
                <div class="form-group">
                    <label class="form-label">Relist</label>
                    <input style="width: 20px;height: 20px" onclick="showprice()" type="checkbox"
                        class="select_cancel" name="relist" id='relist'>
                </div>
            </div>

            <div class="col-sm-6 col-md-6" id="r1" style="display: none">
                <div class="form-group">
                    <label class="form-label">New Relist Price</label>
                    <input type="number" name="listed_price" id='relist_id' class="form-control">
                </div>
            </div>


        </div>

        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                <label class="form-label">HISTORY</label>
                <textarea required name="history_update" id='history_update' class="form-control"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '10')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">Schedule HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row" id="dipatchpickup">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}"
                id='order_id1' placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control  getpickupdate">
                        {{-- <option value="" selected disabled>Select</option> --}}
                        <option value="10" selected>Schedule</option>
                        <option value="34">Schedule To Another Driver</option>
                        <option value="11">Pickup</option>

                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">Select Carrier
                        <a href="javascript:;"
                            onclick="this.href='/carrier_add/'+ document.getElementById('order_id1').value"
                            type="button" target="_blank" class="btn btn-info btn-sm" id="carrier_add">UPDATE
                            CARRIER</a>

                    </label>
                    <select id="current_carrier" class="form-control select_cancel" name="current_carrier"
                        required style=" height: auto; " data-validation-required-message="This field is required">
                        <option value="">Please Add Carrier</option>
                    </select>
                </div>
            </div>
            <!--<div class="col-sm-6 col-md-6">-->
            <!--    <div class="form-group">-->
            <!--        <label class="form-label">EXPECTED DATE / DELIVER-->
            <!--            DATE</label>-->
            <!--        <input type="date" required name="expected_date"-->
            <!--               id='expected_date' -->
            <!--               class="form-control">-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-sm-6 col-md-6 pickupdatediv"></div>-->
            <div class="col-sm-6 col-md-6 pickupdatediv">
            </div>
            <div class="col-sm-12 col-md-12">
                <div id="vehicle_condition" style="display: flex; width: 100%;"></div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row auctionupdate mb-2"></div>
        <script>
            $("#pstatus").on('change', function() {
                if ($(this).val() == 11) {
                    $("#carrier_add").attr('disabled', true);
                    var order_id = $("#order_id1").val();
                    $.ajax({
                        url: "{{ url('/get_sheet') }}",
                        type: "GET",
                        data: {
                            order_id: order_id
                        },
                        dataType: "JSON",
                        success: function(res) {
                            var pickup = '';
                            if (res.pickup_date) {
                                pickup = res.pickup_date;
                            }
                            $(".pickupdatediv").html(`
                                <div class="form-group">
                                    <label class="form-label">PICKUP DATE</label>
                                    <input type="datetime-local" value="${pickup}" required name="pickup_date" 
                                    id='pickup_date' class="form-control">
                                    <input type="checkbox" name="approvalpickup" value="1"/>MARK AS APPROVED
                                </div>
                            `);
                        }
                    })
                    $.ajax({
                        url: "{{ url('/get_carrier2') }}",
                        type: "GET",
                        data: {
                            order_id: order_id
                        },
                        dataType: "JSON",
                        success: function(res) {
                            var phone1 = '';
                            if (res[0].driverphoneno) {
                                phone1 = res[0].driverphoneno;
                            }
                            var name = '';
                            if (res[0].companyname) {
                                name = res[0].companyname;
                            }
                            $(".auctionupdate").html(`
                                <input type="hidden" value="11" name="pstatus222">
                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                <div class="col-md-12 text-center"><h2>Pickup Sheet</h2></div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header justify-content-center"><h3 class="m-auto">Auction Status</h3></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Auction Status</label>
                                                    <input class="form-control" id="auc_auction_status1" name="auc_auction_status1" placeholder="Auction Status" value="" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Storage</label>
                                                    <input class="form-control" id="auc_storage1" name="auc_storage1" placeholder="Storage" value="" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Vehicle Condition</label>
                                                    <input class="form-control" id="auc_condition1" placeholder="Vehicle Condition" name="auc_condition1" value="" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Title</label>
                                                    <select id="auc_title_keys1" name="auc_title_keys1" class="form-control h-50" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Key</label>
                                                    <select id="auc_keys1" name="auc_keys1" class="form-control h-50" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Vehicle Position</label>
                                                    <input id="auc_vehicle_position1" name="auc_vehicle_position1" class="form-control" placeholder="Vehicle Position" value="" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Additional</label>
                                                    <input class="form-control" id="auc_additional1" placeholder="Additional" name="auc_additional1" value="" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header justify-content-center"><h3 class="m-auto">Driver Status</h3></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Driver Status</label>
                                                    <input class="form-control" id="auc_driver_status" name="auc_driver_status" placeholder="Driver Status" value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Company Name</label>
                                                    <input class="form-control" id="auc_company_name" name="auc_company_name" placeholder="Company Name" value="${name}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Driver Name</label>
                                                    <input class="form-control" id="auc_carrier_name" name="auc_carrier_name" placeholder="Driver Name" value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Driver Payment</label>
                                                    <input class="form-control" id="auc_driver_payment" name="auc_driver_payment" placeholder="Driver Payment" value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Driver No1#</label>
                                                    <input class="form-control driverphoneno" id="auc_driver_no" name="auc_driver_no" placeholder="Driver No1#" value="${phone1}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Driver No2#</label>
                                                    <input class="form-control driverphoneno" id="auc_driver_no2" name="auc_driver_no2" placeholder="Driver No2#" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Driver No3#</label>
                                                    <input class="form-control driverphoneno" id="auc_driver_no3" name="auc_driver_no3" placeholder="Driver No3#" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Driver No4#</label>
                                                    <input class="form-control driverphoneno" id="auc_driver_no4" name="auc_driver_no4" placeholder="Driver No4#" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Storage</label>
                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Delivery Datetime</label>
                                                    <input class="form-control" id="auc_delivery_date" type="datetime-local"  placeholder="Delivery Datetime" name="auc_delivery_date"
                                                           value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Vehicle Condition</label>
                                                    <input class="form-control" id="auc_condition" placeholder="Vehicle Condition" name="auc_condition" value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Vehicle Position</label>
                                                    <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Payment</label>
                                                    <select id="auc_payment" class="form-control h-50" name="auc_payment" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Payment Charged Or Owes</label>
                                                    <input class="form-control" id="auc_payment_charged_or_owes" name="auc_payment_charged_or_owes" required placeholder="Payment Charged Or Owes" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Payment Method</label>
                                                    <input class="form-control" id="auc_payment_method" name="auc_payment_method" required placeholder="Payment Method" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Total Amount (If Owed)</label>
                                                    <input class="form-control" id="auc_price" name="auc_price" required placeholder="Total Amount (If Owed)" value="">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Title</label>
                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Key</label>
                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Dock Receipt (If Port)</label>
                                                    <input class="form-control" id="auc_stamp_dock_receipt" name="auc_stamp_dock_receipt" placeholder="Dock Receipt (If Port)" value="" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Additional</label>
                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                            $(".driverphoneno").keypress(function(e) {
                                if ($(this).val() == '') {
                                    $(this).mask("(999) 999-9999");
                                }
                                var x = e.which || e.keycode;
                                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                                    return true;
                                } else {
                                    return false;
                                }
                            })
                        }
                    });
                } else if ($(this).val() == 34) {
                    $("#carrier_add").attr('disabled', false);
                    $(".auctionupdate").html(`
                        <input type="hidden" value="10" name="pstatus222">
                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                        <div class="col-md-12 text-center"><h4>Scheduling Sheet</h4></div>
                        <div class="col-md-4">
                            <label>Pickedup Time</label>
                            <input class="form-control" type="datetime-local" id="auc_pickedup" name="auc_pickedup" placeholder="=PickedUp time" required>
                        </div>
                        <div class="col-md-4">
                            <label>Delivery Time</label>
                            <input class="form-control" type="datetime-local" id="auc_delivery_date" name="auc_delivery_date" placeholder="=Delivery time" required>
                        </div>
                        <div class="col-md-4">
                            <label>Dispatch Price</label>
                            <input class="form-control" type="text" id="auc_price" name="auc_price" placeholder="Dispatch Price" required>
                        </div>
                        <div class="col-md-4">
                            <label>Vehicle Condition</label>
                            <input class="form-control" type="text" id="auc_condition" name="auc_condition" placeholder="Vehicle Condition" required>
                        </div>
                        <div class="col-md-4">
                            <label>Storage</label>
                            <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label>Driver FMCSA (Active)?</label>
                            <select id="auc_driver_fmcsa" name="auc_driver_fmcsa" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Carrier Rating</label>
                            <input class="form-control" id="auc_carrier_rating" name="auc_carrier_rating" placeholder="Carrier Rating" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label>Verify FMCSA?</label>
                            <input class="form-control" id="auc_fmcsa" name="auc_fmcsa" placeholder="FMCSA" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label>Date Of Insurance (FMCSA)</label>
                            <input class="form-control" type="date" id="auc_insurance_date" name="auc_insurance_date" placeholder="Date Of Insurance (FMCSA)" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label>COI Holder</label>
                            <select id="auc_coi_holder" name="auc_coi_holder" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Waiting">Waiting</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Is Vehicle Luxury?</label>
                            <select id="auc_vehicle_luxury" name="auc_vehicle_luxury" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Aware Driver Delivery</label>
                            <input class="form-control" type="text" id="auc_aware_driver_delivery_date" name="auc_aware_driver_delivery_date" placeholder="Aware Driver Delivery" required>
                        </div>
                        <div class="col-md-4">
                            <label>New/Old Driver</label>
                            <select id="auc_new_old_driver" name="auc_new_old_driver" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Old Driver">Old Driver</option>
                                <option value="New Driver">New Driver</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Is Local?</label>
                            <select id="auc_is_local" name="auc_is_local" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Job Accept</label>
                            <input class="form-control" id="auc_job_accept" name="auc_job_accept" placeholder="Job Accept" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label>Title</label>
                            <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Key</label>
                            <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Auction Update</label>
                            <input id="auc_auction_update" name="auc_auction_update" class="form-control" placeholder="Auction Update" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label>Storage Pay</label>
                            <select id="auc_who_pay_storage" name="auc_who_pay_storage" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Vehicle Position</label>
                            <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label>Payment Method</label>
                            <input class="form-control" id="auc_payment_method" name="auc_payment_method" placeholder="Payment Method" value="" required>
                        </div>
                        <div class="col-md-12">
                            <label>Additional</label>
                            <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                        </div>
                    `);
                    $(".pickupdatediv").html(`
                        <div class="form-group">
                            <label class="form-label">EXPECTED DATE</label>
                            <input type="date" required name="expected_date"
                                   id='expected_date' 
                                   class="form-control select_cancel">
                        </div>
                    `);
                } else {
                    $(".pickupdatediv").html('');
                    $("#carrier_add").attr('disabled', false);
                }
            })
        </script>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '11' && $approve_pickup == '1')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">PickedUp HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}"
                id='order_id1' placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="11" selected>Pickup</option>
                        <option value="12">Deliver</option>

                    </select>
                </div>
            </div>
            <!--<div class="col-sm-6 col-md-6">-->
            <!--    <div class="form-group">-->
            <!--        <label class="form-label">EXPECTED DATE</label>-->
            <!--        <input type="date" required name="expected_date"-->
            <!--               id='expected_date' -->
            <!--               class="form-control">-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-sm-6 col-md-6 pickupdatediv2">-->

            <!--</div>-->
            <div class="col-sm-6 col-md-6 deliverdate">

            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row auctionupdate mb-2"></div>
        <script>
            $("#pstatus").on('change', function() {
                if ($(this).val() == 12) {
                    var order_id = $("#order_id1").val();
                    $.ajax({
                        url: "{{ url('/get_sheet') }}",
                        type: "GET",
                        data: {
                            order_id: order_id
                        },
                        dataType: "JSON",
                        success: function(res) {
                            var delivery = '';
                            if (res.delivery_date) {
                                delivery = res.delivery_date;
                            }
                            $(".deliverdate").html(`
                                <div class="form-group">
                                    <label class="form-label">DELIVER DATE</label>
                                    <input required  type="datetime-local" value="${delivery}"  name="deliver_date" 
                                    id='deliver_date'class="form-control">
                                    <input type="checkbox" name="approvaldeliver" value="1"/> &nbsp; Mark AS APPROVED (DELIVER)
                                </div>
                            `);
                        }
                    })
                    $.ajax({
                        url: "{{ url('/get_carrier2') }}",
                        type: "GET",
                        data: {
                            order_id: order_id
                        },
                        dataType: "JSON",
                        success: function(res) {
                            var phone1 = '';
                            if (res[0].driverphoneno) {
                                phone1 = res[0].driverphoneno;
                            }
                            $(".auctionupdate").html(`
                                <input type="hidden" value="12" name="pstatus222">
                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                <div class="col-md-12 text-center"><h4>Delivery Sheet</h4></div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Driver No1#</label>
                                            <input class="form-control driverphoneno" id="auc_driver_no" name="auc_driver_no" placeholder="Driver No1#" value="${phone1}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Driver No2#</label>
                                            <input class="form-control driverphoneno" id="auc_driver_no2" name="auc_driver_no2" placeholder="Driver No2#" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Driver No3#</label>
                                            <input class="form-control driverphoneno" id="auc_driver_no3" name="auc_driver_no3" placeholder="Driver No3#" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Driver No4#</label>
                                            <input class="form-control driverphoneno" id="auc_driver_no4" name="auc_driver_no4" placeholder="Driver No4#" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Driver Status</label>
                                    <input id="auc_driver_status" name="auc_driver_status" class="form-control" placeholder="Driver Status" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Driver Payment Status</label>
                                    <input id="auc_driver_payment_status" name="auc_driver_payment_status" class="form-control" placeholder="Driver Payment Status" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Vehicle Condition</label>
                                    <input class="form-control" id="auc_condition" placeholder="Vehicle Condition" name="auc_condition" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Customer Informed</label>
                                    <input class="form-control" id="auc_customer_informed" placeholder="Customer Informed" name="auc_customer_informed" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Vehicle Position</label>
                                    <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Delivery Datetime</label>
                                    <input class="form-control" id="auc_delivery_date" type="datetime-local"  placeholder="Delivery Datetime" name="auc_delivery_date"
                                           value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Storage Pay</label>
                                    <input id="auc_who_pay_storage" name="auc_who_pay_storage" class="form-control" placeholder="Who Pay Storage" value="" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Title</label>
                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Key</label>
                                    <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Client & Status</label>
                                    <select id="auc_client_status" name="auc_client_status" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Owes Status</label>
                                    <select id="auc_owes_status" name="auc_owes_status" class="form-control h-50" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Additional</label>
                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                </div>
                            `);
                            $(".driverphoneno").keypress(function(e) {
                                if ($(this).val() == '') {
                                    $(this).mask("(999) 999-9999");
                                }
                                var x = e.which || e.keycode;
                                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                                    return true;
                                } else {
                                    return false;
                                }
                            })
                        }
                    })
                } else {
                    $(".deliverdate").html('');

                }
            })
        </script>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '12' && $approve_deliver == '1')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">Delivered HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}"
                id='order_id1' placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="12">Deliver</option>
                        <option value="13">Completed</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 expectdate">
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row auctionupdate mb-2"></div>
        <script>
            $("#pstatus").on('change', function() {
                if ($(this).val() == 13) {
                    $(".expectdate").html(`
                        <div class="form-group">
                            <label class="form-label">EXPECTED DATE</label>
                            <input type="date" required name="expected_date"
                                   id='expected_date' 
                                   class="form-control">
                        </div>
                    `);
                    $(".auctionupdate").html(`
                        <input type="hidden" value="13" name="pstatus222">
                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                        <div class="col-md-12 text-center"><h4>Completed Sheet</h4></div>
                        <div class="col-md-3">
                            <label>Remarks Status</label>
                            <input class="form-control h-50" id="auc_remarks" name="auc_remarks" placeholder="Remarks Status" value="" required>
                        </div>
                        <div class="col-md-3">
                            <label>Comments</label>
                            <input class="form-control h-50" id="auc_comments" name="auc_comments" placeholder="Comments" value="" required>
                        </div>
                        <div class="col-md-3">
                            <label>Satisfied?</label>
                            <input class="form-control h-50" id="auc_satisfied" name="auc_satisfied" placeholder="How you Satisfied?" value="" required>
                        </div>
                        <div class="col-md-3">
                            <label>Review</label>
                            <select id="auc_review" name="auc_review" class="form-control h-50" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-12" id="all_rating" style="display:none;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Website</label>
                                    <select id="auc_website" name="auc_website" class="form-control h-50">
                                        <option value="" selected disabled>Select</option>
                                        <option value="BBB">BBB</option>
                                        <option value="Trust Pilot">Trust Pilot</option>
                                        <option value="Google">Google</option>
                                        <option value="Yelp">Yelp</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-sm-6" style="display:none;" id="other_website">
                                    <label>Other Website</label>
                                    <input class="form-control h-50" id="auc_website_other" name="auc_website_other" placeholder="Other Website" value="">
                                </div>
                                <div class="col-sm-6">
                                    <label>Rating</label>
                                    <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Positive">Positive</option>
                                        <option value="Neutral">Neutral</option>
                                        <option value="Negative">Negative</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Website Link</label>
                                    <input class="form-control h-50" id="auc_website_link" name="auc_website_link" placeholder="Website Link" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Additional</label>
                            <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                        </div>
                    `);
                    $(document).on("change", "#auc_website", function() {
                        if ($(this).val() == 'Other') {
                            $("#other_website").show();
                        } else {
                            $("#other_website").hide();
                        }
                    })
                    $(document).on("change", "#auc_review", function() {
                        if ($(this).val() == 'Yes') {
                            $("#all_rating").show();
                        } else {
                            $("#all_rating").hide();
                        }
                    })
                } else {
                    $(".expectdate").html('');

                }
            })
        </script>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '13')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">Completed HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}"
                id='order_id1' placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="13">Completed</option>
                    </select>
                </div>
            </div>
            <!--<div class="col-sm-6 col-md-6">-->
            <!--    <div class="form-group">-->
            <!--        <label class="form-label">EXPECTED DATE</label>-->
            <!--        <input type="date" required name="expected_date"-->
            <!--               id='expected_date' -->
            <!--               class="form-control">-->
            <!--    </div>-->
            <!--</div>-->
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '14')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">Cancel HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}"
                id='order_id1' placeholder="" readonly>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="14">Cancel</option>
                    </select>
                </div>
            </div>
            <!--<div class="col-sm-6 col-md-6">-->
            <!--    <div class="form-group">-->
            <!--        <label class="form-label">EXPECTED DATE</label>-->
            <!--        <input type="date" required name="expected_date" id='expected_date' -->
            <!--               class="form-control">-->
            <!--    </div>-->
            <!--</div>-->
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '16')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">OWES MONEY HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" id='order_id1' placeholder=""
                readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="16">OWES MONEY</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" id='expected_date' class="form-control">
                </div>
            </div>
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '18')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">On Approval HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}"
                id='order_id1' placeholder="" readonly>

            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        <option value="18">ON APPROVAL</option>
                        <option value="9">LISTED</option>
                        <option value="19">ONAPPROVAL CANCEL</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">EXPECTED DATE</label>
                    <input type="date" required name="expected_date" disabled id='expected_date'
                        class="form-control">
                </div>
            </div>
            <div class="col-sm-5 col-md-5">
                <div class="form-group">
                    <label class="form-label">LISTED PRICE</label>
                    <input type="number" required name="listed_price" id='listed_price' disabled
                        class="form-control">
                </div>
            </div>
            @php
                $ptype = 1;
                $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                if (!empty($query)) {
                    $ptype = $query['penal_type'];
                }

                if ($ptype == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } elseif ($ptype == 2) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                } elseif ($ptype == 3) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_test);
                } elseif ($ptype == 4) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_4);
                } elseif ($ptype == 5) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_5);
                } elseif ($ptype == 6) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_6);
                } else {
                    $phoneaccess = [];
                }
            @endphp
            @if (in_array('76', $phoneaccess))
                <?php
                $dis = \App\User::with('daily_ass')
                    ->whereHas('userRole', function ($q) {
                        $q->where('name', 'Dispatcher');
                    })
                    ->where('deleted', 0)
                    ->get();
                ?>
                <div class="col-sm-3 col-md-3 my-auto">
                    <button class="btn btn-primary" type="button" id="showingDispatchers" disabled
                        onclick="$('#showDispatchers').toggle();">Assign Dispatcher</button>
                </div>
                <div class="col-sm-4 col-md-4" id="showDispatchers" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Dispatchers <span class="text-muted">(Optional)</span></label>
                        <select name="dis_id" id='dis_id' class="form-control">
                            <option value="" selected disabled>Select</option>
                            @foreach ($dis as $key => $dispa)
                                <option value="{{ $dispa->id }}">
                                    {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                    ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">HISTORY</label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row auctionupdate mb-2"></div>
        <script>
            $("#pstatus").on('change', function() {
                if ($(this).val() == 9) {
                    $(".auctionupdate").html(`
                        <input type="hidden" value="9" name="pstatus222">
                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                        <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                        <div class="col-md-4">
                            <label>Paid</label>
                            <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Storage</label>
                            <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Listed Price</label>
                            <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Auction Update</label>
                            <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                   value="">
                        </div>
                        <div class="col-md-4">
                            <label>Title</label>
                            <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Key</label>
                            <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Listed Count</label>
                            <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                        </div>
                        <div class="col-md-4">
                            <label>Old/New Price</label>
                            <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                   value="">
                        </div>
                        <div class="col-md-4">
                            <label>Vehicle Position</label>
                            <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                        </div>
                        <div class="col-md-12">
                            <label>Additional</label>
                            <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                        </div>
                    `);
                    $("#expected_date").attr('disabled', false);
                    $("#listed_price").attr('disabled', false);
                    $("#showingDispatchers").attr('disabled', false);
                } else if ($(this).val() == 18) {
                    $("#expected_date").attr('disabled', false);
                    $("#listed_price").attr('disabled', true);
                    $("#showingDispatchers").attr('disabled', true);
                    $("#showDispatchers").hide();
                    $("#dis_id").children('option').removeAttr('selected');
                    $("#dis_id").children('option').eq(0).attr('selected', true);
                } else {
                    $("#expected_date").attr('disabled', true);
                    $("#listed_price").attr('disabled', true);
                    $("#showingDispatchers").attr('disabled', true);
                    $("#showDispatchers").hide();
                    $("#dis_id").children('option').removeAttr('selected');
                    $("#dis_id").children('option').eq(0).attr('selected', true);
                    $(".auctionupdate").html('');
                }
            })
        </script>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif

@if ($pstatus == '19')
    <form method="post" id="saveChangesForm" action="{{ route('call_history_post') }}">
        @csrf
        <div class="card-title font-weight-bold">On Approval Cancel HISTORY/CHANGE
            STATUS:
        </div>
        <div class="row">
            <input type="hidden" class="form-control" name="order_id1" value="{{ $order_id }}"
                id='order_id1' placeholder="" readonly>

            <div class="col-sm-12 col-md-12" id="last_history"></div>

            @php
                $ptype = 1;
                $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                if (!empty($query)) {
                    $ptype = $query['penal_type'];
                }

                if ($ptype == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } elseif ($ptype == 2) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                } elseif ($ptype == 3) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_test);
                } elseif ($ptype == 4) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_4);
                } elseif ($ptype == 5) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_5);
                } elseif ($ptype == 6) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_6);
                } else {
                    $phoneaccess = [];
                }

            @endphp
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">CHANGE STATUS</label>
                    <select name="pstatus" id='pstatus' required class="form-control ">
                        <option value="" selected disabled>Select</option>
                        @if (in_array('77', $phoneaccess))
                            <option value="14">CANCEL ORDER</option>
                        @else
                            <option value="19">ONAPPROVAL CANCEL</option>
                        @endif
                    </select>
                </div>
            </div>
            <!--<div class="col-sm-6 col-md-6">-->
            <!--    <div class="form-group">-->
            <!--        <label class="form-label">EXPECTED DATE</label>-->
            <!--        <input type="date" required name="expected_date"-->
            <!--               id='expected_date' -->
            <!--               class="form-control">-->
            <!--    </div>-->
            <!--</div>-->
            <div class="col-sm-6 col-md-6" id="mistakers"></div>
            <div class="col-sm-6 col-md-6" id="calls"></div>
            <div class="col-sm-6 col-md-6" id="decisions"></div>

            <div class="col-sm-6 col-md-12">
                <div class="form-group">
                    <label class="form-label">
                        @if (in_array('77', $phoneaccess))
                            Admin Remarks
                        @else
                            HISTORY
                        @endif
                    </label>
                    <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-3">
                <label>Review</label>
                <select id="auc_reviewss" name="auc_review" class="form-control h-50 auc_review" required>
                    <option value="" selected disabled>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Website</label>
                        <select id="auc_website" name="auc_website" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="BBB">BBB</option>
                            <option value="Trust Pilot">Trust Pilot</option>
                            <option value="Google">Google</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="display:none;" id="other_website">
                        <label>Other Website</label>
                        <input class="form-control h-50" id="auc_website_other" name="auc_website_other"
                            placeholder="Other Website" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Rating</label>
                        <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                            <option value="" selected disabled>Select</option>
                            <option value="Positive">Positive</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Website Link</label>
                        <input class="form-control h-50" id="auc_website_link" name="auc_website_link"
                            placeholder="Website Link" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Reviewer</label>
                        <input type="text" class="form-control" id="" name="auc_reviewer"
                            placeholder="Reviewer" value="">
                    </div>
                    <div class="col-sm-3">
                        <label>Attach Screenshot</label>
                        <input type="file" class="form-control" id="" name="screenshot"
                            placeholder="screenshot" value="">
                    </div>
                </div>
            </div>
        </div>
        <script>
            $("#pstatus").on('change', function() {
                if ($(this).val() == 14) {
                    var order_id = $("#order_id1").val();
                    $.ajax({
                        url: "{{ url('/order_users') }}",
                        type: "GET",
                        data: {
                            id: order_id
                        },
                        dataType: "JSON",
                        success: function(res) {
                            var ot = 'No Order Taker';
                            var dis = 'No Dispatcher Assigned';
                            var ot_id = '';
                            var dis_id = '';
                            var both = '';
                            if (res.ot) {
                                ot = res.ot;
                            }
                            if (res.dis) {
                                dis = res.dis;
                            }
                            if (res.ot_id) {
                                ot_id = res.ot_id;
                            }
                            if (res.dis_id) {
                                dis_id = res.dis_id;
                            }
                            if (res.dis_id) {
                                dis_id = res.dis_id;
                                both = both + ',' + res.dis_id
                            }
                            $("#mistakers").html(`
                                <input type="hidden" name="mistaker_id" id="mistaker_id" />
                                <div class="form-group">
                                    <label class="form-label">Mistaker</label>
                                    <select name="mistaker" id='mistaker' required class="form-control ">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Customer" data-value="">Customer</option>
                                        <option value="${ot}" data-value="${ot_id}">${ot}</option>
                                        <option value="${dis}" data-value="${dis_id}">${dis}</option>
                                        <option value="Both" data-value="${both}">Both</option>
                                    </select>
                                </div>
                            `);
                            $("#calls").html(`
                                <div class="form-group">
                                    <label class="form-label">No Of Calls</label>
                                    <input type="number" required name="no_of_calls" id="no_of_calls" class="form-control" />
                                </div>
                            `);
                            $("#decisions").html(`
                                <div class="form-group">
                                    <label class="form-label">Decision</label>
                                    <input type="text" required name="decision" id="decision" class="form-control" />
                                </div>
                            `);

                            $(document).on("change", "#mistaker", function() {
                                $("#mistaker_id").val($(this).children('option:selected').data(
                                    'value'));
                            })

                            if (res.last_history) {
                                $("#last_history").html(`
                                    <div class="message-feed media m-0 p-0">
                                        <div class="media-body">
                                            <div class="mf-content w-100">
                                                <h6>User: ${res.last_history.username}</h6>
                                                ${res.last_history.history}
                                                <h6>
                                                    <strong class="mf-date"><i class="fa fa-clock-o"></i>  ${res.last_history.created}</strong>
                                                </h6>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input type="radio" value="Agree" name="agree_disagree" id="agree" /> <label class="ml-2" for="agree">Agree</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="radio" checked value="Disagree" name="agree_disagree" id="disagree" /> <label class="ml-2" for="disagree">Disagree</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            } else {
                                $("#last_history").html(`
                                    <div class="message-feed media m-0 p-0">
                                        <div class="media-body">
                                            <div class="mf-content w-100">
                                                <h6>No Last History</h6>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            }
                        }
                    })
                }
            })
        </script>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
@endif
<script>
    $("#saveChangesForm").on('submit', function() {
        $(this).children('button[type="submit"]').attr('disabled', true);
    })

    $(document).on("change", ".auc_review", function() {
        if ($(this).val() == 'Yes') {
            $(".all_rating").show();
        } else {
            $(".all_rating").hide();
        }
    })
</script>
