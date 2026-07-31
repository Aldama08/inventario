<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        
        <?php if (session()->getFlashdata('mensaje')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('mensaje') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gestión de Documento - Lote: <?= esc($lote['codigo_lote']) ?></h5>
                <a href="<?= base_url('inventario') ?>" class="btn btn-sm btn-outline-light">Regresar</a>
            </div>
            
            <div class="card-body p-4">
                
                <!-- PASO 1: DESCARGAR ARCHIVO DIRECTO DESDE PUBLIC/UPLOADS -->
                <div class="row mb-5 align-items-center">
                    <div class="col-md-2 text-center">
                        <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="fw-bold text-primary">Paso 1: Descargar el formato original</h5>
                        <p class="text-muted mb-2">Descarga el documento en formato PDF, imprímelo o fírmalo digitalmente con tu firma autorizada.</p>
                        
                        <?php if(!empty($lote['archivo_original'])): ?>
                            <a href="<?= base_url('uploads/' . $lote['archivo_original']) ?>" download class="btn btn-outline-danger">
                                <i class="bi bi-download"></i> Descargar PDF Original
                            </a>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> El documento original aún no ha sido cargado.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <hr class="mb-4">

                <!-- PASO 2: SUBIR DOCUMENTO FIRMADO -->
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <i class="bi bi-cloud-arrow-up text-success" style="font-size: 3rem;"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="fw-bold text-success">Paso 2: Subir el documento firmado</h5>
                        <p class="text-muted mb-3">Una vez firmado, escanea el documento y súbelo aquí. Solo se permiten formatos PDF.</p>
                        
                        <?php if(!empty($lote['archivo_firmado'])): ?>
                            <div class="alert alert-info py-2 d-flex justify-content-between align-items-center mb-3">
                                <span><i class="bi bi-check-circle-fill text-success"></i> Documento firmado cargado previamente.</span>
                                <a href="<?= base_url('uploads/' . $lote['archivo_firmado']) ?>" target="_blank" class="btn btn-sm btn-success">
                                    <i class="bi bi-eye"></i> Ver Firmado
                                </a>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('inventario/subirFirmado') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_interno" value="<?= esc($lote['id_interno']) ?>">
                            
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" name="archivo_firmado" id="archivo_firmado" accept=".pdf" required>
                                <button class="btn btn-success" type="submit">
                                    <i class="bi bi-upload"></i> Subir Firmado
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>