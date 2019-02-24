<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $company_ID
 * @property string $date
 * @property string $purpose
 * @property int $reminder
 * @property float $amount
 * @property int $billNumber
 * @property float $fraction
 * @property boolean $done
 * @property int $status
 * @property string $billHtml
 * @property string $dueDate
 * @property string $Komment
 * @property float $halfbills
 * @property string $printeddate
 * @property string $signeddate
 * @property int $Unsicher
 * @property boolean $ignored
 * @property string $aktenziech
 * @property string $billtext
 * @property string $payday
 * @property string $csvfilename
 */
class Invoice extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 't_rechnungstermine';

    /**
     * @var array
     */
    protected $fillable = ['company_ID', 'date', 'purpose', 'reminder', 'amount', 'billNumber', 'fraction', 'done', 'status', 'billHtml', 'dueDate', 'Komment', 'halfbills', 'printeddate', 'signeddate', 'Unsicher', 'ignored', 'aktenziech', 'billtext', 'payday', 'csvfilename'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
    ];

    /**
     * Get the company that owns the invoice.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Company', 'company_ID');
    }

}
