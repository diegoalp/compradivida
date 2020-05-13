<template>
    <div>
        <div>
            Nome: {{ dados }}
        </div>

    </div>
</template>

<script>
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import {
    VMoney
} from 'v-money'
export default {
    props: {
        proposta: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            dados: this.proposta,
            moeda: {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                suffix: '',
                precision: 2,
                masked: false
            },
        }
    },
    methods: {
        formatMoeda(value) {
            let val = (value / 1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        valorRendimento() {
            var valor = this.proposta.valor_boleto.replace(".", "");
            var valor = valor.replace("R$", "");
            var valor = valor.replace(",", ".");
            var valor = parseFloat(valor);
            this.proposta.rendimento = (valor * 0.05).toFixed(4);
        },
        formProposta(e) {
            e.preventDefault();
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'application/json'
                }
            }
            console.log(this.proposta);
            axios.post('/cadastrarproposta', this.proposta, config)
                .then(function (response) {
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
            $('#novaPropostaModal').removeClass('in');
            $('#novaPropostaModal').attr("aria-hidden", "true");
            $('#novaPropostaModal').css("display", "none");
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            this.loadPropostas();
        },
    },
    mounted() {
        typeof this.proposta;
    }
}
</script>
