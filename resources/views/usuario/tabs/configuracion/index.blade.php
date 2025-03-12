<template x-if="tab === 'preferences'">
                    <div class="switch">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Choose Theme</h5>
                                <div class="flex justify-around">
                                    <label class="inline-flex cursor-pointer">
                                        <input class="form-radio ltr:mr-4 rtl:ml-4 cursor-pointer" type="radio"
                                            name="flexRadioDefault" checked="" />
                                        <span>
                                            <img class="ms-3" width="100" height="68" alt="settings-dark"
                                                src="/assets/images/settings-light.svg" />
                                        </span>
                                    </label>

                                    <label class="inline-flex cursor-pointer">
                                        <input class="form-radio ltr:mr-4 rtl:ml-4 cursor-pointer" type="radio"
                                            name="flexRadioDefault" />
                                        <span>
                                            <img class="ms-3" width="100" height="68" alt="settings-light"
                                                src="/assets/images/settings-dark.svg" />
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Información de Usuario</h5>
                                <p>Descargar los datos del usuario</p>
                                <button type="button" class="btn btn-primary">Download Data</button>
                     
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Public Profile</h5>
                                <p>Your <span class="text-primary">Profile</span> will be visible to anyone on the
                                    network.</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox1" />
                                    <span for="custom_switch_checkbox1"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Enviar Accesos</h5>
                                <p>Your <span class="text-primary">Email</span> will be visible to anyone on the
                                    network.</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox2" />
                                    <span for="custom_switch_checkbox2"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Enable keyboard shortcuts</h5>
                                <p>When enabled, press <span class="text-primary">ctrl</span> for help</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox3" />
                                    <span for="custom_switch_checkbox3"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Hide left navigation</h5>
                                <p>Sidebar will be <span class="text-primary">hidden</span> by default</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox4" />
                                    <span for="custom_switch_checkbox4"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Advertisements</h5>
                                <p>Display <span class="text-primary">Ads</span> on your dashboard</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox5" />
                                    <span for="custom_switch_checkbox5"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Social Profile</h5>
                                <p>Enable your <span class="text-primary">social</span> profiles on this network</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox6" />
                                    <span for="custom_switch_checkbox6"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </template>