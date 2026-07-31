<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Cargar Formato Base (Lote: <?= esc($lote['codigo_lote']) ?>)</h5>
                <a href="<?= base_url('inventario') ?>" class="btn btn-sm btn-outline-light">Regresar</a>
            </div>
            
            <div class="card-body p-4">
                <p class="text-muted mb-4">Sube el documento PDF base para este lote. Este será el archivo que el cliente descargará para firmar.</p>

                <form action="<?= base_url('inventario/procesarArchivo') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- ID del lote enviado oculto -->
                    <input type="hidden" name="id_interno" value="<?= esc($lote['id_interno']) ?>">
                    
                    <div class="mb-4">
                        <label for="archivo_adjunto" class="form-label fw-bold">Seleccionar Formato (PDF)</label>
                        <input class="form-control" type="file" id="archivo_adjunto" name="archivo_adjunto" accept=".pdf" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-up"></i> Asignar Documento al Lote
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>