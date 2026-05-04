<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileCard extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'oemail',
        'ophone',
        'ophone2',
        'oaddress',
        'originzsc',
        'payment_method',
        'customer_type',
        'business_name',
        'nature_of_customer',
        'recent_follow_up',

        // Auction 1 fields
        'auction_1_terminal',
        'auction_1_title',
        'auction_1_phone',
        'auction_1_account',
        'auction_1_account_name',
        'auction_1_buyer_no',
        'auction_1_gate_pass_pin',
        'auction_1_lot_no',

        // Auction 2 fields
        'auction_2_terminal',
        'auction_2_title',
        'auction_2_phone',
        'auction_2_account',
        'auction_2_account_name',
        'auction_2_buyer_no',
        'auction_2_gate_pass_pin',
        'auction_2_lot_no',

        // Auction 3 fields
        'auction_3_terminal',
        'auction_3_title',
        'auction_3_phone',
        'auction_3_account',
        'auction_3_account_name',
        'auction_3_buyer_no',
        'auction_3_gate_pass_pin',
        'auction_3_lot_no',

        'first_quote_date',
        'first_quote_status',
        'first_quote_agent',
        'recent_quote_date',
        'recent_quote_status',
        'recent_quote_agent',
        'customer_review',
        'average_margin',
        'customer_rating',
        'social_profile_name',
        'panel_type',
    ];



}
