<template>
    <div class="container">
         <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
         <div class="row mt-5">
          <div class="col-12">
            <div class="card">
              <div class="card-header">                  
                  <div class="form-group">
                    <label for="cursos">Mis Cursos</label>
                    <select class="form-control" name="cursos" id="cursos" v-model="course" @change="getStudents()">
                      <option v-for="item in courses" :key="item.id" v-bind:value="item.id">{{item.name}}</option>                      
                    </select>
                  </div>
                <h3 class="card-title">Estudiantes inscritos cursos</h3>   
                <button v-show="mail" class="btn btn-primary" @click="newModal"><i class="fas fa-envelope"></i></button>                              
              </div>             
              <div class="card-body table-responsive p-2">
                  <v-client-table :data="tableData" :columns="columns" :options="options">
                        
                  </v-client-table>
               
              </div>
              <!-- /.card-body -->
              <div class="card-footer">                  
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
 <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enviar mensaje a estudiantes</h5>                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="sendMessage()">
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="message" id="message" cols="30" rows="10" class="form-control" v-model="message"></textarea>            
                            
                        </div>                                   
                   

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>                        
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>

                    </form>

                    </div>
                </div>
            </div>

    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';   
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    props: {
            user: '',    
        },
    data() {
        return {
            isLoading  : false,
            mail       : false,
            usuario    : this.user,
            courses    : [],
            course     : '',
            message  : '',  
            columns: ['id', 'name', 'email'],
            tableData:[],
            options: {
                    filterByColumn: false,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100],     
            }, 
        }
    },
     components: {
           Loading
    },  
    methods: {
        loadCourses() {
            axios.post('teacher/ajax_courses',{
                teacher_id:this.user
            }).then(resp => {   
                
                this.courses = resp.data;
            })
        },
        getStudents() {
            this.isLoading = true;
            this.mail = false;
            axios.post('teacher/ajax_students',{
                course_id: this.course
            }).then(resp => {    
                this.tableData = resp.data
                if(this.tableData != "")this.mail = true;
                this.isLoading = false;
            }).catch(error => {
                console.log(error);
                this.isLoading = false;
            })    
        },
        sendMessage() {
            console.log(this.message);
            this.isLoading = true;
            if(this.message == "")
            {
                this.isLoading = false;
                swal.fire(
                        'Error!',
                        'El mensaje no puede estar vacío.',
                        'error'
                    )    
            }
            else{
                
                axios.post('teacher/ajax_sendmessage',{
                course_id   : this.course,
                message     : this.message
            }).then(resp => {  
                this.isLoading = false;
                console.log(resp.data);
                $('#addNew').modal('hide');
               swal.fire(
                        'Enviado!',
                        'Mensaje enviado exitosamente.',
                        'success'
                        ) 
                
            }).catch(error => {
                console.log(error);
                this.isLoading = false;
            })   
            }
            
        },
        newModal(){                
            $('#addNew').modal('show');
        },
        
    },
    created() {
        this.loadCourses();
     //   this.getStudents();
    }
}
</script>