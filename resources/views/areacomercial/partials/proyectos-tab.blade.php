<style>
    .capitalize {
        text-transform: capitalize;
    }

    [x-cloak] {
        display: none !important;
    }

    .bg-blue-50 {
        background-color: #eff6ff;
    }

    .bg-green-50 {
        background-color: #f0fdf4;
    }

    .bg-yellow-50 {
        background-color: #fefce8;
    }

    .bg-purple-50 {
        background-color: #faf5ff;
    }

    /* contenedor visible */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #e5e7eb;
        /* gray-200 */
        border-radius: .5rem;
        /* rounded-md */
        padding: .375rem .5rem;
        /* px-2 py-1.5 */
        min-height: 42px;
    }

    /* input de búsqueda inline */
    .select2-container .select2-search--inline .select2-search__field {
        margin: 0;
        padding: 0;
        line-height: 1.25rem;
    }
</style>

<div x-data="scrumboard" x-init="loadProjects()" class="p-5">

    <div>
        <button type="button" class="btn btn-primary flex" @click="addEditProject()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="w-5 h-5 ltr:mr-3 rtl:ml-3">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Flujo De Estados
        </button>
    </div>

    <!-- project list -->
    <div class="relative pt-5">
        <div class="perfect-scrollbar h-full -mx-2">
            <div class="overflow-x-auto flex items-start flex-nowrap gap-5 pb-2 px-2">
                <template x-for="project in projectList" :key="project.id">
                    <div class="panel w-80 flex-none">
                        <div class="flex justify-between mb-5">
                            <h4 x-text="project.title" class="text-base font-semibold"></h4>
                            <div class="flex items-center">
                                <button type="button" class="hover:text-primary ltr:mr-2 rtl:ml-2"
                                    @click="addEditTask(project.id)">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                        <circle opacity="0.5" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                </button>
                                <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                                    <button type="button" class="hover:text-primary" @click="toggle">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 opacity-70 hover:opacity-100">
                                            <circle cx="5" cy="12" r="2" stroke="currentColor"
                                                stroke-width="1.5"></circle>
                                            <circle opacity="0.5" cx="12" cy="12" r="2"
                                                stroke="currentColor" stroke-width="1.5"></circle>
                                            <circle cx="19" cy="12" r="2" stroke="currentColor"
                                                stroke-width="1.5"></circle>
                                        </svg>
                                    </button>
                                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                        class="ltr:right-0 rtl:left-0">
                                        <li><a href="javascript:;" @click="toggle, addEditProject(project)">Editar</a>
                                        </li>
                                        <li><a href="javascript:;"
                                                @click="toggle, deleteConfirmProject(project)">Eliminar</a></li>
                                        <li><a href="javascript:;" @click="toggle, clearProjects(project)">Limpiar
                                                Todo</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- task list -->
                        <div class="sortable-list min-h-[150px]" :data-id="project.id">
                            <template x-for="task in project.tasks" :key="task.id">
                                <div :data-id="project.id + '' + task.id" :data-task-id="task.id"
                                    class="shadow bg-[#f4f4f4] dark:bg-[#262e40] p-3 pb-5 rounded-md mb-5 space-y-3 cursor-move">
                                    <template x-if="task.image">
                                        <img :src="task.image" alt="task image"
                                            class="h-32 w-full object-cover rounded-md" />
                                    </template>
                                    <div class="text-base font-medium" x-text="task.title"></div>
                                    <p class="break-all" x-text="task.description"></p>
                                    <div class="flex gap-2 items-center flex-wrap">
                                        <template x-if="task.tags?.length">
                                            <template x-for="(tag, i) in task.tags" :key="i">
                                                <div class="btn px-2 py-1 flex btn-outline-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5 shrink-0">
                                                        <path
                                                            d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                                                            stroke="currentColor" stroke-width="1.5" />
                                                        <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                                                            transform="rotate(-45 8.60699 8.87891)"
                                                            stroke="currentColor" stroke-width="1.5" />
                                                        <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" />
                                                    </svg>
                                                    <span class="ltr:ml-2 rtl:mr-2" x-text="tag"></span>
                                                </div>
                                            </template>
                                        </template>
                                        <template x-if="!task.tags?.length">
                                            <div
                                                class="btn px-2 py-1 flex text-white-dark dark:border-white-dark/50 shadow-none">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5 shrink-0">
                                                    <path
                                                        d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                                                        stroke="currentColor" stroke-width="1.5" />
                                                    <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                                                        transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                                                        stroke-width="1.5" />
                                                    <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>
                                                <span class="ltr:ml-2 rtl:mr-2">Sin etiquetas</span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="font-medium flex items-center hover:text-primary">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 ltr:mr-3 rtl:ml-3 shrink-0">
                                                <path
                                                    d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <path opacity="0.5" d="M7 4V2.5" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" />
                                                <path opacity="0.5" d="M17 4V2.5" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" />
                                                <path opacity="0.5" d="M2 9H22" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                            <span x-text="formatDate(task.date)"></span>
                                        </div>
                                        <div class="flex items-center">
                                            <button type="button" class="hover:text-info"
                                                @click="addEditTask(project.id, task)">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/surlg"
                                                    class="w-5 h-5 ltr:mr-3 rtl:ml-3">
                                                    <path opacity="0.5"
                                                        d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path
                                                        d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                                                        stroke="currentColor" stroke-width="1.5" />
                                                    <path opacity="0.5"
                                                        d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                                                        stroke="currentColor" stroke-width="1.5" />
                                                </svg>
                                            </button>
                                            <button type="button" class="hover:text-danger"
                                                @click="deleteConfirmModal(project.id, task)">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5">
                                                    <path opacity="0.5"
                                                        d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round"></path>
                                                    <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round"></path>
                                                    <path
                                                        d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round"></path>
                                                    <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round"></path>
                                                    <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="pt-3" x-show="project.title === 'Lista de proyectos'">
                            <button type="button" class="btn btn-primary mx-auto" @click="addEditTask(project.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Agregar Proyecto
                            </button>
                        </div>

                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- add project modal -->
    <div class="fixed inset-0 bg-[black]/60 z-[999] px-4 overflow-y-auto hidden"
        :class="isAddProjectModal && '!block'">
        <div class="flex items-center justify-center min-h-screen">
            <div x-show="isAddProjectModal" x-transition x-transition.duration.300
                @click.outside="isAddProjectModal = false"
                class="panel border-0 p-0 rounded-lg overflow-hidden md:w-full max-w-lg w-[90%] my-8">
                <button type="button" class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark"
                    @click="isAddProjectModal = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="w-6 h-6">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                    x-text="params.id ? 'Editar estado' : 'Agregar estado'"></div>
                <div class="p-5">
                    <form @submit.prevent="saveProject">
                        <div class="grid gap-5">
                            <div x-show="estadosDisponibles.length > 0">
                                <label for="title">Nombre</label>
                                <select id="title" x-model="params.title" class="form-select mt-1">
                                    <option value="" disabled selected>Selecciona un estado</option>
                                    <template x-for="estado in estadosDisponibles" :key="estado">
                                        <option :value="estado"
                                            x-text="estado.charAt(0).toUpperCase() + estado.slice(1)"></option>
                                    </template>
                                </select>
                            </div>
                            <div x-show="estadosDisponibles.length === 0" class="text-red-500 mt-2">
                                Todos los estados ya han sido utilizados.
                            </div>

                        </div>

                        <div class="flex justify-end items-center mt-8">
                            <button type="button" class="btn btn-outline-danger"
                                @click="isAddProjectModal = false">Cancelar</button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                x-text="params.id ? 'Update' : 'Agregar'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- add task modal -->
    <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto hidden" :class="isAddTaskModal && '!block'">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="isAddTaskModal = false">
            <div x-show="isAddTaskModal" x-transition x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden md:w-full max-w-4xl w-[90%] my-8 max-h-[90vh] overflow-y-auto">

                <button type="button"
                    class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark z-10"
                    @click="isAddTaskModal = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="w-6 h-6">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <div class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px] sticky top-0"
                    x-text="paramsTask.id ? 'Editar tarea - ' + currentProjectName : 'Agregar tarea - ' + currentProjectName">
                </div>

                <div class="p-5">
                    <form @submit.prevent="saveTask">
                        <!-- Pestañas para diferentes secciones -->
                        <div class="mb-5 border-b">
                            <ul class="flex flex-wrap -mb-px">
                                <li class="mr-2">
                                    <button type="button"
                                        class="inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-primary"
                                        :class="activeTab === 'general' && 'border-primary text-primary'"
                                        @click="activeTab = 'general'">
                                        Información General
                                    </button>
                                </li>
                                <li class="mr-2" x-show="currentProjectName.toLowerCase().includes('cotizacion')">
                                    <button type="button"
                                        class="inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-primary"
                                        :class="activeTab === 'cotizacion' && 'border-primary text-primary'"
                                        @click="activeTab = 'cotizacion'">
                                        Datos de Cotización
                                    </button>
                                </li>
                                <li class="mr-2" x-show="currentProjectName.toLowerCase().includes('reunion')">
                                    <button type="button"
                                        class="inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-primary"
                                        :class="activeTab === 'reunion' && 'border-primary text-primary'"
                                        @click="activeTab = 'reunion'">
                                        Datos de Reunión
                                    </button>
                                </li>
                                <li class="mr-2"
                                    x-show="currentProjectName.toLowerCase().includes('levantamiento')">
                                    <button type="button"
                                        class="inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-primary"
                                        :class="activeTab === 'levantamiento' && 'border-primary text-primary'"
                                        @click="activeTab = 'levantamiento'">
                                        Datos de Levantamiento
                                    </button>
                                </li>
                                <li class="mr-2" x-show="currentProjectName.toLowerCase().includes('ganado')">
                                    <button type="button"
                                        class="inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-primary"
                                        :class="activeTab === 'ganado' && 'border-primary text-primary'"
                                        @click="activeTab = 'ganado'">
                                        Datos de Proyecto Ganado
                                    </button>
                                </li>
                                <li class="mr-2" x-show="currentProjectName.toLowerCase().includes('observado')">
                                    <button type="button"
                                        class="inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-primary"
                                        :class="activeTab === 'observado' && 'border-primary text-primary'"
                                        @click="activeTab = 'observado'">
                                        Datos de Proyecto Observado
                                    </button>
                                </li>
                                <li class="mr-2" x-show="currentProjectName.toLowerCase().includes('rechazado')">
                                    <button type="button"
                                        class="inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-primary"
                                        :class="activeTab === 'rechazado' && 'border-primary text-primary'"
                                        @click="activeTab = 'rechazado'">
                                        Datos de Proyecto Rechazado
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Sección General -->
                        <div x-show="activeTab === 'general'" class="space-y-4">
                            <div>
                                <label for="taskTitle">Nombre *</label>
                                <input id="taskTitle" x-model="paramsTask.title" type="text" class="form-input"
                                    placeholder="Introduzca el nombre" required />
                            </div>

                            <div>
                                <label for="taskdesc">Descripción</label>
                                <textarea id="taskdesc" x-model="paramsTask.description" class="form-textarea min-h-[100px]"
                                    placeholder="Introduzca la descripción"></textarea>
                            </div>

                            <div>
                                <label for="taskTag">Etiquetas (separadas con comas)</label>
                                <input id="taskTag" x-model="paramsTask.tags" type="text" class="form-input"
                                    placeholder="Introducir etiquetas" />
                            </div>

                            <!-- TABLA DE RELACIONES -->
                            <div>
                                <h2 class="text-lg font-bold mb-4">Actividades de la Tarea</h2>

                                <template x-if="tarea && tarea.relaciones && tarea.relaciones.length > 0">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left border border-gray-300">
                                            <thead class="bg-gray-200">
                                                <tr>
                                                    <th class="px-4 py-2">Tipo</th>
                                                    <th class="px-4 py-2">ID</th>
                                                    <th class="px-4 py-2">Detalle</th>
                                                    <th class="px-4 py-2">Fecha</th>
                                                    <th class="px-4 py-2">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="relacion in tarea.relaciones"
                                                    :key="relacion.id + '-' + relacion.tipo">
                                                    <tr class="border-t hover:bg-gray-100">
                                                        <td class="px-4 py-2" x-text="relacion.tipo"></td>
                                                        <td class="px-4 py-2" x-text="relacion.id"></td>
                                                        <td class="px-4 py-2" x-text="relacion.detalle"></td>
                                                        <td class="px-4 py-2" x-text="relacion.fecha"></td>
                                                        <td class="px-4 py-2">
                                                            <button @click="verDetalles(relacion)"
                                                                class="btn btn-sm btn-primary">
                                                                Ver Detalles
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </template>

                                <template x-if="!tarea || !tarea.relaciones || tarea.relaciones.length === 0">
                                    <div class="text-center py-4 text-gray-500">
                                        No hay actividades registradas para esta tarea.
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Sección Cotización -->
                        <div x-show="activeTab === 'cotizacion' && currentProjectName.toLowerCase().includes('cotizacion')"
                            class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="codigoCotizacion">Código de Cotización</label>
                                    <input id="codigoCotizacion" x-model="paramsTask.codigoCotizacion" type="text"
                                        class="form-input" placeholder="Código de cotización" />
                                </div>

                                <div>
                                    <label for="fechaCotizacion">Fecha de Cotización</label>
                                    <input id="fechaCotizacion" x-model="paramsTask.fechaCotizacion" type="date"
                                        class="form-input" />
                                </div>

                                <div class="md:col-span-2">
                                    <label for="detalleproducto">Detalle del Producto/Servicio</label>
                                    <textarea id="detalleproducto" x-model="paramsTask.detalleproducto" class="form-textarea"
                                        placeholder="Describa el producto o servicio"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="condicionescomerciales">Condiciones Comerciales</label>
                                    <textarea id="condicionescomerciales" x-model="paramsTask.condicionescomerciales" class="form-textarea"
                                        placeholder="Condiciones comerciales"></textarea>
                                </div>

                                <div>
                                    <label for="totalcotizacion">Total Cotización</label>
                                    <input id="totalcotizacion" x-model="paramsTask.totalcotizacion" type="number"
                                        step="0.01" class="form-input" placeholder="0.00" />
                                </div>

                                <div>
                                    <label for="validezcotizacion">Validez</label>
                                    <input id="validezcotizacion" x-model="paramsTask.validezcotizacion"
                                        type="text" class="form-input" placeholder="Ej: 30 días" />
                                </div>


                                <div style="display: none;">
                                    <label for="responsablecotizacion">Responsable</label>
                                    <input id="responsablecotizacion" x-model="paramsTask.responsablecotizacion"
                                        type="text" class="form-input" placeholder="Nombre del responsable" />
                                </div>
                                <div>
                                <label for="NiveldePorcentaje">Nivel de Porcentaje</label>
                                <select id="nivelPorcentajeCotizacion" x-model="paramsTask.nivelPorcentajeCotizacion"
                                    class="form-select">
                                    <option value="">Seleccionar estado</option>
                                    <option value="0">Inicial (0%)</option>
                                    <option value="0.5">En Proceso (50%)</option>
                                    <option value="1">Finalizado (100%)</option>
                                </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="observacionescotizacion">Observaciones</label>
                                    <textarea id="observacionescotizacion" x-model="paramsTask.observacionescotizacion" class="form-textarea"
                                        placeholder="Observaciones adicionales"></textarea>
                                </div>


                               
                            </div>

                            <!-- Botones para agregar/actualizar cotización -->
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" x-show="cotizacionEditId"
                                    @click="limpiarFormularioCotizacion()" class="btn btn-outline-secondary">
                                    Cancelar Edición
                                </button>
                                <button type="button" @click="agregarCotizacion()" class="btn btn-primary"
                                    :disabled="!paramsTask.codigoCotizacion && !paramsTask.fechaCotizacion && !paramsTask
                                        .detalleproducto">
                                    <span
                                        x-text="cotizacionEditId ? 'Actualizar Cotización' : 'Agregar Cotización'"></span>
                                </button>
                            </div>

                            <!-- Lista de cotizaciones existentes -->
                            <div class="mt-6 border-t pt-4">
                                <h4 class="text-lg font-semibold mb-3">Cotizaciones Registradas</h4>

                                <!-- Filtros de búsqueda -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="filtroCodigo" class="text-sm">Buscar por código:</label>
                                        <input id="filtroCodigo" x-model="filtroCodigo" type="text"
                                            class="form-input" placeholder="Código de cotización">
                                    </div>
                                    <div>
                                        <label for="filtroResponsable" class="text-sm">Buscar por responsable:</label>
                                        <input id="filtroResponsable" x-model="filtroResponsable" type="text"
                                            class="form-input" placeholder="Nombre del responsable">
                                    </div>
                                    <div>
                                        <label for="filtroFecha" class="text-sm">Filtrar por fecha:</label>
                                        <input id="filtroFecha" x-model="filtroFecha" type="date"
                                            class="form-input">
                                    </div>
                                </div>

                                <!-- Tabla de cotizaciones -->
                                <div class="overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead>
                                            <tr class="bg-gray-100 dark:bg-gray-700">
                                                <th class="px-4 py-2 text-left">Código</th>
                                                <th class="px-4 py-2 text-left">Fecha</th>
                                                <th class="px-4 py-2 text-left">Producto/Servicio</th>
                                                <th class="px-4 py-2 text-left">Total</th>
                                                <th class="px-4 py-2 text-left">Responsable</th>
                                                <th class="px-4 py-2 text-left">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="cotizacion in filteredCotizaciones"
                                                :key="cotizacion.id">
                                                <tr
                                                    class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td class="px-4 py-2" x-text="cotizacion.codigo_cotizacion"></td>
                                                    <td class="px-4 py-2"
                                                        x-text="formatDate(cotizacion.fecha_cotizacion)"></td>
                                                    <td class="px-4 py-2" x-text="cotizacion.detalle_producto"></td>
                                                    <td class="px-4 py-2"
                                                        x-text="formatCurrency(cotizacion.total_cotizacion)"></td>
                                                    <td class="px-4 py-2" x-text="cotizacion.responsable_cotizacion">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex space-x-2">
                                                            <button type="button"
                                                                @click="editarCotizacion(cotizacion)"
                                                                class="text-blue-500 hover:text-blue-700"
                                                                title="Editar">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>
                                                            <button type="button"
                                                                @click="eliminarCotizacion(cotizacion.id)"
                                                                class="text-red-500 hover:text-red-700"
                                                                title="Eliminar">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Mensaje cuando no hay cotizaciones -->
                                <template x-if="filteredCotizaciones.length === 0">
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p>No se encontraron cotizaciones registradas</p>
                                    </div>
                                </template>

                                <!-- Paginación (opcional) -->
                                <div class="flex justify-between items-center mt-4"
                                    x-show="filteredCotizaciones.length > 0">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Mostrando <span x-text="filteredCotizaciones.length"></span> registros
                                    </div>
                                    <div class="flex space-x-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm">
                                            Exportar a Excel
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm">
                                            Imprimir
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Sección Reunión -->
                        <div x-show="activeTab === 'reunion' && currentProjectName.toLowerCase().includes('reunion')"
                            class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fechareunion">Fecha de Reunión</label>
                                    <input id="fechareunion" x-model="paramsTask.fechareunion" type="date"
                                        class="form-input" />
                                </div>

                                <div>
                                    <label for="tiporeunion">Tipo de Reunión</label>
                                    <select id="tiporeunion" x-model="paramsTask.tiporeunion"
                                        class="form-select w-full">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="Presencial">Presencial</option>
                                        <option value="Virtual">Virtual</option>
                                    </select>
                                </div>


                                <div>
                                    <label for="motivoreunion">Motivo de Reunión</label>
                                    <input id="motivoreunion" x-model="paramsTask.motivoreunion" type="text"
                                        class="form-input" placeholder="Motivo principal" />
                                </div>
                            <div x-data="scrumboard" x-init="fetchUsuarios()" id="scrumboard">
                            <label for="participantesreunion">Participantes</label>
                            <select id="participantesreunion"
                                    x-ref="participantesSelect"
                                    multiple
                                    class="form-select w-full">
                                <template x-if="usuarios && usuarios.length > 0">
                                    <template x-for="usuario in usuarios" :key="usuario.id">
                        <option :value="usuario.id" x-text="usuario.nombre_completo"></option>
                                    </template>
                                </template>
                            </select>
                        </div>


                                <div style="display: none;">
                                    <label for="responsablereunion">Responsable</label>
                                    <input id="responsablereunion" x-model="paramsTask.responsablereunion"
                                        type="text" class="form-input" placeholder="Nombre del responsable" />
                                </div>

                                <div>
                                    <label for="linkreunion">Link de Reunión</label>
                                    <input id="linkreunion" x-model="paramsTask.linkreunion" type="url"
                                        class="form-input" placeholder="URL para reunión virtual" />
                                </div>

                                <div>
                                    <label for="direccionfisica">Dirección Física</label>
                                    <input id="direccionfisica" x-model="paramsTask.direccionfisica" type="text"
                                        class="form-input" placeholder="Dirección para reunión presencial" />
                                </div>

                                <div> 

                                <label for="NiveldePorcentaje">Nivel de Porcentaje</label>
                                <select id="nivelPorcentajeReunion" x-model="paramsTask.nivelPorcentajeReunion"
                                    class="form-select">
                                    <option value="">Seleccionar estado</option>
                                    <option value="0">Inicial (0%)</option>
                                    <option value="0.5">En Proceso (50%)</option>
                                    <option value="1">Finalizado (100%)</option>
                                </select>

                                </div>


                                <div class="md:col-span-2">
                                    <label for="minutareunion">Minuta de Reunión</label>
                                    <textarea id="minutareunion" x-model="paramsTask.minutareunion" class="form-textarea"
                                        placeholder="Resumen o acuerdos de la reunión"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="actividadesReunion">Actividades</label>
                                    <textarea id="actividadesReunion" x-model="paramsTask.actividadesReunion" class="form-textarea"
                                        placeholder="Actividades de la reunión"></textarea>
                                </div>
                            </div>


                            <!-- Botones para agregar/actualizar reunión -->
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" x-show="reunionEditId" @click="limpiarFormularioReunion()"
                                    class="btn btn-outline-danger">
                                    Cancelar Edición
                                </button>
                                <button type="button" @click="agregarReunion()" class="btn btn-primary"
                                    :disabled="!paramsTask.fechareunion && !paramsTask.tiporeunion && !paramsTask.motivoreunion">
                                    <span x-text="reunionEditId ? 'Actualizar Reunión' : 'Agregar Reunión'"></span>
                                </button>
                            </div>



                            <!-- Lista de reuniones existentes -->
                            <div class="mt-6 border-t pt-4">
                                <h4 class="text-lg font-semibold mb-3">Reuniones Registradas</h4>

                                <!-- Filtros de búsqueda -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="filtroTipoReunion" class="text-sm">Buscar por tipo:</label>
                                        <input id="filtroTipoReunion" x-model="filtroTipoReunion" type="text"
                                            class="form-input" placeholder="Tipo de reunión">
                                    </div>
                                    <div>
                                        <label for="filtroResponsableReunion" class="text-sm">Buscar por
                                            responsable:</label>
                                        <input id="filtroResponsableReunion" x-model="filtroResponsableReunion"
                                            type="text" class="form-input" placeholder="Responsable">
                                    </div>
                                    <div>
                                        <label for="filtroFechaReunion" class="text-sm">Filtrar por fecha:</label>
                                        <input id="filtroFechaReunion" x-model="filtroFechaReunion" type="date"
                                            class="form-input">
                                    </div>
                                </div>

                                <!-- Tabla de reuniones -->
                                <div class="overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead>
                                            <tr class="bg-gray-100 dark:bg-gray-700">
                                                <th class="px-4 py-2 text-left">Fecha</th>
                                                <th class="px-4 py-2 text-left">Tipo</th>
                                                <th class="px-4 py-2 text-left">Motivo</th>
                                                <th class="px-4 py-2 text-left">Responsable</th>
                                                <th class="px-4 py-2 text-left">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="reunion in filteredReuniones" :key="reunion.id">
                                                <tr
                                                    class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td class="px-4 py-2" x-text="formatDate(reunion.fecha_reunion)">
                                                    </td>
                                                    <td class="px-4 py-2" x-text="reunion.tipo_reunion || '-'"></td>
                                                    <td class="px-4 py-2"
                                                        x-text="reunion.motivo_reunion ? reunion.motivo_reunion.substring(0, 30) + '...' : '-'">
                                                    </td>
                                                    <td class="px-4 py-2" x-text="reunion.responsable_reunion || '-'">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex space-x-2">
                                                            <button type="button" @click="editarReunion(reunion)"
                                                                class="text-blue-500 hover:text-blue-700">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>
                                                            <button type="button"
                                                                @click="eliminarReunion(reunion.id)"
                                                                class="text-red-500 hover:text-red-700">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <template x-if="filteredReuniones.length === 0">
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <p>No se encontraron reuniones registradas</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Sección Levantamiento -->
                        <div x-show="activeTab === 'levantamiento' && currentProjectName.toLowerCase().includes('levantamiento')"
                            class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fecharequerimiento">Fecha de Requerimiento</label>
                                    <input id="fecharequerimiento" x-model="paramsTask.fecharequerimiento"
                                        type="date" class="form-input" />
                                </div>

                                <div>
                                    <label for="participanteslevantamiento">OT</label>
                                    <input id="participanteslevantamiento"
                                        x-model="paramsTask.participanteslevantamiento" type="text"
                                        class="form-input" placeholder="Numero de OT" />
                                </div>

                                <div>
                                    <label for="ubicacionlevantamiento">Ubicación</label>
                                    <input id="ubicacionlevantamiento" x-model="paramsTask.ubicacionlevantamiento"
                                        type="text" class="form-input"
                                        placeholder="Ubicación del levantamiento" />
                                </div>

                                <div>
                                <label for="NiveldePorcentaje">Nivel de Porcentaje</label>
                                <select id="nivelPorcentajeLevantamiento" x-model="paramsTask.nivelPorcentajeLevantamiento"
                                    class="form-select">
                                    <option value="">Seleccionar estado</option>
                                    <option value="0">Inicial (0%)</option>
                                    <option value="0.5">En Proceso (50%)</option>
                                    <option value="1">Finalizado (100%)</option>
                                </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="descripcionrequerimiento">Descripción del Requerimiento</label>
                                    <textarea id="descripcionrequerimiento" x-model="paramsTask.descripcionrequerimiento" class="form-textarea"
                                        placeholder="Describa el requerimiento"></textarea>
                                </div>


                                <!-- <div>
                                    <label for="nivelPorcentajeCotizacion">Estado de tarea</label>
                                    <select id="nivelPorcentajeCotizacion"
                                        x-model="paramsTask.nivelPorcentajeCotizacion" class="form-select w-full">
                                        <option value="">Seleccionar estado</option>
                                        <option value="0">Inicial (0%)</option>
                                        <option value="0.5">En Proceso (50%)</option>
                                        <option value="1">Finalizado (100%)</option>
                                    </select>
                                </div> -->

                                <div class="md:col-span-2">
                                    <label for="observacioneslevantamiento">Observaciones</label>
                                    <textarea id="observacioneslevantamiento" x-model="paramsTask.observacioneslevantamiento" class="form-textarea"
                                        placeholder="Observaciones adicionales"></textarea>
                                </div>

                            </div>

                            <!-- Botones para agregar/actualizar levantamiento -->
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" x-show="levantamientoEditId"
                                    @click="limpiarFormularioLevantamiento()" class="btn btn-outline-danger">
                                    Cancelar Edición
                                </button>
                                <button type="button" @click="agregarLevantamiento()" class="btn btn-primary"
                                    :disabled="!paramsTask.fecharequerimiento && !paramsTask.participanteslevantamiento && !
                                        paramsTask.ubicacionlevantamiento">
                                    <span
                                        x-text="levantamientoEditId ? 'Actualizar Levantamiento' : 'Agregar Levantamiento'"></span>
                                </button>
                            </div>



                            <!-- Lista de levantamientos existentes -->
                            <div class="mt-6 border-t pt-4">
                                <h4 class="text-lg font-semibold mb-3">Levantamientos Registrados</h4>

                                <!-- Filtros de búsqueda -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="filtroUbicacion" class="text-sm">Buscar por ubicación:</label>
                                        <input id="filtroUbicacion" x-model="filtroUbicacion" type="text"
                                            class="form-input" placeholder="Ubicación">
                                    </div>
                                    <div>
                                        <label for="filtroFechalevantamiento" class="text-sm">Filtrar por
                                            fecha:</label>
                                        <input id="filtroFechalevantamiento" x-model="filtroFechalevantamiento"
                                            type="date" class="form-input">
                                    </div>
                                </div>

                                <!-- Tabla de levantamientos -->
                                <div class="overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead>
                                            <tr class="bg-gray-100 dark:bg-gray-700">
                                                <th class="px-4 py-2 text-left">Fecha</th>
                                                <th class="px-4 py-2 text-left">Ubicación</th>
                                                <th class="px-4 py-2 text-left">OT</th>
                                                <th class="px-4 py-2 text-left">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="levantamiento in filteredLevantamientos"
                                                :key="levantamiento.id">
                                                <tr
                                                    class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td class="px-4 py-2"
                                                        x-text="formatDate(levantamiento.fecha_requerimiento)"></td>
                                                    <td class="px-4 py-2" x-text="levantamiento.ubicacion || '-'">
                                                    </td>
                                                    <td class="px-4 py-2"
                                                        x-text="levantamiento.participantes ? levantamiento.participantes.substring(0, 50) + '...' : '-'">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex space-x-2">
                                                            <button type="button"
                                                                @click="editarLevantamiento(levantamiento)"
                                                                class="text-blue-500 hover:text-blue-700">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>
                                                            <button type="button"
                                                                @click="eliminarLevantamiento(levantamiento.id)"
                                                                class="text-red-500 hover:text-red-700">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <template x-if="filteredLevantamientos.length === 0">
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <p>No se encontraron levantamientos registrados</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Sección Ganado -->
                        <div x-show="activeTab === 'ganado' && currentProjectName.toLowerCase().includes('ganado')"
                            class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fechaganado">Fecha Ganado</label>
                                    <input id="fechaganado" x-model="paramsTask.fechaganado" type="date"
                                        class="form-input" />
                                </div>

                                <div>
                                    <label for="codigoCotizacionGanado">Código de Cotización</label>
                                    <input id="codigoCotizacionGanado" x-model="paramsTask.codigoCotizacionGanado"
                                        type="text" class="form-input" placeholder="Código asociado" />
                                </div>

                               <div>
                                <label for="tiporelacion">Tipo de Relación</label>
                                <select id="tiporelacion" x-model="paramsTask.tiporelacion" class="form-input">
                                    <option value="">Selecciona una opción</option>
                                    <option value="Nuevo Cliente">Nuevo Cliente</option>
                                    <option value="Cliente Recurrente">Cliente Recurrente</option>
                                    <option value="Cliente Potencial">Cliente Potencial</option>
                                    <option value="Cliente Inactivo">Cliente Inactivo</option>
                                    <option value="Socio Estratégico">Socio Estratégico</option>
                                    <option value="Proveedor">Proveedor</option>
                                    <option value="Aliado Comercial">Aliado Comercial</option>
                                    <option value="Distribuidor">Distribuidor</option>
                                    <option value="Revendedor">Revendedor</option>
                                    <option value="Consultor">Consultor</option>
                                    <option value="Partner Tecnológico">Partner Tecnológico</option>
                                    <option value="Cliente VIP">Cliente VIP</option>
                                    <option value="Cliente Referido">Cliente Referido</option>
                                    <option value="Interno">Interno</option>
                                    <option value="Gobierno / Institución Pública">Gobierno / Institución Pública</option>
                                    <option value="ONG / Fundación">ONG / Fundación</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>


                                <div>
                                    <label for="tiposervicio">Tipo de Servicio</label>
                                    <input id="tiposervicio" x-model="paramsTask.tiposervicio" type="text"
                                        class="form-input" placeholder="Ej: Consultoría" />
                                </div>

                                <div>
                                    <label for="valorganado">Valor Ganado</label>
                                    <input id="valorganado" x-model="paramsTask.valorganado" type="number"
                                        step="0.01" class="form-input" placeholder="0.00" />
                                </div>

                                <div>
                                    <label for="formacierre">Forma de Cierre</label>
                                    <input id="formacierre" x-model="paramsTask.formacierre" type="text"
                                        class="form-input" placeholder="Ej: Contrato firmado" />
                                </div>

                                <div>
                                    <label for="duraciondelacuerdo">Duración del Acuerdo</label>
                                    <input id="duraciondelacuerdo" x-model="paramsTask.duraciondelacuerdo"
                                        type="text" class="form-input" placeholder="Ej: 6 meses" />
                                </div>

                        <div>
                                  <label for="NiveldePorcentaje">Nivel de Porcentaje</label>
                                <select id="nivelPorcentajeGanado" x-model="paramsTask.nivelPorcentajeGanado"
                                    class="form-select">
                                    <option value="">Seleccionar estado</option>
                                    <option value="0">Inicial (0%)</option>
                                    <option value="0.5">En Proceso (50%)</option>
                                    <option value="1">Finalizado (100%)</option>
                                </select>
                        </div>

                                <div class="md:col-span-2">
                                    <label for="observacionesganado">Observaciones</label>
                                    <textarea id="observacionesganado" x-model="paramsTask.observacionesganado" class="form-textarea"
                                        placeholder="Observaciones adicionales"></textarea>
                                </div>
                            </div>

                            <!-- Botones para agregar/actualizar proyecto ganado -->
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" x-show="ganadoEditId" @click="limpiarFormularioGanado()"
                                    class="btn btn-outline-danger">
                                    Cancelar Edición
                                </button>
                                <button type="button" @click="agregarGanado()" class="btn btn-primary"
                                    :disabled="!paramsTask.fechaganado && !paramsTask.codigoCotizacionGanado && !paramsTask
                                        .tiposervicio">
                                    <span x-text="ganadoEditId ? 'Actualizar Proyecto' : 'Agregar Proyecto'"></span>
                                </button>
                            </div>

                            <!-- Lista de ganados existentes -->
                            <div class="mt-6 border-t pt-4">
                                <h4 class="text-lg font-semibold mb-3">Proyectos Ganados Registrados</h4>

                                <!-- Filtros de búsqueda -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="filtroCodigoGanado" class="text-sm">Buscar por código:</label>
                                        <input id="filtroCodigoGanado" x-model="filtroCodigoGanado" type="text"
                                            class="form-input" placeholder="Código de cotización">
                                    </div>
                                    <div>
                                        <label for="filtroTipoServicio" class="text-sm">Buscar por servicio:</label>
                                        <input id="filtroTipoServicio" x-model="filtroTipoServicio" type="text"
                                            class="form-input" placeholder="Tipo de servicio">
                                    </div>
                                    <div>
                                        <label for="filtroFechaGanado" class="text-sm">Filtrar por fecha:</label>
                                        <input id="filtroFechaGanado" x-model="filtroFechaGanado" type="date"
                                            class="form-input">
                                    </div>
                                </div>



                                <!-- Tabla de ganados -->
                                <div class="overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead>
                                            <tr class="bg-gray-100 dark:bg-gray-700">
                                                <th class="px-4 py-2 text-left">Código</th>
                                                <th class="px-4 py-2 text-left">Fecha</th>
                                                <th class="px-4 py-2 text-left">Tipo Servicio</th>
                                                <th class="px-4 py-2 text-left">Valor</th>
                                                <th class="px-4 py-2 text-left">Forma Cierre</th>
                                                <th class="px-4 py-2 text-left">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="ganado in filteredGanados" :key="ganado.id">
                                                <tr
                                                    class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td class="px-4 py-2" x-text="ganado.codigo_cotizacion || '-'">
                                                    </td>
                                                    <td class="px-4 py-2" x-text="formatDate(ganado.fecha_ganado)">
                                                    </td>
                                                    <td class="px-4 py-2" x-text="ganado.tipo_servicio || '-'"></td>
                                                    <td class="px-4 py-2"
                                                        x-text="formatCurrency(ganado.valor_ganado)"></td>
                                                    <td class="px-4 py-2" x-text="ganado.forma_cierre || '-'"></td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex space-x-2">
                                                            <button type="button" @click="editarGanado(ganado)"
                                                                class="text-blue-500 hover:text-blue-700">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>
                                                            <button type="button" @click="eliminarGanado(ganado.id)"
                                                                class="text-red-500 hover:text-red-700">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <template x-if="filteredGanados.length === 0">
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <p>No se encontraron proyectos ganados registrados</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Sección Observado -->
                        <div x-show="activeTab === 'observado' && currentProjectName.toLowerCase().includes('observado')"
                            class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fechaobservado">Fecha Observado</label>
                                    <input id="fechaobservado" x-model="paramsTask.fechaobservado" type="date"
                                        class="form-input" />
                                </div>

                              <div>
                                    <label for="estadoactual">Estado Actual</label>
                                    <select id="estadoactual" x-model="paramsTask.estadoactual" class="form-input">
                                        <option value="">Selecciona un estado</option>
                                        <option value="Observado">Observado</option>
                                        <option value="Por corregir">Por corregir</option>
                                        <option value="En revisión">En revisión</option>
                                        <option value="Corregido">Corregido</option>
                                        <option value="Aprobado">Aprobado</option>
                                        <option value="Rechazado">Rechazado</option>
                                        <option value="Finalizado">Finalizado</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                </div>


                                <div class="md:col-span-2">
                                    <label for="detallesobservado">Detalles de Observación</label>
                                    <textarea id="detallesobservado" x-model="paramsTask.detallesobservado" class="form-textarea"
                                        placeholder="Detalles de la observación"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="comentariosobservado">Comentarios</label>
                                    <textarea id="comentariosobservado" x-model="paramsTask.comentariosobservado" class="form-textarea"
                                        placeholder="Comentarios adicionales"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="accionespendientes">Acciones Pendientes</label>
                                    <textarea id="accionespendientes" x-model="paramsTask.accionespendientes" class="form-textarea"
                                        placeholder="Acciones por realizar"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="detalleobservado">Detalle Específico</label>
                                    <textarea id="detalleobservado" x-model="paramsTask.detalleobservado" class="form-textarea"
                                        placeholder="Detalle específico de la observación"></textarea>
                                </div>

                                    <div>                                   
                                        
                                <label for="NiveldePorcentaje">Nivel de Porcentaje</label>
                                <select id="nivelPorcentajeObservado" x-model="paramsTask.nivelPorcentajeObservado"
                                    class="form-select">
                                    <option value="">Seleccionar estado</option>
                                    <option value="0">Inicial (0%)</option>
                                    <option value="0.5">En Proceso (50%)</option>
                                    <option value="1">Finalizado (100%)</option>
                                </select>
                                    </div>

                             



                            </div>

                            <!-- Botones para agregar/actualizar proyecto observado -->
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" x-show="observadoEditId" @click="limpiarFormularioObservado()"
                                    class="btn btn-outline-danger">
                                    Cancelar Edición
                                </button>
                                <button type="button" @click="agregarObservado()" class="btn btn-primary"
                                    :disabled="!paramsTask.fechaobservado && !paramsTask.estadoactual && !paramsTask
                                        .detallesobservado">
                                    <span x-text="observadoEditId ? 'Actualizar Proyecto' : 'Agregar Proyecto'"></span>
                                </button>
                            </div>


                            <!-- Lista de observados existentes -->
                            <div class="mt-6 border-t pt-4">
                                <h4 class="text-lg font-semibold mb-3">Proyectos Observados Registrados</h4>

                                <!-- Filtros de búsqueda -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="filtroEstadoObservado" class="text-sm">Buscar por estado:</label>
                                        <input id="filtroEstadoObservado" x-model="filtroEstadoObservado"
                                            type="text" class="form-input" placeholder="Estado actual">
                                    </div>
                                    <div>
                                        <label for="filtroFechaObservado" class="text-sm">Filtrar por fecha:</label>
                                        <input id="filtroFechaObservado" x-model="filtroFechaObservado"
                                            type="date" class="form-input">
                                    </div>
                                </div>

                                <!-- Tabla de observados -->
                                <div class="overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead>
                                            <tr class="bg-gray-100 dark:bg-gray-700">
                                                <th class="px-4 py-2 text-left">Fecha</th>
                                                <th class="px-4 py-2 text-left">Estado</th>
                                                <th class="px-4 py-2 text-left">Detalles</th>
                                                <th class="px-4 py-2 text-left">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="observado in filteredObservados" :key="observado.id">
                                                <tr
                                                    class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td class="px-4 py-2"
                                                        x-text="formatDate(observado.fecha_observado)"></td>
                                                    <td class="px-4 py-2" x-text="observado.estado_actual || '-'">
                                                    </td>
                                                    <td class="px-4 py-2"
                                                        x-text="observado.detalles ? observado.detalles.substring(0, 50) + '...' : '-'">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex space-x-2">
                                                            <button type="button"
                                                                @click="editarObservado(observado)"
                                                                class="text-blue-500 hover:text-blue-700">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>
                                                            <button type="button"
                                                                @click="eliminarObservado(observado.id)"
                                                                class="text-red-500 hover:text-red-700">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <template x-if="filteredObservados.length === 0">
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <p>No se encontraron proyectos observados registrados</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Sección Rechazado - ACTUALIZADA -->
                        <div x-show="activeTab === 'rechazado' && currentProjectName.toLowerCase().includes('rechazado')"
                            class="space-y-4">
                            <!-- Sección Rechazado -->
                            <div x-show="activeTab === 'rechazado' && currentProjectName.toLowerCase().includes('rechazado')"
                                class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="fecharechazo">Fecha de Rechazo</label>
                                        <input id="fecharechazo" x-model="paramsTask.fecharechazo" type="date"
                                            class="form-input" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="motivorechazo">Motivo de Rechazo</label>
                                        <textarea id="motivorechazo" x-model="paramsTask.motivorechazo" class="form-textarea"
                                            placeholder="Explique el motivo del rechazo"></textarea>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="comentarioscliente">Comentarios del Cliente</label>
                                        <textarea id="comentarioscliente" x-model="paramsTask.comentarioscliente" class="form-textarea"
                                            placeholder="Comentarios o feedback del cliente"></textarea>
                                    </div>
<!-- 
                                <div>
                                    <label for="nivelPorcentajeCotizacion">Estado de tarea</label>
                                    <select id="nivelPorcentajeCotizacion"
                                        x-model="paramsTask.nivelPorcentajeCotizacion" class="form-select w-full">
                                        <option value="">Seleccionar estado</option>
                                        <option value="0">Inicial (0%)</option>
                                        <option value="0.5">En Proceso (50%)</option>
                                        <option value="1">Finalizado (100%)</option>
                                    </select>
                                </div> -->
                                </div>

                                <!-- Botones para agregar/actualizar proyecto rechazado -->
                                <div class="flex justify-end space-x-3 mt-4">
                                    <button type="button" x-show="rechazadoEditId"
                                        @click="limpiarFormularioRechazado()" class="btn btn-outline-danger">
                                        Cancelar Edición
                                    </button>
                                    <button type="button" @click="agregarRechazado()" class="btn btn-primary"
                                        :disabled="!paramsTask.fecharechazo && !paramsTask.motivorechazo">
                                        <span
                                            x-text="rechazadoEditId ? 'Actualizar Proyecto' : 'Agregar Proyecto'"></span>
                                    </button>
                                </div>



                                <!-- Lista de rechazados existentes -->
                                <div class="mt-6 border-t pt-4">
                                    <h4 class="text-lg font-semibold mb-3">Proyectos Rechazados Registrados</h4>

                                    <!-- Filtros de búsqueda -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="filtroMotivoRechazo" class="text-sm">Buscar por
                                                motivo:</label>
                                            <input id="filtroMotivoRechazo" x-model="filtroMotivoRechazo"
                                                type="text" class="form-input" placeholder="Motivo de rechazo">
                                        </div>
                                        <div>
                                            <label for="filtroFechaRechazo" class="text-sm">Filtrar por
                                                fecha:</label>
                                            <input id="filtroFechaRechazo" x-model="filtroFechaRechazo"
                                                type="date" class="form-input">
                                        </div>
                                    </div>

                                    <!-- Tabla de rechazados -->
                                    <div class="overflow-x-auto">
                                        <table class="table-auto w-full">
                                            <thead>
                                                <tr class="bg-gray-100 dark:bg-gray-700">
                                                    <th class="px-4 py-2 text-left">Fecha</th>
                                                    <th class="px-4 py-2 text-left">Motivo</th>
                                                    <th class="px-4 py-2 text-left">Comentarios</th>
                                                    <th class="px-4 py-2 text-left">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="rechazado in filteredRechazados"
                                                    :key="rechazado.id">
                                                    <tr
                                                        class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                        <td class="px-4 py-2"
                                                            x-text="formatDate(rechazado.fecha_rechazo)"></td>
                                                        <td class="px-4 py-2"
                                                            x-text="rechazado.motivo_rechazo ? rechazado.motivo_rechazo.substring(0, 50) + '...' : '-'">
                                                        </td>
                                                        <td class="px-4 py-2"
                                                            x-text="rechazado.comentarios_cliente ? rechazado.comentarios_cliente.substring(0, 50) + '...' : '-'">
                                                        </td>
                                                        <td class="px-4 py-2">
                                                            <div class="flex space-x-2">
                                                                <button type="button"
                                                                    @click="editarRechazado(rechazado)"
                                                                    class="text-blue-500 hover:text-blue-700">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                </button>
                                                                <button type="button"
                                                                    @click="eliminarRechazado(rechazado.id)"
                                                                    class="text-red-500 hover:text-red-700">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>

                                    <template x-if="filteredRechazados.length === 0">
                                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                            <p>No se encontraron proyectos rechazados registrados</p>
                                        </div>
                                    </template>
                                </div>


                            </div>
                        </div>




                        <div class="flex justify-end items-center mt-8 gap-3">
                            <button type="button" class="btn btn-outline-secondary"
                                @click="activeTab = 'general'" x-show="activeTab !== 'general'">
                                ← Volver a General
                            </button>
                            <button type="button" class="btn btn-outline-danger"
                                @click="isAddTaskModal = false">Cancelar</button>
                            <button type="submit" class="btn btn-primary"
                                x-text="paramsTask.id ? 'Actualizar' : 'Crear'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles completos -->
    <div x-show="mostrarModalDetalles" x-transition.opacity x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display: none;">
        <div class="bg-white p-6 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                <h3 class="text-2xl font-bold text-primary"
                    x-text="'Detalles de ' + (detalleSeleccionado ? detalleSeleccionado.tipo : 'Registro')">
                </h3>
                <button @click="mostrarModalDetalles = false"
                    class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Contenido -->
            <template x-if="detalleSeleccionado && detalleSeleccionado.data">
                <div class="space-y-6">
                    <!-- Información básica -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg mb-3 text-gray-800">Información General</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ID:</span>
                                    <span class="font-medium" x-text="detalleSeleccionado.data.id"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tipo:</span>
                                    <span class="font-medium" x-text="detalleSeleccionado.tipo"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Fecha:</span>
                                    <span class="font-medium" x-text="detalleSeleccionado.fecha"></span>
                                </div>
                                <div class="flex justify-between" x-show="detalleSeleccionado.data.task_id">
                                    <span class="text-gray-600">Task ID:</span>
                                    <span class="font-medium" x-text="detalleSeleccionado.data.task_id"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles específicos según el tipo -->
                        <template x-if="detalleSeleccionado.tipo === 'Cotización'">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-lg mb-3 text-blue-800">Detalles de Cotización</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Código:</span>
                                        <span class="font-medium"
                                            x-text="detalleSeleccionado.data.codigo_cotizacion"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Producto:</span>
                                        <span class="font-medium"
                                            x-text="detalleSeleccionado.data.detalle_producto"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="detalleSeleccionado.data.total_cotizacion">
                                        <span class="text-blue-600">Total:</span>
                                        <span class="font-medium"
                                            x-text="'S/ ' + detalleSeleccionado.data.total_cotizacion"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="detalleSeleccionado.data.validez_cotizacion">
                                        <span class="text-blue-600">Validez:</span>
                                        <span class="font-medium"
                                            x-text="detalleSeleccionado.data.validez_cotizacion + ' días'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="detalleSeleccionado.tipo === 'Reunión'">
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-lg mb-3 text-green-800">Detalles de Reunión</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-green-600">Tipo:</span>
                                        <span class="font-medium"
                                            x-text="detalleSeleccionado.data.tipo_reunion"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-600">Motivo:</span>
                                        <span class="font-medium"
                                            x-text="detalleSeleccionado.data.motivo_reunion"></span>
                                    </div>
                                    <div class="flex justify-between"
                                        x-show="detalleSeleccionado.data.responsable_reunion">
                                        <span class="text-green-600">Responsable:</span>
                                        <span class="font-medium"
                                            x-text="detalleSeleccionado.data.responsable_reunion"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Agrega más templates para otros tipos aquí -->
                    </div>

                    <!-- Campos adicionales -->
                    <div class="bg-white border rounded-lg p-4">
                        <h4 class="font-semibold text-lg mb-3 text-gray-800">Información Adicional</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="(value, key) in detalleSeleccionado.data">
                                <div x-show="value && !['id', 'task_id', 'created_at', 'updated_at'].includes(key)"
                                    class="flex justify-between items-start">
                                    <span class="text-gray-600 capitalize"
                                        x-text="key.replace(/_/g, ' ') + ':'"></span>
                                    <span class="font-medium text-right ml-2"
                                        x-text="typeof value === 'string' && value.length > 50 ? value.substring(0, 50) + '...' : value">
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Campos de texto largos -->
                    <template x-if="detalleSeleccionado.data.observaciones">
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg mb-2 text-yellow-800">Observaciones</h4>
                            <p class="text-gray-700" x-text="detalleSeleccionado.data.observaciones"></p>
                        </div>
                    </template>

                    <template x-if="detalleSeleccionado.data.condiciones_comerciales">
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg mb-2 text-purple-800">Condiciones Comerciales</h4>
                            <p class="text-gray-700" x-text="detalleSeleccionado.data.condiciones_comerciales"></p>
                        </div>
                    </template>

                    <!-- Timestamps -->
                    <div class="bg-gray-100 p-3 rounded-lg text-sm text-gray-500">
                        <div class="flex justify-between">
                            <span>Creación:</span>
                            <span x-text="new Date(detalleSeleccionado.data.created_at).toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Actualización:</span>
                            <span x-text="new Date(detalleSeleccionado.data.updated_at).toLocaleString()"></span>
                        </div>
                    </div>
                </div>
            </template>

            <div class="mt-6 pt-4 border-t flex justify-end">
                <button @click="mostrarModalDetalles = false" class="btn btn-primary">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- delete task modal -->
    <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto hidden" :class="isDeleteModal && '!block'">
        <div class="flex items-center justify-center min-h-screen px-4 " @click.self="isDeleteModal = false">
            <div x-show="isDeleteModal" x-transition x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden md:w-full max-w-lg w-[90%] my-8">
                <button type="button" class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark"
                    @click="isDeleteModal = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="w-6 h-6">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div
                    class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                    <span x-text="isDeleteProject ? 'Eliminar Proyecto' : 'Eliminar Tarea'"></span>
                </div>
                <div class="p-5 text-center">
                    <div class="text-white bg-danger ring-4 ring-danger/30 p-4 rounded-full w-fit mx-auto">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path opacity="0.5"
                                d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round"></path>
                            <path
                                d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round"></path>
                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round"></path>
                        </svg>
                    </div>
                    <div class="text-base sm:w-3/4 mx-auto mt-5">
                        <span
                            x-text="isDeleteProject 
                            ? '¿Estás seguro de que quieres eliminar este proyecto y todas sus tareas?' 
                            : '¿Estás seguro de que quieres eliminar esta tarea?'"></span>
                    </div>

                    <div class="flex justify-center items-center mt-8">
                        <button type="button" class="btn btn-outline-danger"
                            @click="isDeleteModal = false">Cancelar</button>
                        <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                            @click="isDeleteProject ? deleteProject() : deleteTask()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
