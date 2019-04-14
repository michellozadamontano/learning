@extends('layouts.app')

@section('content')
<div class="pl-5 pr-5">
        
        <a href="{{ url()->previous() }}" class="bt btn-success fa fa-backward fa-2x"></a>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-dark">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope= col>Descripcion</th>
                        <th scope="col">Acciones</th>                                
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contenFiles as $item)
                        <tr>
                            <td>
                                {{$item->id}}
                            </td>
                            <td>
                                {{$item->file}}
                            </td>
                            <td>
                                @php
                                   echo $item->description
                                @endphp                                
                            </td>
                            <td>
                            <a href="" class="btn btn-success" data-id="{{$item->id}}" data-file = "{{$item->file}}" data-descrip = "{{$item->description}}" data-toggle="modal" data-target="#myModal">
                                    <i class=" fa fa-edit"> editar</i>
                                </a>
                                <a href="" class="btn btn-danger" data-id="{{$item->id}}"  data-toggle="modal" data-target="#myModalDelete">
                                    <i class=" fa fa-trash"> eliminar</i>
                                </a>                                
                            </td>
                        </tr> 
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</div>
@include('partials.courses.modal_edit_content_files')
@include('partials.courses.modal_delete_content_files')
@endsection

@push('scripts')
    <script>
        $(document).ready(function (e) {
            $('#myModal').on('show.bs.modal', function(e) {                
                var button = $(e.relatedTarget); // Button that triggered the modal
                var title = button.data('file');
                var descrip = button.data('descrip');
                var id    = button.data('id');
                var modal = $(this);
                modal.find('#titulo_video').val(title);
                modal.find('#course_content_id').val(id);
                modal.find('#description').val(descrip);
            });
            $('#myModalDelete').on('show.bs.modal', function(e) {                
                var delete_button = $(e.relatedTarget); // Button that triggered the modal                    
                var id    = delete_button.data('id');
                var modal = $(this);                   
                modal.find('#delete_id').val(id);
            });

            $( "#url_vimeo" ).hide();
            $( "#youtube_radio" ).on( "click", function() {
                $( "#url_youtube" ).show();
                $( "#url_vimeo" ).hide();
            });
            $( "#vimeo_radio" ).on( "click", function() {
                $( "#url_youtube" ).hide();
                $( "#url_vimeo" ).show();
            });
        });
    </script>
@endpush