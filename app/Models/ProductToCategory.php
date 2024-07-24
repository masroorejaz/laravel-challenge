<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductToCategory extends Model
{
    use HasFactory;

     // Specify the table if it's different from the default
     protected $table = 'product_to_category';

     // Specify which attributes are mass assignable
     protected $fillable = ['product_id', 'category_id'];
 
     // Optionally, add timestamps if your table uses them
     public $timestamps = false;
}
