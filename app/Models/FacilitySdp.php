<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class FacilitySdp extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'facility_sdps';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];

	/**
     * Facility relationship
     */
    public function facility()
    {
       return $this->belongsTo('App\Models\Facility');
    }
    /**
     * Sdp relationship
     */
    public function sdp()
    {
       return $this->belongsTo('App\Models\Sdp');
    }
    /**
     * surveys relationship
     */
    public function surveys()
    {
       return $this->hasMany('App\Models\Survey');
    }
    /**
    * Function to split given string to get sdp and comment
    */
    public static function splitSdp($facility, $id)
    {
        $sdpName = '';
        $tier_id = null;
        if(stripos($id, '-') !==FALSE)
        {
            $id = explode('-', $id);
            $sdpName = $id[0];
            if(trim($id[1])!='')
                $tier_id = $id[1];
        }
        else
            $sdpName = $id;
        $sdp_id = Sdp::idByName($sdpName);
        $tier_id = Tier::find(trim($tier_id));
        return FacilitySdp::where('facility_id', $facility)->where('sdp_id', $sdp_id)->where('sdp_tier_id', $tier_id)->first();
    }
    /**
    * Function to split given string to get sdp and comment
    */
    public static function cojoinSdp($id)
    {
        $cojoined = FacilitySdp::find($id);
        $cojoined->sdp_tier_id?$fsdp=Sdp::find($cojoined->sdp_id)->name.' - '.Tier::find($cojoined->sdp_tier_id)->name:$fsdp=Sdp::find($cojoined->sdp_id)->name;
        return $fsdp;
    }
}