<template>
    <div class="row col-12">
        <div class="col-md-12">
                        <form @submit.prevent="relatorioPropostas()">
                            <div class="form-row">
                                <div class="form-group col">
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
                                <div class="form-group col">
                                    <label>Mês</label>
                                    <input type="month" class="form-control text-uppercase" v-model="mes">
                                </div>
                                <div class="col align-baseline" style="padding-top: 30px;">
                                    <button type="submit" class="btn btn-success" >ENVIAR</button>
                                </div>
                            </div>
                        </form>  
        </div>
    
        <div class="col-md-12">
            <div class="card" id="impressao">
                <div class="card-header">RELATÓRIO DE PROPOSTAS
                </div>
                <div class="card-body">
                    <div v-if="listaPropostas !== 0">
                        <table class="table table-striped">
                            <tbody><tr>
                                    <th>Nº</th>
                                    <th>Nome</th>
                                    <th>Valor do boleto</th>
                                    <th>Comissão total</th>
                                    <th>Comissão escritório</th>
                                    <th><strong>Comissão vendedor</strong></th>
                                    <th>Status</th>
                                    </tr>
                                    <tr v-for="p in listaPropostas" v-bind:key="p.id">
                                        <!-- <td><a href="#" data-toggle="modal" :data-target="'#PropostaModal' + p.id"><strong>{{ p.protocolo }}</strong></a></td> -->
                                        <td><strong>{{ p.protocolo }}</strong></td>
                                        <td>{{ p.nome }}</td>
                                        <td>R$ {{ formatMoeda(p.valor_boleto ) }}</td>
                                        <td>R$ {{ formatMoeda(p.comissao_total) }}</td>
                                        <td>R$ {{ formatMoeda(p.comissao_escritorio ) }}</td>
                                        <td><strong>R$ {{ formatMoeda(p.comissao_vendedor ) }}</strong></td>
                                        <td>
                                            <span v-if="p.status === 1" class="badge badge-warning">CONTA ABERTA</span>
                                            <span v-if="p.status === 2" class="badge badge-danger">BOLETO PARA QUITAR</span>
                                            <span v-if="p.status === 3" class="badge badge-primary">BOLETO QUITADO</span>
                                            <span v-if="p.status === 4" class="badge badge-success">FINALIZADO</span>
                                        </td>
                                        <!-- Modal proposta -->
                                        <!-- <div class="modal fade" :id="'PropostaModal' + p.id" tabindex="-1" role="dialog" aria-labelledby="PropostaModalLabel" aria-hidden="true">
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
                                        </div> -->
                                        <!-- Fim Modal proposta -->
                                    </tr>
                                    <tr v-if="totalVendedor !== '' && nome_vendedor !== 'GERAL'">
                                        <td colspan="5">COMISSÃO TOTAL DO VENDEDOR:</td>
                                        <td colspan="2"><h5>R$ {{ formatMoeda(totalVendedor) }}</h5></td>
                                    </tr>  
                                    <tr v-if="totalEscritorio !== ''">
                                        <td colspan="5">COMISSÃO TOTAL DO ESCRITÓRIO:</td>
                                        <td colspan="2"><h5>R$ {{ formatMoeda(totalEscritorio) }}</h5></td>
                                    </tr>  
                                    
                            </tbody>
                        </table>
                        <div class="col-md-12" v-if="totalEscritorio !== '' && nome_vendedor === 'GERAL'">
                            <div class="row">
                                <div class="col-md-3" style="margin-bottom: 20px; padding: 0px !important;">
                                    <div class="card border-success mx-sm-1 p-3">
                                        <span class="text-success text-center mt-3">Rendimento</span>
                                        <h2 class="text-success text-center mt-2">R${{ formatMoeda(totalRendimento) }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 20px; padding: 0px !important;">
                                    <div class="card border-info mx-sm-1 p-3">
                                        <span class="text-info text-center mt-3">Comissão total</span>
                                        <h2 class="text-info text-center mt-2">R${{ formatMoeda(totalComissaoTotal) }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 20px; padding: 0px !important;">
                                    <div class="card border-primary mx-sm-1 p-3">
                                        <span class="text-primary text-center mt-3">Comissão do escritório</span>
                                        <h2 class="text-primary text-center mt-2">R${{ formatMoeda(totalEscritorio) }}</h2>
                                    </div>  
                                </div>
                                <div class="col-md-3" style="margin-bottom: 20px; padding: 0px !important;">
                                    <div class="card border-danger mx-sm-1 p-3">
                                        <span class="text-danger text-center mt-3">Comissões a pagar</span>
                                        <h2 class="text-danger text-center mt-2">R${{ formatMoeda(totalVendedor) }}</h2>
                                    </div>   
                                </div>
                            </div>    
                        </div>
                    </div>    
                </div>
            </div>
            <button style="margin-top: 20px" class="btn btn-outline-dark" @click="printDiv"> IMPRIMIR</button>
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
                totalVendedor: '',
                totalEscritorio: '',
                totalRendimento: '',
                totalComissaoTotal: ''
            }
        },
        methods:{
            relatorioPropostas() {
                axios.get('/relatorio/' + this.nome_vendedor + '/' + this.mes)
                    .then(response => {
                        this.listaPropostas = response.data;
                        var propostas = this.listaPropostas
                        //somando os valores da comissao do vendedor
                        //this.totalVendedor = propostas.map(p => p.comissao_vendedor).reduce((a,c)=> parseInt(a) + parseInt(c));
                        var propostasV = propostas.map(p => p.comissao_vendedor);
                        var totalV = 0;
                        for(var i = 0; i < propostasV.length; i++){
                            totalV += parseFloat(propostasV[i]);
                        }
                        this.totalVendedor = totalV;
                        //somando os valores da comissao do escritório
                        var propostasE = propostas.map(p => p.comissao_escritorio);
                        var totalE = 0;
                        for(var i = 0; i < propostasE.length; i++){
                            totalE += parseFloat(propostasE[i]);
                        }
                        this.totalEscritorio = totalE;
                        //somando os valores da comissao do rendimento
                        var propostasR = propostas.map(p => p.rendimento);
                        var totalR = 0;
                        for(var i = 0; i < propostasR.length; i++){
                            totalR += parseFloat(propostasR[i]);
                        }
                        this.totalRendimento = totalR;
                        
                        //somando os valores da comissao da comissão total
                        var propostasCT = propostas.map(p => p.comissao_total);
                        var totalCT = 0;
                        for(var i = 0; i < propostasCT.length; i++){
                            totalCT += parseFloat(propostasCT[i]);
                        }
                        this.totalComissaoTotal = totalCT;
                    });
            },
            formatData(data) {
                return Vue.moment(data).add(1, 'd').format('DD/MM/YYYY');
            },
            formatMoeda(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            printDiv() {
                this.$htmlToPaper("impressao");
            }
        },
        mounted() {
        }
    }
</script>
