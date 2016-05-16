@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    
                    <span class="btn btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Selecione os arquivos...</span>
                        <input id="fileupload"  type="file" name="arquivo" data-token="{!! csrf_token() !!}" data-user-id="{!! $user->id !!}">
                    </span>
                    <br>
                    <br>
                    <div id="progress" class="progress">
                        <div class="progress-bar progress-bar-success"></div>
                    </div>

                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {!! Session::get('success') !!}
                        </div>
                    @endif

                    <table class="table table-striped table-hover">

                    
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Enviado em</th>
                            <th>Usuário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($user->files as $file)
                        <tr>
                            <td>{!! $file->name !!}</td>
                            <td>{!! $file->created_at !!}</td>
                            <td>{!! $user->name !!}</td>
                            <td>
                                <a href="{!! route('files.download', [$user->id, $file->id]) !!}" class="btn btn-xs btn-success">Download</a>
                                <a href="{!! route('files.destroy', [$user->id, $file->id]) !!}" class="btn btn-xs btn-danger">Excluir</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>


                        
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
    <script src="/js/vendor/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="/js/jquery.iframe-transport.js" type="text/javascript"></script>
    <script src="/js/jquery.fileupload.js" type="text/javascript"></script>

    <script type="text/javascript">

            
        ;(function($)
        {
            'use strict';
            var $fileupload = $('#fileupload');

            

            $fileupload.fileupload({
                    url: '/upload',
                    dataType: 'json',
                    formData: {_token: $fileupload.data('token'), userId: $fileupload.data('userId')},

                    done: function (e, data) {
                        $.each(data.result.files, function (index, file) {
                            //$('<p/>').text(file.name).appendTo('#files');
                            $('#progress .progress-bar').css('width', '0%');
                        });
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#progress .progress-bar').css(
                            'width',
                            progress + '%'
                        )
                    }
                })
        })(window.jQuery);

    </script>
@stop