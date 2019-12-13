$(document).ready(function () {
    var count = 0;
    loadMoreService(count);
    loadMoreUploadFile(count);
    deleteServiceItem('service');
    deleteScanFile();
    addNewService();
    
    
    function loadMoreService (count) {
        // get content service selectbox
        var service = '';
        service += '<select class="custom-select" name="service_id[]" required>';
        $("#service_id option").each(function () {
            service += '<option value="' + $(this).val() + '"> ' + $(this).text() + ' </option>';
        });
        service += '</select >';
        
        $('#ads-new').on('click', function (e) {
            count = count + 1;
            var html = '';
            html += ' <div class="form-row" id="form-row-service-item-' + count + '">';
            html += '   <div class="form-group col-12 col-md-6 col-lg">';
            html += '       <label for="service_id">Service name</label>';
            html +=         service;
            html += '   </div>';
            html += '    <div class="form-group col-12 col-md-6 col-lg">';
            html += '       <label for="service_start_date">Service start date</label>';
            html += '       <input type="text" name="service_start_date[]" class="form-control datepicker" placeholder="YYYY-MM-DD" required>';
            html += '    </div>';
            html += '    <div class="form-group col-12 col-md-6 col-lg">';
            html += '       <label for="time_service">QTY</label>';
            html += '       <div class="input-group">';
            html += '           <div class="input-group-prepend">';
            html += '               <span class="input-group-text">months</span>';
            html += '           </div>';
            html += '           <input type="number" min="1" class="form-control" id="time_service" name="time_service[]" placeholder="Duration" required>';
            html += '       </div>';
            html += '    </div>';
            html += '    <div class="form-group col-12 col-md-6 col-lg">';
            html += '       <label for="ads-discount">Discount rate</label>';
            html += '       <input type="text" class="form-control" id="ads-discount" name="discount_rate[]" placeholder="%">';
            html += '    </div>';
            html += '   <a href="#" class="item-delete" title ="Delete this item!" data-count=' + count + '><i class="fas fa-times-circle text-danger" style="font-size: 18px;"></i></a>';
            html += ' </div>';
            $('#service-menu').append(html);
            
            // delete item
            deleteServiceItem('service');
            
            $('.datepicker').datetimepicker({
                format:'YYYY-MM-DD'
            });
            
        });
    }
    
    function loadMoreUploadFile (count) {
        $('#add-more-file').on('click', function (e) {
            count++;
            e.preventDefault();
            
            var html = ''
            html    += '<div class="form-row" id="form-row-file-item-' + count + '">';
            html    +=      '<div class="col-6"  id="form-row-item-' + count + '">';
            html    +=          '<input type = "file" class="form-control" name = "scan_file[]">';
            html    +=      '</div>';
            html    +=      '<a href="#" class="item-delete mt-2" title ="Delete this item!" data-count=' + count + '><i class="fas fa-times-circle text-danger" style="font-size: 18px;"></i></a>';
            html    += '</div>';
            $('#menu-file').append(html);
            
            deleteServiceItem('file');
        });
    }
    
    function deleteServiceItem(position) {
        $('.item-delete').on('click', function (e) {
            e.preventDefault();
            var count = $(this).data('count');
            $('#form-row-' + position + '-item-' + count).remove();
        });
    }
    
    function deleteScanFile () {
        $('.item-file').on('click', function (e) {
            let id = $(this).data('id');
            e.preventDefault();
            BootstrapDialog.confirm({
                title: 'Delete Scan file',
                message: 'Do you really want to delete selected item? <br/>You can not restore after confirm it...',
                type: BootstrapDialog.TYPE_DANGER,
                callback: function (result) {
                    if (result) {
                        $.ajax({
                            method: 'POST',
                            url: base_url + '/contract/scan-file-delete',
                            data: {
                                id: id
                            }
                        })
                        .done(function (data) {
                            if (data == 1) {
                                $('#scanfile-' + id).remove();
                                $('#file-delete-' + id).remove();
                            }
                        })
                        .fail(function (xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        })
                    }
                }
            });
        });
    }
    
    function addNewService () {
        $('#add-service-menu').hide();
        
        // show input service menu
        $('#add-new-service').on('click', function (e) {
            e.preventDefault();
            $('#add-service-menu').show();
        });
        
        // save service menu
        $('#save-service').on('click', function (e) {
            e.preventDefault();
            
            let name = $('#service_name').val();
            let price = $('#service_price').val();
            $.ajax({
                method: 'POST',
                data: {
                    name: name,
                    price: price,
                    ajaxInsert: 1
                },
                url: base_url + '/service/post-new/' + 0
            })
            .done(function (data) {
                if (data.result) {
                    console.log('success saved!');
                    $('#add-service-menu').hide();
                    let html = '<option value="' + data.id + '">' + data.new + ' ($' + data.price + ')' + '</option>'
                    $('#service_id').append(html);
                }
            })
            .fail (function (xhr, error, status) {
                console.log(xhr.responseText);
                console.log(status);
                console.log(error);
            })
            
        })
    }
});