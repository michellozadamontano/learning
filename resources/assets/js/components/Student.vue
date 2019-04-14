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
                <h3 class="card-title">Estudiates inscritos a cursos</h3> 
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
export default {
    name: "students",
    data() {
        return {
            isLoading: false,
            msg:'Editar',
            url: 'admin/student_data',
            columns: ['id', 'name', 'email','courses_formatted','plan','costo','paypal_email','country','city','descuento','coupon','actions'],
            tableData:[],
            excel_object:{
                nombre:"",
                email: "",
                cursos: "",
                plan:"",
                costo:"",
                paypal_email:"",
                country:"",
                city:"",
                descuento:"",
                coupon: "",
            },
            array_excel:[],
            options: {
                filterByColumn: false,
                perPage: 10,
                perPageValues: [10, 25, 50, 100, 500],
                    headings: {
                    id                  : 'ID',
                    name                : 'Nombre',
                    email               : 'Email',
                    courses_formatted   : 'Cursos',
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
                        
                    },
                     descuento: function(h,row){
                        let descuento = ""
                        if(row.user.paypal_subscription != null)
                        {
                            if(row.user.paypal_subscription.coupon != null)
                            {
                                descuento =  'SI'
                            }
                            else{
                                descuento =  'NO'
                            }
                            
                        }
                        return descuento;                        
                    },
                    coupon: function(h,row){
                        let coupon = ""
                        if(row.user.paypal_subscription != null)
                        {
                            coupon =  row.user.paypal_subscription.coupon
                        }
                        return coupon;                        
                    }
                }
            }
        }
    },
    components: {
        Loading
    }, 
    methods: {
        loadStudent(){
            this.isLoading = true;
            axios.get(this.url).then(res => {
                this.tableData = res.data;
                console.log(res.data);
                this.array_excel = []
                let desc  = "";
                res.data.forEach(element => {
                    if(element.user.paypal_subscription != null)
                    {                           
                        desc = element.user.paypal_subscription.coupon != null ? "SI" : "NO"                                                 
                    }

                   let obj =  {
                        nombre          : element.user.name,
                        email           : element.user.email,
                        cursos          : element.courses_formatted,
                        plan            : element.user.paypal_subscription != null ? element.user.paypal_subscription.plan : "",
                        costo           : element.user.paypal_subscription != null ? element.user.paypal_subscription.amount : "",
                        paypal_email    : element.user.paypal_subscription != null ? element.user.paypal_subscription.paypal_email : "",
                        country         : element.user.paypal_subscription != null ? element.user.paypal_subscription.country : "",
                        city            : element.user.paypal_subscription != null ? element.user.paypal_subscription.city : "",                       
                        descuento       : desc,
                        coupon          : element.user.paypal_subscription != null ? element.user.paypal_subscription.coupon : ""
                    }
                    this.array_excel.push(obj);
                    desc = "";
                });



                this.isLoading = false;
                
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
    },
}
</script>
<style>

</style>
