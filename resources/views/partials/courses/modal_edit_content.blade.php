<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">{{__("Actualizar Seccion")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('courses.editContentAction')}}"
                    method="POST"
                    enctype="multipart/form-data">   
                    @csrf      
                    <div class="modal-body">
                                
                        <div class="form-group">
                            <label for="">Titulo</label>
                            <input type="text"
                            class="form-control" name="title" id="title" aria-describedby="helpId" placeholder="">
                            <small id="helpId" class="form-text text-muted">titulo de la seccion</small>
                        </div>
                        <div class="form-group">
                            <label for=""></label>
                            <input type="file" class="form-control-file" name="picture" id="picture" >
                            <small id="fileHelpId" class="form-text text-muted">foto</small>
                        </div>
                        <input type="hidden" name="id" id="content_id">               
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" >Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Cierra Modal -->