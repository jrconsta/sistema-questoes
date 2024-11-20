@extends('layouts.masterpage')

@section('card-title')
    Questoes CFSII <a href="{{ route('questoes.index') }}">Home</a>

    <div class="d-flex align-items-center mb-3 justify-content-between"" style="margin-top: 5px" >
        <form method="GET" action="{{ route('questoes.index') }}" class="questoes-form" id="questoes-form">
            <select class="form-control me-2" name="materia" id="materia">
                <option value="">Selecione a Disciplina</option>
                @foreach ($disciplinas['nome'] as $index => $nome)
                    <option value="{{ $disciplinas['nome_sigla'][$index] }}">
                        {{ $nome }} ({{ $disciplinas['nome_sigla'][$index] }})
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-info" style="margin-top: 5px">Filtrar</button>
        </form>
    </div>
    {{ $questoes->count() }} Questões Filtradas
    
@endsection

@section('card-body')

    <form method="GET" action="{{ route('questoes::resultados') }}" class="questoes-form" id="questoes-form">
        <input type="hidden" name="numero_questoes_marcadas" id="numero_questoes_marcadas" value="0">
        <div id="questoes-container">
            @foreach ($questoes as $questao)
                <div style="background-color: lightblue; margin-bottom:15px; padding:10px; border-radius: 10px;">
                    <p style="margin-top: 10px"><strong>{{ $questao->id_questao }}</strong> - {!! nl2br(str_replace('\n', "\n", $questao->enunciado)) !!}</p>
                    @if($questao->resposta_correta == 'v' || $questao->resposta_correta == 'f')
                        <!-- Exibir apenas Verdadeiro/Falso -->
                        <div style="margin-top: 10px">
                            <input
                                id="{{ $questao->id_questao }}_verdadeiro"
                                type="radio"
                                name="{{ $questao->id_questao }}"
                                value="verdadeiro"
                                onchange="atualizarContagem()"
                            >
                            <label style="font-weight: normal;" for="{{ $questao->id_questao }}_verdadeiro">a) Verdadeiro</label>
                        </div>

                        <div style="margin-top: 10px">
                            <input
                                id="{{ $questao->id_questao }}_falso"
                                type="radio"
                                name="{{ $questao->id_questao }}"
                                value="falso"
                                onchange="atualizarContagem()"
                            >
                            <label style="font-weight: normal;" for="{{ $questao->id_questao }}_falso">b) Falso</label>
                        </div>
                    @else
                        @foreach ($questao->alternativas_embaralhadas as $index => $alternativa)
                            <div style="margin-top: 10px">
                                <input
                                    {{-- id="questao_{{ $questao->id_questao }}_{{ $alternativa }}" --}}
                                    id="{{ $questao->id_questao }}_{{ $alternativa }}"
                                    type="radio"
                                    name="{{ $questao->id_questao }}"
                                    value="{{ $alternativa }}"
                                    onchange="atualizarContagem()"
                                >
                                <label style="font-weight: normal;" for="{{ $questao->id_questao }}_{{ $alternativa }}">{{ $letras[$index] }}{{ preg_replace('/^[a-z]\)\s*/i', '', $alternativa) }}</label>
                            </div>
                        @endforeach
                    @endif    
                    <button type="button" class="btn btn-primary" style="margin-top: 10px" onclick="limparQuestao('{{ $questao->id_questao }}')">Limpar Opção</button>
                    <button class="btn btn-outline-success" style="margin-top: 10px" type="submit">Enviar</button>
                </div>
            @endforeach
        </div>

        <button class="btn btn-outline-success" style="margin-top: 10px" type="submit">Enviar Tudo</button>
        <button class="btn btn-outline-warning" style="margin-top: 10px" type="button" onclick="limpar()">Desmarcar todos</button>
    </form>

@endsection

@section('scripts')

    <script>

        function limparQuestao(idQuestao) {
            // Seleciona o botão de rádio da questão especificada e desmarca
            const opcoesMarcadas = document.querySelectorAll(`input[name="${idQuestao}"]:checked`);
            opcoesMarcadas.forEach(function(opcao) {
                opcao.checked = false;
            });
            console.log(`Opção da questão ${idQuestao} desmarcada`);
        }

        function limpar() {
            // Limpar todas as opções
            let opcoesMarcadas = document.querySelectorAll('input[type="radio"]:checked');
            opcoesMarcadas.forEach(function(opcao) {
                opcao.checked = false;
            });
            console.log("Todas as opções foram desmarcadas");
        }

        function atualizarContagem() {
            // Seleciona todas as questões com alternativas marcadas
            const radiosMarcados = document.querySelectorAll('input[type="radio"]:checked');
            
            // Atualiza o valor do input hidden com a quantidade de questões marcadas
            document.getElementById('numero_questoes_marcadas').value = radiosMarcados.length;
            console.log(radiosMarcados.length)

        }
        
    </script>

@endsection
