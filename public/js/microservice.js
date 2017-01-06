$(function(){

    var htmlHelper = new Mocker.HtmlHelper();
    var table = $('#microservices-table');
    var modal = $('#microservices-modal');
    var fields = {
        name: $('#name', modal),
        description: $('#description', modal)
    };

    var dataTable = table.DataTable({
        autoWidth: false,
        ajax: '/mocker-api/microservices',
        order: [[ 1, 'asc' ]],
        columns: [
            {
                data: 'id',
                visible: false,
                searchable: false
            },
            {
                data: 'name',
                width: '25%'
            },
            {
                data: 'description',
                width: '40%'
            },
            {
                data: 'contracts',
                class: 'center',
                orderable: false,
                render: function(contracts){
                    return htmlHelper.renderContractsCounterLabel(contracts)
                }
            },
            {
                data: null,
                orderable: false,
                defaultContent: htmlHelper.renderDeleteButton()
            }
        ]
    });

    var view = new Mocker.View(dataTable, modal);
    var controller = new Mocker.Controller(view);

    $('.pre-create').on('click', function(){
        $('.errors', modal).html('').hide();
        modal.find('input, textarea').val('');
    });

    modal.on('click', '.create', function() {
        controller.create({
            trigger: $(this),
            url: '/mocker-api/microservices',
            data: {
                name: fields.name.val(),
                description: fields.description.val()
            },
            callback: function(response, status, xhr){
                $.get(xhr.getResponseHeader('Location'), function(response) {
                    view.addRow(response.data);
                });
            }
        })
    });

    table.on('click', '.delete', function(){
        controller.delete($(this), '/mocker-api/microservices', function(microservice) {
            view.removeRow(microservice);
        });
    });
});