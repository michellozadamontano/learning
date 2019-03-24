<template>
    <div>
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="col-md-10  listing-block" v-for="content in contents" :key="content.id">
                <div class="media" style="height: 250px;" >
                    <img
                        style="height: 200px; width: 300px;"
                        class="img-rounded"
                        :src="`/images/courses/${content.picture}`" 
                        alt=""
                    />

                    <div class="media-body pl-3" style="height: 250px;overflow: scroll">
                        <div class="price">
                            <small class="badge-danger text-white text-center">
                                {{ content.titulo }}
                            </small>
                           
                            <div class="alert alert-primary" role="alert" v-for="file in content.files" :key="file.id">
                               
                                    <small v-if="file.arhivo">
                                            <a :href="`/courses/${file.arhivo}/download`" style="color:red">
                                                <i class="fas fa-file" aria-hidden="true"></i>
                                                <span>{{file.file}}</span><br>
                                            </a> 
                                    </small>         
                                                              
                                    <small v-if="file.path" data-toggle="tooltip" data-placement="top" :title="file.description">                                       
                                        <router-link :to="'/video/' + file.id + '/'+ course_id" style="color: blue">
                                            <i class="fas fa-youtube-play" aria-hidden="true"></i>                                            
                                            <span>{{file.file}}</span><br>
                                        </router-link>
                                    </small>                            
                                                                 
                                   <small>Descripción: {{file.description}}</small>
                                </div>    
                                                     
                        </div>

                        
                    </div>
                </div>
            </div>   
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';   
    import 'vue-loading-overlay/dist/vue-loading.css';
export default { 
    components: {
           Loading
    },   
    data() {
        return {
            isLoading: false,
            contents:[],
            course_id:""
        }
    },
    methods: {
        
    },
    created() {
        this.isLoading = true;
        let id = this.$route.params.id;
        this.course_id = id;
        axios.post('/admin/courses/showCourseContent', {courseId: id})
        .then(res => {
            this.isLoading = false;            
            this.contents = res.data.course_content;
            
        })
        .catch((error)=> {
            // handle error
            console.log(error);
            this.isLoading = false;
        })
    },
    mounted() {
        
        
    },
}
</script>