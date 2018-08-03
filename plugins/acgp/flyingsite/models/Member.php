<?php namespace ACGP\FlyingSite\Models;

use Lang;
use Model;
use System\Models\File as FileModel;

/**
 * Model
 */
class Member extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Encryptable;

    /**
     * Set the soft delete column name to archived_at
     */
    protected const DELETED_AT = 'archived_at';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'acgp_flyingsite_members';

    /**
     * @var array The properties to be cast to dates
     */
    protected $dates = ['created_at', 'updated_at', 'archived_at'];

    /**
     * @var array Validation Rules
     */
    public $rules = [
        'given_names'         => 'required',
        'surname'             => 'required',
        'type'                => 'required',
        'rank'                => 'required',
        'sensitive_data.unit' => 'required',
        'sensitive_data.sn'   => 'required_if:type,coats,ci,regf,resf',
        'date_of_birth'       => 'required_if:type,cadet,coats,resf',
        'contact_data.phone'  => 'required',
        'contact_data.email'  => 'required|email',
    ];

    /**
     * @var array List of attributes to encrypt
     */
    protected $encryptable = ['contact_data', 'sensitive_data'];

    /**
     * Relations
     */
     public $attachOne = [
         'photo'           => [FileModel::class, 'public' => false],
         'pen_sig'         => [FileModel::class, 'public' => false],
         'pen_witness_sig' => [FileModel::class, 'public' => false],
     ];

     /**
      * Get the available rank options based on the current type
      * @return array $ranks
      */
     public function getRankOptions()
     {
         $ranks = [];

         switch ($this->type) {
             case 'coats':
             case 'regf':
             case 'resf':
                $ranks = [
                    'ocdt' => 'acgp.flyingsite::lang.models.member.rank.ocdt',
                    '2lt'  => 'acgp.flyingsite::lang.models.member.rank.2lt',
                    'lt'   => 'acgp.flyingsite::lang.models.member.rank.lt',
                    'capt' => 'acgp.flyingsite::lang.models.member.rank.capt',
                    'maj'  => 'acgp.flyingsite::lang.models.member.rank.maj',
                    'lcol' => 'acgp.flyingsite::lang.models.member.rank.lcol',
                    'col'  => 'acgp.flyingsite::lang.models.member.rank.col',
                ];
                break;
            case 'ci':
            case 'cv':
                $ranks = ['na' => 'acgp.flyingsite::lang.models.member.rank.na'];
                break;
            case 'cadet':
            default:
                $ranks = [
                    'cdt-ac'   => 'acgp.flyingsite::lang.models.member.rank.cdt-ac',
                    'cdt-lac'  => 'acgp.flyingsite::lang.models.member.rank.cdt-lac',
                    'cdt-cpl'  => 'acgp.flyingsite::lang.models.member.rank.cdt-cpl',
                    'cdt-fcpl' => 'acgp.flyingsite::lang.models.member.rank.cdt-fcpl',
                    'cdt-sgt'  => 'acgp.flyingsite::lang.models.member.rank.cdt-sgt',
                    'cdt-fsgt' => 'acgp.flyingsite::lang.models.member.rank.cdt-fsgt',
                    'cdt-wo2'  => 'acgp.flyingsite::lang.models.member.rank.cdt-wo2',
                    'cdt-wo1'  => 'acgp.flyingsite::lang.models.member.rank.cdt-wo1',
                ];
                break;
         }

         return $ranks;
     }

     /**
      * Get the human-readable version of the type
      * @return string
      */
     public function getTypeCleanAttribute()
     {
         return Lang::get("acgp.flyingsite::lang.models.member.type.{$this->type}");
     }

     /**
      * Get the human-readable version of the rank
      * @return string
      */
     public function getRankCleanAttribute()
     {
         return Lang::get("acgp.flyingsite::lang.models.member.rank.{$this->rank}");
     }
}
