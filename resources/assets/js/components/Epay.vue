<template>
    <div>        
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <form @submit.prevent="processForm">
                    <div class="form-group">
                    <label for="">Seleccione Plan</label>
                    <select v-model="form.plan" class="form-control" name="plan" id="plan">
                        <option value="MENSUAL">MENSUAL</option>
                        <option value="TRIMESTRAL">TRIMESTRAL</option>
                        <option value="ANUAL">ANUAL</option>
                    </select>
                    </div>
                    <div class="form-group">
                        <label for="">Precio</label>
                        <input v-model="form.price" class="form-control" @keypress="isNumber($event)">  
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
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in epaydata" :key="item.id">
                                <td scope="row">{{item.plan_id}}</td>
                                <td scope="row">{{item.price}}</td>                                
                            </tr>                            
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>    
    import Loading from 'vue-loading-overlay';    
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
         components: {
           Loading
        },  
        data() {
            return {                
                isLoading: false,
                plan    :"",
                price   :"",
                
                epaydata:[],
                 form: new Form({                    
                    plan    :"",
                    price   :"",                         
                })              
            }
        },
        created(){
            this.isLoading = true;
            axios.get('/epay').then(res => {
                this.epaydata = res.data;
                this.isLoading = false;
                console.log(res.data);
                
            })
        },
        methods: {
            processForm: function() {
                this.isLoading = true;                
                this.form.post('/epay/create')
                .then((response)=>{
                    console.log(response.data);        
                   
                    this.epaydata = response.data; 
                    this.isLoading = false;
                })
                .catch((error)=>{
                    console.log('Este es el error', error);
                    this.isLoading = false;
                    
                })    
                
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            }
        },
    }
</script>