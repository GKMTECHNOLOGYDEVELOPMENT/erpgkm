
 
    <div x-data="calendar">
        <div class="panel">
            <div class="mb-5">
                <div class="mb-4 flex items-center sm:flex-row flex-col sm:justify-between justify-center">
                    <div class="sm:mb-0 mb-4">
                        <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right text-center">Calendar</div>
                        <div class="flex items-center mt-2 flex-wrap sm:justify-start justify-center">
                            <div class="flex items-center ltr:mr-4 rtl:ml-4">
                                <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-primary"></div>
                                <div>Work</div>
                            </div>
                            <div class="flex items-center ltr:mr-4 rtl:ml-4">
                                <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-info"></div>
                                <div>Travel</div>
                            </div>
                            <div class="flex items-center ltr:mr-4 rtl:ml-4">
                                <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-success"></div>
                                <div>Personal</div>
                            </div>
                            <div class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-danger"></div>
                                <div>Important</div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" @click="editEvent()">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Create Event
                    </button>
                    <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto hidden"
                        :class="isAddEventModal && '!block'">
                        <div class="flex items-center justify-center min-h-screen px-4"
                            @click.self="isAddEventModal = false">
                            <div x-show="isAddEventModal" x-transition x-transition.duration.300
                                class="panel border-0 p-0 rounded-lg overflow-hidden md:w-full max-w-lg w-[90%] my-8">
                                <button type="button"
                                    class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark"
                                    @click="isAddEventModal = false">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                                <h3 class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                                    x-text="params.id ? 'Edit Event' : 'Add Event'"></h3>
                                <div class="p-5">
                                    <form @submit.prevent="saveEvent">
                                        <div class="mb-5">
                                            <label for="title">Event Title :</label>
                                            <input id="title" type="text" name="title" id="title"
                                                class="form-input" placeholder="Enter Event Title"
                                                x-model="params.title" required />
                                            <div class="text-danger mt-2" id="titleErr"></div>
                                        </div>

                                        <div class="mb-5">
                                            <label for="dateStart">From :</label>
                                            <input id="dateStart" type="datetime-local" name="start" id="start"
                                                class="form-input" placeholder="Event Start Date" x-model="params.start"
                                                :min="minStartDate" @change="startDateChange($event)" required />
                                            <div class="text-danger mt-2" id="startDateErr"></div>
                                        </div>
                                        <div class="mb-5">
                                            <label for="dateEnd">To :</label>
                                            <input id="dateEnd" type="datetime-local" name="end" id="end"
                                                class="form-input" placeholder="Event End Date" x-model="params.end"
                                                :min="minEndDate" required />
                                            <div class="text-danger mt-2" id="endDateErr"></div>
                                        </div>
                                        <div class="mb-5">
                                            <label for="description">Event Description :</label>
                                            <textarea id="description" name="description" id="description" class="form-textarea min-h-[130px]"
                                                placeholder="Enter Event Description" x-model="params.description"></textarea>
                                        </div>
                                        <div class="mb-5">
                                            <label>Badge:</label>
                                            <div class="mt-3">
                                                <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                    <input type="radio" class="form-radio" name="badge"
                                                        value="primary" x-model="params.type" />
                                                    <span class="ltr:pl-2 rtl:pr-2">Work</span>
                                                </label>
                                                <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                    <input type="radio" class="form-radio text-info" name="badge"
                                                        value="info" x-model="params.type" />
                                                    <span class="ltr:pl-2 rtl:pr-2">Travel</span>
                                                </label>
                                                <label class="inline-flex cursor-pointer ltr:mr-3 rtl:ml-3">
                                                    <input type="radio" class="form-radio text-success"
                                                        name="badge" value="success" x-model="params.type" />
                                                    <span class="ltr:pl-2 rtl:pr-2">Personal</span>
                                                </label>
                                                <label class="inline-flex cursor-pointer">
                                                    <input type="radio" class="form-radio text-danger"
                                                        name="badge" value="danger" x-model="params.type" />
                                                    <span class="ltr:pl-2 rtl:pr-2">Important</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="flex justify-end items-center mt-8">
                                            <button type="button" class="btn btn-outline-danger"
                                                @click="isAddEventModal = false">Cancel</button>
                                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                                x-text="params.id ? 'Update Event' : 'Create Event'"></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="calendar-wrapper" id='calendar'></div>
            </div>
        </div>
    </div>


