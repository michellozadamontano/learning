<template>
    <div>
        <div>
            <div class="card" v-for="content in courseContent" :key="content.id">                        
                    <div class="card-body">
                    <h4 class="card-title">{{content.titulo}}</h4> 
                    <hr>                    
                        <div v-for="file in content.files" :key="file.id">
                            <p>{{file.file}}</p>                             
                            <iframe width="620" height="300"
                                v-bind:src="`https://www.youtube.com/embed/${file.path}`">
                            </iframe>  
                        </div>    
                                    
                    </div>
                </div>   
        </div>

        <div class="alert alert-primary text-center" v-if="processing">
            <i class="fa fa-compass"></i> Procesando petición...
        </div>
        <v-server-table ref="table" :columns="columns" :url="url" :options="options">

            <div slot="activate_deactivate" slot-scope="props">
                <div class="row">
                    <div class="col-md-6">
                        <button
                            v-if="parseInt(props.row.status) === 1"
                            type="button"
                            class="btn btn-danger btn-block"
                            @click="updateStatus(props.row, 3)"
                        >
                            <i class="fa fa-ban"></i> {{ labels.reject }}
                        </button>

                        <button
                            v-else
                            type="button"
                            class="btn btn-success btn-block"
                            @click="updateStatus(props.row, 1)"
                        >
                            <i class="fa fa-rocket"></i> {{ labels.approve }}
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-block" v-bind:href="`/courses/${props.row.slug}/content`">Ver curso</a>
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
</template>

<script>
    import {Event} from 'vue-tables-2';
    export default {
        name: "courses",
        props: {
            labels: {
                type: Object,
                required: true
            },
            route: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                courseContent:[],
                contentFile:[],
                processing: false,
                status: null,
                url: this.route,
                columns: ['id', 'name', 'status', 'activate_deactivate'],
                options: {
                    filterByColumn: true,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100, 500],
                    headings: {
                        id: 'ID',
                        name: this.labels.name,
                        status: this.labels.status,
                        activate_deactivate: this.labels.activate_deactivate,
                        approve: this.labels.approve,
                        reject: this.labels.reject,
                    },
                    customFilters: ['status'],
                    sortable: ['id', 'name', 'status'],
                    filterable: ['name'],
                    requestFunction: function (data) {
                        return window.axios.get(this.url, {
                            params: data
                        })
                        .catch(function (e) {
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
                this.processing = true;
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
                            this.$refs.table.refresh();
                        })
                        .catch(error => {

                        })
                        .finally(() => {
                            this.processing = false;
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
                
            }
        },
        computed: {
            
        },
    }
</script>

<style>
    .table-bordered>thead>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>tfoot>tr>td {
        text-align: center !important;
    }
</style>