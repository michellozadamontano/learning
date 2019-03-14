@extends('layouts.app')

@section('content')
    <div class="pl-5 pr-5">
        <div class="row">
            <div class="col-sm-12">
                    <table class="table table-dark">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Titulo</th>
                                <th scope= col>Imagen</th>
                                <th scope="col">Acciones</th>                                
                              </tr>
                            </thead>
                            <tbody>
                             @foreach ($contents as $item)
                                <tr>
                                    <td>
                                        {{$item->id}}
                                    </td>
                                    <td>
                                        {{$item->titulo}}
                                    </td>
                                    <td>
                                        <img src="/images/courses/{{ $item->picture }}" alt="{{$item->titulo}}" width="50" height="50">
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-success" data-id="{{$item->id}}" data-title = "{{$item->titulo}}" data-toggle="modal" data-target="#myModal">
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
    @include('partials.courses.modal_edit_content')
    @include('partials.courses.modal_delete_content')
    
@endsection

@push('scripts')
    <script>
            $(document).ready(function (e) {
                $('#myModal').on('show.bs.modal', function(e) {                
                    var button = $(e.relatedTarget); // Button that triggered the modal
                    var title = button.data('title');
                    var id    = button.data('id');
                    var modal = $(this);
                    modal.find('#title').val(title);
                    modal.find('#content_id').val(id);
                });
                $('#myModalDelete').on('show.bs.modal', function(e) {                
                    var delete_button = $(e.relatedTarget); // Button that triggered the modal                    
                    var id    = delete_button.data('id');
                    var modal = $(this);                   
                    modal.find('#delete_id').val(id);
                });
            });
    </script>
@endpush