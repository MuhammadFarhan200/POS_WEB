<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ url('listpo.update') }}" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="kode_po" class="col-lg-2 col-lg-offset-1 control-label">Kode PO</label>
                        <div class="col-lg-6">
                            <input type="text" name="kode_po" id="kode_po" class="form-control" required autofocus disabled>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="id_user" class="col-lg-2 col-lg-offset-1 control-label">Petugas Input</label>
                        <div class="col-lg-6">
                            <input type="text" name="id_user" id="id_user" class="form-control" required autofocus disabled>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="start_date" class="col-lg-2 col-lg-offset-1 control-label">Mulai Pengerjaan</label>
                        <div class="col-lg-6">
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="end_date" class="col-lg-2 col-lg-offset-1 control-label">Selesai Pengerjaan</label>
                        <div class="col-lg-6">
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="assigne" class="col-lg-2 col-lg-offset-1 control-label">Penugasan</label>
                        <div class="col-lg-6">
                            <select name="assigne" id="assigne" class="form-control" required>
                                <option value="" selected>-- Pilih --</option>
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id_statuses" class="col-lg-2 col-lg-offset-1 control-label">Status PO</label>
                        <div class="col-lg-6">
                            <select name="id_statuses" id="id_statuses" class="form-control" required>
                                <option value="" selected>-- Pilih --</option>
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>