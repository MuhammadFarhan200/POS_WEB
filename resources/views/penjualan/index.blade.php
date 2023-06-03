@extends('layouts.master')

@section('title')
    Daftar Pemesanan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pemesanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan" style="text-align: left;">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Produk</th>
                        <th>Nama Pemesan</th>
                        <th>No Telepon</th>
                        <th>Alamat</th>
                        <th>Kode Member</th>
                        <th>Total Item</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th>Sumber Pesanan</th>
                        <th>Petugas Input</th>
                        <th width="15%">Aksi</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan.detail')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'nama_catalog'},
                {data: 'nama_pemesan'},
                {data: 'telepon'},
                {data: 'alamat'},
                {data: 'kode_member'},
                {data: 'total_item'},
                {data: 'bayar'},
                {data: 'diskon'},
                {data: 'harga_bayar'},
                {data: 'sumber_po'},
                {data: 'kasir'},
                {data: 'aksi', searchable: false, sortable: false},
            ],

            createdRow: function(row, data, index) {

        // Updated Schedule Week 1 - 07 Mar 22

        if (data.status == '2') {
          $('td', row).css('background-color', 'Yellow');  //Original Date
        }
},
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                // {data: 'subtotal'},
            ]
        })
    });

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function kirimData(url) {
        if (confirm('Yakin ingin mengirim data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post'
                })
                .done((response) => {
                    alert('Berhasil Mengirim Data')
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat mengirim');
                    return;
                });
        }
    }
</script>
@endpush
