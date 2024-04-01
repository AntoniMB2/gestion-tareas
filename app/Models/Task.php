<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const STATUS = [
        'Pendiente',
        'En_proceso',
        'Bloqueado',
        'Completado',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'assigned_to',
        'completed_at',
        'duration',
    ];

    /**
     * Get the user that is assigned to the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the comments for the task.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the attachments for the task.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get the validation rules that apply to the model.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'status' => 'in:' . implode(',', self::STATUS),
        ];
    }
}