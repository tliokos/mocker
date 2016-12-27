var Mocker = Mocker || {};

Mocker.HtmlHelper = function(){

    this.renderDeleteButton = function() {
        return $('<button>').prop({
            class: 'btn btn-danger btn-sm delete float-right'
        }).html('<span class="glyphicon glyphicon-trash"></span>').prop('outerHTML');
    };

    this.renderUpdateButton = function() {
        return $('<button>').prop({
            class: 'btn btn-primary btn-sm pre-update float-right'
        }).html('<span class="glyphicon glyphicon-edit"></span>').prop('outerHTML');
    };

    this.renderActionButtons = function() {
        return this.renderDeleteButton() + this.renderUpdateButton();
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

    this.renderMockerLink = function(data){
        var id = 'mocker-link-' + data.id;
        var value = 'mocker/' + data.id;
        return '<div class="input-group">'
            + '<input readonly="readonly" type="text" class="form-control" id="' + id + '" value="' + value +  '"/>'
            + '<span class="input-group-btn">'
            + '<button class="btn copy-to-clipboard" data-clipboard-target="#' + id + '">Copy</button>'
            + '</span>'
            + '</div>'
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
        $.get('/api/microservices', function(microservices){
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
        $.get('/api/httpHeaders', function(response){
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
            success: function (response) {
                params.trigger.removeAttr('disabled');
                var data = response ? response.data : null;
                params.callback(params.data, data);
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

    this.delete = function(trigger, url, callback) {
        var row = view.getRow(trigger);
        var label = url.split('/')[2].slice(0, -1);
        bootbox.confirm({
            title: 'Delete ' + label + '? This cannot be undone.',
            message: 'Are you sure you want to delete the ' + label + '?',
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm'
                }
            },
            callback: function (confirmed) {
                if(confirmed) {
                    $.ajax({
                        url: url + '/' + row.data().id,
                        type: 'DELETE',
                        success: function() {
                            callback(row);
                        }
                    });
                }
            }
        });
    };
};