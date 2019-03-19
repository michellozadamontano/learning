<template>
    <div>
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
                               
                                    <small v-if="file.archivo">
                                            <a :href="`/courses/${file.arhivo}/download`">
                                                <i class="fa fa-file" aria-hidden="true"></i>
                                                <span>{{file.file}}</span><br>
                                            </a> 
                                    </small>         
                                                              
                                    <small v-if="file.path" data-toggle="tooltip" data-placement="top" :title="file.description">
                                        <a :href="`/courses/${file.id}/show_video`">
                                            <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                            <span>{{file.file}}</span><br>
                                        </a>
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
export default {   
    data() {
        return {
            contents:[]
        }
    },
    methods: {
        
    },
    created() {
        let id = this.$route.params.id;
        axios.post('/admin/courses/showCourseContent', {courseId: id})
        .then(res => {
            console.log(res.data);
            this.contents = res.data.course_content;
            
        })
        .catch((error)=> {
            // handle error
            console.log(error);
        })
    },
    mounted() {
        
        
    },
}
</script>