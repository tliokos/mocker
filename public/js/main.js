var Mocker = Mocker || {};

Mocker.HtmlHelper = function(){

    this.renderDeleteButton = function() {
        return $('<button>').prop({
            class: 'btn btn-danger btn-sm delete float-right',
            title: 'Delete Contract'
        }).html('<span class="glyphicon glyphicon-trash"></span>').prop('outerHTML');
    };

    this.renderUpdateButton = function() {
        return $('<button>').prop({
            class: 'btn btn-primary btn-sm pre-update float-right',
            title: 'Update Contract'
        }).html('<span class="glyphicon glyphicon-edit"></span>').prop('outerHTML');
    };

    this.renderCopyButton = function(row) {
        return $('<button>').prop({
            class: 'btn btn-success btn-sm clipboard float-right',
            title: 'Copy Contract URL'
        }).attr('data-clipboard-text', window.location.host + '/mocker-api/contracts/' + row.id + '?decoded=true')
        .html('<span class="glyphicon glyphicon-copy"></span>').prop('outerHTML');
    };

    this.renderActionButtons = function(row) {
        return  this.renderDeleteButton() + this.renderUpdateButton() + this.renderCopyButton(row);
    };

    this.renderContractsCounterLabel = function(contracts){
        return $('<span>').prop({
            class: 'label label-' + (contracts == 0 ? 'danger' : 'success')
        }).html(contracts).prop('outerHTML')
    };

    this.renderResponseCodeLabel = function(code) {
        var label = code < 300 ? 'success' : (
            code < 400 ? 'danger' : (
                code < 500 ? 'info' : 'warning'
            )
        );
        return $('<span>').prop({
            class: 'label label-' + label
        }).html(code).prop('outerHTML');
    };

    this.renderJsonEditor = function(element, theme){
        var editor = ace.edit(element);
        editor.setTheme(theme);
        var jsonMode = ace.require("ace/mode/json").Mode;
        editor.session.setMode(new jsonMode());
        editor.$blockScrolling = Infinity;
        return editor;
    };

    this.populateMicroservicesDropDown = function(dropDown) {
        $.get('/mocker-api/microservices', function(microservices){
            for(var i in microservices.data) {
                if(microservices.data.hasOwnProperty(i)) {
                    dropDown.append($('<option>', {
                        value: microservices.data[i]['id'],
                        text: microservices.data[i]['name']
                    }));
                }
            }
        });
    };

    this.renderHeaderInputs = function() {
        $('#tab-request').on('click', '.add-header', function(event){
            event.preventDefault();
            var formGroup = $(this).parents('.form-group');
            var headers = $(this).parents('.input-group').clone();
            headers.appendTo(formGroup);
            $('.header-label', headers).typeahead({source:httpHeaders});
            $('input', headers).val('');
            formGroup.find('.input-group:not(:last) .add-header')
                .removeClass('add-header').addClass('remove-header')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="glyphicon glyphicon-minus"></span>');
        }).on('click', '.remove-header', function(event){
            event.preventDefault();
            $(this).parents('.input-group:first').remove();
            return false;
        });
        return this;
    };

    this.populateHeaders = function(headers, modal) {
        var totalHeaders = headers.length;
        var iteration = 1;
        for(var i in headers) {
            $('.header-label:last', modal).val(headers[i].label);
            $('.header-value:last', modal).val(headers[i].value);
            if(iteration < totalHeaders) {
                $('.add-header', modal).click();
            }
            iteration++;
        }
    };

    this.registerAutocomplete = function(){
        $.get('/mocker-api/httpHeaders', function(response){
            httpHeaders = response;
            $('.header-label').typeahead({
                source:httpHeaders
            });
        },'json');
    };

    this.onlyButton = function(button, modal){
        $('.btn-primary', modal).hide();
        $('.' + button, modal).show();
    };

    this.getHeaderData = function(){
        var headers = [];
        $('.headers').each(function(){
            var headerLabel = $(this).find('.header-label').val();
            var headerValue = $(this).find('.header-value').val();
            if($.trim(headerLabel)) {
                headers.push({
                    label: headerLabel,
                    value: headerValue
                });
            }
        });
        return headers;
    };
};

Mocker.View = function(dataTable, modal){

    var errorsWrapper = $('.errors', modal);

    this.displayErrors = function displayErrors(errors) {
        errorsWrapper.html('<ul>');
        for(var i in errors) {
            if (errors.hasOwnProperty(i)) {
                errorsWrapper. append('<li><span><b>' + i + '</b>: ' + errors[i] + '</span></li>');
            }
        }
        errorsWrapper.show();
    };

    this.getRow = function(trigger) {
      return dataTable.row(trigger.closest('tr')[0]);
    };

    this.addRow = function(row) {
        dataTable.row.add(row).draw();
    };

    this.removeRow = function(row) {
        dataTable.row(row).remove().draw();
    };

    this.reload = function(){
        dataTable.ajax.reload();
    };

    this.hideModal = function(){
        modal.modal('hide');
        return this;
    };
};

Mocker.Controller = function(view) {

    this.ajax = function(method, params){
        params.trigger.attr('disabled', true);
        $.ajax({
            url: params.url,
            type: method,
            dataType: 'json',
            contentType: "application/json",
            data: JSON.stringify(params.data),
            statusCode: {
                422: function(response) {
                    params.trigger.removeAttr('disabled');
                    view.displayErrors(response.responseJSON);
                }
            },
            success: function (response, status, xhr) {
                params.trigger.removeAttr('disabled');
                params.callback(response, status, xhr);
                view.hideModal();
            }
        });
    };

    this.create = function (params) {
        this.ajax('POST', params);
    };

    this.update = function (params) {
        this.ajax('PUT', params);
    };

    this.delete = function(params) {
        var row = view.getRow(params.trigger);
        bootbox.confirm({
            title: 'Delete Element?',
            message: 'Are you sure you want to delete ' + params.getLabel(row) + '?',
            buttons: {
                cancel: {
                    label: 'Cancel'
                },
                confirm: {
                    label: 'Confirm'
                }
            },
            callback: function (confirmed) {
                if(confirmed) {
                    $.ajax({
                        url: params.url + '/' + row.data().id,
                        type: 'DELETE',
                        success: function() {
                            params.callback(row);
                        }
                    });
                }
            }
        });
    };
};