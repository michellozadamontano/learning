<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">{{__("Actualizar Seccion")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('courses.editFileAction')}}"
                    method="POST"
                    enctype="multipart/form-data"
                    >   
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-12">                   
                                <div class="card">
                                    <div class="card-header">
                                            {{ __("Contenido para cada clase") }}
                                    </div>
                                    <div class="card-body">                                
                                        <div class="form-group row">
                                            <input type="hidden" name="course_content_id" id="course_content_id">
                                        </div>
                                        <div class="form-group row">
                                                <div class="form-check offset-4">
                                                    <input class="form-check-input" type="radio" name="video_radio" id="youtube_radio" value="youtube" checked>
                                                    <label class="form-check-label" for="youtube">
                                                        Videos de Youtube
                                                    </label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="video_radio" id="vimeo_radio" value="vimeo">
                                                    <label class="form-check-label" for="vimeo">
                                                        Videos de Vimeo
                                                    </label>
                                                </div>
                                        </div> 
                                        <div class="form-group row"> 
                                                <label for="titulo_video" class="col-md-4 col-form-label text-md-right">
                                                    {{ __("Titulo Contenido") }}
                                                </label>
                                                <div class="col-md-6">                                            
                                                    <input
                                                        name="titulo_video"
                                                        id="titulo_video"
                                                        class="form-control{{ $errors->has('titulo_video') ? ' is-invalid' : '' }}"
                                                        value=""
                                                        required
                                                        autofocus
                                                    />
                
                                                    @if ($errors->has('titulo_video'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('titulo_video') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                        </div>
                                        <div class="form-group row"> 
                                                <label for="url_youtube" class="col-md-4 col-form-label text-md-right">
                                                    {{ __("Url video youtube") }}
                                                </label>
                                                <div class="col-md-6">                                            
                                                    <input
                                                        name="url_youtube"
                                                        id="url_youtube"
                                                        class="form-control{{ $errors->has('url_youtube') ? ' is-invalid' : '' }}"
                                                        value=""                                                
                                                        autofocus
                                                    />
                
                                                    @if ($errors->has('url_youtube'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('url_youtube') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                        </div>  
                                        <div class="form-group row"> 
                                                <label for="url_vimeo" class="col-md-4 col-form-label text-md-right">
                                                    {{ __("Url video Vimeo") }}
                                                </label>
                                                <div class="col-md-6">                                            
                                                    <input
                                                        name="url_vimeo"
                                                        id="url_vimeo"
                                                        class="form-control{{ $errors->has('url_vimeo') ? ' is-invalid' : '' }}"
                                                        value=""                                                
                                                        autofocus
                                                    />
                
                                                    @if ($errors->has('url_vimeo'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('url_vimeo') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label text-md-right" for="archivo">Archivo (Opcional,pdf,txt,docx,xlsx,mp4s,m4a,mp4a)</label>
                                            <div class="col-md-6">
                                                <input type="file" class="form-control-file" id="archivo" name="archivo">
                                            </div>
                                            @if ($errors->has('archivo'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('archivo') }}</strong>
                                                    </span>
                                            @endif
                                            
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label text-md-right" for="archivo">Breve Descripción</label>
                                            <div class="col-md-6">
                                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                            </div>    
                                        </div>                   
                                        <button type="submit" class="btn btn-success offset-4">
                                            Aceptar
                                        </button>                                                  
                                    </div>
                                
                                </div>                                     
                        </div>  
                    </div>              
                </form>    
            </div>
        </div>
    </div>
    <!-- Cierra Modal -->