<!-- Modal Popup untuk delete-->
<div class="modal fade" id="modal_delete">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:100px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="text-align:center;">ANDA YAKIN MENGHAPUS DATA!</h4>
            </div>

            <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                <a href="#" class="btn btn-danger btn-sm" id="delete_link">HAPUS</a>
                <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">BATAL</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Popup untuk logout-->
<div class="modal fade" id="modal_keluar">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:100px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="text-align:center;">ANDA YAKIN UNTUK KELUAR!</h4>
            </div>

            <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                <a href="#" class="btn btn-danger btn-sm" id="logout_link">KELUAR</a>
                <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">BATAL</button>
            </div>
        </div>
    </div>
</div>

<!-- Javascript untuk popup modal Delete-->
<script type="text/javascript">
    function confirm_modal(delete_url) {
        $('#modal_delete').modal('show', {
            backdrop: 'static'
        });
        document.getElementById('delete_link').setAttribute('href', delete_url);
    }

    function keluar_modal(delete_url) {
        $('#modal_keluar').modal('show', {
            backdrop: 'static'
        });
        document.getElementById('logout_link').setAttribute('href', delete_url);
    }
</script>