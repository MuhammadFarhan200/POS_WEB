@extends('layouts.master')

@section('title')
    Daftar Pre Order
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pre Order</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                @if(auth()->user()->level == 1)
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <label for="">Filter Berdasarkan Pegawai</label>
                        <select name="assigne" id="assigne" class="form-control">
                            <option value="">Semua Pegawai</option>
                            @foreach ($pegawai as $assigne)
                            <option value="{{ $assigne->id }}">{{ $assigne->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3" style="padding-top: 25px">
                        <button type="button" class="btn btn-primary" id="filter-btn">Filter</button>
                    </div>
                </div>
                @endif
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode PO</th>
                        <th>Petugas Input</th>
                        <th>Mulai pengerjaan</th>
                        <th>Selesai Pengerjaan</th>
                        <th>Pegawai</th>
                        <th>Status PO</th>
                        <th width="15%">Aksi</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@include('list_po.form')
@endsection

@push('scripts')
<script>
    let table;
    let assigne_opt = false;
    let status_opt  = false;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('listpo.data') }}',
                data: function (d) {
                    d.assigne = $('#assigne').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_po'},
                {data: 'nama_user'},
                {data: 'mulai'},
                {data: 'selesai'},
                {data: 'penugasan'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#filter-btn').on('click', function() {
            table.draw();
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_kategori]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit PO');

        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form #kode_po').focus();

        $.get(url)
            .done((response) => {
                let list = response.list;
                let assigne = response.assigne;
                let status = response.status;
                let user_input = response.user_input;
                let start_date = '';
                let end_date = '';

                if((list.start_date != null && list.end_date != null)){
                    start_date   = list.start_date.substr(0,10);
                    end_date     = list.end_date.substr(0,10);
                }

                for (const key in assigne) {
                    if(!assigne_opt) {
                        let selected = key == list.assigne ? 'selected' : '';
                        const name = assigne[key];
                        $('#modal-form #assigne option:last-child').after('<option value=' + key + ' ' + selected + '>' + name + '</option');
                    }
                }

                status.forEach(el => {
                    if(!status_opt) {
                        let selected = el.id == list.id_statuses ? 'selected' : '';
                        $('#modal-form #id_statuses option:last-child').after('<option value=' + el.id +' ' + selected + '>' + el.nama.toUpperCase() + '</option');
                    }
                });

                assigne_opt = true;
                status_opt = true;

                $('#modal-form #kode_po').val(list.kode_po);
                $('#modal-form #id_user').val(response.user_input);
                $('#modal-form #start_date').val(start_date);
                $('#modal-form #end_date').val(end_date);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    alert('Berhasil Menghapus Data')
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush
