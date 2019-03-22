<template>
    <div class="container">        

        <div class="alert alert-primary text-center" v-if="processing">
            <i class="fa fa-compass"></i> Procesando petición...
        </div>
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Profesores</h3>

                <div class="card-tools">
                    
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Cursos</th>
                        <th>Registrado</th>
                        <th>Acciones</th>
                  </tr>

                  <tr v-for="teacher in tableData.data" :key="teacher.id">

                    <td>{{teacher.id}}</td>
                    <td>{{teacher.user.name}}</td>
                    <td>{{teacher.user.email}}</td>
                    <td></td>
                    <td>{{teacher.created_at | myDate}}</td>

                    <td>
                        <a href="#" >
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" >
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
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
    import {Event} from 'vue-tables-2';
    export default {
        name: "teachers",       
        data () {
            return {               
                processing: false,
                name: null,
                url: '/admin/teachers_json',//this.route,
                tableData:{},              
               
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
            getResults(page = 1) {
                this.processing = true;
                axios.get('/admin/teachers_json?page=' + page)
                    .then(response => {
                        this.tableData = response.data;
                        this.processing = false;
                    });
            },
           
            
           
        },
        computed: {
            
        },
        mounted() {
       /* this.$http.get(this.url).then(res => {
            this.tableData = res.data;
           console.log(this.tableData);
            
        })*/
        //this.loadTeacher();
        this.getResults();
    }
    }
</script>

<style>
    .table-bordered>thead>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>tfoot>tr>td {
        text-align: center !important;
    }
</style>