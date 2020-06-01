<template>
    <div class="row col-12">
        <div class="col-md-3">
            <div class="card">
                        <form @submit.prevent="relatorioPropostas()">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Vendedor</label>
                                    <select class="form-control" id="vendedor" name="vendedor" v-model="nome_vendedor" required>
                                        <option value="GERAL">GERAL</option>
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
                                <div class="form-group col-md-12">
                                    <label>Mês</label>
                                    <input type="month" class="form-control text-uppercase" v-model="mes">
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success" >ENVIAR</button>
                                </div>
                            </div>
                        </form>  
            </div>
        </div>
    
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">RELATÓRIOS
                </div>
                <div class="card-body">
                    <div v-if="listaPropostas !== 0">
                        <table class="table table-striped">
                            <tbody><tr>
                                    <th style="cursor: pointer;" v-on:click="ordemColuna('protocolo')">Nº</th>
                                    <th style="cursor: pointer;" v-on:click="ordemColuna('nome')">Nome</th>
                                    <th style="cursor: pointer;" v-on:click="ordemColuna('cpf')">CPF</th>
                                    <th style="cursor: pointer;" v-on:click="ordemColuna('valor_boleto')">Valor do boleto</th>
                                    <th style="cursor: pointer;" v-on:click="ordemColuna('comissao_total')">Comissão total</th>
                                    <th style="cursor: pointer;" v-on:click="ordemColuna('comissao_vendedor')"><strong>Comissão vendedor</strong></th>
                                    <th style="cursor: pointer;" v-on:click="ordemColuna('status')">Status</th>
                                    </tr>
                                    <tr v-for="p in listaPropostas" v-bind:key="p.id">
                                        <td><a href="#" data-toggle="modal" :data-target="'#PropostaModal' + p.id"><strong>{{ p.protocolo }}</strong></a></td>
                                        <td>{{ p.nome }}</td>
                                        <td>{{ p.cpf }}</td>
                                        <td>R$ {{ formatMoeda(p.valor_boleto ) }}</td>
                                        <td>R$ {{ formatMoeda(p.comissao_total ) }}</td>
                                        <td><strong>R$ {{ formatMoeda(p.comissao_vendedor ) }}</strong></td>
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
                                    </tr>
                                    <tr>
                                        <td>Total:</td>
                                        <td>R$ {{ formatMoeda(totalVendedor) }}</td>
                                    </tr>   
                                    
                            </tbody>
                        </table>
                    </div>    
                </div>
            </div>
        </div> 
    </div>
</template>

<script>
    export default {
        data() {
            return {
                nome_vendedor: '',
                mes: '',
                listaPropostas: [],
                ordemCol: '',
                ordem: '',
                listaPropostas: [],
                totalVendedor: ''
            }
        },
        methods:{
            relatorioPropostas() {
                axios.get('/relatorio/' + this.nome_vendedor + '/' + this.mes)
                    .then(response => {
                        this.listaPropostas = response.data;
                        var propostas = this.listaPropostas

                        this.totalVendedor = propostas.Sum({comissao_vendedor});
                        
                        console.log(this.totalVendedor);
                    });
            },
            formatData(data) {
                return Vue.moment(data).add(1, 'd').format('DD/MM/YYYY');
            },
            formatMoeda(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
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
            },
            ordemColuna(coluna){
                this.ordemCol = coluna;
                if(this.ordem.toLowerCase() == "asc"){
                    this.ordem = "desc";
                }else{
                    this.ordem = "asc";
                }
            },
        },
        mounted() {
        }
    }
</script>
