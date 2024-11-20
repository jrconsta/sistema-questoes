@extends('layouts.masterpage')

@section('card-title')
    <a href="{{ route('questoes.index') }}" >Home</a>
    <br> 
    <h5 class="text-primary"><strong>Total de Questões - {{ $totalQuestoes }}</strong></h5>
    <h5 class="text-success"><strong>Total de Acertos - {{ $contador }}</strong></h5>
    <h5 class="text-danger"><strong>Total de Erros - {{ $totalQuestoes - $contador }}</strong></h5>
    <h3 style="color: {{ $porcentagemAcertos >= 90 ? 'green' : ($porcentagemAcertos >= 80 ? 'blue' : 'red') }};">
        <strong> Resultado - {{ number_format($porcentagemAcertos, 2).' %' }} </strong>
    </h3>
@endsection

@section('card-body')
    @foreach ($questoesProcessadas as $questaoProcessada)
        <div class="questao-container" style="background-color: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            {{-- Enunciado --}}
            <div class="mb-3">
                <strong>{{ $questaoProcessada['questao']->id_questao }}</strong> - {{ $questaoProcessada['questao']->enunciado }}
            </div>

            {{-- Alternativas para questões V/F --}}
            @if($questaoProcessada['questao']->resposta_correta == 'v' || $questaoProcessada['questao']->resposta_correta == 'f')
                <div class="alternativas-container">
                    {{-- Verdadeiro --}}
                    @php
                        $bgColorVerdadeiro = '#ffffff'; // cor padrão
                        if(($questaoProcessada['textoColunaMarcada'] == $questaoProcessada['questao']->a && $questaoProcessada['resultado']) || 
                           $questaoProcessada['questao']->resposta_correta == 'v') {
                            $bgColorVerdadeiro = '#d4edda'; // verde para correto
                        } elseif($questaoProcessada['textoColunaMarcada'] == $questaoProcessada['questao']->a && !$questaoProcessada['resultado']) {
                            $bgColorVerdadeiro = '#f8d7da'; // vermelho para errado
                        }
                    @endphp
                    <div class="alternativa" style="padding: 10px; border-radius: 5px; margin-bottom: 10px; background-color: {{ $bgColorVerdadeiro }}">
                        a) Verdadeiro
                        @if($questaoProcessada['questao']->resposta_correta == 'v')
                            <i class="fas fa-check-double" style="color: #28a745; margin-left: 10px;"></i>
                        @endif
                    </div>

                    {{-- Falso --}}
                    @php
                        $bgColorFalso = '#ffffff'; // cor padrão
                        if(($questaoProcessada['textoColunaMarcada'] == $questaoProcessada['questao']->b && $questaoProcessada['resultado']) || 
                           $questaoProcessada['questao']->resposta_correta == 'f') {
                            $bgColorFalso = '#d4edda'; // verde para correto
                        } elseif($questaoProcessada['textoColunaMarcada'] == $questaoProcessada['questao']->b && !$questaoProcessada['resultado']) {
                            $bgColorFalso = '#f8d7da'; // vermelho para errado
                        }
                    @endphp
                    <div class="alternativa" style="padding: 10px; border-radius: 5px; margin-bottom: 10px; background-color: {{ $bgColorFalso }}">
                        b) Falso
                        @if($questaoProcessada['questao']->resposta_correta == 'f')
                            <i class="fas fa-check-double" style="color: #28a745; margin-left: 10px;"></i>
                        @endif
                    </div>

                    {{-- Explicação para V/F quando errar --}}
                    @if(!$questaoProcessada['resultado'] && $questaoProcessada['questao']->resposta_v_f)
                        <div class="explicacao" style="margin-top: 10px; padding: 10px; background-color: #fff3cd; border-radius: 5px; color: #856404;">
                            <i class="fas fa-info-circle"></i> {{ $questaoProcessada['questao']->resposta_v_f }}
                        </div>
                    @endif
                </div>
            @else
                {{-- Para questões de múltipla escolha --}}
                  @foreach(['a', 'b', 'c', 'd', 'e'] as $letra)
                        @if(!empty($questaoProcessada['questao']->$letra))
                        @php
                              $bgColor = '#ffffff'; // cor padrão
                              if($questaoProcessada['questao']->resposta_correta == $letra) {
                                    $bgColor = '#d4edda'; // verde para correto
                              } elseif($questaoProcessada['textoColunaMarcada'] == $questaoProcessada['questao']->$letra && !$questaoProcessada['resultado']) {
                                    $bgColor = '#f8d7da'; // vermelho para errado
                              }
                              
                              // Remove a letra do início da alternativa caso exista
                              $textoAlternativa = preg_replace('/^[a-e]\)\s*/i', '', $questaoProcessada['questao']->$letra);
                        @endphp
                        <div class="alternativa" style="padding: 10px; border-radius: 5px; margin-bottom: 10px; background-color: {{ $bgColor }}">
                              {{ $letra }}) {{ $textoAlternativa }}
                              @if($questaoProcessada['questao']->resposta_correta == $letra)
                                    <i class="fas fa-check-double" style="color: #28a745; margin-left: 10px;"></i>
                              @endif
                              @if($questaoProcessada['textoColunaMarcada'] == $questaoProcessada['questao']->$letra && !$questaoProcessada['resultado'])
                                    <i class="fas fa-times" style="color: #dc3545; margin-left: 10px;"></i>
                              @endif
                        </div>
                        @endif
                  @endforeach
            @endif
        </div>
        <hr>
    @endforeach
@endsection