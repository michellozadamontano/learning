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
                <h3 class="card-title">Estudiantes inscritos a cursos</h3>
                
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
                  <div class="row">                      
                      <div class="col-12">
                        <v-client-table :data="tableData" :columns="columns" :options="options" @row-click="showData">
                            
                        </v-client-table>
                      </div>
                  </div>
                  
               
              </div>
              <!-- /.card-body -->
              <div class="card-footer">                  
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>        
       <!-- Modal -->   
       
       <!-- Modal -->
       <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title">Datos de Usuarios</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                   </div>
                   <div class="modal-body">
                       <div class="media">
                            <img :src="`images/users/${picture}`" class="mr-3" alt="photo">
                            <div >
                                <ul>
                                    <li class="nav-item">
                                        <b>Nombre:</b>  <span>{{nameM}}</span>   
                                          
                                    </li>
                                    <li>
                                       <b>Email:</b>    <span>{{s_email}}</span>
                                    </li> 
                                    <li>
                                        <b>Profession:</b>  <span>{{s_profession}}</span>
                                    </li>  
                                    <li>
                                       <b> Plan:</b>       <span>{{s_plan}}</span>
                                    </li> 
                                    <li>
                                        <b>Costo:</b>      <span>{{s_costo}}</span>
                                    </li>
                                    <li>
                                        <b>Cursos:</b>     <span>{{s_cursos}}</span> 
                                    </li> 
                                    <li>
                                        <b>Pais:</b>       <span>{{s_country}}</span>
                                    </li>
                                    <li>
                                        <b>Ciudad:</b>     <span>{{s_city}}</span>  
                                    </li>                              
                                </ul>                             
                            </div>
                        </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                       
                   </div>
               </div>
           </div>
       </div>
    </div>
</template>
<script>
import {Event} from 'vue-tables-2';
import Loading from 'vue-loading-overlay';   
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "students",
    data() {
        return {
            isLoading   : false,
            msg         :'Editar',
            nameM       : '',
            picture     : '',
            s_email     : '',
            s_cursos    : '',
            s_plan      : '',
            s_country   : '',
            s_city      : '',
            s_profession: '',
            s_costo     : '',
            url         : 'admin/student_data',
            columns     : ['id', 'name', 'email',],
            tableData   :[],
            excel_object:{
                nombre      :"",
                email       : "",
                cursos      : "",
                plan        :"",
                costo       :"",
                paypal_email:"",
                country     :"",
                city        :"",
                descuento   :"",
                coupon      : "",
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
        },  
        showData(data) {
            console.log(data);
            this.nameM          = data.row.user.name;
            this.picture        = data.row.user.picture;
            this.s_email        = data.row.user.email;
            this.s_profession   = data.row.title;
            this.s_cursos       = data.row.courses_formatted;
            this.s_plan         = data.row.user.paypal_subscription != null ? data.row.user.paypal_subscription.plan :''
            this.s_costo        = data.row.user.paypal_subscription != null ? data.row.user.paypal_subscription.amount :''
            this.s_country      = data.row.user.paypal_subscription != null ? data.row.user.paypal_subscription.country :''
            this.s_city         = data.row.user.paypal_subscription != null ? data.row.user.paypal_subscription.city :''
            $('#modelId').modal('show');
        }      
        
    },
    mounted() {
        this.loadStudent();       
    },
    
}
</script>
<style>

</style>
