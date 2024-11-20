<?php

namespace Modules\Questoes\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Questoes\app\Models\Disciplinas;

class Questoes extends Model
{
//     use \Modules\Auditoria\app\Models\AuditoriaTrait;

    protected $table = 'questoes';
    protected $primaryKey = 'id_questoes';
    public $timestamps = false;

    protected $fillable = [
        'ano',
        'sigla'
    ];

    public function disciplinas()
    {
        return $this->belongsTo(Disciplinas::class,  'id_disciplina_fk', 'id_disciplina');
    }

}