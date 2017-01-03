$(function(){

    var htmlHelper = new Mocker.HtmlHelper();
    var table = $('#contracts-table');
    var modal = $('#contracts-modal');
    var fields = {
        id: $('#id', modal),
        microservice: $('#microservice', modal),
        method: $('#method', modal),
        url: $('#url', modal),
        headers: $('.headers', modal),
        code: $('#code', modal),
        request: htmlHelper.renderJsonEditor('request', 'ace/theme/github'),
        response: htmlHelper.renderJsonEditor('response', 'ace/theme/github')
    };

    var dataTable = table.DataTable({
        autoWidth: false,
        ajax: '/mocker-api/contracts',
        columns: [
            {
                data: 'id',
                visible: false,
                searchable: false
            },
            {
                data: 'microservice.id',
                visible: false,
                searchable: false
            },
            {
                data: 'microservice.name',
                width: '20%',
                class: 'list-filtering'
            },
            {
                data: 'method',
                width: '20%',
                class: 'list-filtering'
            },
            {
                data: 'url',
                width: '20%',
                class: 'text-filtering'
            },
            {
                data: 'code',
                width: '20%',
                class: 'list-filtering',
                render: function(code) {
                    return htmlHelper.renderResponseCodeLabel(code);
                }
            },
            {
                orderable: false,
                data: null,
                defaultContent: htmlHelper.renderActionButtons()
            }
        ],
        initComplete: function () {
            var footerRows = $('tfoot tr', table);
            footerRows.each(function(){
                $('th', this).html('');
            });
            $('thead', table).append(footerRows);
            this.api().columns('.list-filtering').every(function () {
                var column = this;
                var select = $('<select class="filter"><option value="">Filter</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function() {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search( val ? '^'+val+'$' : '', true, false ).draw();
                    });
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            });
            this.api().columns('.text-filtering').every(function () {
                var column = this;
                var input = $('<input type="text" class="filter"/>')
                    .appendTo($(column.footer()).empty())
                    .on('keyup change', function() {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                });
            });
        }
    });

    htmlHelper.populateMicroservicesDropDown(fields.microservice);
    htmlHelper.renderHeaderInputs().registerAutocomplete();
    var view = new Mocker.View(dataTable, modal);
    var controller = new Mocker.Controller(view);

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (event) {
        fields.request.resize();
        fields.request.clearSelection();
        fields.response.resize();
        fields.response.clearSelection();
    });

    $('.pre-create').on('click', function(){
        $('.errors', modal).html('').hide();
        modal.find('input').val('');
        fields.request.setValue('', -1);
        fields.response.setValue('', -1);
        $('.remove-header').click();
        fields.microservice.prop('disabled', false).val('');
        fields.method.val($('option:first', fields.method).val());
        fields.code.val('');
        htmlHelper.onlyButton('create', modal);
        $('.nav-tabs a[href="#tab-general"]').tab('show');
    });

    table.on('click', '.pre-update', function(){
        var contract = view.getRow($(this)).data();
        fields.id.val(contract.id);
        fields.microservice.prop('disabled', true).val(contract.microservice.id);
        fields.method.val(contract.method);
        fields.url.val(contract.url);
        fields.request.setValue(contract.request, -1);
        fields.code.val(contract.code);
        fields.response.setValue(contract.response, -1);
        htmlHelper.populateHeaders(contract.headers, modal);
        htmlHelper.onlyButton('update', modal);
        $('.nav-tabs a[href="#tab-general"]').tab('show');
        modal.modal('show')
    });

    modal.on('click', '.create', function() {
        var headers = htmlHelper.getHeaderData();
        controller.create({
            trigger: $(this),
            url: '/mocker-api/contracts',
            data: {
                microservice: {
                    id: fields.microservice.val(),
                    name: $('option[value="' + fields.microservice.val() + '"]', microservice).text()
                },
                method: fields.method.val(),
                url: fields.url.val(),
                headers: headers,
                request: fields.request.getValue(),
                code: fields.code.val(),
                response: fields.response.getValue()
            },
            callback: function(request, contract){
                contract.microservice = request.microservice;
                contract.method = request.method;
                contract.url = request.url;
                contract.headers = request.headers;
                contract.request = request.request;
                contract.code = request.code;
                contract.response = request.response;
                view.addRow(contract)
            }
        });
    });

    modal.on('click', '.update', function() {
        var headers = htmlHelper.getHeaderData();

        controller.update({
            trigger: $(this),
            url: '/mocker-api/contracts/' + fields.id.val(),
            data: {
                microservice: {
                    id: fields.microservice.val(),
                    name: $('option[value="' + fields.microservice.val() + '"]', microservice).text()
                },
                method: fields.method.val(),
                url: fields.url.val(),
                headers: headers,
                request: fields.request.getValue(),
                code: fields.code.val(),
                response: fields.response.getValue()
            },
            callback: function(){
                view.reload()
            }
        })
    });

    table.on('click', '.copy-to-clipboard', function(){
        new Clipboard(this);
    });

    table.on('click', '.delete', function(){
        controller.delete($(this), '/mocker-api/contracts', function(contract) {
            view.removeRow(contract);
        });
    });

});