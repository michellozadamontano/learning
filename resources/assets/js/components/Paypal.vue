<template>
    <div>
        <div class="alert alert-primary text-center" v-if="processing">
            <i class="fa fa-compass"></i> Procesando petición...
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <form @submit.prevent="processForm">
                    <div class="form-group">
                    <label for="">Seleccione Plan</label>
                    <select v-model="plan" class="form-control" name="" id="">
                        <option value="1">Mensual</option>
                        <option value="3">Trimestral</option>
                        <option value="12">Anual</option>
                    </select>
                    </div>
                    <div class="form-group">
                    <label for="">Precio</label>
                    <input v-model="price" type=number step=0.01
                        class="form-control" name="" id="" aria-describedby="helpId" placeholder="">              
                    </div>
                    <div class="form-group">
                    <label for="">Codigo Paypal</label>
                    <input v-model="code" readonly type="text"
                        class="form-control" name="" id="" aria-describedby="helpId" placeholder="" >
                    <small id="helpId" class="form-text text-muted">Codigo paypal para el plan seleccionado</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>   
        </div>
        <div class="justify-content-center mt-4">
            <div class="col-sm-8 offset-2">
                <table class="table table-striped table-inverse table-responsive">
                    <thead class="thead-inverse">
                        <tr>
                            <th>PLAN</th>
                            <th>PRECIO</th>
                            <th>PAYPAL ID</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in paypaldata" :key="item.id">
                                <td scope="row">{{item.paypal_plan.plan}}</td>
                                <td  scope="row">{{item.price}}</td>
                                <td  scope="row">{{item.paypal_code}}</td>
                            </tr>                            
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                processing: false,
                plan:"",
                price:"",
                code:"",
                paypaldata:[],              
            }
        },
        created(){
            axios.get('/subscriptions/paypalplans').then(res => {
                this.paypaldata = res.data.prices;
                console.log(res.data);
                
            })
        },
        methods: {
            processForm: function() {
                this.processing = true;               
                axios
                    .post('/subscriptions/plan/create',
                    {
                        plan:this.plan,
                        price: this.price
                    })
                    .then(response =>{
                      this.code = response.data.price.paypal_code;
                      this.paypaldata = response.data.prices;                          
                      this.processing = false;                     
                    } )
                
            }
        },
    }
</script>