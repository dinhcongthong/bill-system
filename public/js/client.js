$(document).ready(function () {
    addNewClient();
    getEditClient();
    saveClient();
    deleteContract();
    var storeBranchCount = 0;
    deleteBranchItem();
    addMoreStoreBranch(storeBranchCount);
});

function addNewClient () {
    $('.next-btn-client').on('click', function (e) {
        if($(this).hasClass("swiper-button-disabled"))
        {
            $('#client-steps').submit();
        }
    });
}

function getEditClient () {
    $('.client-edit').on('click', function (e) {
        e.preventDefault();
        
        $.ajax({
            method: 'GET',
            url: base_url + '/client/edit',
            data: {
                id: $(this).data('id')
            }
        })
        .done(function (data) {
            $('#newClientModal').find('.modal-body').html(data);
            $('#newClientModal').modal();
            var count = 0;
            addNewClient(count);
            
            // add more store branch when edit client
            var countStoreBranch = $('#count-store-branch').val();
            addMoreStoreBranch(countStoreBranch - 1);
            deleteBranchItem();
            $('.selectpicker').selectpicker();
        })
        .fail(function (xhr, error, status) {
            console.log(xhr);
            console.log(error);
            console.log(status);
            console.log(xhr.responseText);
        })
    });
}

function saveClient () {
    $('.client-save').on('click', function (e) {
        e.preventDefault();
        
        var id = $(this).data('id');
        $.ajax({
            method: 'POST',
            url: base_url + '/client/client-save',
            data: {
                id: id
            }
        })
        .done (function (data) {
            if (data.result == 1) {
                $('#client-id-' + id).remove();
                BootstrapDialog.show({
                    title: 'Save to client',
                    message: 'Save client was successfully!',
                    type: BootstrapDialog.TYPE_SUCCESS
                });
            }
            else {
                BootstrapDialog.show({
                    titile: 'Warning Save to client',
                    message: data.errors,
                    type: BootstrapDialog.TYPE_WARNING
                });
            }
        })
        .fail (function (xhr, err, status) {
            console.log(xhr);
            console.log(err);
            console.log(status);
        })
    })
}

function deleteContract() {
    $('.contract-delete').on('click', function (e) {
        e.preventDefault();
        
        let id = $(this).data('id');
        let clientId = $(this).data('client');
        
        BootstrapDialog.confirm({
            title: 'Delete Contract',
            message: 'Do you really want to delete selected item? <br/>You can not restore after confirm it...',
            type: BootstrapDialog.TYPE_DANGER,
            callback: function (result) {
                if (result) {
                    $.ajax({
                        method: 'POST',
                        url: base_url + '/contract/delete',
                        data: {
                            id: id
                        }
                    })
                    .done(function (data) {
                        console.log(data);
                        if (data == 1) {
                            let totalContract = $('#total-contract-' + clientId).text();
                            totalContract = parseInt(totalContract);
                            $('#total-contract-' + clientId).text((totalContract - 1) + ' contracts');
                            $('#contract-' + id).remove();
                        }
                        else {
                            BootstrapDialog.show({
                                title: 'Failed to delete contract',
                                message: 'This contract have not payment yet!',
                                type: BootstrapDialog.TYPE_WARNING
                            })
                        }
                    })
                    .fail(function (xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(status.responseText);
                        console.log(error.responseText);
                    })
                }
            }
        });
    })
}

function addMoreStoreBranch (count) {
    $('#branch-new').on('click', function () {
        count++;
        // get content storeType selectbox
        let storeType = '';
        storeType += '<select class="selectpicker" multiple data-live-search="true" name="store_type_id_' + (count) + '[]">';
        
        let totalStoreType = $('#count-store-type').val();
        for (let i = 1; i <= totalStoreType; i++) {
            storeType += '<option value="' + i + '"> ' + $('#store-type-id-' + i).val() + ' </option>';
        }
        storeType += '</select>';
        
        let html = '';
        html += '<div class="input-group mb-3 item-delete-' + count + '">';
        html += '<a href="#" class="float-right g-col-1 g-col-sm-2 post-delete" data-id="' + count + '"><i class="fas fa-times-circle text-danger" style="font-size: 18px;"></i></a>';
        html +=     '<div class="input-group-prepend">';
        html +=         '<span class="input-group-text" id="input-client-store-type">Store type</span>';
        html +=     '</div>';
        html +=     storeType;
        html += '</div>';
        html += '<div class="input-group mb-3 item-delete-' + count + '">';
        html +=     '<div class="input-group-prepend">';
        html +=         '<span class="input-group-text" id="input-client-store-name">Store name</span>';
        html +=     '</div>';
        html +=     '<input type="text" name="store_name[]" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-tax-code">';
        html += '</div>';
        html += '<div class="input-group mb-3 item-delete-' + count + '">';
        html +=     '<div class="input-group-prepend">';
        html +=         '<span class="input-group-text" id="input-client-store-address">Store address</span>';
        html +=     '</div>';
        html +=     '<input type="text" name="store_addr[]" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-tax-code">';
        html += '</div>';
        html += '<div class="input-group mb-3 item-delete-' + count + '">';
        html +=     '<div class="input-group-prepend">';
        html +=         '<span class="input-group-text" id="input-client-store-address">Store tax code</span>';
        html +=     '</div>';
        html +=     '<input type="text" name="store_tax_code[]" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-tax-code">';
        html += '</div>';
        $('#add-client-menu').append(html);
        $('.selectpicker').selectpicker();
        deleteBranchItem();
    });
}

function deleteBranchItem() {
    $('.post-delete').on('click', function (e) {
        e.preventDefault();
        id = $(this).data('id');
        $('.item-delete-' + id).remove();
    });
}


$('#client-search').focus(function(){
    $('.focus').removeClass('shadow-none').addClass('shadow');
});
$('#client-search').blur(function(){
    $('.focus').removeClass('shadow').addClass('shadow-none');
});