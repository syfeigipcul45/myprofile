@extends('dashboard.layouts.app')

@section('extra-css')
<link href="{{ asset('dashboard/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Post</h6>
        <a href="{{ route('dashboard.posts.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-newspaper"></i>
            </span>
            <span class="text">Tambah Post</span>
        </a>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Thumbnail</th>
                        <th>Judul Berita</th>
                        <th>Isi Konten</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Thumbnail</th>
                        <th>Judul Berita</th>
                        <th>Isi Konten</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($posts as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>
                            <img src="{{ $item->getFirstMediaUrl('posts', 'thumb')}}" / width="120px">
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{!! shrinkText($item->content) !!}</td>
                        <td>{{ $item->created_at }}</td>
                        <td class="text-center">
                            <a href="" class="btn btn-warning btn-circle btn-sm">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-circle btn-sm remove-news" data-toggle="modal" data-target="#deleteModal" data-href="">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<!-- Delete Modal-->
@include('dashboard.posts.includes.modal-delete')

@section('extra-js')
<!-- Page level plugins -->
<script src="{{ asset('dashboard/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dashboard/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('dashboard/js/demo/datatables-demo.js') }}"></script>

<!-- Custom scripts -->
<script>
    $('.remove-news').click(function() {
        const hrefRemove = $(this).data('href');
        $('#remove-news').attr('action', hrefRemove);
    });
</script>
@endsection