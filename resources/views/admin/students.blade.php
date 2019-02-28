@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Administrar Estudiantes Subscritos', 'icon' => 'unlock-alt'])
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
@endpush

@section('content')
<div class="pl-5 pr-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __("Estudiantes Subscritos") }}
                    </div>
                    <div class="card-body">
                        <table
                            class="table table-striped table-bordered nowrap"
                            cellspacing="0"
                            id="students-table-subscribed"
                        >
                            <thead>
                                <tr>
                                    <th>{{ __("ID") }}</th>
                                    <th>{{ __("Nombre") }}</th>
                                    <th>{{ __("Email") }}</th>
                                    <th>{{ __("Cursos") }}</th>
                                    <th>{{ __("Plan") }}</th>
                                    <th>{{ __("Comenzo") }}</th>
                                    <th>{{ __("Termina") }}</th>
                                    <th>{{ __("Profesor") }}</th>
                                    <th>{{ __("Email-Prof") }}</th>                                    
                                    <th>{{ __("Acciones") }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script>
        let dt;
        jQuery(document).ready(function() {
            dt = jQuery("#students-table-subscribed").DataTable({
                pageLength: 5,
                lengthMenu: [ 5, 10, 25, 50, 75, 100 ],
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.datastudents') }}',
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                columns: [
                    {data: 'user.id', visible: false},
                    {data: 'user.name'},
                    {data: 'user.email'},
                    {data: 'courses_formatted'},
                    {data: 'plan'},
                    {data: 'start_date'},
                    {data: 'end_date'},
                    {data: 'actions'}
                ]
            });
        })
    </script>
@endpush