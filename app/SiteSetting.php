<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class SiteSetting extends Model

{

    use HasFactory;

    protected $table="site_setting";



    protected $fillable=['id','name','value'];



    public $timestamps=true;



   

}

