<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * An agent's submitted IRS Form W-9.
 *
 * The taxpayer identification number is encrypted at rest — set it through setTin() and read it
 * back (only where genuinely needed, e.g. the generated PDF) through tin(). Admin screens should
 * use tin_last4 instead of ever decrypting.
 */
class W9Form extends Model
{
    protected $table = 'w9_forms';

    protected $fillable = [
        'user_id', 'hr_employee_id',
        'legal_name', 'business_name',
        'tax_classification', 'llc_tax_class', 'other_classification',
        'exempt_payee_code', 'fatca_code',
        'address', 'city', 'state', 'zip', 'account_numbers',
        'tin_type', 'tin_encrypted', 'tin_last4',
        'signature', 'signed_ip', 'signed_at', 'document_path',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /** Never expose the ciphertext or the signature blob by accident. */
    protected $hidden = ['tin_encrypted', 'signature'];

    /** Human labels for the federal tax classification (line 3). */
    public const CLASSIFICATIONS = [
        'individual'   => 'Individual / sole proprietor or single-member LLC',
        'c_corp'       => 'C Corporation',
        's_corp'       => 'S Corporation',
        'partnership'  => 'Partnership',
        'trust_estate' => 'Trust / estate',
        'llc'          => 'Limited liability company',
        'other'        => 'Other',
    ];

    public function setTin(string $tin): void
    {
        $digits = preg_replace('/\D/', '', $tin);
        $this->tin_encrypted = Crypt::encryptString($tin);
        $this->tin_last4     = $digits ? substr($digits, -4) : null;
    }

    /** Decrypt the TIN. Only call where the full number is genuinely required (the PDF). */
    public function tin(): ?string
    {
        if (!$this->tin_encrypted) {
            return null;
        }

        try {
            return Crypt::decryptString($this->tin_encrypted);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Masked form for display, e.g. ***-**-1234. */
    public function maskedTin(): string
    {
        if (!$this->tin_last4) {
            return '—';
        }

        return $this->tin_type === 'ein'
            ? '**-*******' . $this->tin_last4
            : '***-**-' . $this->tin_last4;
    }

    public function classificationLabel(): string
    {
        $label = self::CLASSIFICATIONS[$this->tax_classification] ?? $this->tax_classification;

        if ($this->tax_classification === 'llc' && $this->llc_tax_class) {
            $label .= ' (' . $this->llc_tax_class . ')';
        }
        if ($this->tax_classification === 'other' && $this->other_classification) {
            $label .= ' — ' . $this->other_classification;
        }

        return $label;
    }
}
