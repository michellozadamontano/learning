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
                <h3 class="card-title">Estudiates registrados no suscritos</h3>     
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
                            <!--<a href="#" v-tooltip="msg">                                
                                <i class="fas fa-edit blue   "></i>
                            </a>-->
                            /
                            <!--<a href="#">
                                <i class="fa fa-trash red"></i>
                            </a>-->                            
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
    name: "student_registered",
    data() {
        return {
            isLoading: false,
            msg:'Editar',
            url: '/subscriptions/users_registered',
            columns: ['id', 'name', 'email','actions'],
            tableData:[],           
            array_excel:[],
           
            options: {
                filterByColumn: false,
                perPage: 10,
                perPageValues: [10, 25, 50, 100, 500],
                    headings: {
                    id                  : 'ID',
                    name                : 'Nombre',
                    email               : 'Email',                  
                    actions             : "Acciones",    
                },               
            },    
        }
    },
    components: {
        Loading,       
    }, 
    methods: {
        async loadStudent(){
            this.isLoading = true;
            axios.get(this.url).then(res => {
                this.tableData = res.data;  
                console.log(res.data);                              
                res.data.forEach(element => {  
                  
                    let obj =  {
                        nombre          : element.name,
                        email           : element.email    
                    }
                    this.array_excel.push(obj);    

                });
                    console.log(this.array_excel);
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
