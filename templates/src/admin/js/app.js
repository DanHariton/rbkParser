window.$ = window.jQuery = require('jquery/dist/jquery');
require('bootstrap');
require('startbootstrap-sb-admin-2/vendor/jquery-easing/jquery.easing.js');
require('startbootstrap-sb-admin-2/js/sb-admin-2');
window.$.fn.DataTable = require('startbootstrap-sb-admin-2/vendor/datatables/jquery.dataTables.min.js');
window.$.fn.DataTable = require('startbootstrap-sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js');

$(document).ready(function () {
    $('#table').DataTable({
        order: [],
        language: {
            "processing": "Подождите...",
            "search": "Поиск:",
            "lengthMenu": "Показать _MENU_ записей",
            "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
            "infoEmpty": "Записи с 0 до 0 из 0 записей",
            "infoFiltered": "(отфильтровано из _MAX_ записей)",
            "infoPostFix": "",
            "loadingRecords": "Загрузка записей...",
            "zeroRecords": "Записи отсутствуют.",
            "emptyTable": "В таблице отсутствуют данные",
            "paginate": {
                "first": "Первая",
                "previous": "Предыдущая",
                "next": "Следующая",
                "last": "Последняя"
            },
            "aria": {
                "sortAscending": ": активировать для сортировки столбца по возрастанию",
                "sortDescending": ": активировать для сортировки столбца по убыванию"
            },
            "select": {
                "rows": {
                    "_": "Выбрано записей: %d",
                    "0": "Кликните по записи для выбора",
                    "1": "Выбрана одна запись"
                }
            }
        }
    });
});