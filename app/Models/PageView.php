<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;
    protected $table = 'ad_page_views';
    protected $fillable = [
        'id',
        'order',
        'created_at',
        'updated_at',
        'parent_menu_id'
    ];
}
