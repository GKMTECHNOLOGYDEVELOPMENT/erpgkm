<template x-if="tab === 'preferences'">
    <div class="switch" x-data="{
        cvUrl: '/uploads/cv.pdf',
        dniUrl: '/uploads/dni.pdf',
        penalesUrl: null,
        judicialesUrl: '/uploads/judiciales.pdf',
        otrosUrl: null
    }">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

            <!-- Plantilla visual -->
            <div class="panel space-y-5">
                <h5 class="font-semibold text-lg mb-4">Template</h5>
                <div class="flex justify-around">
                    <label class="inline-flex cursor-pointer">
                        <input class="form-radio ltr:mr-4 rtl:ml-4 cursor-pointer" type="radio" name="flexRadioDefault" checked />
                        <span>
                            <img class="ms-3" width="100" height="68" alt="settings-dark" src="/assets/images/settings-light.svg" />
                        </span>
                    </label>
                    <label class="inline-flex cursor-pointer">
                        <input class="form-radio ltr:mr-4 rtl:ml-4 cursor-pointer" type="radio" name="flexRadioDefault" />
                        <span>
                            <img class="ms-3" width="100" height="68" alt="settings-light" src="/assets/images/settings-dark.svg" />
                        </span>
                    </label>
                </div>
            </div>

            <!-- Panel para subir CV -->
            <div class="panel space-y-4">
                <h5 class="font-semibold text-lg">Subir CV</h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">Archivos permitidos: .pdf, .doc, .docx</p>
                <template x-if="cvUrl">
                    <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center">
                        <span class="text-sm truncate w-3/4">Archivo actual: CV</span>
                        <a :href="cvUrl" download class="btn btn-outline-primary btn-sm">Descargar</a>
                    </div>
                </template>
                <form @submit.prevent="uploadCV" enctype="multipart/form-data" class="space-y-3">
                    <input type="file" name="cv" accept=".pdf,.doc,.docx" class="file-input file-input-bordered w-full" required>
                    <button type="submit" class="btn btn-primary w-full">Subir CV</button>
                </form>
            </div>

            <!-- DNI -->
            <div class="panel space-y-4">
                <h5 class="font-semibold text-lg">Documento de identidad</h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">Archivos permitidos: .jpg, .png, .pdf</p>
                <template x-if="dniUrl">
                    <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center">
                        <span class="text-sm truncate w-3/4">Archivo actual: DNI</span>
                        <a :href="dniUrl" download class="btn btn-outline-primary btn-sm">Descargar</a>
                    </div>
                </template>
                <form @submit.prevent="uploadDNI" enctype="multipart/form-data" class="space-y-3">
                    <input type="file" name="dni" accept=".jpg,.jpeg,.png,.pdf" class="file-input file-input-bordered w-full" required>
                    <button type="submit" class="btn btn-primary w-full">Subir DNI</button>
                </form>
            </div>

            <!-- Antecedentes Penales -->
            <div class="panel space-y-4">
                <h5 class="font-semibold text-lg">Antecedentes Penales</h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">Solo PDF</p>
                <template x-if="penalesUrl">
                    <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center">
                        <span class="text-sm truncate w-3/4">Archivo actual: Penales</span>
                        <a :href="penalesUrl" download class="btn btn-outline-primary btn-sm">Descargar</a>
                    </div>
                </template>
                <form @submit.prevent="uploadPenales" enctype="multipart/form-data" class="space-y-3">
                    <input type="file" name="antecedentes_penales" accept=".pdf" class="file-input file-input-bordered w-full">
                    <button type="submit" class="btn btn-primary w-full">Subir documento</button>
                </form>
            </div>

            <!-- Antecedentes Judiciales -->
            <div class="panel space-y-4">
                <h5 class="font-semibold text-lg">Antecedentes Judiciales</h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">Solo PDF</p>
                <template x-if="judicialesUrl">
                    <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center">
                        <span class="text-sm truncate w-3/4">Archivo actual: Judiciales</span>
                        <a :href="judicialesUrl" download class="btn btn-outline-primary btn-sm">Descargar</a>
                    </div>
                </template>
                <form @submit.prevent="uploadJudiciales" enctype="multipart/form-data" class="space-y-3">
                    <input type="file" name="antecedentes_judiciales" accept=".pdf" class="file-input file-input-bordered w-full">
                    <button type="submit" class="btn btn-primary w-full">Subir documento</button>
                </form>
            </div>

            <!-- Otros documentos -->
            <div class="panel space-y-4">
                <h5 class="font-semibold text-lg">Otros documentos</h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">Imágenes o PDFs adicionales</p>
                <template x-if="otrosUrl">
                    <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center">
                        <span class="text-sm truncate w-3/4">Archivo actual: Otros</span>
                        <a :href="otrosUrl" download class="btn btn-outline-primary btn-sm">Descargar</a>
                    </div>
                </template>
                <form @submit.prevent="uploadOtros" enctype="multipart/form-data" class="space-y-3">
                    <input type="file" name="otros_documentos" multiple accept=".pdf,.jpg,.jpeg,.png" class="file-input file-input-bordered w-full">
                    <button type="submit" class="btn btn-primary w-full">Subir documentos</button>
                </form>
            </div>

        </div>
    </div>
</template>
