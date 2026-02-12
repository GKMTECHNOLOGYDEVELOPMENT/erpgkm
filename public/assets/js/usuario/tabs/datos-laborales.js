window.initFlatpickr = function () {

    flatpickr('.flatpickr', {
        locale: 'es',
        dateFormat: 'd/m/Y',
        allowInput: true,
        altFormat: 'j F, Y',
        altInput: true,
        altInputClass: 'form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700',
    });

    flatpickr('.flatpickr-time', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        time_24hr: true,
        defaultHour: 9,
        defaultMinute: 0,
        locale: 'es',
        allowInput: true,
    });
};
