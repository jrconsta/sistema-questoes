<?php

namespace Modules\Questoes\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Questoes\app\Models\Questoes;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;

class QuestoesController extends Controller
{
    #[Get(uri: '/questoes', name: 'questoes::index')]
    public function index(Request $request)
    {
        $filtro = $request->materia;

        // Carrega todas as questões, filtrando se um filtro for aplicado
        $questoes = Questoes::when($filtro, function($query) use ($filtro) {
            return $query->where('Materia', $filtro);
        })->get();
        // ->inRandomOrder()->get();

        // dd($questoes->count());

        foreach ($questoes as $questao) {
            // dd(vars: $questao->disciplinas->pluck('nome')->unique(), $questao->disciplinas->pluck('nome_sigla')->unique());
            // dump($questao);
            $disciplinas = [
                'nome' => $questao->disciplinas->pluck('nome')->unique(),
                'nome_sigla' => $questao->disciplinas->pluck('nome_sigla')->unique()
            ];
        }

        $letras = ['a) ', 'b) ', 'c) ', 'd) ', 'e) '];

        // Para cada questão, embaralhamos as alternativas
        foreach ($questoes as $questao) {
            $alternativas = [
                'a) ' => $questao->a,
                'b) ' => $questao->b,
                'c) ' => $questao->c,
                'd) ' => $questao->d,
                'e) ' => $questao->e,
            ];

            // Embaralha as alternativas
            shuffle($alternativas);

            // Adiciona as alternativas embaralhadas à questão
            $questao->alternativas_embaralhadas = $alternativas;
        }

        // dd($alternativas);

        return view('questoes::index', compact('questoes', 'letras', 'disciplinas'));
    }

    #[Get(uri: '/resultados', name: 'questoes::resultados')]
    public function corrigir(Request $request)
    {
        $dados = $request->all();
        $contador = 0;
        $resultados = [];
        $questoesProcessadas = []; // Array para armazenar as questões processadas
        $totalQuestoes = $request->numero_questoes_marcadas;

        // Remove a chave 'numero_questoes_marcadas' do array
        unset($dados['numero_questoes_marcadas']);

        foreach ($dados as $id => $alternativaMarcada) {

            $questao = Questoes::where('id_questao', '=', $id)->first();
            // dump($questao);

            //Extrai a letra da alternativa marcada (Ex: 'a', 'b', 'c'....)
            $letraMarcada = substr($alternativaMarcada, 0, 1);

            // dd($letraMarcada);

            // Obtendo o texto da coluna que corresponde à opção marcada
            switch ($letraMarcada) {
                case 'a':
                    $textoColunaMarcada = $questao->a;
                    break;
                case 'b':
                    $textoColunaMarcada = $questao->b;
                    break;
                case 'c':
                    $textoColunaMarcada = $questao->c;
                    break;
                case 'd':
                    $textoColunaMarcada = $questao->d;
                    break;
                case 'e':
                    $textoColunaMarcada = $questao->e;
                    break;
                case 'v':
                    $textoColunaMarcada = $questao->a;
                    break;
                case 'f':
                    $textoColunaMarcada = $questao->b;
                    break;    
                default:
                    $textoColunaMarcada = null;
            }

            
            // Obtendo o texto da coluna que corresponde à resposta correta
            switch ($questao->resposta_correta) {
                case 'a':
                    $respostaCorreta = $questao->a;
                    break;
                case 'b':
                    $respostaCorreta = $questao->b;
                    break;
                case 'c':
                    $respostaCorreta = $questao->c;
                    break;
                case 'd':
                    $respostaCorreta = $questao->d;
                    break;
                case 'e':
                    $respostaCorreta = $questao->e;
                    break;
                case 'v':
                    $respostaCorreta = $questao->resposta_v_f;
                    break;
                case 'f':
                    $respostaCorreta = $questao->resposta_v_f;
                    break;
                default:
                    $respostaCorreta = null;
            }

            if ($letraMarcada == $questao->resposta_correta) {
                $resultados[$id] = true; // Resposta correta
                $contador++;
            }else{
                $resultados[$id] = false; // Resposta incorreta
            }

            
            // Adiciona a questão processada ao array
            $questoesProcessadas[$id] = [
                'questao' => $questao,
                'textoColunaMarcada' => $textoColunaMarcada,
                'respostaCorreta' => $respostaCorreta,
                'resultado' => $resultados[$id]
            ];
            
            
        }
        
        $porcentagemAcertos = ($contador / $totalQuestoes) * 100;
        $porcetagemAcertosFormat = number_format($porcentagemAcertos, 2).'%';

        return view('questoes::resultados', compact('questoesProcessadas', 'contador', 'totalQuestoes', 'porcentagemAcertos'));

    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('questoes::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('questoes::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('questoes::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
