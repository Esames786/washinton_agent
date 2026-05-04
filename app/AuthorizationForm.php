<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorizationForm extends Model
{
    protected $table = 'authorization_form';
    
    protected $fillable = [
            'user_id',
            'email',
            'date',
            'company_name',
            'card_holders',
            'billing_address',
            'city',
            'state',
            'zip_code',
            'country',
            'phone',
            'card',
            'card_number',
            'expdate',
            'Code',
            'issuing_bank',
            'bank_approval',
            'card_issuer',
            'invoice',
            'invoice_amount',
            'signatureData',
        ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->select('id','slug','name','last_name');
    }
    
    public function images()
    {
        return $this->hasMany(AuthorizationFormImages::class, 'form_id');
    }
}
