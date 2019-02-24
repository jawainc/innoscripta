<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $project_ID
 * @property string $name
 * @property string $fundingCode
 * @property string $referenceNr
 * @property string $state
 * @property float $funding
 * @property float $jae
 * @property float $annualSales
 * @property float $markupFactor
 * @property float $fundingRate
 * @property float $maxSalary
 * @property string $comment
 * @property string $street
 * @property string $code
 * @property string $city
 * @property string $country
 * @property int $customerNumber
 * @property float $salesTax
 * @property float $fee
 * @property string $contactGender
 * @property string $contactPrename
 * @property string $contactName
 * @property string $contactEmail
 * @property boolean $billsGenerated
 * @property string $contactPrename2
 * @property string $contactName2
 * @property string $contactEmail2
 * @property string $contactGender2
 * @property string $Bewilligungsbescheid
 * @property string $Antrag
 * @property string $Zustandiger
 * @property string $Fax
 * @property string $Mail
 * @property string $Telefon
 * @property string $type
 * @property string $Fakultat
 * @property string $Department
 * @property string $phone1
 * @property string $phone2
 * @property boolean $empfangsbestatigung
 * @property string $date1
 * @property string $date2
 * @property float $stundensatz
 * @property string $maxhours
 * @property string $maxhours2
 * @property string $contactgender3
 * @property string $contactname3
 * @property string $contactprename3
 * @property string $contactphone3
 * @property string $contacthours3
 * @property string $contactmail3
 * @property string $partner
 * @property string $amountcomp
 * @property string $amountpar
 * @property string $fee2
 * @property boolean $belingcheck
 * @property boolean $fuecheck
 * @property float $belingsum
 * @property float $FuEsum
 * @property boolean $sendtocontact1
 * @property boolean $sendtocontact2
 * @property boolean $sendtocontact3
 * @property boolean $fehler
 * @property boolean $benachrichtigungAus
 * @property int $first_tranche
 * @property int $cost
 * @property string $created_at
 * @property string $updated_at
 * @property boolean $showcomp
 * @property string $street2
 * @property string $contactgender4
 * @property string $contactprename4
 * @property string $contactname4
 * @property string $contactmail4
 * @property boolean $sendtocontact4
 * @property string $contactphone4
 * @property string $contacthours4
 */
class Company extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 't_firmen';

    /**
     * @var array
     */
    protected $fillable = ['project_ID', 'name', 'fundingCode', 'referenceNr', 'state', 'funding', 'jae', 'annualSales', 'markupFactor', 'fundingRate', 'maxSalary', 'comment', 'street', 'code', 'city', 'country', 'customerNumber', 'salesTax', 'fee', 'contactGender', 'contactPrename', 'contactName', 'contactEmail', 'billsGenerated', 'contactPrename2', 'contactName2', 'contactEmail2', 'contactGender2', 'Bewilligungsbescheid', 'Antrag', 'Zustandiger', 'Fax', 'Mail', 'Telefon', 'type', 'Fakultat', 'Department', 'phone1', 'phone2', 'empfangsbestatigung', 'date1', 'date2', 'stundensatz', 'maxhours', 'maxhours2', 'contactgender3', 'contactname3', 'contactprename3', 'contactphone3', 'contacthours3', 'contactmail3', 'partner', 'amountcomp', 'amountpar', 'fee2', 'belingcheck', 'fuecheck', 'belingsum', 'FuEsum', 'sendtocontact1', 'sendtocontact2', 'sendtocontact3', 'fehler', 'benachrichtigungAus', 'first_tranche', 'cost', 'created_at', 'updated_at', 'showcomp', 'street2', 'contactgender4', 'contactprename4', 'contactname4', 'contactmail4', 'sendtocontact4', 'contactphone4', 'contacthours4'];

    /**
     * get invoices for the company
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices ()
    {
        return $this->hasMany('App\Invoice', 'company_ID');
    }

}
