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
              </div>             
              <div class="card-body table-responsive p-2">
                  <v-client-table :data="tableData" :columns="columns" :options="options">
                        <div slot="actions" slot-scope="">
                            <a href="#" >                                
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
            url: 'admin/student_data',
            columns: ['id', 'name', 'email','courses_formatted','plan','actions'],
            tableData:[],
            options: {
                filterByColumn: false,
                perPage: 10,
                perPageValues: [10, 25, 50, 100, 500],
                    headings: {
                    id      : 'ID',
                    name    : 'Nombre',
                    email   : 'Email',
                    courses_formatted  : 'Cursos',
                    plan    : 'Plan',
                    actions : "Acciones",    
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
                this.isLoading = false;
                
            }).catch(error => {
                console.log(error);
                this.isLoading = false;
                
            })
        }
        
    },
    mounted() {
        this.loadStudent();
    },
}
</script>