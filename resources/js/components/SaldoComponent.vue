<template>
<div>
    <form @submit="attSaldo">
        <input type="text" v-model="saldo"  v-money="moeda">
    </form>
</div>
</template>

<script>
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import {VMoney} from 'v-money'

    export default {
        data() {
            return {
                saldo: '',
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
        methods:{
            // formatMoeda(value) {
            //     let val = (value/1).toFixed(2).replace('.', ',')
            //     return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            // },
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
                console.log(saldo);
                axios.post('/attsaldo/' + 1, saldo)
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
            this.loadSaldo();
        }
    }
</script>
