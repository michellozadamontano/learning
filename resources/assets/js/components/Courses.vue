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
                        <h3 class="card-title">Cursos</h3>     
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
                    <v-server-table ref="table" :columns="columns" :url="url" :options="options">

                            <div slot="activate_deactivate" slot-scope="props">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button
                                                v-tooltip="reject"
                                                    v-if="parseInt(props.row.status) === 1 || parseInt(props.row.status) === 2"
                                                    type="button"
                                                    class="btn btn-danger btn-block"                                    
                                                    @click="updateStatus(props.row, 3)"
                                                >
                                                    <i class="fa fa-ban"></i> 
                                                </button>   
                                            </div>
                                            <div class="col-md-6">
                                                <button
                                                    v-tooltip="approve"
                                                    v-if="parseInt(props.row.status) === 3 || parseInt(props.row.status) === 2" 
                                                    type="button"
                                                    class="btn btn-success btn-block"                                    
                                                    @click="updateStatus(props.row, 1)"
                                                >
                                                    <i class="fa fa-rocket"></i> 
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <!--<a class="btn btn-primary btn-block" v-bind:href="`/courses/${props.row.slug}/content`">Ver curso</a>-->
                                        <router-link :to="'/content/' + props.row.id" class="btn btn-primary btn-block"> Ver Curso</router-link>
                                    </div>
                                </div>
                                
                                <!--<button                    
                                    type="button"
                                    class="btn btn-primary btn-block"
                                    @click="showContentCourse(props.row)"
                                >
                                    <i class="fa fa-rocket"></i> Ver contenido
                                </button>-->
                                
                                
                                
                            </div>

                            <div slot="status" slot-scope="props">
                                {{ formattedStatus(props.row.status) }}
                            </div>

                            <div slot="filter__status" @change="filterByStatus">
                                <select class="form-control" v-model="status">
                                    <option selected value="0">Selecciona una opción</option>
                                    <option value="1">Publicado</option>
                                    <option value="2">Pendiente</option>
                                    <option value="3">Rechazado</option>
                                </select>
                            </div>

                        </v-server-table>
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
        name: "courses",
        components: {
           Loading
        }, 
        data () {
            return {
                isLoading: false,
                courseContent:[],
                contentFile:[],
                processing: false,
                status: null,
                reject: "Rechazar",
                approve: "Aprobar",
                url: '/admin/courses_json',//this.route,
                excel_object:{
                    nombre:"",
                    descripcion:"",
                    estado: "",    
                },
                array_excel:[],
                columns: ['id', 'name', 'status', 'activate_deactivate'],
                options: {
                    filterByColumn: true,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100, 500],
                    headings: {
                        id: 'ID',
                        name: 'Nombre',//this.labels.name,
                        status: 'Estado',//this.labels.status,
                        activate_deactivate: "Activar / Desactivar",// this.labels.activate_deactivate,
                        approve: "Aprobar",// this.labels.approve,
                        reject: "Rechazar",//this.labels.reject,
                    },
                    customFilters: ['status'],
                    sortable: ['id', 'name', 'status'],
                    filterable: ['name'],
                    requestFunction: function (data) {
                         this.isLoading = true;
                        return window.axios.get(this.url, {                             
                            params: data

                        })
                        .catch(function (e) {
                            this.isLoading = false;
                            this.dispatch('error', e);
                        }.bind(this));
                    }
                }
            }
        },
        methods: {
            filterByStatus () {
                parseInt(this.status) !== 0 ? Event.$emit('vue-tables.filter::status', this.status) : null;
            },
            formattedStatus (status) {
                const statuses = [
                    null,
                    'Publicado',
                    'Pendiente',
                    'Rechazado'
                ];
                return statuses[status];
            },
            updateStatus (row, newStatus) {
                //this.processing = true;
                this.isLoading = true;
                setTimeout(() => {
                    this.$http.post(
                        '/admin/courses/updateStatus',
                        {courseId: row.id, status: newStatus},
                        {
                            headers: {
                                'x-csrf-token': document.head.querySelector('meta[name=csrf-token]').content
                            }
                        }
                    )
                        .then(response => {
                            this.isLoading = false;
                            this.$refs.table.refresh();
                        })
                        .catch(error => {
                            console.log(error);                            
                            this.isLoading = false;
                        })
                        .finally(() => {                           
                            this.isLoading = false;
                        });
                }, 1500);
            },
            showContentCourse(row){               
                    this.$http.post(
                        '/admin/courses/showCourseContent',
                        {courseId: row.id},
                        {
                            headers: {
                                'x-csrf-token': document.head.querySelector('meta[name=csrf-token]').content
                            }
                        }
                    )
                        .then(res => {
                           console.log(res.data);
                           this.courseContent = res.data.course_content;
                           
                        })
                        .catch(error => {

                        })
                        .finally(() => {
                           // this.processing = false;
                        });
                
            },
            showContentFiles(id){               
                    this.$http.post(
                        '/admin/courses/showContentFiles',
                        {contentId: id},
                        {
                            headers: {
                                'x-csrf-token': document.head.querySelector('meta[name=csrf-token]').content
                            }
                        }
                    )
                        .then(res => {
                           console.log(res.data);
                           this.contentFile = res.data;
                           
                        })
                        .catch(error => {

                        })
                        .finally(() => {
                           // this.processing = false;
                        });
                
            },
            startDownload(){
                this.isLoading = true;
            },
            finishDownload(){
                this.isLoading = false;
            },
            loadData(){
                axios.get('/admin/courses_excel').then(res => {
                    console.log(res.data);
                    res.data.forEach(element => {
                        let state
                        if(element.status == 1)state="PUBLICADO";
                        if(element.status == 2)state="PENDIENTE";
                        if(element.status == 3)state="RECHAZADO";
                        
                        this.excel_object = {
                            nombre      : element.name,
                            descripcion : element.description,
                            estado      : state
                        }
                        this.array_excel.push(this.excel_object);

                    });
                    
                })
            }
        },
        computed: {
            
        },
        mounted() {
            this.loadData();
        }
    }
</script>

<style>
    .table-bordered>thead>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>tfoot>tr>td {
        text-align: center !important;
    }
</style>