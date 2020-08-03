<template>
<div class="row">
    <div class="col-md-12">
        <div class="float-left" style="margin-bottom: 20px;">
                <h4 class="text-primary mt-3">Saldo: </h4>
                    <form @submit.prevent="attSaldo">
                        <input type="text" v-model="saldo" v-money="moeda">
                    </form>  
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-black bg-warning mb-3 text-center" @click="buscar = 1" style="cursor: pointer;">
            <h1>{{ contaAberta }}</h1>
            CONTAS ABERTAS
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-danger mb-3 text-center" @click="buscar = 2" style="cursor: pointer;">
            <h1>{{ boletoQuitar }}</h1>
            PARA QUITAR
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-primary mb-3 text-center" @click="buscar = 3" style="cursor: pointer;">
            <h1>{{ boletoQuitado }}</h1>
            BOLETOS QUITADOS
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-success mb-3 text-center" @click="buscar = 4" style="cursor: pointer;">
            <h1>{{ propostapaga }}</h1>
            PROPOSTAS PAGAS
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-info mb-3 text-center" @click="buscar = 5" style="cursor: pointer;">
            <h1>{{ boletoFinalizado }}</h1>
            FINALIZADOS
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-dark mb-3 text-center" @click="buscar = ''" style="cursor: pointer;">
            <h1>{{ totalPropostas }}</h1>
            TOTAL
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3" style="margin-bottom: 20px; padding: 0px !important;">
                <div class="card border-success mx-sm-1 p-3">
                    <span class="text-success text-center mt-3">Rendimento</span>
                    <h2 class="text-success text-center mt-2">R${{ formatMoeda(comissoes.rendimento) }}</h2>
                </div>
            </div>
            <div class="col-md-3" style="margin-bottom: 20px; padding: 0px !important;">
                <div class="card border-info mx-sm-1 p-3">
                    <span class="text-info text-center mt-3">Comissão total</span>
                    <h2 class="text-info text-center mt-2">R${{ formatMoeda(comissoes.comissao_total) }}</h2>
                </div>
            </div>
            <div class="col-md-3" style="margin-bottom: 20px; padding: 0px !important;">
                <div class="card border-primary mx-sm-1 p-3">
                    <span class="text-primary text-center mt-3">Comissão do escritório</span>
                    <h2 class="text-primary text-center mt-2">R${{ formatMoeda(comissoes.comissao_escritorio) }}</h2>
                </div>  
            </div>
            <div class="col-md-3" style="margin-bottom: 20px; padding: 0px !important;">
                <div class="card border-danger mx-sm-1 p-3">
                    <span class="text-danger text-center mt-3">Comissões a pagar</span>
                    <h2 class="text-danger text-center mt-2">R${{ formatMoeda(comissoes.comissoes_vendedor) }}</h2>
                </div>   
            </div>
        </div>    
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">PROPOSTAS
                <div class="float-right" style="width: 350px;">
                    <input type="search" name="table_search" placeholder="Buscar" class="form-control pull-right text-uppercase" v-model="buscar">
                </div>
            </div>
            <div class="card-body">
        <table class="table table-striped">
            <tbody><tr>
                    <th style="cursor: pointer;" v-on:click="ordemColuna('protocolo')" width="100">Nº</th>
                    <th style="cursor: pointer;" v-on:click="ordemColuna('nome')">Nome</th>
                    <th style="cursor: pointer;" v-on:click="ordemColuna('cpf')">CPF</th>
                    <th style="cursor: pointer;" v-on:click="ordemColuna('nome_vendedor')">Vendedor</th>
                    <th style="cursor: pointer;" v-on:click="ordemColuna('valor_boleto')">Valor do boleto</th>
                    <th style="cursor: pointer;" v-on:click="ordemColuna('data_vencimento_boleto')">Vencimento</th>
                    <th style="">Valor de aviso de débito</th>
                    <th style="cursor: pointer;" v-on:click="ordemColuna('status')">Status</th>
                    </tr>
                    <tr v-for="p in paginaDePropostas" v-bind:key="p.id">
                        <td><a href="#" data-toggle="modal" :data-target="'#PropostaModal' + p.id"><strong>{{ p.protocolo }}</strong></a></td>
                        <td>{{ p.nome }}</td>
                        <td>{{ p.cpf }}</td>
                        <td><a href="javascript:;" v-on:click="comissoesVendedor(p.nome_vendedor)" data-toggle="modal" data-target="#ComissoesVendedorModal">{{ p.nome_vendedor }}</a></td>
                        <td>R$ {{ formatMoeda(p.valor_boleto ) }}</td>
                        <td><span v-if="p.data_vencimento_boleto !== null">{{ formatData(p.data_vencimento_boleto) }}</span><span v-else class="text-center"> - </span></td>
                        <td> <span v-if="p.valor_boleto !== null && p.rendimento !== null && p.comissao_total !== null">R$ {{ avisoDebito(p.valor_boleto,p.rendimento,p.comissao_total ) }}</span>
                        <span v-else class="text-center"> - </span>
                        </td>
                        <td>
                            <span v-if="p.status === 1" class="badge badge-warning">CONTA ABERTA</span>
                            <span v-if="p.status === 2" class="badge badge-danger">BOLETO PARA QUITAR</span>
                            <span v-if="p.status === 3" class="badge badge-primary">BOLETO QUITADO</span>
                            <span v-if="p.status === 4" class="badge badge-success">PROPOSTA PAGA</span>
                            <span v-if="p.status === 5" class="badge badge-info">FINALIZADO</span>
                            <span v-if="p.status !== 5"><a href="#" class="badge badge-pill badge-dark" data-toggle="modal" :data-target="'#EditarPropostaModal'+p.id">EDITAR</a></span>
                            <span v-if="p.status == 1 || p.status == 2"><a href="#" class="badge badge-pill badge-danger" @click="removerProposta(p.id)">EXCLUIR</a></span>
                        </td>
                        <!-- Modal editar proposta -->
                        <div class="modal fade" :id="'EditarPropostaModal' + p.id" tabindex="-1" role="dialog" aria-labelledby="EditarPropostaModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="EditarPropostaModalLabel">Nº {{ p.protocolo }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form @submit.prevent="formAtualizarProposta(p.id)">
                                                <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label >Nome</label>
                                                                    <input type="text" class="form-control text-uppercase" v-model="p.nome" disabled>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label>CPF</label>
                                                                    <input type="text" class="form-control" v-mask="'###.###.###-##'" v-model="p.cpf" disabled>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="telefone_cliente">Telefone</label>
                                                                    <input type="text" class="form-control" v-mask="'(##) #####-####'" v-model="p.telefone" disabled>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label>Vendedor</label>
                                                                    <input type="text" class="form-control text-uppercase" v-model="p.nome_vendedor" disabled>
                                                                </div>
                                                                <div class="col-12"><hr></div>
                                                                <div class="form-group col-md-4">
                                                                    <label>Banco do boleto</label>
                                                                    <input type="text" class="form-control text-uppercase" v-model="p.banco_boleto" disabled>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label>Agência</label>
                                                                    <input type="text" class="form-control text-uppercase" v-model="p.agencia_quitacao" disabled>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label>Banco de quitação</label>
                                                                    <input type="text" class="form-control text-uppercase" v-model="p.banco_quitacao" disabled>
                                                                </div>
                                                                <div v-if="p.valor_boleto" class="form-group col-md-4 col-sm-12">
                                                                        <label>Valor do boleto*</label>
                                                                        <input class="form-control" type="text" v-money="moeda" :value="formatMoeda(p.valor_boleto)" disabled>
                                                                </div>
                                                                <div v-else class="form-group col-md-4 col-sm-12">
                                                                        <label>Valor do boleto*</label>
                                                                        <input class="form-control" type="text" v-money="moeda" @blur="valorRendimento" v-model="novosdados.valor_boleto" required>
                                                                </div>
                                                                <div v-if="p.data_vencimento_boleto" class="form-group col-md-4 col-sm-12">
                                                                    <label>Data do vencimento*</label>
                                                                    <input type="text" class="form-control" :value="formatData(p.data_vencimento_boleto)" disabled>
                                                                </div>
                                                                <div v-else class="form-group col-md-4 col-sm-12">
                                                                    <label>Data do vencimento*</label>
                                                                    <input type="date" class="form-control" v-model="novosdados.data_vencimento_boleto" required>
                                                                </div>
                                                                <div v-if="p.rendimento" class="form-group col-md-4 col-sm-12">
                                                                        <label>Rendimento</label>
                                                                        <input class="form-control" type="text" v-money="moeda" :value="formatMoeda(p.rendimento)" disabled>
                                                                </div>
                                                                <div v-else class="form-group col-md-4 col-sm-12">
                                                                        <label>Rendimento</label>
                                                                        <input class="form-control" type="text" v-money="moeda" v-model="novosdados.rendimento" disabled>
                                                                </div>
                                                                <div class="form-group col-md-4 col-sm-12">
                                                                    <label>Comissão total</label>
                                                                    <small id="comissao_totalHelp" v-if="p.comissao_total" class="form-text text-muted">Valor atual: {{ formatMoeda(p.comissao_total) }}</small>
                                                                    <input class="form-control" type="text" v-money="moeda"  @blur="valorComissao" v-model="novosdados.comissao_total">
                                                                </div>
                                                                <div class="form-group col-md-4 col-sm-12">
                                                                        <label>Comissão do escritório</label>
                                                                        <small id="comissao_escritorioHelp" v-if="p.comissao_escritorio" class="form-text text-muted">Valor atual: {{ formatMoeda(p.comissao_escritorio) }}</small>
                                                                        <input class="form-control" type="text" v-money="moeda" v-model="novosdados.comissao_escritorio" disabled>
                                                                </div>
                                                                <div class="form-group col-md-4 col-sm-12">
                                                                        <label>Comissão do vendedor</label>
                                                                        <small id="comissao_vendedorHelp" v-if="p.comissao_vendedor" class="form-text text-muted">Valor atual: {{ formatMoeda(p.comissao_vendedor) }}</small>
                                                                        <input class="form-control" type="text" v-money="moeda" v-model="novosdados.comissao_vendedor" disabled>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label>Atualizar status</label>
                                                                    <select class="form-control" v-model="novosdados.status" required>
                                                                        <option v-if="p.status <= 1" value="1" v-bind:selected="p.status == 1">CONTA ABERTA</option>
                                                                        <option v-if="p.status <= 2" value="2" v-bind:selected="p.status == 2">BOLETO PARA QUITAR</option>
                                                                        <option v-if="p.status <= 3" value="3" v-bind:selected="p.status == 3">BOLETO QUITADO</option>
                                                                        <option v-if="p.status <= 4" value="4" v-bind:selected="p.status == 4">PROPOSTA PAGA</option>
                                                                        <option v-if="p.status <= 5" value="5" v-bind:selected="p.status == 5">FINALIZADO</option>
                                                                    </select>
                                                                </div>
                                                                <div v-if="novosdados.status == 3" class="form-group col-md-4">
                                                                    <label>Data de quitação</label>
                                                                    <input type="date" class="form-control" v-model="novosdados.data_quitacao" required>
                                                                </div>
                                                                <div v-if="novosdados.status == 5" class="form-group col-md-4">
                                                                    <label>Data de finalização</label>
                                                                    <input type="date" class="form-control" v-model="novosdados.data_finalizacao" required>
                                                                </div>
                                                                <div class="form-group col-md-12"  style="vertical-align: bottom !important;">
                                                                    <button class="btn btn-success">ATUALIZAR</button>
                                                                </div>                                                                             
                                                            </div>
                                            </form>
                                        </div>
                                </div>
                            </div>
                        </div>    
                        <!-- Fim Modal editar proposta --> 
                        <!-- Modal proposta -->
                        <div class="modal fade" :id="'PropostaModal' + p.id" tabindex="-1" role="dialog" aria-labelledby="PropostaModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="PropostaModalLabel">Nº {{ p.protocolo }} - 
                                                <span v-if="p.status === 1" class="badge badge-warning">CONTA ABERTA</span>
                                                <span v-if="p.status === 2" class="badge badge-danger">BOLETO PARA QUITAR</span>
                                                <span v-if="p.status === 3" class="badge badge-primary">BOLETO QUITADO</span>
                                                <span v-if="p.status === 4" class="badge badge-success">PROPOSTA PAGA</span>
                                                <span v-if="p.status === 5" class="badge badge-info">FINALIZADO</span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped">
                                                    <tr><td colspan="4">Cliente: <strong>{{ p.nome }}</strong></td></tr> 
                                                    <tr>
                                                        <td>CPF: {{ p.cpf }}</td>
                                                        <td>Telefone: {{ p.telefone }}</td>
                                                        <td>Vendedor: {{ p.nome_vendedor }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Valor do boleto: R$ {{ formatMoeda(p.valor_boleto) }}</td>
                                                        <td>Rendimento: R$ {{ formatMoeda(p.rendimento) }}</td>
                                                        <td>Comissão total: R$ {{ formatMoeda(p.comissao_total) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Comissão escritório: R$ {{ formatMoeda(p.comissao_escritorio) }}</td>
                                                        <td>Banco: {{ p.banco_boleto }}</td>
                                                        <td>Vencimento: {{ formatData(p.data_vencimento_boleto) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Comissão vendedor: R$ {{ formatMoeda(p.comissao_vendedor) }}</td>
            
                                                    </tr>
                                                    <tr v-if="p.data_quitacao_boleto">
                                                        <td>Data de quitação: {{ formatData(p.data_quitacao_boleto) }}</td>
                                                        <td>Agência de quitação: {{ p.agencia_quitacao }}</td>
                                                        <td>Banco de quitação: {{ p.banco_quitacao }}</td>
                                                    </tr>   
                                            </table>
                                            
                                        </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim Modal proposta -->
                        <!-- Modal comissões do vendedor -->
                        <div class="modal fade" id="ComissoesVendedorModal" tabindex="-1" role="dialog" aria-labelledby="ComissoesVendedorModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ComissoesVendedorModalLabel">COMISSÕES DO VENDEDOR
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped" id="imprimirComissoes">
                                                <tr>
                                                    <td width="100">#</td>
                                                    <td>Cliente</td>
                                                    <td>Comissão total</td>
                                                    <td>Quitação</td>
                                                    <td>Finalização</td>
                                                    <td>Comissão do vendedor</td>
                                                </tr>
                                                <tr  v-for="cv in comissoesDoVendedor" :key="cv.id">
                                                    <td> {{ cv.protocolo }} </td>
                                                    <td> {{ cv.nome }} </td>
                                                    <td>R$ {{ formatMoeda(cv.comissao_total) }}</td>
                                                    <td>{{ formatData(cv.data_quitacao_boleto) }}</td>
                                                    <td>{{ formatData(cv.data_finalizacao_boleto) }}</td>
                                                    <td>R$ {{ formatMoeda(cv.comissao_vendedor) }}</td>
                                                </tr>
                                            </table>
                                        </div>   
                                </div>
                            </div>
                        </div>
                        <!-- Fim Modal comissões do vendedor -->
                    </tr>
                    
            </tbody>
        </table>
        <jw-pagination v-bind:items="lista" @changePage="onChangePage" :labels="customLabels" :styles="customStyles"></jw-pagination>

        <!-- Modal nova proposta -->
        <div class="modal fade" id="novaPropostaModal" tabindex="-1" role="dialog" aria-labelledby="novaPropostaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form @submit="formProposta">
                    <div class="modal-header">
                        <h5 class="modal-title" id="novaPropostaModalLabel">CADASTRAR PROPOSTA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control text-uppercase" id="nome" aria-describedby="nomeHelp" v-model="proposta.nome" required>
                                <small id="nomeHelp" class="form-text text-muted">Informe o nome completo do cliente.</small>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cpf">CPF</label>
                                <input type="text" class="form-control" v-mask="'###.###.###-##'" id="cpf" name="cpf" v-model="proposta.cpf" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control" id="telefone" v-mask="'(##) #####-####'" name="telefone" v-model="proposta.telefone" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="vendedor">Vendedor</label>
                                <select class="form-control" id="vendedor" name="vendedor" v-model="proposta.nome_vendedor" required>
                                    <option value="ANA CAROLINA">ANA CAROLINA</option>
                                        <option value="BARBARA BARROS">BARBARA BARROS</option>
                                        <option value="BIANCA TRINDADE">BIANCA TRINDADE</option>
                                        <option value="BRUNO ARAUJO">BRUNO ARAUJO</option>
                                        <option value="BRUNO SILVA">BRUNO SILVA</option>
                                        <option value="CAMILA RAFAELE">CAMILA RAFAELE</option>
                                        <option value="DEJANIRO WANDERLEY">DEJANIRO WANDERLEY</option>
                                        <option value="DIVINO">DIVINO</option>
                                        <option value="FABRICIO ALLAN">FABRICIO ALLAN</option>
                                        <option value="FELIPE OLIVEIRA">FELIPE OLIVEIRA</option>
                                        <option value="FERNANDA CAMILO">FERNANDA CAMILO</option>
                                        <option value="FERNANDO JUNIOR">FERNANDO JUNIOR</option>
                                        <option value="IEDI ANJOS">IEDI ANJOS</option>
                                        <option value="ISADORA GOMES">ISADORA GOMES</option>
                                        <option value="JESSIKA TEIXEIRA">JESSIKA TEIXEIRA</option>
                                        <option value="JULIO CESAR">JULIO CESAR</option>
                                        <option value="LINDAURA TORRES">LINDAURA TORRES</option>
                                        <option value="MICHELLY MEIRELES">MICHELLY MEIRELES</option>
                                        <option value="MOACYR LUZ">MOACYR LUZ</option>
                                        <option value="NARA CAROLINA">NARA CAROLINA</option>
                                        <option value="NEUZA LEAL">NEUZA LEAL</option>
                                        <option value="RAFAEL FELIPE">RAFAEL FELIPE</option>
                                        <option value="ROSANIA MEIRELLES">ROSANIA MEIRELLES</option>
                                        <option value="TONI RAMOS">TONI RAMOS</option>
                                        <option value="WALTER MEIRELLES">WALTER MEIRELLES</option>
                                </select>
                            </div>
                            <div class="col-12"><hr></div>
                            <div class="form-group col-md-4">
                                <label for="banco_boleto">Banco do boleto</label>
                                <vue-simple-suggest
                                    v-model="proposta.banco_boleto"
                                    :list="listabancos"
                                    display-attribute="nome"
                                    :styles="autoCompleteStyle"
                                    :filter-by-query="true">
                                </vue-simple-suggest>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="agencia">Agência</label>
                                <input type="text" class="form-control text-uppercase" id="agencia" name="agencia" v-model="proposta.agencia_quitacao" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="banco_boleto">Banco de quitação</label>
                                <vue-simple-suggest
                                    v-model="proposta.banco_quitacao"
                                    :list="listabancos"
                                    :styles="autoCompleteStyle"
                                    display-attribute="nome"
                                    :filter-by-query="true">
                                </vue-simple-suggest>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">FECHAR</button>
                        <button type="submit" class="btn btn-primary">SALVAR</button>
                    </div>
                </form>    
            </div>
        </div>
        </div>

        </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#novaPropostaModal">CADASTRAR</button>
                    <button type="button" class="btn btn-outline-success" >RELATÓRIOS</button>
                </div>
            </div>  
        </div>
</div>        
</template>

<script>
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import {VMoney} from 'v-money'
import VueSimpleSuggest from 'vue-simple-suggest'
import 'vue-simple-suggest/dist/styles.css'


const customLabels = {
    first: '<< Primeira',
    last: 'Última >>',
    previous: '<',
    next: '>'
};
const customStyles = {
    ul: {
        margin: '0 auto',
    },
};
    export default {
        components: {
            VueSimpleSuggest
        },
        data() {
            return {
                autoCompleteStyle : {
                    vueSimpleSuggest: "position-relative",
                    inputWrapper: "",
                    defaultInput : "form-control",
                },
                filtro_status: '',
                listabancos: [],
                comissoes: '',
                status: '',
                saldo: '',
                data_quitacao: '',
                data_finalizacao: '',
                buscar: '',
                moeda: {
                    decimal: ',',
                    thousands: '.',
                    prefix: 'R$ ',
                    suffix: '',
                    precision: 2,
                    masked: true
                },
                proposta:{
                    nome: '',
                    nome_vendedor: '',
                    cpf: '',
                    telefone: '',
                    banco_boleto: '',
                    banco_quitacao: '',
                    agencia_quitacao: ''
                },
                novosdados: {
                    data_vencimento_boleto: '',
                    valor_boleto: '',
                    rendimento: '',
                    comissao_total: '',
                    comissao_escritorio: '',
                    comissao_vendedor: '',
                    status: '',
                    data_quitacao: '',
                    data_finalizacao: ''
                },
                contaAberta: '',
                boletoQuitar: '',
                boletoQuitado: '',
                boletoFinalizado: '',
                propostapaga: '',
                totalPropostas: '',
                propostasDoMes: [],
                ordemCol: '',
                ordem: '',
                listaPropostas: [],
                comissoesDoVendedor: [],
                paginaDePropostas: [],
                customLabels,
                customStyles
            }
        },
        methods:{
            formatData(data) {
                return Vue.moment(data).add(1, 'd').format('DD/MM/YYYY');
            },
            formatMoeda(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            avisoDebito(a,b,c) {
                let value = parseFloat(a) + parseFloat(b) + parseFloat(c)
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            onChangePage(paginaDePropostas) {
            // update page of items
            this.paginaDePropostas = paginaDePropostas;
            },
            loadPropostas() {
                axios.get('/listapropostas')
                    .then(response => {
                        this.listaPropostas = response.data;
                        var propostas = response.data;
                        var contaaberta = 0;
                        var boletoquitar = 0;
                        var boletoquitado = 0;
                        var propostapaga = 0;
                        var boletofinalizado = 0;

                        for(var i = 0; i < propostas.length; i++){
                            if(propostas[i].status === 1){
                                contaaberta += 1;
                            }else if(propostas[i].status === 2){
                                boletoquitar += 1;
                            }else if(propostas[i].status === 3){
                                boletoquitado += 1;
                            }else if(propostas[i].status === 4){
                                propostapaga += 1;
                            }else{
                                boletofinalizado += 1;
                            }
                        }
                        this.contaAberta = contaaberta;
                        this.boletoQuitar = boletoquitar;
                        this.boletoQuitado = boletoquitado;
                        this.propostapaga = propostapaga;
                        this.boletoFinalizado = boletofinalizado;
                        this.totalPropostas = propostas.length;
                    });
            },
            loadComissoes() {
                axios.get('/comissoes')
                    .then(response => {
                        this.comissoes = response.data;
                        this.propostasDoMes = response.data.propostaMes;
                    });
            },
            loadBancos: function () {
            var _this = this;
            axios.get('/bancos.json')
                .then(function (response) {
                    // Sucesso
                    _this.listabancos = response.data;
                })
            },
            valorRendimento(){
                var valor = this.novosdados.valor_boleto.replace(".","");
                var valor = valor.replace("R$","");
                var valor = valor.replace(",",".");
                var valor = parseFloat(valor);
                this.novosdados.rendimento = (valor * 0.05).toFixed(2);
            },
            novoValorRendimento(valor){
                var valor = valor.replace(".","");
                var valor = valor.replace("R$","");
                var valor = valor.replace(",",".");
                var valor = parseFloat(valor);
                this.novosdados.rendimento = (valor * 0.05).toFixed(2);
            },
            valorComissao(){
                var comissao = this.novosdados.comissao_total.replace(".","");
                var comissao = comissao.replace("R$","");
                var comissao = comissao.replace(",",".");
                var comissao = parseFloat(comissao);
                this.novosdados.comissao_escritorio = (comissao * 0.65).toFixed(2);
                this.novosdados.comissao_vendedor = (comissao * 0.35).toFixed(2);
            },
            formProposta(e){
            e.preventDefault();
                let currentObj = this;
                const config = {
                    headers:{'content-type':'application/json'}
                }
                console.log(this.proposta);
                axios.post('/cadastrarproposta', this.proposta, config)
                    .then(function (response){
                        currentObj.success = response.data.success;
                        Swal.fire({
                                    title: 'Sucesso',
                                    text: 'Proposta cadastrada com sucesso!',
                                    icon: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                        })
                    })
                    .catch(function (error) {

                        currentObj.output = error;
                        Swal.fire({
                                    title: 'Ops!',
                                    text: 'Erro ao cadastrar proposta.',
                                    icon: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                })

                    });
                    this.proposta = "";
                            $('#novaPropostaModal').removeClass('in');
                            $('#novaPropostaModal').attr("aria-hidden","true");
                            $('#novaPropostaModal').css("display", "none");
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                    this.loadPropostas();
                    this.loadSaldo();
            },
            removerProposta(id) {
            axios.delete('/proposta/' + id)
                .then((response) => {
                    Swal.fire({
                                    title: 'Sucesso',
                                    text: 'Proposta removida com sucesso',
                                    icon: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                    })
                }).catch(function (error) {

                        currentObj.output = error;
                        Swal.fire({
                                    title: 'Ops!',
                                    text: 'Não foi possível remover esta proposta.',
                                    icon: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                })

            });
            this.loadPropostas();
            },
            formAtualizarProposta(id){
                let currentObj = this;
                
                axios.post('/attproposta/' + id, this.novosdados)
                    .then(function (response){
                        currentObj.success = response.data.success;
                        Swal.fire({
                                    title: 'Sucesso',
                                    text: 'Proposta atualizada com sucesso!',
                                    icon: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                        })
                    })
                    .catch(function (error) {

                        currentObj.output = error;
                        Swal.fire({
                                    title: 'Ops!',
                                    text: 'Não foi possível atualizar esta proposta.',
                                    icon: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                })

                    });
                    this.loadPropostas();
                    this.loadSaldo();
                    this.loadComissoes();
            },
            formAttProposta(id){
                let currentObj = this;
                let formData = new FormData();
                formData.append('status', this.status);
                formData.append('data_quitacao', this.data_quitacao);
                formData.append('data_finalizacao', this.data_finalizacao);
                
                axios.post('/attproposta/' + id, formData)
                    .then(function (response){
                        currentObj.success = response.data.success;
                        Swal.fire({
                                    title: 'Sucesso',
                                    text: 'Proposta atualizada com sucesso!',
                                    icon: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                        })
                    })
                    .catch(function (error) {

                        currentObj.output = error;
                        Swal.fire({
                                    title: 'Ops!',
                                    text: 'Não há saldo para atualizar esta proposta.',
                                    icon: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                })

                    });
                    this.loadPropostas();
                    this.loadSaldo();
                    this.loadComissoes();
            },
            loadSaldo() {
                axios.get('/getsaldo')
                    .then(response => {
                        this.saldo = parseFloat(response.data.saldo).toFixed(2);
                    });
            },
            attSaldo(e){
                e.preventDefault();
                let currentObj = this;
                var saldo = this.saldo.replace(".","");
                var saldo = saldo.replace("R$","");
                var saldo = saldo.replace(",",".");
                var saldo = parseFloat(saldo);
                let formData = new FormData();
                formData.append('saldo', saldo);
                console.log(saldo);
                axios.post('/attsaldo/' + 1, formData)
                    .then(function (response){
                        currentObj.success = response.data.success;
                        Swal.fire({
                                    title: 'Sucesso',
                                    text: 'Saldo atualizado com sucesso!',
                                    icon: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                        })
                    })
                    .catch(function (error) {

                        currentObj.output = error;
                        Swal.fire({
                                    title: 'Ops!',
                                    text: 'Erro ao atualizar saldo.',
                                    icon: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                })

                    });
                    // this.loadPropostas();
            },
            ordemColuna(coluna){
                this.ordemCol = coluna;
                if(this.ordem.toLowerCase() == "asc"){
                    this.ordem = "desc";
                }else{
                    this.ordem = "asc";
                }
            },
            propostasFinalizadasDoMes(){
                axios.get('/comissoes/vendedor')
                    .then(response => {
                        this.propostasDoMes = response.data;
                    });
            },
            comissoesVendedor(nome_vendedor){
                this.comissoesDoVendedor = this.propostasDoMes.filter(p => p.nome_vendedor.indexOf(nome_vendedor) > -1);
            },
            // filtroStatus(status){

            //     if (status == undefined) return this.listaPropostas;
                
            //     this.listaPropostas = this.listaPropostas.filter(p => p.status === status);
            //     console.log(status);

            // }
        },
        computed: {
            lista:function(){
                let ordem = this.ordem || "desc";
                let coluna = this.ordemCol || "protocolo";
                coluna = coluna.toLowerCase();
                if(ordem == "asc"){
                    this.listaPropostas.sort(function(a,b){
                    if (a[coluna] > b[coluna]) {return 1;}
                    if (a[coluna] < b[coluna]) {return -1;}
                    return 0;
                });
                }else{
                    this.listaPropostas.sort(function(a,b){
                    if (a[coluna] < b[coluna]) {return 1;}
                    if (a[coluna] > b[coluna]) {return -1;}
                    return 0;
                });
                }

                const busca = this.buscar;

                if (!busca) return this.listaPropostas;

                return this.listaPropostas.filter(p => p.status == busca || p.nome.indexOf(busca) > -1);
            }
        },
        mounted() {
            this.loadBancos();
            this.loadPropostas();
            this.loadSaldo();
            this.loadComissoes();
            this.propostasFinalizadasDoMes();
        }
    }
</script>
