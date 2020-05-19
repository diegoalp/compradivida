<template>
    <div>
        
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
