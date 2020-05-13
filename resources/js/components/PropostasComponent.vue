<template>
    <div>
        <div class="float-right" style="margin-bottom: 20px;">Saldo: 
            <form @submit.prevent="attSaldo">
                <input type="text" v-model="saldo"  v-money="moeda">
            </form>
        </div>
        <table class="table table-striped">
            <tbody><tr>
                    <th>Nº</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Vendedor</th>
                    <th>Valor do boleto</th>
                    <th>Status</th>
                    </tr>
                    <tr v-for="p in paginaDePropostas" v-bind:key="p.id">
                        <td><a href="#" data-toggle="modal" :data-target="'#PropostaModal' + p.id"><strong>{{ p.protocolo }}</strong></a></td>
                        <td>{{ p.nome }}</td>
                        <td>{{ p.cpf }}</td>
                        <td>{{ p.nome_vendedor }}</td>
                        <td>R$ {{ formatMoeda(p.valor_boleto) }}</td>
                        <td>
                            <span v-if="p.status === 1" class="badge badge-warning">CONTA ABERTA</span>
                            <span v-if="p.status === 2" class="badge badge-danger">BOLETO PARA QUITAR</span>
                            <span v-if="p.status === 3" class="badge badge-primary">BOLETO QUITADO</span>
                            <span v-if="p.status === 4" class="badge badge-success">FINALIZADO</span>
                        </td>
                        <!-- Modal proposta -->
                        <div class="modal fade" :id="'PropostaModal' + p.id" tabindex="-1" role="dialog" aria-labelledby="PropostaModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="PropostaModalLabel">Nº {{ p.protocolo }} - 
                                                <span v-if="p.status === 1" class="badge badge-warning">CONTA ABERTA</span>
                                                <span v-if="p.status === 2" class="badge badge-danger">BOLETO PARA QUITAR</span>
                                                <span v-if="p.status === 3" class="badge badge-primary">BOLETO QUITADO</span>
                                                <span v-if="p.status === 4" class="badge badge-success">FINALIZADO</span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped">
                                                    <tr><td colspan="4">Nome: <strong>{{ p.nome }}</strong></td></tr> 
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
                                                        <td>Vencimento: {{ p.data_vencimento_boleto | moment("DD/MM/YYYY") }}</td>
                                                    </tr>
                                                    <tr v-if="p.data_quitacao_boleto">
                                                        <td colspan="3">Data de quitação: {{ p.data_quitacao_boleto | moment("DD/MM/YYYY") }}</td>
                                                    </tr>   
                                                    <tr>
                                                        <td  colspan="3">   
                                                            <form @submit.prevent="formAttProposta(p.id)">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-4">
                                                                            <label for="status">Atualizar status</label>
                                                                            <select id="status" class="form-control" name="status" v-model="status">
                                                                                <option value="1">CONTA ABERTA</option>
                                                                                <option value="2">BOLETO PARA QUITAR</option>
                                                                                <option value="3">BOLETO QUITADO</option>
                                                                                <option value="4">FINALIZADO</option>
                                                                            </select>
                                                                        </div>
                                                                        <div v-if="status == 3" class="form-group col-md-4">
                                                                            <label for="data_quitacao">Data de quitação</label>
                                                                            <input type="date" class="form-control" name="data_quitacao" id="data_quitacao" v-model="data_quitacao" required>
                                                                        </div>
                                                                        <div class="form-group col-md-12"  style="vertical-align: bottom !important;">
                                                                            <button class="btn btn-success">ATUALIZAR</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                        </td>
                                                    </tr>
                                            </table>
                                            
                                        </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim Modal proposta -->                
                    </tr>
                    
            </tbody>
        </table>
        <jw-pagination v-bind:items="listaPropostas" @changePage="onChangePage" :labels="customLabels" :styles="customStyles"></jw-pagination>

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
                                <input type="text" class="form-control text-uppercase" id="vendedor" name="vendedor" v-model="proposta.nome_vendedor" required>
                            </div>
                            <div class="col-12"><hr></div>
                            <div class="form-group col-md-4">
                                <label for="banco_boleto">Banco do boleto</label>
                                <select class="form-control" id="banco_boleto" name="banco_boleto" v-model="proposta.banco_boleto" required>
                                    <option v-for="b in listabancos" v-bind:key="b.codigo" v-bind:value="b.codigo + ' - ' + b.nome">{{ b.codigo }} - {{ b.nome }} </option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                    <label for="valor_boleto">Valor do boleto</label>
                                    <input class="form-control" type="text" v-money="moeda" @blur="valorRendimento" name="valor_boleto" v-model="proposta.valor_boleto" required>
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="data_vencimento_boleto">Data do vencimento</label>
                                <input type="date" class="form-control" name="data_vencimento_boleto" id="data_vencimento_boleto" v-model="proposta.data_vencimento_boleto" required>
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                    <label for="rendimento">Rendimento</label>
                                    <input class="form-control" type="text" v-money="moeda_rendimento" name="rendimento" v-model="proposta.rendimento" disabled>
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                    <label for="comissao_total">Comissão total</label>
                                    <input class="form-control" type="text" v-money="moeda" name="comissao_total" v-model="proposta.comissao_total" required>
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                    <label for="comissao_escritorio">Comissão do escritório</label>
                                    <input class="form-control" type="text" v-money="moeda" name="comissao_escritorio" v-model="proposta.comissao_escritorio" required>
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
</template>

<script>
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import {VMoney} from 'v-money'

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
        data() {
            return {
                listabancos: [],
                status: '',
                saldo: '',
                data_quitacao: '',
                moeda: {
                    decimal: ',',
                    thousands: '.',
                    prefix: 'R$ ',
                    suffix: '',
                    precision: 2,
                    masked: false
                },
                moeda_rendimento: {
                    decimal: ',',
                    thousands: '.',
                    prefix: 'R$ ',
                    suffix: '',
                    precision: 4,
                    masked: false
                },
                proposta:{
                    nome: '',
                    nome_vendedor: '',
                    cpf: '',
                    telefone: '',
                    banco_boleto: '',
                    data_vencimento_boleto: '',
                    valor_boleto: '',
                    rendimento: '',
                    comissao_total: '',
                    comissao_escritorio: ''
                },
                listaPropostas: [],
                paginaDePropostas: [],
                customLabels,
                customStyles
            }
        },
        methods:{
            formatMoeda(value) {
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
                var valor = this.proposta.valor_boleto.replace(".","");
                var valor = valor.replace("R$","");
                var valor = valor.replace(",",".");
                var valor = parseFloat(valor);
                this.proposta.rendimento = (valor * 0.05).toFixed(4);
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
            formAttProposta(id){
                let currentObj = this;
                let formData = new FormData();
                formData.append('status', this.status);
                formData.append('data_quitacao', this.data_quitacao);
                
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
            },
            loadSaldo() {
                axios.get('/getsaldo')
                    .then(response => {
                        this.saldo = parseFloat(response.data.saldo).toFixed(2);
                        console.log(this.saldo);
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
        },
        mounted() {
            this.loadBancos();
            this.loadPropostas();
            this.loadSaldo();
        }
    }
</script>
