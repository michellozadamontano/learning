<template>
    <div class="container">        

        <loading :active.sync="isLoading" 
            :can-cancel="true" 
            :on-cancel="onCancel"
            :is-full-page="fullPage">
        </loading>
        
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Profesores</h3>
                    <download-excel        
                        :data="array_excel"
                        :before-generate = "startDownload"
                        :before-finish = "finishDownload"
                        class="hover-excel ">
                        Datos Excel
                        <i class="fas fa-file-excel green"></i>                    
                    </download-excel>   
                <div class="card-tools">
                    
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-2">
                  <v-client-table :data="tableData" :columns="columns" :options="options">
                        <div slot="actions" slot-scope="">
                            <a href="#" >
                                <i class="fa fa-edit blue"></i>
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
    <!--Modal -->    
    
    <!-- Modal -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        Body
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Event} from 'vue-tables-2';
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        name: "teachers",
        components: {
           Loading
        },       
        data () {
            return {               
                processing: false,
                name: null,
                url: '/admin/teachers_json',//this.route,
                //tableData:{},
                isLoading: false,
                fullPage: true,
                columns: ['id', 'name', 'email','cursos','actions'],
                tableData:[],
                array_excel:[],
                options: {
                    filterByColumn: true,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100, 500],
                     headings: {
                        id: 'ID',
                        name: 'Nombre',
                        email: 'Email',
                        cursos: 'Cursos',
                        actions: "Acciones",    
                    },
                    templates:{
                        name: function(h,row,index){
                            return row.user.name
                        },
                        email: function (h,row,index){
                            return row.user.email
                        },
                        cursos: function (h,row) {
                            let value = "";
                             row.courses.forEach(element => {
                                 value  += element.name + ' , ' ;                      
                                                               
                            });
                            return value
                        }
                    }
                },              
               
            }
        },
        methods: {
            filterByName () {
                parseInt(this.name) !== 0 ? Event.$emit('vue-tables.filter::name', this.name) : null;
            },
            loadTeacher(){
                this.$Progress.start();
                axios.get('/admin/teachers_json').then(resp => {
                    this.tableData = resp.data;
                    this.$Progress.finish();
                    //console.log(resp);
                    
                })
            },
            getResults() {               
                this.isLoading = true;
                axios.get('/admin/teachers_json')
                    .then(response => {
                        this.tableData = response.data;                        
                        response.data.forEach(element => {
                            let value = "";
                             element.courses.forEach(row => {
                                 value  += row.name + ' , ' ;                      
                                                               
                            });
                            let obj = {
                                nombre: element.user.name,
                                correo: element.user.email,
                                cursos: value
                            }
                            this.array_excel.push(obj);
                        });                        
                        this.processing = false;
                        this.isLoading = false;
                      //  console.log(response.data);
                        
                    });
            },
            onCancel() {
              console.log('Se cancelo el loading.')
            },
            startDownload(){
                this.isLoading = true;
            },
            finishDownload(){
                this.isLoading = false;
            },
           
            
           
        },
        computed: {
            
        },
        mounted() {   
            this.getResults();
        }
    }
</script>

<style>
    .table-bordered>thead>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>tfoot>tr>td {
        text-align: center !important;
    }
</style>