<template>
    <div>
        <form @submit.prevent="formAtualizarProposta(proposta.id)">
            <div class="row">
                            <div class="form-group col-md-12">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control text-uppercase" id="nome" aria-describedby="nomeHelp" v-model="proposta.nome" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cpf">CPF</label>
                                <input type="text" class="form-control" v-mask="'###.###.###-##'" id="cpf" name="cpf" v-model="proposta.cpf" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control" id="telefone" v-mask="'(##) #####-####'" name="telefone" v-model="proposta.telefone" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="vendedor">Vendedor</label>
                                <input type="text" class="form-control text-uppercase" id="vendedor" name="vendedor" v-model="proposta.nome_vendedor" disabled>
                            </div>
                            <div class="col-12"><hr></div>
                            <div class="form-group col-md-4">
                                <label for="banco_boleto">Banco do boleto</label>
                                <input type="text" class="form-control text-uppercase" id="banco_boleto" name="banco_boleto" v-model="proposta.banco_boleto" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="agencia">Agência</label>
                                <input type="text" class="form-control text-uppercase" id="agencia" name="agencia" v-model="proposta.agencia_quitacao" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="banco_quitacao">Banco de quitação</label>
                                <input type="text" class="form-control text-uppercase" id="banco_quitacao" name="banco_quitacao" v-model="proposta.banco_quitacao" disabled>
                            </div>
                            <div v-if="proposta.status >= 2" class="form-group col-md-4 col-sm-12">
                                    <label for="valor_boleto">Valor do boleto</label>
                                    <input class="form-control" type="text" name="valor_boleto" :value="formatMoeda(proposta.valor_boleto)" disabled>
                            </div>
                            <div v-if="proposta.status >= 2" class="form-group col-md-4 col-sm-12">
                                <label for="data_vencimento_boleto">Data do vencimento</label>
                                <input type="text" class="form-control" name="data_vencimento_boleto" id="data_vencimento_boleto" :value="proposta.data_vencimento_boleto | moment('DD/MM/YYYY')" disabled>
                            </div>
                            <div v-if="proposta.status >= 2" class="form-group col-md-4 col-sm-12">
                                    <label for="rendimento">Rendimento</label>
                                    <input class="form-control" type="text" v-money="moeda" name="rendimento" :value="formatMoeda(proposta.rendimento)" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Atualizar status</label>
                                <select id="status" class="form-control" name="status" v-model="novosdados.status">
                                    <option value="1" :selected="proposta.status === 1">CONTA ABERTA</option>
                                    <option value="2" :selected="proposta.status === 2">BOLETO PARA QUITAR</option>
                                    <option value="3" :selected="proposta.status === 3">BOLETO QUITADO</option>
                                    <option value="4" :selected="proposta.status === 4">FINALIZADO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-8"></div>
                            <div v-if="novosdados.status == 2" class="form-group col-md-4 col-sm-12">
                                    <label for="valor_boleto">Valor do boleto*</label>
                                    <input class="form-control" type="text" v-money="moeda" @blur="valorRendimento" name="valor_boleto" v-model="novosdados.valor_boleto" required>
                            </div>
                            <div v-if="novosdados.status == 2" class="form-group col-md-4 col-sm-12">
                                <label for="data_vencimento_boleto">Data do vencimento*</label>
                                <input type="date" class="form-control" name="data_vencimento_boleto" id="data_vencimento_boleto" v-model="novosdados.data_vencimento_boleto" required>
                            </div>
                            <div v-if="novosdados.status == 2" class="form-group col-md-4 col-sm-12">
                                    <label for="rendimento">Rendimento</label>
                                    <input class="form-control" type="text" v-money="moeda" name="rendimento" v-model="novosdados.rendimento" disabled>
                            </div>
                            <div v-if="novosdados.status == 2" class="form-group col-md-4 col-sm-12">
                                    <label for="comissao_total">Comissão total</label>
                                    <input class="form-control" type="text" v-money="moeda" name="comissao_total" @blur="valorComissao" v-model="novosdados.comissao_total">
                            </div>
                            <div v-if="novosdados.status == 2" class="form-group col-md-4 col-sm-12">
                                    <label for="comissao_escritorio">Comissão do escritório</label>
                                    <input class="form-control" type="text" v-money="moeda" name="comissao_escritorio" v-model="novosdados.comissao_escritorio" disabled>
                            </div>
                            <div v-if="novosdados.status == 2" class="form-group col-md-4 col-sm-12">
                                    <label for="comissao_vendedor">Comissão do vendedor</label>
                                    <input class="form-control" type="text" v-money="moeda" name="comissao_vendedor" v-model="novosdados.comissao_vendedor" disabled>
                            </div>
                            <div v-if="novosdados.status == 3" class="form-group col-md-4">
                                <label for="data_quitacao">Data de quitação</label>
                                <input type="date" class="form-control" name="data_quitacao" id="data_quitacao" v-model="novosdados.data_quitacao" required>
                            </div>
                            <div v-if="novosdados.status == 3" class="form-group col-md-4 col-sm-12">
                                    <label for="comissao_total">Comissão total</label>
                                    <input class="form-control" type="text" v-money="moeda" name="comissao_total" @blur="valorComissao" v-model="novosdados.comissao_total" required>
                            </div>
                            <div v-if="novosdados.status == 3" class="form-group col-md-4 col-sm-12">
                                    <label for="comissao_escritorio">Comissão do escritório</label>
                                    <input class="form-control" type="text" v-money="moeda" name="comissao_escritorio" v-model="novosdados.comissao_escritorio" disabled>
                            </div>
                            <div v-if="novosdados.status == 3" class="form-group col-md-4 col-sm-12">
                                    <label for="comissao_vendedor">Comissão do vendedor</label>
                                    <input class="form-control" type="text" v-money="moeda" name="comissao_vendedor" v-model="novosdados.comissao_vendedor" disabled>
                            </div>
                            <div v-if="novosdados.status == 4" class="form-group col-md-4">
                                <label for="data_finalização">Data de finalização</label>
                                <input type="date" class="form-control" name="data_finalização" id="data_finalização" v-model="novosdados.data_finalizacao" required>
                            </div>
                            <div class="form-group col-md-12"  style="vertical-align: bottom !important;">
                                <button class="btn btn-success">ATUALIZAR</button>
                            </div>                                                                             
                        </div>
        </form>
    </div>
</template>

<script>
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import VueSimpleSuggest from 'vue-simple-suggest'
import 'vue-simple-suggest/dist/styles.css'
import {
    VMoney
} from 'v-money'
export default {
    props: ['proposta'],
    data() {
        return {
            moeda: {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                suffix: '',
                precision: 2,
                masked: false
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
        }
    },
    methods: {
        formatMoeda(value) {
            let val = (value / 1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        valorRendimento(){
                var valor = this.novosdados.valor_boleto.replace(".","");
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
    },
    mounted() {
    }
}
</script>
