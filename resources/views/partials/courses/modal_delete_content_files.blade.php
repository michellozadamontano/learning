<div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalDeleteLabel">{{__("Eliminar contenido")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('courses.deleteContentFile')}}"
                method="POST"
                >   
                @csrf      
                <div class="modal-body">                
                    <p style="color: brown">Esta seguro de borrar este contenidgo?</p>
                    <input type="hidden" name="delete_id" id="delete_id">               
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" >Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>   