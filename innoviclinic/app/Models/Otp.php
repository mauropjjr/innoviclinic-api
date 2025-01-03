<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
	protected $table = 'otps';

	protected $casts = [
		'pessoa_id' => 'int',
		'code' => 'int',
		'created_at' => 'date',
		'updated' => 'date',
	];

	protected $fillable = [
		'pessoa_id',
		'code',
		'created_at',
		'updated',
	];

	protected static function boot()
	{
		parent::boot();
		static::creating(function ($objeto) {
			// $user = Pessoa::find($objeto->pessoa_id);
			$lastOtp = Otp::where("pessoa_id", $objeto->pessoa_id);
			if ($lastOtp->exists()) {
				$lastOtp->delete();
			}
			$objeto->code = mt_rand(100000, 999999);
		});
	}
}
