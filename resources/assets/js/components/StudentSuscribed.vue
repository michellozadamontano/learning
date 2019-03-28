<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Estudiates subscritos</h3>     
                <download-excel                    
                   
                    :data="array_excel"
                    :before-generate = "startDownload"
                    :before-finish = "finishDownload"
                    class="hover-excel ">
                     Datos Excel
                     <i class="fas fa-file-excel green"></i>                    
                </download-excel>           
              </div>             
              <div class="card-body table-responsive p-2">
                  <span class="purple"><h3>Filtrar por rango de fecha</h3> </span>
                  <div class="col-8">
                      <div class="input-group">                       
                        <datepicker v-model="desde" placeholder="Desde" input-class="form-control"></datepicker>
                        <div class="input-group-prepend">
                            <span class="input-group-text">-</span>
                        </div>                        
                        <datepicker v-model="hasta" placeholder="Hasta" input-class="form-control"></datepicker>
                        <button class="btn btn-primary" @click="showByrange()">
                           mostrar
                        </button>
                    </div>                    
                        
                  
                  </div>
                  
                  <v-client-table :data="tableData" :columns="columns" :options="options">
                        <div slot="actions" slot-scope="">
                            <a href="#" v-tooltip="msg">                                
                                <i class="fas fa-edit blue   "></i>
                            </a>
                            /
                            <a href="#">
                                <i class="fa fa-trash red"></i>
                            </a>                            
                        </div>
                    </v-client-table>
               
              </div>
              <!-- /.card-body -->
              <div class="card-footer">                  
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>        
        
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';   
import 'vue-loading-overlay/dist/vue-loading.css';
import Datepicker from 'vuejs-datepicker';
export default {
    name: "studentsubcribed",
    data() {
        return {
            isLoading: false,
            msg:'Editar',
            url: '/subscriptions/users_subscribed',
            columns: ['id', 'name', 'email','plan','costo','paypal_email','country','city','actions'],
            tableData:[],
            excel_object:{
                nombre:"",
                email: "",
                plan:"",
                costo:"",
                paypal_email:"",
                country:"",
                city:"",
            },
            array_excel:[],
            desde:"",
            hasta:"",
            options: {
                filterByColumn: false,
                perPage: 10,
                perPageValues: [10, 25, 50, 100, 500],
                    headings: {
                    id                  : 'ID',
                    name                : 'Nombre',
                    email               : 'Email',                    
                    plan                : 'Plan',
                    costo               : 'Costo',
                    paypal_email        : 'Paypal Email',
                    country             : 'Pais',
                    city                : 'Ciudad',
                    actions             : "Acciones",    
                },
                templates:{
                    name: function(h,row,index){
                        return row.user.name
                    },
                    email: function (h,row,index){
                        return row.user.email
                    },   
                    plan: function(h,row){
                        let plan = ""
                        if(row.user.paypal_subscription != null)
                        {
                            plan =  row.user.paypal_subscription.plan
                        }
                        return plan;
                        
                    },
                    costo: function(h,row){
                        let costo = ""
                        if(row.user.paypal_subscription != null)
                        {
                            costo =  row.user.paypal_subscription.amount
                        }
                        return costo;
                        
                    },
                    paypal_email: function(h,row){
                        let paypal_email = ""
                        if(row.user.paypal_subscription != null)
                        {
                            paypal_email =  row.user.paypal_subscription.paypal_email
                        }
                        return paypal_email;
                        
                    },
                    country: function(h,row){
                        let country = ""
                        if(row.user.paypal_subscription != null)
                        {
                            country =  row.user.paypal_subscription.country
                        }
                        return country;
                        
                    },
                    city: function(h,row){
                        let city = ""
                        if(row.user.paypal_subscription != null)
                        {
                            city =  row.user.paypal_subscription.city
                        }
                        return city;
                        
                    }
                }
            },
            json_data:this.array_excel,
        }
    },
    components: {
        Loading,
        Datepicker
    }, 
    methods: {
        loadStudent(){
            this.isLoading = true;
            axios.get(this.url).then(res => {
                this.tableData = res.data;
                console.log(res.data);
               /* res.data.forEach(element => {
                   let obj =  this.excel_object = {
                        nombre          : element.user.name,
                        email           : element.user.email,
                        plan            : element.user.paypal_subscription.plan,
                        costo           : element.user.paypal_subscription.amount,
                        paypal_email    : element.user.paypal_subscription.paypal_email,
                        country         : element.user.paypal_subscription.country,
                        city            : element.user.paypal_subscription.city,
                    }
                    this.array_excel.push(obj);

                });*/
               // console.log(this.array_excel);
                
                this.isLoading = false;

                
            }).catch(error => {
                console.log(error);
                this.isLoading = false;
                
            })
        },
        showByrange(){
           
            console.log("desde:"+ this.desde + ' hasta:' + this.hasta);
            if(this.desde != "" && this.hasta != ""){
                this.isLoading = true;
                axios.post('subscriptions/users_subscribed_range',{
                    desde: this.desde,
                    hasta: this.hasta
                }).then(res => {
                    this.tableData = res.data;
                   // console.log(res.data);
                    this.isLoading = false;
                    
                }).catch(error => {
                    console.log(error);
                    this.isLoading = false;
                    
                })
            }
             
        },
        async fetchData(){
            this.array_excel = []
            axios.get(this.url).then(res => {              
               
                res.data.forEach(element => {
                   let obj =  this.excel_object = {
                        nombre          : element.user.name,
                        email           : element.user.email,
                        plan            : element.user.paypal_subscription.plan,
                        costo           : element.user.paypal_subscription.amount,
                        paypal_email    : element.user.paypal_subscription.paypal_email,
                        country         : element.user.paypal_subscription.country,
                        city            : element.user.paypal_subscription.city,
                    }
                    this.array_excel.push(obj);

                });
               // console.log(this.array_excel);
                this.isLoading = false;
                return this.array_excel;
                

                
            }).catch(error => {
                console.log(error);
                this.isLoading = false;
                
            })
           
        },
        startDownload(){
        this.isLoading = true;
        },
        finishDownload(){
             this.isLoading = false;
        }
        
    },
    mounted() {
        this.loadStudent();
        this.fetchData();
    },
}
</script>
<style>

</style>
