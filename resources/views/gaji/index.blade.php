@extends('layouts.master')

@section('title')
    Daftar Penggajian
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pre Order</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="row">
                    @if(auth()->user()->level == 1)
                    <div class="col-md-4">
                        <label for="">Filter Berdasarkan Pegawai</label>
                        <select name="assigne" id="pegawai" class="form-control">
                            <option value="">Semua Pegawai</option>
                            @foreach ($pegawai as $assigne)
                            <option value="{{ $assigne->id }}">{{ $assigne->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-8">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <label for="">Tanggal Selesai Awal</label>
                                <input type="text" name="tanggal_awal" id="tanggal_awal" class="form-control datepicker" style="border-radius: 0 !important" value="{{ request('tanggal_awal') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="">Tanggal Selesai Akhir</label>
                                <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control datepicker" style="border-radius: 0 !important" value="{{ request('tanggal_akhir') }}" required>
                            </div>
                            <div class="col-md-4" style="padding-top: 25px">
                                <button type="button" class="btn btn-primary" id="filter-btn">Filter</button>
                                <form action="{{ route('gaji.cetak') }}" style="display: inline" method="post">
                                    @csrf
                                    <select name="assigne" id="_pegawai" hidden>
                                        <option value="">Semua Pegawai</option>
                                        @foreach ($pegawai as $assigne)
                                        <option value="{{ $assigne->id }}">{{ $assigne->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="tanggal_awal" id="_tanggal_awal" value="">
                                    <input type="hidden" name="tanggal_akhir" id="_tanggal_akhir" value="">
                                    <button type="submit" class="btn btn-success" id="cetak" {{ auth()->user()->level == 1 ? 'disabled' : '' }}>Cetak PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode PO</th>
                        <th>Pegawai</th>
                        <th>Tanggal Selesai</th>
                        <th>Harga Satuan</th>
                        <th>Bonus Satuan</th>
                        <th>Total Satuan</th>
                        {{-- <th width="15%">Aksi</th> --}}
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('listpo.form')
@endsection

@push('scripts')
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('gaji.data') }}',
                data: function (d) {
                    d.assigne = $('select[name=assigne]').val();
                    d.tanggal_awal = $('input[name=tanggal_awal]').val();
                    d.tanggal_akhir = $('input[name=tanggal_akhir]').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode'},
                {data: 'pegawai'},
                {data: 'selesai'},
                {data: 'harga'},
                {data: 'bonus'},
                {data: 'total'},
                // {data: 'aksi', searchable: false, sortable: false},
            ],
            columnDefs: [
                { targets: [3], type: 'data-range' , filterOptions: {
                    dateFormat: 'yyyy-mm-dd',
                    autoApply: true
                }}
            ]
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#filter-btn').on('click', function() {
            if ($('#tanggal_awal').val() > $('#tanggal_akhir').val()) {
                alert('Tanggal selesai awal tidak boleh lebih besar dari tanggal selesai akhir');
                return;
            }
            table.draw();
        });

        $('#pegawai').on('change', function() {
            if ($('#pegawai').val() != '' && $('#tanggal_awal').val() != '' && $('#tanggal_akhir').val() != '') {
                $('#cetak').removeAttr('disabled');
            }
            $('#_pegawai').val($(this).val());
        });

        $('#tanggal_awal').on('change', function() {
            if ($('#pegawai').val() != '' && $('#tanggal_awal').val() != '' && $('#tanggal_akhir').val() != '') {
                $('#cetak').removeAttr('disabled');
            }
            $('#_tanggal_awal').val($(this).val());
        });

        $('#tanggal_akhir').on('change', function() {
            if ($('#pegawai').val() != '' && $('#tanggal_awal').val() != '' && $('#tanggal_akhir').val() != '') {
                $('#cetak').removeAttr('disabled');
            }
            $('#_tanggal_akhir').val($(this).val());
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

    // function addForm(url) {
    //     $('#modal-form').modal('show');
    //     $('#modal-form .modal-title').text('Tambah Kategori');

    //     $('#modal-form form')[0].reset();
    //     $('#modal-form form').attr('action', url);
    //     $('#modal-form [name=_method]').val('post');
    //     $('#modal-form [name=nama_kategori]').focus();
    // }

    // function editForm(url) {
    //     $('#modal-form').modal('show');
    //     $('#modal-form .modal-title').text('Edit PO');
    //     // echo "tes"; exit();


    //     $('#modal-form form').attr('action', url);
    //     $('#modal-form [name=_method]').val('put');
    //     $('#modal-form [name=kode_po]').focus();


    //     $.get(url)
    //         .done((response) => {
    //             $('#modal-form [name=kode_po]').val(response.kode_po);
    //     // $('#modal-form [name=id_statuses]').val(response.id_statuses);
    //     // $('#modal-form [name=id_user]').val(response.nama);
    //     // $('#modal-form [name=start_date]').val(response.start_date);
    //     // $('#modal-form [name=end_date]').val(response.end_date);
    //     // $('#modal-form [name=assigne]').val(response.assigne);

    //         })
    //         .fail((errors) => {
    //             alert('Tidak dapat menampilkan data');
    //             return;
    //         });
    // }

    // function deleteData(url) {
    //     if (confirm('Yakin ingin menghapus data terpilih?')) {
    //         $.post(url, {
    //                 '_token': $('[name=csrf-token]').attr('content'),
    //                 '_method': 'delete'
    //             })
    //             .done((response) => {
    //                 alert('Berhasil Menghapus Data')
    //                 table.ajax.reload();
    //             })
    //             .fail((errors) => {
    //                 alert('Tidak dapat menghapus data');
    //                 return;
    //             });
    //     }
    // }
</script>
@endpush
