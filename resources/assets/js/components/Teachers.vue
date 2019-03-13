<template>
    <div>        

        <div class="alert alert-primary text-center" v-if="processing">
            <i class="fa fa-compass"></i> Procesando petición...
        </div>
        <v-server-table ref="table" :columns="columns" :url="url" :options="options">
         
                
                
                  

        </v-server-table>
    </div>
</template>

<script>
    import {Event} from 'vue-tables-2';
    export default {
        name: "teachers",
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
                processing: false,
                name: null,
                url: this.route,
                columns: ['id', 'name', 'email'],
                options: {
                    filterByColumn: true,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100, 500],
                    headings: {
                        id: 'ID',
                        name: this.labels.name,
                        email: this.labels.email,                        
                    },
                    customFilters: ['name'],
                    sortable: ['id', 'name'],
                    filterable: ['name'],
                    requestFunction: function (data) {
                        return window.axios.get(this.url, {
                            params: data,    
                            
                        })
                        .catch(function (e) {
                            this.dispatch('error', e);
                        }.bind(this));
                    }
                }
            }
        },
        methods: {
            filterByName () {
                parseInt(this.name) !== 0 ? Event.$emit('vue-tables.filter::name', this.name) : null;
            },
           /* formattedStatus (status) {
                const statuses = [
                    null,
                    'Publicado',
                    'Pendiente',
                    'Rechazado'
                ];
                return statuses[status];
            },*/
            
           
        },
        computed: {
            
        },
        mounted() {
        this.$http.get(this.url).then(res => {
           console.log(res.body);
            
        })
    }
    }
</script>

<style>
    .table-bordered>thead>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>tfoot>tr>td {
        text-align: center !important;
    }
</style>