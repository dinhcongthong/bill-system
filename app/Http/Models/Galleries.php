<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Galleries extends Model
{
    protected $table = 'galleries';
    protected $fillable = [
        'name', 'path', 'contract_id', 'setting_id'
    ];
    
    public static function uploadFile ($file_name, $path, $type, $id) {
        switch ($type) {
            case 'contract':
            $data = [
                'contract_id' => $id,
                'name'        => $file_name,
                'path'        => $path
            ];
            $galleries = self::create($data);
            $galleries = $galleries->save();
            break;
            case 'setting':
            $data = [
                'setting_id' => $id,
                'name'       => $file_name,
                'path'       => $path
            ];
            $galleries = self::create($data);
            $galleries = $galleries->save();
            break;
        }
    }
}
