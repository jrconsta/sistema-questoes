<?php

namespace Modules\Questoes\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Questoes\app\Models\Questoes;

class Disciplinas extends Model
{
//     use \Modules\Auditoria\app\Models\AuditoriaTrait;

    protected $table = 'disciplinas';
    protected $primaryKey = 'id_disciplinas';
    public $timestamps = false;

    protected $fillable = [
        'ano',
        'sigla'
    ];

    public function questoes()
    {
        return $this->hasMany(Questoes::class,  'id_disciplina_fk', 'id_disciplina');
    }

    // Relacionamento com Disciplinas
    // public function disciplinas()
    // {
    //     return $this->belongsTo(Disciplinas::class, 'id_disciplina_fk', 'id_disciplina');
    // }

}